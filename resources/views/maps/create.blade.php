@extends('layouts.app')

@section('title', 'Create Marker - Hajusrakendused')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        width: 100%;
        border-radius: 8px;
        margin-bottom: 20px;
        border: 2px solid var(--primary-color);
    }
</style>
@endsection

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1>Create Marker</h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('markers.index') }}" class="btn btn-outline-cyan">
            <i class="bi bi-arrow-left"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="map"></div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('markers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control bg-custom-dark text-white border-secondary @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control bg-custom-dark text-white border-secondary @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', '58.2478') }}" required>
                            @error('latitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control bg-custom-dark text-white border-secondary @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', '22.5087') }}" required>
                            @error('longitude')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control bg-custom-dark text-white border-secondary @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-cyan">Create Marker</button>
                    </div>
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