@extends('layouts.admin')

@section('title', 'Comment Details')

@section('page-title', 'Comment Details')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Comment Information</h5>
        <div>
            <a href="{{ route('comments.edit', $comment) }}" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
            <a href="{{ route('comments.index') }}" class="btn btn-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 25%">User</th>
                        <td>
                            {{ $comment->user->full_name ?? 'Unknown' }}
                            @if($comment->user)
                            <a href="{{ route('users.show', $comment->user) }}" class="btn btn-sm btn-outline-primary ms-2">
                                View Profile
                            </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Post</th>
                        <td>
                            {{ $comment->post->title ?? 'Unknown' }}
                            @if($comment->post)
                            <a href="{{ route('posts.show', $comment->post) }}" class="btn btn-sm btn-outline-primary ms-2">
                                View Post
                            </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ $comment->created_at->format('F j, Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $comment->updated_at->format('F j, Y H:i:s') }}</td>
                    </tr>
                    <tr>
                        <th>Content</th>
                        <td>{{ $comment->content }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Post Summary</div>
                    <div class="card-body">
                        @if($comment->post)
                            <h6>{{ $comment->post->title }}</h6>
                            <p class="text-muted">{{ Str::limit($comment->post->content, 150) }}</p>
                            <div class="text-center">
                                <a href="{{ route('posts.show', $comment->post) }}" class="btn btn-primary">
                                    <i class="fas fa-eye me-1"></i> View Full Post
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">Post not found or deleted</div>
                        @endif
                    </div>
                </div>
                
                <div class="card mt-3">
                    <div class="card-header">Actions</div>
                    <div class="card-body">
                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this comment?')">
                                <i class="fas fa-trash me-1"></i> Delete Comment
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection