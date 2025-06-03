@extends('layouts.app')

@section('title', 'Create Marker - Hajusrakendused')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    body {
        background: #f6f0eb !important;
    }
    .marker-create-container {
        max-width: 700px;
        margin: 3rem auto;
        background: #fff;
        border-radius: 18px;
        box-shadow: 0 6px 32px rgba(0,0,0,0.08);
        padding: 2.5rem 2rem 2rem 2rem;
    }
    .marker-create-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .marker-create-title {
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
    .invalid-feedback {
        color: #dc3545;
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
        margin-top: 0.5rem;
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
        .marker-create-container {
            padding: 1.2rem 0.5rem;
        }
        #map {
            height: 200px;
        }
    }
</style>
@endsection

@section('content')
<div class="marker-create-container">
    <div class="marker-create-header">
        <h1 class="marker-create-title">Create Marker</h1>
        <a href="{{ route('markers.index') }}" class="back-link">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
    <div id="map"></div>
    <form action="{{ route('markers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', '58.2478') }}" required>
                @error('latitude')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', '22.5087') }}" required>
                @error('longitude')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('markers.index') }}" class="btn btn-outline-red">
                <i class="bi bi-x-lg"></i> Cancel
            </a>
            <button type="submit" class="btn btn-red">
                <i class="bi bi-check-lg"></i> Create Marker
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kuressaareLat = 58.2478;
        const kuressaareLng = 22.5087;
        const defaultZoom = 13;

        const map = L.map('map').setView([kuressaareLat, kuressaareLng], defaultZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let marker = null;

        const latField = document.getElementById('latitude');
        const lngField = document.getElementById('longitude');
        
        marker = L.marker([kuressaareLat, kuressaareLng]).addTo(map);
        
        map.on('click', function(e) {
            latField.value = e.latlng.lat.toFixed(8);
            lngField.value = e.latlng.lng.toFixed(8);

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }
        });
    });
</script>
@endsection