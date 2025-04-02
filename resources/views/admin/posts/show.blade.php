@extends('layouts.admin')

@section('title', 'Post Details')

@section('page-title', 'Post Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Post Information</h5>
        <div>
            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <h4>{{ $post->title }}</h4>
                
                <div class="d-flex align-items-center text-muted mb-3">
                    <small>
                        <i class="fas fa-user me-1"></i> {{ $post->user->full_name ?? 'Unknown' }} |
                        <i class="fas fa-calendar me-1"></i> {{ $post->created_at->format('F j, Y') }} |
                        @if($post->activity)
                        <i class="fas fa-link me-1"></i> <a href="{{ route('activities.show', $post->activity) }}">{{ $post->activity->title }}</a> |
                        @endif
                        <i class="fas fa-comments me-1"></i> {{ $post->comments_count }} Comments |
                        <i class="fas fa-heart me-1"></i> {{ $post->likes_count }} Likes
                    </small>
                </div>
                
                <div class="card mb-4">
                    <div class="card-body">
                        {{ $post->content }}
                    </div>
                </div>
                
                <h5 class="mb-3">Comments ({{ $post->comments->count() }})</h5>
                
                @forelse($post->comments as $comment)
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $comment->user->full_name ?? 'Unknown' }}</strong>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <div>
                                <form action="{{ route('posts.comments.delete', $comment->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this comment?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p class="mb-0 mt-2">{{ $comment->content }}</p>
                    </div>
                </div>
                @empty
                <div class="alert alert-info">No comments yet</div>
                @endforelse
                
                @if($post->comments->count() > 5)
                <div class="text-center mt-3">
                    <a href="{{ route('posts.comments', $post) }}" class="btn btn-primary">View All Comments</a>
                </div>
                @endif
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Post Image</div>
                    <div class="card-body text-center">
                        @if($post->image)
                            <img src="{{ asset('storage/'.$post->image) }}" class="img-fluid rounded">
                        @else
                            <div class="alert alert-info">No image for this post</div>
                        @endif
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header">Post Statistics</div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Comments
                                <span class="badge bg-primary rounded-pill">{{ $post->comments_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Likes
                                <span class="badge bg-danger rounded-pill">{{ $post->likes_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Created
                                <span>{{ $post->created_at->format('M j, Y H:i') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Last Updated
                                <span>{{ $post->updated_at->format('M j, Y H:i') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection