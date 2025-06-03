@extends('layouts.app')

@section('title', 'Monsters')

@section('styles')
<style>
    .monster-card {
        background-color: var(--secondary-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }
    .monster-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 200, 227, 0.15);
        border-color: var(--primary-hover);
    }
    .monster-card .card-img-top-placeholder {
        height: 160px;
        background-color: #2a2a2a;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }
    .monster-card .card-img-top-placeholder .icon {
        font-size: 3.5rem;
        color: var(--primary-color);
    }
    .monster-card .card-body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .monster-card .card-title {
        color: var(--primary-color);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    .monster-card .description-title,
    .monster-card .behavior-title,
    .monster-card .habitat-title {
        color: var(--text-color);
        font-weight: 500;
        font-size: 0.8rem;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .monster-card .card-text {
        color: #bbb;
        font-size: 0.8rem;
        line-height: 1.4;
        margin-bottom: 0.75rem;
    }
    .page-title {
        color: #fff;
        font-weight: 700;
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
        padding-bottom: 0.5rem;
    }
    .page-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: var(--primary-color);
    }

    .form-container {
        background-color: var(--secondary-bg);
        padding: 1.5rem;
        border-radius: 8px;
        margin-bottom: 2.5rem;
        border: 1px solid var(--border-color);
    }
    .form-container h2 {
        color: var(--primary-color);
        margin-bottom: 1rem;
        text-align: center;
        font-weight: 600;
        font-size: 1.5rem;
    }
    .form-label {
        color: #ccc;
        margin-bottom: 0.3rem;
        font-weight: 500;
        font-size: 0.9rem;
    }
    .form-control,
    .form-control:focus {
        background-color: #2a2a2a;
        color: var(--text-color);
        border: 1px solid var(--border-color);
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        box-shadow: none !important;
    }
    .form-control:focus {
        border-color: var(--primary-color);
    }
    .form-control::placeholder {
        color: #666;
    }
    textarea.form-control {
        min-height: 80px;
    }
    .invalid-feedback {
        color: #ff6b6b;
        font-size: 0.8rem;
    }
    .alert-success {
        background-color: rgba(0, 200, 227, 0.1);
        border-color: rgba(0, 200, 227, 0.2);
        color: var(--primary-color);
        font-size: 0.9rem;
        padding: 0.75rem 1rem;
    }
    .form-container .btn-lg {
        padding: 0.6rem 1.2rem;
        font-size: 0.95rem;
    }

    .delete-monster-btn {
        position: absolute;
        top: 8px;
        right: 8px;
        background-color: rgba(180, 50, 60, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 5px;
        width: 26px;
        height: 26px;
        font-size: 0.9rem;
        line-height: 1;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        opacity: 0.6;
        z-index: 10;
    }
    .monster-card:hover .delete-monster-btn {
        opacity: 1;
    }
    .delete-monster-btn:hover {
        background-color: rgba(180, 50, 60, 1);
        border-color: rgba(255, 255, 255, 0.3);
        transform: scale(1.1);
    }
</style>
@endsection

@section('content')
<div class="container mt-4">

    {{-- Monster Creation Form (adjusted styles) --}}
    <div class="form-container">
        <h2>Add New Monster</h2>

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

        <form action="{{ route('monsters.store') }}" method="POST">
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
                    <label for="habitat" class="form-label">Habitat (Optional)</label>
                    <input type="text" class="form-control @error('habitat') is-invalid @enderror" id="habitat" name="habitat" value="{{ old('habitat') }}">
                    @error('habitat')
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
                <div class="col-12 mb-2">
                    <label for="behavior" class="form-label">Behavior</label>
                    <textarea class="form-control @error('behavior') is-invalid @enderror" id="behavior" name="behavior" rows="2" required>{{ old('behavior') }}</textarea>
                    @error('behavior')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12 mb-3">
                    <label for="image" class="form-label">Image URL (Optional)</label>
                    <input type="url" class="form-control @error('image') is-invalid @enderror" id="image" name="image" value="{{ old('image') }}" placeholder="https://example.com/image.jpg">
                     @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
               </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-cyan btn-lg">Add Monster</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Existing Monster Grid --}}
    <h1 class="page-title">Robins Horror Games Monsters</h1>
    <a href="https://hajusrakendused.tak22parnoja.itmajakas.ee/current/public/index.php/api/monsters"><h3 class="page-title">https://hajusrakendused.tak22parnoja.itmajakas.ee/current/public/index.php/api/monsters</h3></a>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-5 g-4">
        @forelse ($monsters as $monster)
            <div class="col">
                <div class="card monster-card h-100">
                    {{-- Delete Button for Admins --}}
                    @auth
                        @if(Auth::user()->isAdmin())
                            <form action="{{ route('monsters.destroy', $monster->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $monster->title }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-monster-btn" title="Delete Monster">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        @endif
                    @endauth

                    {{-- Display Image or Placeholder --}}
                     @if($monster->image)
                        <img src="{{ $monster->image }}" class="card-img-top monster-image" alt="{{ $monster->title }}" style="height: 160px; object-fit: cover; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                    @else
                        <div class="card-img-top-placeholder">
                             <i class="bi bi-shield-shaded icon"></i>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $monster->title }}</h5>
                        <div>
                            <h6 class="description-title">Description</h6>
                            <p class="card-text">{{ $monster->description }}</p>
                        </div>
                        <div>
                            <h6 class="behavior-title">Behavior</h6>
                            <p class="card-text">{{ $monster->behavior }}</p>
                        </div>
                        @if($monster->habitat)
                        <div class="mt-1">
                            <h6 class="habitat-title">Habitat</h6>
                            <p class="card-text mb-0">{{ $monster->habitat }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center text-muted mt-5">No monsters found in the compendium yet.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection 
</body>
</html> 