@extends('layouts.app')

@section('content')
<div class="container mt-4">

    {{-- News Creation Form --}}
    <div class="form-container">
        <h2>Add News Article</h2>

        {{-- Display Success Message --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('web.news.store') }}" method="POST">
            @csrf
            <div class="row g-2">
                <div class="col-md-6 mb-2">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" class="form-control @error('author') is-invalid @enderror" id="author" name="author" value="{{ old('author') }}" required>
                    @error('author')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mb-2">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-2">
                    <label for="published_at" class="form-label">Published At</label>
                    <input type="date" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at') }}" required>
                    @error('published_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="image" class="form-label">Image URL (Optional)</label>
                    <input type="url" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image') }}" placeholder="https://example.com/image.jpg">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-lg">Add News</button>
                </div>
            </div>
        </form>
    </div>

    
    <h1 class="page-title">Latest News</h1>
    <a href="{{ url('api/news') }}">
        <h3 class="page-title" style="font-size:1.1rem; margin-bottom:2rem; color:#1de9b6;">
            {{ url('api/news') }}
        </h3>
    </a>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
        @forelse ($news as $item)
            <div class="col">
                <div class="card monster-card h-100">
                    
                    @if($item->image)
                        <img src="{{ $item->image }}" class="card-img-top monster-image" alt="{{ $item->title }}" style="height: 160px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    @else
                        <div class="card-img-top-placeholder">
                            <i class="bi bi-newspaper icon"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <div>
                            <h6 class="description-title">Description</h6>
                            <p class="card-text">{{ $item->description }}</p>
                        </div>
                        <div>
                            <h6 class="behavior-title">Author</h6>
                            <p class="card-text">{{ $item->author }}</p>
                        </div>
                        <div class="mt-1">
                            <h6 class="habitat-title">Published At</h6>
                            <p class="card-text mb-0">{{ $item->published_at }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted mt-5">No news articles found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection