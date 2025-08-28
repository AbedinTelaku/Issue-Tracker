<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\TagRequest;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::withCount('issues')->latest()->paginate(20);
        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagRequest $request)
    {
        $data = $request->validated();
        
        // Use color_hex if provided, otherwise use color
        if (isset($data['color_hex'])) {
            $data['color'] = $data['color_hex'];
            unset($data['color_hex']);
        }
        
        $tag = Tag::create($data);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tag created successfully',
                'tag' => $tag
            ]);
        }

        return redirect()->route('tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        $tag->load(['issues.project']);
        return view('tags.show', compact('tag'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagRequest $request, Tag $tag)
    {
        $data = $request->validated();
        
        // Use color_hex if provided, otherwise use color
        if (isset($data['color_hex'])) {
            $data['color'] = $data['color_hex'];
            unset($data['color_hex']);
        }
        
        $tag->update($data);

        return redirect()->route('tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        $tag->delete();

        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
