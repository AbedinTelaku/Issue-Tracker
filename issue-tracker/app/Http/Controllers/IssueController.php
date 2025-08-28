<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use App\Http\Requests\IssueRequest;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Issue::with(['project', 'tags']);

        // Apply filters
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('priority')) {
            $query->byPriority($request->priority);
        }

        if ($request->filled('tag')) {
            $query->byTag($request->tag);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $issues = $query->latest()->paginate(15);
        $projects = Project::all();
        $tags = Tag::all();

        return view('issues.index', compact('issues', 'projects', 'tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projects = Project::all();
        $tags = Tag::all();
        return view('issues.create', compact('projects', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IssueRequest $request)
    {
        $issue = Issue::create($request->only([
            'project_id', 'title', 'description', 'status', 'priority', 'due_date'
        ]));

        if ($request->filled('tags')) {
            $issue->tags()->sync($request->tags);
        }

        return redirect()->route('issues.index')
            ->with('success', 'Issue created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue)
    {
        $issue->load(['project', 'tags', 'comments' => function ($query) {
            $query->latest();
        }]);

        $allTags = Tag::all();

        return view('issues.show', compact('issue', 'allTags'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue)
    {
        $projects = Project::all();
        $tags = Tag::all();
        $issue->load('tags');
        
        return view('issues.edit', compact('issue', 'projects', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IssueRequest $request, Issue $issue)
    {
        $issue->update($request->only([
            'project_id', 'title', 'description', 'status', 'priority', 'due_date'
        ]));

        if ($request->filled('tags')) {
            $issue->tags()->sync($request->tags);
        } else {
            $issue->tags()->detach();
        }

        return redirect()->route('issues.index')
            ->with('success', 'Issue updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return redirect()->route('issues.index')
            ->with('success', 'Issue deleted successfully.');
    }

    /**
     * Attach a tag to an issue via AJAX.
     */
    public function attachTag(Request $request, Issue $issue)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id'
        ]);

        if (!$issue->tags()->where('tag_id', $request->tag_id)->exists()) {
            $issue->tags()->attach($request->tag_id);
            $tag = Tag::find($request->tag_id);
            
            return response()->json([
                'success' => true,
                'message' => 'Tag attached successfully',
                'tag' => $tag
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Tag is already attached to this issue'
        ], 422);
    }

    /**
     * Detach a tag from an issue via AJAX.
     */
    public function detachTag(Request $request, Issue $issue)
    {
        $request->validate([
            'tag_id' => 'required|exists:tags,id'
        ]);

        $issue->tags()->detach($request->tag_id);

        return response()->json([
            'success' => true,
            'message' => 'Tag detached successfully'
        ]);
    }
}
