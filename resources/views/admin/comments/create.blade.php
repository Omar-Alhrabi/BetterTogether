@extends('layouts.admin')

@section('title', 'Create New Comment')

@section('page-title', 'Create New Comment')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">New Comment Information</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="post_id" class="form-label">Post <span class="text-danger">*</span></label>
                <select class="form-select @error('post_id') is-invalid @enderror" id="post_id" name="post_id" required>
                    <option value="">Select a post</option>
                    @foreach($posts as $post)
                    <option value="{{ $post->id }}" {{ old('post_id') == $post->id ? 'selected' : '' }}>
                        {{ $post->title }}
                    </option>
                    @endforeach
                </select>
                @error('post_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="comment" class="form-label">Comment <span class="text-danger">*</span></label>
                <textarea class="form-control @error('comment') is-invalid @enderror" id="comment" name="comment" rows="4" required>{{ old('comment') }}</textarea>
                @error('comment')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('comments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection