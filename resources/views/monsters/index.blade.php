@extends('layouts.app')

@section('title', 'Monsters')

@section('styles')
<style>
    body {
        background: #181a1b !important;
    }
    .monster-card {
        background: linear-gradient(135deg, #232526 0%, #414345 100%);
        border: 1.5px solid #1de9b6;
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(.4,2,.6,1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
        box-shadow: 0 4px 24px rgba(0,200,227,0.08);
    }
    .monster-card:hover {
        transform: translateY(-7px) scale(1.03);
        box-shadow: 0 12px 32px rgba(0,200,227,0.18);
        border-color: #00bfae;
    }
    .monster-card .card-img-top-placeholder {
        height: 160px;
        background: #232526;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #555;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .monster-card .card-img-top-placeholder .icon {
        font-size: 3.5rem;
        color: #1de9b6;
    }
    .monster-card .card-body {
        padding: 1.2rem;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .monster-card .card-title {
        color: #1de9b6;
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: 1.2rem;
        letter-spacing: 0.5px;
    }
    .monster-card .description-title,
    .monster-card .behavior-title,
    .monster-card .habitat-title {
        color: #b2dfdb;
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 0.2rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .monster-card .card-text {
        color: #e0f2f1;
        font-size: 0.93rem;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }
    .page-title {
        color: #fff;
        font-weight: 800;
        margin-bottom: 2rem;
        text-align: center;
        position: relative;
        padding-bottom: 0.5rem;
        letter-spacing: 1px;
    }
    .page-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 70px;
        height: 3px;
        background: linear-gradient(90deg, #1de9b6 0%, #00bfae 100%);
        border-radius: 2px;
    }
    .form-container {
        background: linear-gradient(135deg, #232526 0%, #414345 100%);
        padding: 2rem 1.5rem 1.5rem 1.5rem;
        border-radius: 14px;
        margin-bottom: 2.5rem;
        border: 1.5px solid #1de9b6;
        box-shadow: 0 2px 16px rgba(0,200,227,0.07);
    }
    .form-container h2 {
        color: #1de9b6;
        margin-bottom: 1.2rem;
        text-align: center;
        font-weight: 700;
        font-size: 1.5rem;
        letter-spacing: 1px;
    }
    .form-label {
        color: #b2dfdb;
        margin-bottom: 0.3rem;
        font-weight: 600;
        font-size: 0.97rem;
    }
    .form-control,
    .form-control:focus {
        background: #232526;
        color: #e0f2f1;
        border: 1.5px solid #1de9b6;
        padding: 0.55rem 0.85rem;
        font-size: 1rem;
        border-radius: 7px;
        box-shadow: none !important;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #00bfae;
        background: #232526;
        color: #fff;
    }
    .form-control::placeholder {
        color: #80cbc4;
        opacity: 1;
    }
    textarea.form-control {
        min-height: 80px;
    }
    .invalid-feedback {
        color: #ff6b6b;
        font-size: 0.85rem;
    }
    .alert-success {
        background: rgba(29,233,182,0.08);
        border-color: #1de9b6;
        color: #1de9b6;
        font-size: 1rem;
        padding: 0.85rem 1.1rem;
        border-radius: 7px;
    }
    .form-container .btn-lg {
        padding: 0.7rem 1.5rem;
        font-size: 1.05rem;
        font-weight: 700;
        background: linear-gradient(90deg, #1de9b6 0%, #00bfae 100%);
        color: #181a1b;
        border: none;
        border-radius: 7px;
        transition: background 0.2s, color 0.2s;
        box-shadow: 0 2px 8px rgba(0,200,227,0.09);
    }
    .form-container .btn-lg:hover {
        background: linear-gradient(90deg, #00bfae 0%, #1de9b6 100%);
        color: #fff;
    }
    .delete-monster-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(180, 50, 60, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        border-radius: 7px;
        width: 30px;
        height: 30px;
        font-size: 1.1rem;
        line-height: 1;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        opacity: 0.7;
        z-index: 10;
    }
    .monster-card:hover .delete-monster-btn {
        opacity: 1;
    }
    .delete-monster-btn:hover {
        background: #b4323c;
        border-color: #fff;
        transform: scale(1.13);
    }
    @media (max-width: 768px) {
        .form-container {
            padding: 1rem 0.5rem;
        }
        .monster-card .card-img-top-placeholder,
        .monster-card img.monster-image {
            height: 110px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container mt-4">

    {{-- Monster Creation Form --}}
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
                    <button type="submit" class="btn btn-lg">Add Monster</button>
                </div>
            </div>
        </form>
    </div>

    {{-- Existing Monster Grid --}}
    <h1 class="page-title">Robins Horror Games Monsters</h1>
    <a href="https://hajusrakendused.tak22parnoja.itmajakas.ee/current/public/index.php/api/monsters">
        <h3 class="page-title" style="font-size:1.1rem; margin-bottom:2rem; color:#1de9b6;">
            https://hajusrakendused.tak22parnoja.itmajakas.ee/current/public/index.php/api/monsters
        </h3>
    </a>

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
                        <img src="{{ $monster->image }}" class="card-img-top monster-image" alt="{{ $monster->title }}" style="height: 160px; object-fit: cover; border-top-left-radius: 12px; border-top-right-radius: 12px;">
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