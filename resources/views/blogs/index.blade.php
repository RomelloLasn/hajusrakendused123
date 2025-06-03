@extends('layouts.app')

@section('title', 'Blog - Hajusrakendused')

@section('styles')
<style>
    .blog-container {
        max-width: 1100px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .blog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e0e0e0;
    }

    .blog-title {
        font-size: 2rem;
        font-weight: 700;
        color: #181818;
    }

    .create-post-btn {
        background: var(--primary-color, #00c8e3);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .create-post-btn:hover {
        background: var(--primary-hover, #00a7bf);
        color: #fff;
        transform: translateY(-1px) scale(1.03);
    }

    .posts-container {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        border: 1px solid #ececec;
    }

    .post-item {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid #ececec;
        transition: background 0.3s ease;
        align-items: center;
    }

    .post-item:hover {
        background: #f4ede7;
    }

    .post-item:last-child {
        border-bottom: none;
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
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .post-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .post-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #181818;
        margin-bottom: 0.5rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .post-title:hover {
        color: var(--primary-color, #00c8e3);
    }

    .post-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #888;
        font-size: 0.95rem;
    }

    .post-author {
        color: var(--primary-color, #00c8e3);
        text-decoration: none;
        font-weight: 500;
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
        font-size: 0.95rem;
    }

    .stat i {
        color: var(--primary-color, #00c8e3);
    }

    .pagination {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 0.5rem;
    }

    .page-link {
        background: #f4ede7;
        border: 1px solid #ececec;
        color: #181818;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .page-link:hover {
        background: var(--primary-color, #00c8e3);
        color: #fff;
    }

    .page-link.active {
        background: var(--primary-color, #00c8e3);
        color: #fff;
        border-color: var(--primary-color, #00c8e3);
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
        <div class="post-item" style="text-align: center; padding: 3rem 1.5rem; background: #f4ede7;">
            <div style="color: #888;">
                <i class="bi bi-journal-text" style="font-size: 3rem; color: var(--primary-color, #00c8e3); margin-bottom: 1rem;"></i>
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