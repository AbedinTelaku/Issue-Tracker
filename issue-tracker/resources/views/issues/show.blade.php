@extends('layouts.app')

@section('title', $issue->title . ' - Issue Tracker')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ $issue->title }}</h1>
        <div>
            <a href="{{ route('issues.edit', $issue) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <a href="{{ route('issues.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Issues
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Issue Details -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted mb-2">Project: {{ $issue->project->name }}</h6>
                            <div class="d-flex gap-2 flex-wrap">
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
                        </div>
                        <small class="text-muted">Created {{ $issue->created_at->diffForHumans() }}</small>
                    </div>
                    
                    <p class="card-text">{{ $issue->description }}</p>
                </div>
            </div>

            <!-- Assigned Users Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Assigned Members</h6>
                    <button type="button" class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#userModal">
                        <i class="bi bi-people"></i> Manage Members
                    </button>
                </div>
                <div class="card-body">
                    <div id="assigned-users">
                        @forelse($issue->assignedUsers as $user)
                            <span class="badge bg-info me-2 mb-2" data-user-id="{{ $user->id }}">
                                <i class="bi bi-person"></i> {{ $user->name }}
                                <button type="button" class="btn-close btn-close-white ms-1" aria-label="Unassign user" onclick="unassignUser({{ $user->id }})"></button>
                            </span>
                        @empty
                            <p class="text-muted mb-0" id="no-users">No users assigned yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tags Section -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Tags</h6>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tagModal">
                        <i class="bi bi-plus-circle"></i> Manage Tags
                    </button>
                </div>
                <div class="card-body">
                    <div id="current-tags">
                        @forelse($issue->tags as $tag)
                            <span class="tag text-white me-2 mb-2" style="background-color: {{ $tag->color ?? '#6c757d' }};" data-tag-id="{{ $tag->id }}">
                                {{ $tag->name }}
                                <button type="button" class="btn-close btn-close-white ms-1" aria-label="Remove tag" onclick="detachTag({{ $tag->id }})"></button>
                            </span>
                        @empty
                            <p class="text-muted mb-0" id="no-tags">No tags assigned yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Comments ({{ $issue->comments->count() }})</h6>
                </div>
                <div class="card-body">
                    <!-- Add Comment Form -->
                    <form id="comment-form" class="mb-4">
                        @csrf
                        <input type="hidden" name="issue_id" value="{{ $issue->id }}">
                        <div class="mb-3">
                            <label for="author_name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="author_name" name="author_name" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="mb-3">
                            <label for="body" class="form-label">Comment</label>
                            <textarea class="form-control" id="body" name="body" rows="3" required></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Add Comment
                        </button>
                    </form>

                    <!-- Comments List -->
                    <div id="comments-container">
                        @forelse($issue->comments as $comment)
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <strong>{{ $comment->author_name }}</strong>
                                    <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-0 mt-1">{{ $comment->body }}</p>
                            </div>
                        @empty
                            <p class="text-muted" id="no-comments">No comments yet. Be the first to comment!</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Issue Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Issue Details</h6>
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7">
                            <span class="badge status-{{ $issue->status }}">
                                {{ ucwords(str_replace('_', ' ', $issue->status)) }}
                            </span>
                        </dd>

                        <dt class="col-sm-5">Priority:</dt>
                        <dd class="col-sm-7">
                            <span class="badge priority-{{ $issue->priority }}">
                                {{ ucfirst($issue->priority) }}
                            </span>
                        </dd>

                        <dt class="col-sm-5">Project:</dt>
                        <dd class="col-sm-7">
                            <a href="{{ route('projects.show', $issue->project) }}" class="text-decoration-none">
                                {{ $issue->project->name }}
                            </a>
                        </dd>

                        @if($issue->due_date)
                            <dt class="col-sm-5">Due Date:</dt>
                            <dd class="col-sm-7">{{ $issue->due_date->format('M d, Y') }}</dd>
                        @endif

                        <dt class="col-sm-5">Created:</dt>
                        <dd class="col-sm-7">{{ $issue->created_at->format('M d, Y') }}</dd>

                        @if($issue->updated_at != $issue->created_at)
                            <dt class="col-sm-5">Updated:</dt>
                            <dd class="col-sm-7">{{ $issue->updated_at->format('M d, Y') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tag Management Modal -->
<div class="modal fade" id="tagModal" tabindex="-1" aria-labelledby="tagModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tagModalLabel">Manage Tags</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Available Tags</h6>
                <div id="available-tags">
                    @foreach($allTags as $tag)
                        @if(!$issue->tags->contains($tag->id))
                            <span class="tag text-white me-2 mb-2" style="background-color: {{ $tag->color ?? '#6c757d' }}; cursor: pointer;" 
                                  data-tag-id="{{ $tag->id }}" onclick="attachTag({{ $tag->id }})">
                                {{ $tag->name }}
                                <i class="bi bi-plus-circle ms-1"></i>
                            </span>
                        @endif
                    @endforeach
                </div>

                <hr>

                <h6>Create New Tag</h6>
                <form id="new-tag-form">
                    @csrf
                    <div class="mb-3">
                        <label for="tag_name" class="form-label">Tag Name</label>
                        <input type="text" class="form-control" id="tag_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="tag_color" class="form-label">Color</label>
                        <input type="color" class="form-control form-control-color" id="tag_color" name="color" value="#6c757d">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Tag</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- User Assignment Modal -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">Manage Assigned Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Available Users</h6>
                <div id="available-users">
                    @foreach($allUsers as $user)
                        @if(!$issue->assignedUsers->contains($user->id))
                            <span class="badge bg-outline-info me-2 mb-2" style="cursor: pointer; border: 1px solid #0dcaf0;" 
                                  data-user-id="{{ $user->id }}" onclick="assignUser({{ $user->id }})">
                                <i class="bi bi-person"></i> {{ $user->name }}
                                <i class="bi bi-plus-circle ms-1"></i>
                            </span>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Tag Management Functions
    function attachTag(tagId) {
        $.post('{{ route("issues.tags.attach", $issue) }}', {
            tag_id: tagId
        })
        .done(function(data) {
            if (data.success) {
                // Remove from available tags
                $(`#available-tags span[data-tag-id="${tagId}"]`).remove();
                
                // Add to current tags
                $('#no-tags').remove();
                $('#current-tags').append(`
                    <span class="tag text-white me-2 mb-2" style="background-color: ${data.tag.color || '#6c757d'};" data-tag-id="${data.tag.id}">
                        ${data.tag.name}
                        <button type="button" class="btn-close btn-close-white ms-1" aria-label="Remove tag" onclick="detachTag(${data.tag.id})"></button>
                    </span>
                `);
                
                showAlert('success', data.message);
            }
        })
        .fail(function(xhr) {
            showAlert('danger', xhr.responseJSON?.message || 'Failed to attach tag');
        });
    }

    function detachTag(tagId) {
        $.ajax({
            url: '{{ route("issues.tags.detach", $issue) }}',
            method: 'DELETE',
            data: { tag_id: tagId }
        })
        .done(function(data) {
            if (data.success) {
                // Remove from current tags
                const tagElement = $(`#current-tags span[data-tag-id="${tagId}"]`);
                const tagName = tagElement.text().trim();
                const tagColor = tagElement.css('background-color');
                tagElement.remove();
                
                // Check if no tags left
                if ($('#current-tags span').length === 0) {
                    $('#current-tags').html('<p class="text-muted mb-0" id="no-tags">No tags assigned yet.</p>');
                }
                
                // Add back to available tags in modal
                $('#available-tags').append(`
                    <span class="tag text-white me-2 mb-2" style="background-color: ${tagColor}; cursor: pointer;" 
                          data-tag-id="${tagId}" onclick="attachTag(${tagId})">
                        ${tagName}
                        <i class="bi bi-plus-circle ms-1"></i>
                    </span>
                `);
                
                showAlert('success', data.message);
            }
        })
        .fail(function() {
            showAlert('danger', 'Failed to detach tag');
        });
    }

    // Comment Form Submission
    $('#comment-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.post('{{ route("comments.store") }}', formData)
        .done(function(data) {
            if (data.success) {
                // Clear form
                $('#comment-form')[0].reset();
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                // Remove "no comments" message
                $('#no-comments').remove();
                
                // Prepend new comment
                $('#comments-container').prepend(`
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <strong>${data.comment.author_name}</strong>
                            <small class="text-muted">just now</small>
                        </div>
                        <p class="mb-0 mt-1">${data.comment.body}</p>
                    </div>
                `);
                
                showAlert('success', data.message);
            }
        })
        .fail(function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                
                for (const field in errors) {
                    $(`#${field}`).addClass('is-invalid');
                    $(`#${field}`).siblings('.invalid-feedback').text(errors[field][0]);
                }
            } else {
                showAlert('danger', 'Failed to add comment');
            }
        });
    });

    // New Tag Form
    $('#new-tag-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = $(this).serialize();
        
        $.post('{{ route("tags.store") }}', formData)
        .done(function(data) {
            if (data.success) {
                // Clear form
                $('#new-tag-form')[0].reset();
                $('#tag_color').val('#6c757d');
                
                // Add to available tags
                $('#available-tags').append(`
                    <span class="tag text-white me-2 mb-2" style="background-color: ${data.tag.color || '#6c757d'}; cursor: pointer;" 
                          data-tag-id="${data.tag.id}" onclick="attachTag(${data.tag.id})">
                        ${data.tag.name}
                        <i class="bi bi-plus-circle ms-1"></i>
                    </span>
                `);
                
                showAlert('success', data.message);
            }
        })
        .fail(function(xhr) {
            if (xhr.status === 422) {
                const errors = xhr.responseJSON.errors;
                for (const field in errors) {
                    showAlert('danger', errors[field][0]);
                }
            } else {
                showAlert('danger', 'Failed to create tag');
            }
        });
    });

    // User Assignment Functions
    function assignUser(userId) {
        $.post('{{ route("issues.users.assign", $issue) }}', {
            user_id: userId
        })
        .done(function(data) {
            if (data.success) {
                // Remove from available users
                $(`#available-users span[data-user-id="${userId}"]`).remove();
                
                // Add to assigned users
                $('#no-users').remove();
                $('#assigned-users').append(`
                    <span class="badge bg-info me-2 mb-2" data-user-id="${data.user.id}">
                        <i class="bi bi-person"></i> ${data.user.name}
                        <button type="button" class="btn-close btn-close-white ms-1" aria-label="Unassign user" onclick="unassignUser(${data.user.id})"></button>
                    </span>
                `);
                
                showAlert('success', data.message);
            }
        })
        .fail(function(xhr) {
            showAlert('danger', xhr.responseJSON?.message || 'Failed to assign user');
        });
    }

    function unassignUser(userId) {
        $.ajax({
            url: '{{ route("issues.users.unassign", $issue) }}',
            method: 'DELETE',
            data: { user_id: userId }
        })
        .done(function(data) {
            if (data.success) {
                // Remove from assigned users
                const userElement = $(`#assigned-users span[data-user-id="${userId}"]`);
                const userName = userElement.text().trim();
                userElement.remove();
                
                // Check if no users left
                if ($('#assigned-users span').length === 0) {
                    $('#assigned-users').html('<p class="text-muted mb-0" id="no-users">No users assigned yet.</p>');
                }
                
                // Add back to available users in modal
                $('#available-users').append(`
                    <span class="badge bg-outline-info me-2 mb-2" style="cursor: pointer; border: 1px solid #0dcaf0;" 
                          data-user-id="${userId}" onclick="assignUser(${userId})">
                        <i class="bi bi-person"></i> ${userName}
                        <i class="bi bi-plus-circle ms-1"></i>
                    </span>
                `);
                
                showAlert('success', data.message);
            }
        })
        .fail(function() {
            showAlert('danger', 'Failed to unassign user');
        });
    }

    // Alert helper function
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert at top
        $('main').prepend(alertHtml);
        
        // Auto dismiss after 5 seconds
        setTimeout(() => {
            $('.alert').alert('close');
        }, 5000);
    }
</script>
@endpush
@endsection
