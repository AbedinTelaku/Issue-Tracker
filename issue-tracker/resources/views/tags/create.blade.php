@extends('layouts.app')

@section('title', 'Create Tag - Issue Tracker')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Create New Tag</h1>
        <a href="{{ route('tags.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Tags
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tags.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Tag Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label">Color</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', '#6c757d') }}">
                                <input type="text" class="form-control @error('color') is-invalid @enderror" 
                                       id="color-hex" name="color_hex" value="{{ old('color', '#6c757d') }}" 
                                       pattern="^#[a-fA-F0-9]{6}$" placeholder="#6c757d">
                            </div>
                            <div class="form-text">Choose a color to help identify this tag.</div>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Preview</label>
                            <div>
                                <span id="tag-preview" class="tag text-white" style="background-color: #6c757d;">
                                    <span id="preview-name">Tag Name</span>
                                </span>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('tags.index') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Create Tag
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Sync color picker with hex input
    $('#color').on('change', function() {
        $('#color-hex').val($(this).val());
        updatePreview();
    });

    $('#color-hex').on('input', function() {
        const color = $(this).val();
        if (/^#[a-fA-F0-9]{6}$/.test(color)) {
            $('#color').val(color);
            updatePreview();
        }
    });

    // Update preview when name changes
    $('#name').on('input', function() {
        updatePreview();
    });

    function updatePreview() {
        const name = $('#name').val() || 'Tag Name';
        const color = $('#color').val();
        
        $('#preview-name').text(name);
        $('#tag-preview').css('background-color', color);
    }

    // Initialize preview
    updatePreview();
</script>
@endpush
@endsection
