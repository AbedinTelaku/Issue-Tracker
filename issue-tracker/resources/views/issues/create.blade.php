@extends('layouts.app')

@section('title', 'Create Issue - Issue Tracker')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New Issue</h1>
        <a href="{{ route('issues.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Issues
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('issues.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="project_id" class="form-label">Project</label>
                            <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror" required>
                                <option value="">Select a project</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" 
                                            {{ old('project_id', request('project_id')) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="title" class="form-label">Issue Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                                        <option value="open" {{ old('status', 'open') === 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="in_progress" {{ old('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="closed" {{ old('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority</label>
                                    <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                        <option value="low" {{ old('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') === 'high' ? 'selected' : '' }}>High</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date</label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" name="due_date" value="{{ old('due_date') }}">
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <select name="tags[]" id="tags" class="form-select @error('tags') is-invalid @enderror" multiple>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}" 
                                            {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Hold Ctrl (Cmd on Mac) to select multiple tags.</div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('issues.index') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Issue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
