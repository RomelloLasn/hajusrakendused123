@extends('layouts.app')

@section('title', 'Edit Marker - Hajusrakendused')

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

    #map {
        height: 400px;
        width: 100%;
        border-radius: 8px;
        margin-bottom: 1.5rem;
        border: 2px solid var(--primary-color);
    }
</style>
@endsection

@section('content')
<div class="marker-container">
    <div class="marker-header">
        <h1 class="marker-title">Edit Marker</h1>
        <a href="{{ route('markers.show', $marker) }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Back to Marker
        </a>
    </div>

    <div class="form-container">
        <div id="map"></div>
        <form action="{{ route('markers.update', $marker) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $marker->name) }}" required>
                @error('name')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $marker->description) }}</textarea>
                @error('description')
                <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $marker->latitude) }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $marker->longitude) }}">

            <div class="form-actions">
                <a href="{{ route('markers.show', $marker) }}" class="btn btn-cancel">
                    <i class="bi bi-x-lg"></i> Cancel
                </a>
                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-check-lg"></i> Update Marker
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const latField = document.getElementById('latitude');
        const lngField = document.getElementById('longitude');
        
        const markerLat = parseFloat(latField.value);
        const markerLng = parseFloat(lngField.value);

        const map = L.map('map').setView([markerLat, markerLng], 15);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        let marker = L.marker([markerLat, markerLng]).addTo(map);

        map.on('click', function(e) {
            latField.value = e.latlng.lat.toFixed(8);
            lngField.value = e.latlng.lng.toFixed(8);
            
            marker.setLatLng(e.latlng);
        });
    });
</script>
@endsection 