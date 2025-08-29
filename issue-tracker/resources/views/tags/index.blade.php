@extends('layouts.app')

@section('title', 'Tags - Issue Tracker')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tags</h1>
        <a href="{{ route('tags.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Tag
        </a>
    </div>

    <div class="row">
        @forelse($tags as $tag)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="tag text-white" style="background-color: {{ $tag->color ?? '#6c757d' }};">
                                {{ $tag->name }}
                            </span>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('tags.show', $tag) }}">
                                        <i class="bi bi-eye"></i> View
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('tags.edit', $tag) }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('tags.destroy', $tag) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this tag?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <p class="text-muted mb-2">
                            Used in {{ $tag->issues_count }} {{ Str::plural('issue', $tag->issues_count) }}
                        </p>
                        
                        @if($tag->color)
                            <small class="text-muted">
                                <i class="bi bi-palette"></i> {{ $tag->color }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-tags" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3">No tags yet</h4>
                    <p class="text-muted">Create tags to organize your issues better.</p>
                    <a href="{{ route('tags.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Create Tag
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($tags->hasPages())
        <!-- Pagination Info -->
        <div class="pagination-info">
            <strong>Showing {{ $tags->firstItem() ?? 0 }} to {{ $tags->lastItem() ?? 0 }} of {{ $tags->total() }} tags</strong>
        </div>
        
        <!-- Enhanced Pagination Container -->
        <div class="pagination-container">
            <div class="d-flex justify-content-center">
                <nav aria-label="Tags pagination">
                    {{ $tags->links() }}
                </nav>
            </div>
            
            <!-- Quick Navigation -->
            <div class="quick-nav text-center">
                <small class="text-muted">
                    @if($tags->currentPage() > 1)
                        <a href="{{ $tags->previousPageUrl() }}" class="text-decoration-none">
                            Previous page
                        </a>
                    @endif
                    
                    @if($tags->currentPage() < $tags->lastPage())
                        @if($tags->currentPage() > 1)
                            <span class="mx-2">•</span>
                        @endif
                        <a href="{{ $tags->nextPageUrl() }}" class="text-decoration-none">
                            Next page
                        </a>
                    @endif
                </small>
            </div>
        </div>
    @endif
</div>
@endsection
