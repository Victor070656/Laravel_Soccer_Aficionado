<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Ad::query()->latest();

        if ($request->filled('placement')) {
            $query->where('placement', $request->placement);
        }

        if ($request->filled('status')) {
            match ($request->status) {
                'active' => $query->running(),
                'inactive' => $query->where('is_active', false),
                'scheduled' => $query->where('is_active', true)->where('starts_at', '>', now()),
                'expired' => $query->where('ends_at', '<=', now()),
                default => null,
            };
        }

        $ads = $query->paginate(20);

        return view('admin.ads.index', compact('ads'));
    }

    public function create()
    {
        return view('admin.ads.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'link_url' => 'nullable|url|max:2000',
            'placement' => 'required|in:sidebar,feed,banner,welcome',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $path = $request->file('image')->store('ads', 'public');

        Ad::create([
            'title' => $validated['title'],
            'image' => $path,
            'link_url' => $validated['link_url'] ?? null,
            'placement' => $validated['placement'],
            'is_active' => $request->boolean('is_active', true),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Ad created successfully!');
    }

    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    public function update(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'link_url' => 'nullable|url|max:2000',
            'placement' => 'required|in:sidebar,feed,banner,welcome',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $ad->image = $request->file('image')->store('ads', 'public');
        }

        $ad->update([
            'title' => $validated['title'],
            'image' => $ad->image,
            'link_url' => $validated['link_url'] ?? null,
            'placement' => $validated['placement'],
            'is_active' => $request->boolean('is_active', true),
            'starts_at' => $validated['starts_at'] ?? null,
            'ends_at' => $validated['ends_at'] ?? null,
        ]);

        return redirect()->route('admin.ads.index')->with('success', 'Ad updated successfully!');
    }

    public function toggle(Ad $ad)
    {
        $ad->update(['is_active' => ! $ad->is_active]);

        $status = $ad->is_active ? 'enabled' : 'disabled';

        return back()->with('success', "Ad \"{$ad->title}\" has been {$status}.");
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }

        $ad->delete();

        return redirect()->route('admin.ads.index')->with('success', 'Ad deleted.');
    }
}
