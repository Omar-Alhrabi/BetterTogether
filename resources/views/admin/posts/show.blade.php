@extends('layouts.admin')

@section('title', 'Post Details')

@section('page-title', 'Post Details')

@section('styles')
<style>
    .post-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .post-meta {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .post-content {
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: justify;
    }
    .post-image-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .post-image {
        width: 100%;
        height: auto;
        transition: transform 0.3s ease;
    }
    .post-image:hover {
        transform: scale(1.03);
    }
    .stats-card {
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
    }
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.1);
    }
    .comment-card {
        border-left: 4px solid #007bff;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .comment-card:hover {
        transform: translateX(5px);
        border-left-color: #0056b3;
    }
    .comment-author {
        font-weight: 600;
    }
    .comment-time {
        font-size: 0.8rem;
    }
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Post Information</h5>
        <div>
            <!-- Edit button removed -->
            <a href="{{ route('posts.index') }}" class="btn btn-secondary ms-2 action-btn">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="post-header">
                    <h3 class="text-primary mb-3">{{ $post->title }}</h3>
                    
                    <div class="post-meta d-flex flex-wrap align-items-center">
                        <div class="me-3 mb-2">
                            <i class="fas fa-user-circle me-1"></i> 
                            {{ $post->user->full_name ?? 'Unknown' }}
                        </div>
                        <div class="me-3 mb-2">
                            <i class="fas fa-calendar-alt me-1"></i> 
                            {{ $post->created_at->format('F j, Y') }}
                        </div>
                        @if($post->activity)
                        <div class="me-3 mb-2">
                            <i class="fas fa-link me-1"></i> 
                            <a href="{{ route('activities.show', $post->activity) }}" class="text-decoration-none">
                                {{ $post->activity->title }}
                            </a>
                        </div>
                        @endif
                        <div class="me-3 mb-2">
                            <i class="fas fa-comments me-1"></i> 
                            {{ $post->comments_count }} Comments
                        </div>
                        <div class="mb-2">
                            <i class="fas fa-heart me-1 text-danger"></i> 
                            {{ $post->likes_count }} Likes
                        </div>
                    </div>
                </div>
                
                <div class="post-content p-3 mb-4 bg-light rounded">
                    {{ $post->content }}
                </div>
                
                <div class="comments-section mt-5">
                    <h4 class="mb-4">
                        <i class="fas fa-comments me-2 text-primary"></i>
                        Comments ({{ $post->comments->count() }})
                    </h4>
                    
                    @forelse($post->comments as $comment)
                    <div class="card comment-card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="comment-author">{{ $comment->user->full_name ?? 'Unknown' }}</span>
                                    <span class="comment-time text-muted ms-2">{{ $comment->created_at ? $comment->created_at->diffForHumans() : 'N/A' }}</span>
                                </div>
                                <div>
                                    <form action="{{ route('posts.comments.delete', $comment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger action-btn" 
                                                onclick="return confirm('Are you sure you want to delete this comment?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="mb-0 mt-3">{{ $comment->content }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No comments yet
                    </div>
                    @endforelse
                    
                    @if($post->comments->count() > 5)
                    <div class="text-center mt-4">
                        <a href="{{ route('posts.comments', $post) }}" class="btn btn-primary">
                            <i class="fas fa-list-alt me-2"></i> View All Comments
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="post-image-container mb-4">
                    @if($post->image)
                        <img src="{{ asset('storage/'.$post->image) }}" class="post-image img-fluid" alt="{{ $post->title }}">
                    @else
                        <div class="alert alert-info text-center p-4">
                            <i class="fas fa-image fa-3x mb-3"></i>
                            <p class="mb-0">No image available for this post</p>
                        </div>
                    @endif
                </div>
                
                <div class="card stats-card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i> Post Statistics</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-comments text-primary me-2"></i> Comments</span>
                                <span class="badge bg-primary rounded-pill">{{ $post->comments_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-heart text-danger me-2"></i> Likes</span>
                                <span class="badge bg-danger rounded-pill">{{ $post->likes_count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-plus me-2"></i> Created</span>
                                <span>{{ $post->created_at->format('M j, Y H:i') }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock me-2"></i> Last Updated</span>
                                <span>{{ $post->updated_at->format('M j, Y H:i') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Delete post card -->
                <div class="card stats-card bg-light">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Danger Zone</h5>
                    </div>
                    <div class="card-body text-center">
                        <p>Permanently delete this post and all its associated comments</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletePostModal">
                            <i class="fas fa-trash me-2"></i> Delete Post
                        </button>
                        
                        <!-- Delete Modal -->
                        <div class="modal fade" id="deletePostModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">Are you sure you want to permanently delete this post? This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Delete Permanently</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection