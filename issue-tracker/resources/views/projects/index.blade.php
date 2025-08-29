@extends('layouts.app')

@section('title', 'Projects - Issue Tracker')

@section('content')
<div class="d-flex justify-content-between align-items-center py-4">
    <h1>Projects</h1>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> New Project
    </a>
</div>

<div class="row">
    @forelse($projects as $project)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">{{ $project->name }}</h5>
                    <p class="card-text">{{ Str::limit($project->description, 100) }}</p>
                    
                    <div class="mb-3">
                        <small class="text-muted">
                            <i class="bi bi-calendar"></i>
                            @if($project->start_date)
                                Started: {{ $project->start_date->format('M d, Y') }}
                            @endif
                            @if($project->deadline)
                                <br>Deadline: {{ $project->deadline->format('M d, Y') }}
                            @endif
                        </small>
                    </div>
                    
                    <div class="mb-3">
                        <span class="badge bg-secondary">
                            {{ $project->issues_count }} {{ Str::plural('issue', $project->issues_count) }}
                        </span>
                    </div>
                </div>
                <div class="card-footer bg-transparent">
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('projects.show', $project) }}" class="btn btn-outline-primary">
                            <i class="bi bi-eye"></i> View
                        </a>
                        <a href="{{ route('projects.edit', $project) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Are you sure you want to delete this project?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-folder" style="font-size: 4rem; color: #dee2e6;"></i>
                <h4 class="mt-3">No projects yet</h4>
                <p class="text-muted">Get started by creating your first project.</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Project
                </a>
            </div>
        </div>
    @endforelse
</div>

@if($projects->hasPages())
    <!-- Pagination Info -->
    <div class="pagination-info">
        <strong>Showing {{ $projects->firstItem() ?? 0 }} to {{ $projects->lastItem() ?? 0 }} of {{ $projects->total() }} projects</strong>
    </div>
    
    <!-- Enhanced Pagination Container -->
    <div class="pagination-container">
        <div class="d-flex justify-content-center">
            <nav aria-label="Projects pagination">
                {{ $projects->links() }}
            </nav>
        </div>
        
        <!-- Quick Navigation -->
        <div class="quick-nav text-center">
            <small class="text-muted">
                @if($projects->currentPage() > 1)
                    <a href="{{ $projects->previousPageUrl() }}" class="text-decoration-none">
                        Previous page
                    </a>
                @endif
                
                @if($projects->currentPage() < $projects->lastPage())
                    @if($projects->currentPage() > 1)
                        <span class="mx-2">•</span>
                    @endif
                    <a href="{{ $projects->nextPageUrl() }}" class="text-decoration-none">
                        Next page
                    </a>
                @endif
            </small>
        </div>
    </div>
@endif
@endsection
