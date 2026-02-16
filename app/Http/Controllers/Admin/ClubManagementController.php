<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Club;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClubManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Club::withCount(['players', 'fans']);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $clubs = $query->orderBy('name')->paginate(25);

        return view('admin.clubs.index', compact('clubs'));
    }

    public function create()
    {
        return view('admin.clubs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:10',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'stadium' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'description' => 'nullable|string|max:5000',
            'website' => 'nullable|url',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs', 'public');
        }

        Club::create($validated);

        return redirect()->route('admin.clubs.index')->with('success', 'Club created successfully!');
    }

    public function edit(Club $club)
    {
        return view('admin.clubs.edit', compact('club'));
    }

    public function update(Request $request, Club $club)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:10',
            'country' => 'required|string|max:100',
            'city' => 'nullable|string|max:100',
            'stadium' => 'nullable|string|max:255',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'description' => 'nullable|string|max:5000',
            'website' => 'nullable|url',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('clubs', 'public');
        }

        $club->update($validated);

        return redirect()->route('admin.clubs.index')->with('success', 'Club updated!');
    }

    public function destroy(Club $club)
    {
        $club->delete();

        return redirect()->route('admin.clubs.index')->with('success', 'Club deleted.');
    }
}
