@extends('layouts.app')

@section('title', $tag->name . ' - Issue Tracker')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>
                <span class="tag text-white" style="background-color: {{ $tag->color ?? '#6c757d' }};">
                    {{ $tag->name }}
                </span>
            </h1>
        </div>
        <div>
            <a href="{{ route('tags.edit', $tag) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('tags.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Tags
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Issues with this tag ({{ $tag->issues->count() }})</h5>
                </div>
                <div class="card-body">
                    @forelse($tag->issues as $issue)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none">
                                            {{ $issue->title }}
                                        </a>
                                    </h6>
                                    <p class="mb-2 text-muted">{{ Str::limit($issue->description, 100) }}</p>
                                    
                                    <div class="d-flex gap-2 flex-wrap mb-2">
                                        <span class="badge status-{{ $issue->status }}">
                                            {{ ucwords(str_replace('_', ' ', $issue->status)) }}
                                        </span>
                                        <span class="badge priority-{{ $issue->priority }}">
                                            {{ ucfirst($issue->priority) }} Priority
                                        </span>
                                        @if($issue->due_date)
                                            <span class="badge bg-secondary">
                                                Due: {{ $issue->due_date->format('M d') }}
                                            </span>
                                        @endif
                                    </div>

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
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: #dee2e6;"></i>
                            <h6 class="mt-3">No issues with this tag</h6>
                            <p class="text-muted">This tag hasn't been assigned to any issues yet.</p>
                            <a href="{{ route('issues.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Create Issue
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Tag Information</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Name:</dt>
                        <dd class="col-sm-7">{{ $tag->name }}</dd>

                        @if($tag->color)
                            <dt class="col-sm-5">Color:</dt>
                            <dd class="col-sm-7">
                                <span class="d-inline-block" style="width: 20px; height: 20px; background-color: {{ $tag->color }}; border-radius: 3px; vertical-align: middle;"></span>
                                {{ $tag->color }}
                            </dd>
                        @endif

                        <dt class="col-sm-5">Issues:</dt>
                        <dd class="col-sm-7">{{ $tag->issues->count() }}</dd>

                        <dt class="col-sm-5">Created:</dt>
                        <dd class="col-sm-7">{{ $tag->created_at->format('M d, Y') }}</dd>

                        @if($tag->updated_at != $tag->created_at)
                            <dt class="col-sm-5">Updated:</dt>
                            <dd class="col-sm-7">{{ $tag->updated_at->format('M d, Y') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>

            @if($tag->issues->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5 class="text-success">{{ $tag->issues->where('status', 'closed')->count() }}</h5>
                                    <small class="text-muted">Closed</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5 class="text-warning">{{ $tag->issues->where('status', 'in_progress')->count() }}</h5>
                                    <small class="text-muted">In Progress</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5 class="text-info">{{ $tag->issues->where('status', 'open')->count() }}</h5>
                                    <small class="text-muted">Open</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <h5 class="text-danger">{{ $tag->issues->where('priority', 'high')->count() }}</h5>
                                    <small class="text-muted">High Priority</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
