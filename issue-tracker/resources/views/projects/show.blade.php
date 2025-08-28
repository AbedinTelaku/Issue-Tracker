@extends('layouts.app')

@section('title', $project->name . ' - Issue Tracker')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $project->name }}</h1>
        <div>
            <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Projects
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Project Description</h5>
                    <p class="card-text">{{ $project->description }}</p>
                    
                    @if($project->start_date || $project->deadline)
                        <div class="row">
                            @if($project->start_date)
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-event"></i> Start Date: {{ $project->start_date->format('M d, Y') }}
                                    </small>
                                </div>
                            @endif
                            @if($project->deadline)
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <i class="bi bi-calendar-x"></i> Deadline: {{ $project->deadline->format('M d, Y') }}
                                    </small>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Issues ({{ $project->issues->count() }})</h5>
                    <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle"></i> New Issue
                    </a>
                </div>
                <div class="card-body">
                    @forelse($project->issues as $issue)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('issues.show', $issue) }}" class="text-decoration-none">
                                            {{ $issue->title }}
                                        </a>
                                    </h6>
                                    <p class="mb-2 text-muted">{{ Str::limit($issue->description, 100) }}</p>
                                    
                                    <div class="d-flex gap-2 flex-wrap">
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
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">{{ $issue->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            
                            @if($issue->tags->count() > 0)
                                <div class="mt-2">
                                    @foreach($issue->tags as $tag)
                                        <span class="tag text-white" style="background-color: {{ $tag->color ?? '#6c757d' }};">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem; color: #dee2e6;"></i>
                            <h6 class="mt-3">No issues yet</h6>
                            <p class="text-muted">Create the first issue for this project.</p>
                            <a href="{{ route('issues.create', ['project_id' => $project->id]) }}" class="btn btn-primary">
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
                    <h6 class="mb-0">Project Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-primary">{{ $project->issues->count() }}</h4>
                                <small class="text-muted">Total Issues</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-success">{{ $project->issues->where('status', 'closed')->count() }}</h4>
                                <small class="text-muted">Closed</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-warning">{{ $project->issues->where('status', 'in_progress')->count() }}</h4>
                                <small class="text-muted">In Progress</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <h4 class="text-info">{{ $project->issues->where('status', 'open')->count() }}</h4>
                                <small class="text-muted">Open</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
