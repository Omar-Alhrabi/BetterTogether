@extends('layouts.admin')

@section('title', 'Edit Post')

@section('page-title', 'Edit Post')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white">
        <h5 class="mb-0">Edit Post: {{ $post->title }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" required>
                @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="activity_id" class="form-label">Related Activity</label>
                <select class="form-select @error('activity_id') is-invalid @enderror" id="activity_id" name="activity_id">
                    <option value="">None (General Post)</option>
                    @foreach($activities as $activity)
                    <option value="{{ $activity->id }}" {{ old('activity_id', $post->activity_id) == $activity->id ? 'selected' : '' }}>
                        {{ $activity->title }}
                    </option>
                    @endforeach
                </select>
                @error('activity_id')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="6" required>{{ old('content', $post->content) }}</textarea>
                @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Post Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                <small class="text-muted">Recommended size: 1200x630px (Max: 2MB)</small>
                @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                @if($post->image)
                <div class="mt-2">
                    <img src="{{ asset('storage/'.$post->image) }}" class="img-thumbnail" width="200">
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" id="delete_image" name="delete_image">
                        <label class="form-check-label" for="delete_image">
                            Remove current image
                        </label>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="d-flex justify-content-between">
                <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection