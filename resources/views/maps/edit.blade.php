@extends('layouts.app')

@section('title', 'Edit Marker - Hajusrakendused')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    body {
        background: #f6f0eb !important;
    }
    .marker-edit-container {
        max-width: 700px;
        margin: 3rem auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 6px 32px rgba(0,0,0,0.08);
        padding: 2.5rem 2rem 2rem 2rem;
    }
    .marker-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .marker-edit-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #181818;
        margin-bottom: 0;
    }
    .back-link {
        color: #ff2222;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.05rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: color 0.2s;
    }
    .back-link:hover {
        color: #b80000;
        text-decoration: underline;
    }
    #map {
        height: 320px;
        width: 100%;
        border-radius: 10px;
        margin-bottom: 24px;
        border: 2px solid #ff2222;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    }
    .form-label {
        color: #181818;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    .form-control {
        background: #f6f0eb !important;
        color: #181818 !important;
        border: 1.5px solid #ececec !important;
        border-radius: 7px;
        transition: border-color 0.3s;
        font-size: 1rem;
    }
    .form-control:focus {
        border-color: #ff2222 !important;
        background: #f3e7df !important;
        color: #181818 !important;
        box-shadow: 0 0 0 2px #ffeaea;
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
    .btn-red, .btn-outline-red {
        background: #ff2222;
        color: #fff;
        border: none;
        border-radius: 7px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: background 0.2s;
        padding: 0.7rem 2rem;
        font-size: 1rem;
    }
    .btn-red:hover, .btn-outline-red:hover {
        background: #b80000;
        color: #fff;
    }
    .btn-outline-red {
        background: #fff;
        color: #ff2222;
        border: 2px solid #ff2222;
    }
    .btn-outline-red:hover {
        background: #ff2222;
        color: #fff;
    }
    @media (max-width: 768px) {
        .marker-edit-container {
            padding: 1.2rem 0.5rem;
        }
        #map {
            height: 200px;
        }
    }
</style>
@endsection

@section('content')
<div class="marker-edit-container">
    <div class="marker-edit-header">
        <h1 class="marker-edit-title">Edit Marker</h1>
        <a href="{{ route('markers.show', $marker) }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to Marker
        </a>
    </div>
    <div id="map"></div>
    <form action="{{ route('markers.update', $marker) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $marker->name) }}" required>
            @error('name')
            <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $marker->description) }}</textarea>
            @error('description')
            <div class="form-error">{{ $message }}</div>
            @enderror
        </div>

        <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude', $marker->latitude) }}">
        <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude', $marker->longitude) }}">

        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('markers.show', $marker) }}" class="btn btn-outline-red">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
            <button type="submit" class="btn btn-red">
                <i class="bi bi-check-lg"></i> Update Marker
            </button>
        </div>
    </form>
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