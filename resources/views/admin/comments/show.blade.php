@extends('layouts.admin')

@section('title', 'Comment Details')

@section('page-title', 'Comment Details')

@section('styles')
<style>
    .comment-header {
        border-bottom: 1px solid #eee;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }
    .comment-meta {
        color: #6c757d;
        font-size: 0.9rem;
    }
    .comment-content {
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: justify;
        background-color: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        border-left: 4px solid #007bff;
    }
    .post-preview-container {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .post-preview-img {
        width: 100%;
        height: 160px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    .post-preview-img:hover {
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
    .action-btn {
        transition: all 0.2s ease;
    }
    .action-btn:hover {
        transform: translateY(-2px);
    }
    .user-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #6c757d;
        margin-right: 15px;
    }
</style>
@endsection

@section('content')
<div class="card shadow">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Comment Information</h5>
        <div>
            <a href="{{ route('comments.index') }}" class="btn btn-secondary action-btn">
                <i class="fas fa-arrow-left me-1"></i> Back to List
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <div class="comment-header d-flex align-items-center">
                    <div class="user-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $comment->user->full_name ?? 'Unknown User' }}</h4>
                        <div class="comment-meta d-flex flex-wrap align-items-center">
                            <div class="me-3 mb-2">
                                <i class="fas fa-calendar-alt me-1"></i> 
                                {{ $comment->created_at ? $comment->created_at->format('F j, Y H:i') : 'N/A' }}
                            </div>
                            <div class="me-3 mb-2">
                                <i class="fas fa-clock me-1"></i> 
                                {{ $comment->created_at ? $comment->created_at->diffForHumans() : 'N/A' }}
                            </div>
                            @if($comment->created_at != $comment->updated_at)
                            <div class="mb-2">
                                <i class="fas fa-edit me-1"></i> 
                                Edited: {{ $comment->updated_at ? $comment->updated_at->diffForHumans() : 'N/A' }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <div class="comment-content mb-4">
                    {{ $comment->content }}
                </div>
                
                <div class="related-post mt-5">
                    <h4 class="mb-4">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Related Post
                    </h4>
                    
                    @if($comment->post)
                    <div class="card">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ $comment->post->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap align-items-center text-muted mb-3">
                                <div class="me-3 mb-2">
                                    <i class="fas fa-user me-1"></i> 
                                    {{ $comment->post->user->full_name ?? 'Unknown' }}
                                </div>
                                <div class="me-3 mb-2">
                                    <i class="fas fa-calendar me-1"></i> 
                                    {{ $comment->post->created_at ? $comment->post->created_at->format('F j, Y') : 'N/A' }}
                                </div>
                                <div class="me-3 mb-2">
                                    <i class="fas fa-comments me-1"></i> 
                                    {{ $comment->post->comments_count ?? '0' }} Comments
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-heart me-1 text-danger"></i> 
                                    {{ $comment->post->likes_count ?? '0' }} Likes
                                </div>
                            </div>
                            
                            <p>{{ Str::limit($comment->post->content, 200) }}</p>
                            
                            <div class="text-end">
                                <a href="{{ route('posts.show', $comment->post) }}" class="btn btn-primary action-btn">
                                    <i class="fas fa-eye me-1"></i> View Complete Post
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        The post related to this comment has been deleted or is unavailable.
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="col-lg-4">
                @if($comment->post)
                <div class="post-preview-container mb-4">
                    @if($comment->post->image)
                        <img src="{{ asset('storage/'.$comment->post->image) }}" class="post-preview-img" alt="{{ $comment->post->title }}">
                    @else
                        <div class="bg-light p-4 text-center">
                            <i class="fas fa-image fa-3x text-muted mb-2"></i>
                            <p class="mb-0">No image available</p>
                        </div>
                    @endif
                    <div class="p-3 bg-light">
                        <h6 class="mb-1">{{ Str::limit($comment->post->title, 50) }}</h6>
                        <small class="text-muted">Posted on {{ $comment->post->created_at ? $comment->post->created_at->format('M j, Y') : 'N/A' }}</small>
                    </div>
                </div>
                @endif
                
                <div class="card stats-card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i> Comment Details</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-user text-primary me-2"></i> Author</span>
                                <span>
                                    @if($comment->user)
                                    <a href="{{ route('users.show', $comment->user) }}" class="text-decoration-none">
                                        {{ $comment->user->full_name ?? 'Unknown' }}
                                    </a>
                                    @else
                                    Unknown
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-calendar-plus me-2"></i> Created</span>
                                <span>{{ $comment->created_at ? $comment->created_at->format('M j, Y H:i') : 'N/A' }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-clock me-2"></i> Last Updated</span>
                                <span>{{ $comment->updated_at ? $comment->updated_at->format('M j, Y H:i') : 'N/A' }}</span>
                            </li>
                            @if($comment->deleted_at)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fas fa-trash text-danger me-2"></i> Deleted</span>
                                <span>{{ $comment->deleted_at->format('M j, Y H:i') }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
                
                <!-- Delete comment card -->
                <div class="card stats-card bg-light">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Danger Zone</h5>
                    </div>
                    <div class="card-body text-center">
                        <p>Permanently delete this comment</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteCommentModal">
                            <i class="fas fa-trash me-2"></i> Delete Comment
                        </button>
                        
                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title">Confirm Deletion</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0">Are you sure you want to permanently delete this comment? This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
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