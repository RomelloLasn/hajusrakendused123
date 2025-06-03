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

    .form-container {
        background: rgba(20, 20, 20, 0.4);
        border-radius: 8px;
        overflow: hidden;
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.75rem;
        font-weight: 600;
        color: #fff;
    }

    .form-control {
        width: 100%;
        background: rgba(40, 40, 40, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        padding: 0.75rem 1rem;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: rgba(0, 200, 227, 0.4);
        box-shadow: 0 0 0 2px rgba(0, 200, 227, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 200px;
    }

    .form-error {
        color: #dc3545;
        font-size: 0.875rem;
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
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-cancel {
        background: rgba(100, 100, 100, 0.2);
        color: #fff;
    }

    .btn-cancel:hover {
        background: rgba(100, 100, 100, 0.3);
    }

    .btn-submit {
        background: rgb(0, 200, 227);
        color: #000;
    }

    .btn-submit:hover {
        background: rgb(0, 220, 247);
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