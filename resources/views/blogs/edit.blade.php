@extends('layouts.app')

@section('title', 'Edit Post - Hajusrakendused')

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

    .form-container {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.07);
        padding: 2.5rem 2rem;
        border: 1px solid #ececec;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: #181818;
    }

    .form-control {
        width: 100%;
        background: #f4ede7;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        color: #181818;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color, #00c8e3);
        box-shadow: 0 0 0 2px rgba(0, 200, 227, 0.08);
        background: #fff;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 160px;
    }

    .form-error {
        color: #dc3545;
        font-size: 0.95rem;
        margin-top: 0.5rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-cancel {
        background: transparent;
        color: #181818;
        border: 1px solid #e0e0e0;
    }

    .btn-cancel:hover {
        background: #f4ede7;
        color: var(--primary-color, #00c8e3);
        border-color: var(--primary-color, #00c8e3);
    }

    .btn-submit {
        background: var(--primary-color, #00c8e3);
        color: #fff;
        border: 1px solid var(--primary-color, #00c8e3);
    }

    .btn-submit:hover {
        background: var(--primary-hover, #00a7bf);
        border-color: var(--primary-hover, #00a7bf);
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="blog-container">
    <div class="blog-header">
        <h1 class="blog-title">Edit Post</h1>
        <a href="{{ route('blogs.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Blog
        </a>
    </div>

    <div class="form-container">
        <form action="{{ route('blogs.update', $blog) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $blog->title) }}" required>
                @error('title')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Content</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $blog->description) }}</textarea>
                @error('description')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('blogs.show', $blog) }}" class="btn btn-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-check-lg"></i> Update Post
                </button>
            </div>
        </form>
    </div>
</div>
@endsection