<?php

namespace App\Http\Controllers\Api;

use App\Models\Report;
use Illuminate\Http\Request;

class ReportApiController extends BaseApiController
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reportable_type' => 'required|in:post,comment,user',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ]);

        $typeMap = [
            'post' => \App\Models\Post::class,
            'comment' => \App\Models\Comment::class,
            'user' => \App\Models\User::class,
        ];

        $reportableType = $typeMap[$validated['reportable_type']];
        $reportable = $reportableType::findOrFail($validated['reportable_id']);

        $report = Report::create([
            'reporter_id' => $request->user()->id,
            'reportable_type' => $reportableType,
            'reportable_id' => $reportable->id,
            'reason' => $validated['reason'],
            'description' => $validated['description'] ?? null,
        ]);

        return $this->success($report, 'Report submitted. Thank you for helping keep our community safe.', 201);
    }
}
