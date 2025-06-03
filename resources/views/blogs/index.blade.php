@extends('layouts.app')

@section('title', 'Blog - Hajusrakendused')

@section('styles')
<style>
    .blog-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .blog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .blog-title {
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
    }

    .create-post-btn {
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
    }

    .create-post-btn:hover {
        background: rgb(0, 220, 247);
        transform: translateY(-1px);
    }

    .posts-container {
        background: rgba(20, 20, 20, 0.4);
        border-radius: 8px;
        overflow: hidden;
    }

    .post-item {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: background 0.3s ease;
    }

    .post-item:hover {
        background: rgba(0, 200, 227, 0.05);
    }

    .post-item:last-child {
        border-bottom: none;
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
    }

    .post-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .post-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .post-title:hover {
        color: rgb(0, 200, 227);
    }

    .post-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #666;
        font-size: 0.85rem;
    }

    .post-author {
        color: rgb(0, 200, 227);
        text-decoration: none;
    }

    .post-stats {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        gap: 0.5rem;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #888;
        font-size: 0.9rem;
    }

    .stat i {
        color: rgb(0, 200, 227);
    }

    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .page-link {
        background: rgba(0, 200, 227, 0.1);
        border: 1px solid rgba(0, 200, 227, 0.2);
        color: #fff;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background: rgba(0, 200, 227, 0.2);
    }

    .page-link.active {
        background: rgb(0, 200, 227);
        color: #000;
    }

    @media (max-width: 768px) {
        .post-item {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .author-avatar {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .post-stats {
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="blog-container">
    <div class="blog-header">
        <h1 class="blog-title">Blog</h1>
        <a href="{{ route('blogs.create') }}" class="create-post-btn">Create Post</a>
    </div>

    <div class="posts-container">
        @forelse($posts as $post)
        <div class="post-item">
            <div class="author-avatar">
                <i class="bi bi-chat-square-text-fill"></i>
            </div>
            <div class="post-content">
                <a href="{{ route('blogs.show', $post->id) }}" class="post-title">{{ $post->title }}</a>
                <div class="post-meta">
                    <a href="#" class="post-author">{{ $post->user->name }}</a>
                </div>
            </div>
            <div class="post-stats">
                <div class="stat">
                    <i class="bi bi-chat-fill"></i>
                    <span>{{ $post->comments_count ?? 0 }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="post-item" style="text-align: center; padding: 3rem 1.5rem;">
            <div style="color: #666;">
                <i class="bi bi-journal-text" style="font-size: 3rem; color: rgb(0, 200, 227); margin-bottom: 1rem;"></i>
                <p style="margin: 0;">No posts yet. Be the first to create one!</p>
            </div>
        </div>
        @endforelse
    </div>

    <div class="pagination">
        {{ $posts->links() }}
    </div>
</div>
@endsection 