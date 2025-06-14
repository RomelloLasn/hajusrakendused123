@extends('layouts.app')

@section('title', $marker->name . ' - Markers - Hajusrakendused')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    .marker-container {
        max-width: 1000px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .marker-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }

    .marker-title {
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

    .card {
        background: rgba(20, 20, 20, 0.4);
        border-radius: 8px;
        border: none;
        box-shadow: 0 4px 24px rgba(0,0,0,0.10);
        overflow: hidden;
    }

    .card-body {
        color: #e0e0e0;
        padding: 2rem;
    }

    .marker-map {
        height: 400px;
        width: 100%;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px 8px 0 0;
    }

    .marker-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #66c;
        font-size: 0.95rem;
        margin-bottom: 1.5rem;
    }

    .marker-coordinates {
        color: rgb(0, 200, 227);
    }

    .marker-description {
        color: #e0e0e0;
        line-height: 1.7;
        font-size: 1.05rem;
        margin-bottom: 2rem;
    }

    .marker-actions {
        display: flex;
        gap: 0.75rem;
    }

    .action-btn {
        background: rgb(0, 200, 227);
        color: #000;
        border: none;
        border-radius: 4px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background 0.3s;
        padding: 0.5rem 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
        text-decoration: none;
    }

    .action-btn:hover {
        background: rgb(0, 220, 247);
        color: #000;
    }

    .delete-btn {
        background: rgba(220, 53, 69, 0.1);
        color: rgb(220, 53, 69);
        border: 1px solid rgba(220, 53, 69, 0.2);
    }

    .delete-btn:hover {
        background: rgba(220, 53, 69, 0.2);
        color: rgb(220, 53, 69);
    }

    @media (max-width: 768px) {
        .marker-actions {
            flex-direction: column;
        }
        .card-body {
            padding: 1rem;
        }
        .marker-map {
            height: 250px;
        }
    }
</style>
@endsection

@section('content')
<div class="marker-container">
    <div class="marker-header">
        <a href="{{ route('markers.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Markers
        </a>
    </div>

    <div class="card">
        <div id="map" class="marker-map"></div>
        <div class="card-body">
            <h1 class="marker-title">{{ $marker->name }}</h1>
            <div class="marker-meta">
                <span class="marker-coordinates">{{ $marker->latitude }}, {{ $marker->longitude }}</span>
                <span>{{ $marker->added->diffForHumans() }}</span>
            </div>
            <div class="marker-description">
                {{ $marker->description ?: 'No description provided.' }}
            </div>
            <div class="marker-actions">
                <a href="{{ route('markers.edit', $marker) }}" class="action-btn">
                    <i class="bi bi-pencil-fill"></i> Edit
                </a>
                <form action="{{ route('markers.destroy', $marker) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn delete-btn" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash-fill"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $marker->latitude }};
        const lng = {{ $marker->longitude }};
        
        const map = L.map('map').setView([lat, lng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        L.marker([lat, lng])
            .addTo(map)
            .bindPopup('<b>{{ $marker->name }}</b><br>{{ $marker->description ? e($marker->description) : "" }}')
            .openPopup();
    });
</script>
@endsection