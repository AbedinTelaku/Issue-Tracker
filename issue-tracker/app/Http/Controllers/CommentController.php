<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Issue;
use App\Http\Requests\CommentRequest;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Load comments for an issue via AJAX with pagination.
     */
    public function index(Request $request, Issue $issue)
    {
        $comments = $issue->comments()->latest()->paginate(10);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'comments' => $comments->items(),
                'pagination' => [
                    'current_page' => $comments->currentPage(),
                    'last_page' => $comments->lastPage(),
                    'per_page' => $comments->perPage(),
                    'total' => $comments->total(),
                ]
            ]);
        }

        return view('comments.index', compact('comments', 'issue'));
    }

    /**
     * Store a newly created comment via AJAX.
     */
    public function store(CommentRequest $request)
    {
        $comment = Comment::create($request->validated());
        $comment->load('issue');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => $comment
            ]);
        }

        return redirect()->route('issues.show', $comment->issue)
            ->with('success', 'Comment added successfully.');
    }

    /**
     * Update the specified comment.
     */
    public function update(CommentRequest $request, Comment $comment)
    {
        $comment->update($request->validated());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'comment' => $comment
            ]);
        }

        return redirect()->route('issues.show', $comment->issue)
            ->with('success', 'Comment updated successfully.');
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        $issue = $comment->issue;
        $comment->delete();

        return redirect()->route('issues.show', $issue)
            ->with('success', 'Comment deleted successfully.');
    }
}
