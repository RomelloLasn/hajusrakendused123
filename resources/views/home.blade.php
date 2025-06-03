{{-- filepath: resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home - Hajusrakendused')

@section('styles')
<style>
    body {
        background: #f4ede7 !important;
    }
    /* Remove the portfolio-nav (red arrow) */
    .rakendused-card {
        background: transparent;
        border-radius: 16px;
        max-width: 900px;
        margin: 0 auto;
        padding: 2.5rem 2rem;
    }
    .rakendused-title {
        color: #181818;
        font-size: 2.2rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-align: center;
    }
    .features-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }
    .feature-btn {
        background: #fff;
        color: #181818;
        border: 2px solid #181818;
        border-radius: 16px;
        padding: 2.5rem 1rem;
        text-align: center;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        transition: 
            background 0.2s,
            color 0.2s,
            border-color 0.2s,
            box-shadow 0.2s;
        box-shadow: 0 2px 12px rgba(0,0,0,0.04);
        font-weight: 600;
    }
    .feature-btn:hover {
        background: #f4ede7;
        color: #181818;
        border-color: #b48c5a;
        box-shadow: 0 6px 24px rgba(0,0,0,0.10);
    }
    .feature-btn i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
        transition: color 0.2s;
    }
    .feature-btn:hover i {
        color: #b48c5a;
    }
    .feature-title {
        margin-top: 0.5rem;
        font-weight: 600;
        font-size: 1.15rem;
        letter-spacing: 1px;
    }
    @media (max-width: 900px) {
        .features-container {
            grid-template-columns: 1fr;
        }
        .rakendused-card {
            padding: 1.5rem 0.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="rakendused-card">
    <h2 class="rakendused-title">Rakendused</h2>
    <div class="features-container">
        <a href="{{ route('weather.index') }}" class="feature-btn weather">
            <i class="bi bi-cloud-sun-fill"></i>
            <div class="feature-title">Weather</div>
        </a>
        <a href="{{ route('markers.index') }}" class="feature-btn maps">
            <i class="bi bi-map-fill"></i>
            <div class="feature-title">Maps</div>
        </a>
        <a href="{{ route('blogs.index') }}" class="feature-btn blog">
            <i class="bi bi-journal-richtext"></i>
            <div class="feature-title">Blog</div>
        </a>
        <a href="{{ route('products.index') }}" class="feature-btn shop">
            <i class="bi bi-shop"></i>
            <div class="feature-title">Shop</div>
        </a>
        <a href="{{ route('monsters.index') }}" class="feature-btn api">
            <i class="bi bi-code-slash"></i>
            <div class="feature-title">API</div>
        </a>
        <a href="{{ url('api-viewer') }}" class="feature-btn api-viewer">
            <i class="bi bi-eye-fill"></i>
            <div class="feature-title">API Viewer</div>
        </a>
    </div>
</div>
@endsection