@extends('layouts.app')

@section('title', 'Issues - Issue Tracker')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Issues</h1>
        <a href="{{ route('issues.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> New Issue
        </a>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('issues.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="priority" class="form-label">Priority</label>
                    <select name="priority" id="priority" class="form-select">
                        <option value="">All Priorities</option>
                        <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="tag" class="form-label">Tag</label>
                    <select name="tag" id="tag" class="form-select">
                        <option value="">All Tags</option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Search title or description..." value="{{ request('search') }}">
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('issues.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Issues List -->
    <div class="row">
        @forelse($issues as $issue)
            <div class="col-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h5 class="card-title">
                                    <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none">
                                        {{ $issue->title }}
                                    </a>
                                </h5>
                                <p class="card-text text-muted">{{ Str::limit($issue->description, 150) }}</p>
                                
                                <div class="d-flex gap-2 flex-wrap mb-2">
                                    <span class="badge status-{{ $issue->status }}">
                                        {{ ucwords(str_replace('_', ' ', $issue->status)) }}
                                    </span>
                                    <span class="badge priority-{{ $issue->priority }}">
                                        {{ ucfirst($issue->priority) }} Priority
                                    </span>
                                    @if($issue->due_date)
                                        <span class="badge bg-secondary">
                                            Due: {{ $issue->due_date->format('M d, Y') }}
                                        </span>
                                    @endif
                                </div>

                                @if($issue->tags->count() > 0)
                                    <div class="mb-2">
                                        @foreach($issue->tags as $tag)
                                            <span class="tag text-white" style="background-color: {{ $tag->color ?? '#6c757d' }};">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <small class="text-muted">
                                    <i class="bi bi-folder"></i> {{ $issue->project->name }} | 
                                    <i class="bi bi-clock"></i> {{ $issue->created_at->diffForHumans() }}
                                </small>
                            </div>
                            
                            <div class="btn-group" role="group">
                                <a href="{{ route('issues.show', $issue) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="{{ route('issues.edit', $issue) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('issues.destroy', $issue) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Are you sure you want to delete this issue?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-exclamation-triangle" style="font-size: 4rem; color: #dee2e6;"></i>
                    <h4 class="mt-3">No issues found</h4>
                    <p class="text-muted">
                        @if(request()->hasAny(['status', 'priority', 'tag', 'search']))
                            Try adjusting your filters or <a href="{{ route('issues.index') }}">clear all filters</a>.
                        @else
                            Get started by creating your first issue.
                        @endif
                    </p>
                    <a href="{{ route('issues.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Create Issue
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    @if($issues->hasPages())
        <!-- Pagination Info -->
        
        
        <!-- Enhanced Pagination Container -->
        <div class="pagination-container">
            
            
            <!-- Quick Navigation with Previous/Next -->
            <div class="quick-nav text-center">
                <small class="text-muted">
                    @if($issues->currentPage() > 1)
                        <a href="{{ $issues->previousPageUrl() }}" class="text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Previous page
                        </a>
                    @endif
                    
                    @if($issues->currentPage() < $issues->lastPage())
                        @if($issues->currentPage() > 1)
                            <span class="mx-2">•</span>
                        @endif
                        <a href="{{ $issues->nextPageUrl() }}" class="text-decoration-none">
                            Next page <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </small>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-submit form on filter change
    $('#status, #priority, #tag').on('change', function() {
        $(this).closest('form').submit();
    });

    // Debounced search
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            $(this).closest('form').submit();
        }, 500);
    });
</script>
@endpush
@endsection
