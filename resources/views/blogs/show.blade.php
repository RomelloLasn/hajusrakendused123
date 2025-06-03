@extends('layouts.app')

@section('title', $blog->title . ' - Hajusrakendused')

@section('styles')
<style>
    .blog-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .blog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .blog-title {
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
    }

    .back-btn {
        color: rgb(0, 200, 227);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        color: rgb(0, 220, 247);
    }

    .post-container {
        background: rgba(20, 20, 20, 0.4);
        border-radius: 8px;
        overflow: hidden;
    }

    .post-header {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(0, 200, 227, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: rgb(0, 200, 227);
        margin-right: 1rem;
    }

    .post-meta {
        flex: 1;
    }

    .post-author {
        font-size: 1rem;
        color: rgb(0, 200, 227);
        text-decoration: none;
        font-weight: 600;
    }

    .post-date {
        font-size: 0.85rem;
        color: #666;
        margin-top: 0.25rem;
    }

    .post-actions {
        display: flex;
        gap: 0.75rem;
    }

    .action-btn {
        background: rgba(0, 200, 227, 0.1);
        color: rgb(0, 200, 227);
        border: 1px solid rgba(0, 200, 227, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .action-btn:hover {
        background: rgba(0, 200, 227, 0.2);
    }

    .delete-btn {
        background: rgba(220, 53, 69, 0.1);
        color: rgb(220, 53, 69);
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .delete-btn:hover {
        background: rgba(220, 53, 69, 0.2);
    }

    .post-content {
        padding: 1.5rem;
        color: #e0e0e0;
        line-height: 1.7;
        font-size: 1.05rem;
    }

    .comments-section {
        margin-top: 2rem;
    }

    .comments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .comments-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #fff;
    }

    .comment-box {
        background: rgba(20, 20, 20, 0.4);
        border-radius: 8px;
        overflow: hidden;
        margin-top: 1rem;
    }

    .comment-form {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .comment-input {
        width: 100%;
        background: rgba(40, 40, 40, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        padding: 1rem;
        color: #fff;
        resize: vertical;
        min-height: 100px;
        margin-bottom: 1rem;
    }

    .comment-input:focus {
        outline: none;
        border-color: rgba(0, 200, 227, 0.4);
    }

    .comment-submit {
        background: rgb(0, 200, 227);
        color: #000;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
    }

    .comment-submit:hover {
        background: rgb(0, 220, 247);
    }

    .comment-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .comment-item {
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .comment-item:last-child {
        border-bottom: none;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
    }

    .comment-user {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .comment-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(0, 200, 227, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: rgb(0, 200, 227);
    }

    .comment-author {
        font-weight: 600;
        color: rgb(0, 200, 227);
    }

    .comment-time {
        color: #666;
        font-size: 0.85rem;
    }

    .comment-text {
        color: #e0e0e0;
        line-height: 1.6;
    }

    .no-comments {
        padding: 2rem;
        text-align: center;
        color: #666;
    }

    .no-comments i {
        font-size: 3rem;
        color: rgb(0, 200, 227);
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .post-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .author-avatar {
            margin-right: 0;
        }

        .post-actions {
            margin-top: 1rem;
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>
@endsection

@section('content')
<div class="blog-container">
    <div class="blog-header">
        <a href="{{ route('blogs.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Blog
        </a>
    </div>

    <div class="post-container">
        <div class="post-header">
            <div class="author-avatar">
                <i class="bi bi-chat-square-text-fill"></i>
            </div>
            <div class="post-meta">
                <h1 class="blog-title">{{ $blog->title }}</h1>
                <div class="post-date">
                    <a href="#" class="post-author">{{ $blog->user->name }}</a>
                    <span>{{ $blog->created_at->diffForHumans() }}</span>
                </div>
            </div>
            <div class="post-actions">
                @can('update', $blog)
                <a href="{{ route('blogs.edit', $blog) }}" class="action-btn">
                    <i class="bi bi-pencil-fill"></i> Edit
                </a>
                @endcan
                @can('delete', $blog)
                <form action="{{ route('blogs.destroy', $blog) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash-fill"></i> Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>
        <div class="post-content">
            {{ $blog->description }}
        </div>
    </div>

    <div class="comments-section">
        <div class="comments-header">
            <h2 class="comments-title">Comments</h2>
        </div>

        <div class="comment-box">
            @auth
            <div class="comment-form">
                <form action="{{ route('comments.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="blog_id" value="{{ $blog->id }}">
                    <textarea name="content" class="comment-input" placeholder="Write a comment..."></textarea>
                    <button type="submit" class="comment-submit">Post Comment</button>
                </form>
            </div>
            @endauth

            <div class="comment-list">
                @forelse($comments as $comment)
                <div class="comment-item">
                    <div class="comment-header">
                        <div class="comment-user">
                            <div class="comment-avatar">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <div class="comment-author">{{ $comment->user->name }}</div>
                                <div class="comment-time">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="comment-text">
                        {{ $comment->content }}
                    </div>
                </div>
                @empty
                <div class="no-comments">
                    <i class="bi bi-chat-text"></i>
                    <p>No comments yet. Be the first to comment!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 