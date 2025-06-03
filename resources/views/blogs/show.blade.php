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
        color: #181818;
    }

    .back-btn {
        color: var(--primary-color, #00c8e3);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        color: var(--primary-hover, #00a7bf);
    }

    .post-container {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        border: 1px solid #ececec;
    }

    .post-header {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid #ececec;
    }

    .author-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--primary-color, #00c8e3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #fff;
        margin-right: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .post-meta {
        flex: 1;
    }

    .post-author {
        font-size: 1rem;
        color: var(--primary-color, #00c8e3);
        text-decoration: none;
        font-weight: 600;
    }

    .post-date {
        font-size: 0.85rem;
        color: #888;
        margin-top: 0.25rem;
    }

    .post-actions {
        display: flex;
        gap: 0.75rem;
    }

    .action-btn {
        background: #f4ede7;
        color: var(--primary-color, #00c8e3);
        border: 1px solid #e0e0e0;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .action-btn:hover {
        background: var(--primary-color, #00c8e3);
        color: #fff;
        border-color: var(--primary-color, #00c8e3);
    }

    .delete-btn {
        background: #fff0f0;
        color: #dc3545;
        border: 1px solid #f5c2c7;
    }

    .delete-btn:hover {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }

    .post-content {
        padding: 1.5rem;
        color: #222;
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
        color: #181818;
    }

    .comment-box {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        margin-top: 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid #ececec;
    }

    .comment-form {
        padding: 1.5rem;
        border-bottom: 1px solid #ececec;
    }

    .comment-input {
        width: 100%;
        background: #f4ede7;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 1rem;
        color: #181818;
        resize: vertical;
        min-height: 100px;
        margin-bottom: 1rem;
        font-size: 1rem;
    }

    .comment-input:focus {
        outline: none;
        border-color: var(--primary-color, #00c8e3);
        box-shadow: 0 0 0 2px rgba(0, 200, 227, 0.08);
        background: #fff;
    }

    .comment-submit {
        background: var(--primary-color, #00c8e3);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
    }

    .comment-submit:hover {
        background: var(--primary-hover, #00a7bf);
        color: #fff;
    }

    .comment-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .comment-item {
        padding: 1.5rem;
        border-bottom: 1px solid #ececec;
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
        background: var(--primary-color, #00c8e3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .comment-author {
        font-weight: 600;
        color: var(--primary-color, #00c8e3);
    }

    .comment-time {
        color: #888;
        font-size: 0.85rem;
    }

    .comment-text {
        color: #222;
        line-height: 1.6;
    }

    .no-comments {
        padding: 2rem;
        text-align: center;
        color: #888;
    }

    .no-comments i {
        font-size: 3rem;
        color: var(--primary-color, #00c8e3);
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