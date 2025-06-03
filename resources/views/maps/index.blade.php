@extends('layouts.app')

@section('title', 'Markers - Hajusrakendused')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    .markers-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .markers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .markers-title {
        font-size: 2rem;
        font-weight: 700;
        color: #fff;
    }

    .create-marker-btn {
        background: rgb(0, 200, 227);
        color: #000;
        padding: 0.75rem 1.5rem;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        border: none;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .create-marker-btn:hover {
        background: rgb(0, 220, 247);
        transform: translateY(-1px);
    }

    .map-container {
        margin-bottom: 2rem;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    #map {
        height: 500px;
        width: 100%;
        border: 2px solid var(--primary-color);
        overflow-y: scroll;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    #map::-webkit-scrollbar {
        display: none;
    }

    .marker-form {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(20, 20, 20, 0.9);
        padding: 1.5rem;
        border-radius: 8px;
        width: 300px;
        z-index: 1000;
        display: none;
        border: 1px solid rgba(0, 200, 227, 0.2);
    }

    .marker-form.active {
        display: block;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #fff;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        background: rgba(40, 40, 40, 0.4);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 4px;
        padding: 0.75rem;
        color: #fff;
        font-size: 0.9rem;
    }

    .form-control:focus {
        outline: none;
        border-color: rgba(0, 200, 227, 0.4);
        box-shadow: 0 0 0 2px rgba(0, 200, 227, 0.1);
    }

    .location-preview {
        margin-top: 1rem;
        padding: 0.75rem;
        background: rgba(0, 200, 227, 0.1);
        border-radius: 4px;
        font-size: 0.85rem;
        color: rgb(0, 200, 227);
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .btn {
        flex: 1;
        padding: 0.75rem;
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
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-submit {
        background: rgb(0, 200, 227);
        color: #000;
    }

    .btn-submit:hover {
        background: rgb(0, 220, 247);
    }

    .btn-cancel {
        background: rgba(100, 100, 100, 0.2);
        color: #fff;
    }

    .btn-cancel:hover {
        background: rgba(100, 100, 100, 0.3);
    }

    .markers-list {
        background: rgba(20, 20, 20, 0.4);
        border-radius: 8px;
        overflow: hidden;
    }

    .marker-item {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        transition: background 0.3s ease;
    }

    .marker-item:hover {
        background: rgba(0, 200, 227, 0.05);
    }

    .marker-item:last-child {
        border-bottom: none;
    }

    .marker-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(0, 200, 227, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: rgb(0, 200, 227);
    }

    .marker-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .marker-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        margin-bottom: 0.5rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .marker-name:hover {
        color: rgb(0, 200, 227);
    }

    .marker-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #666;
        font-size: 0.85rem;
    }

    .marker-coordinates {
        color: rgb(0, 200, 227);
    }

    .marker-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        gap: 0.5rem;
    }

    .action-btn {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
        border: none;
        text-decoration: none;
    }

    .action-btn i {
        font-size: 1rem;
    }

    .btn-view {
        background: rgba(0, 200, 227, 0.1);
        color: var(--primary-color);
    }

    .btn-view:hover {
        background: rgba(0, 200, 227, 0.2);
        color: var(--primary-color);
    }

    .btn-edit {
        background: rgba(255, 193, 7, 0.1);
        color: #ffc107;
    }

    .btn-edit:hover {
        background: rgba(255, 193, 7, 0.2);
        color: #ffc107;
    }

    .btn-delete {
        background: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .btn-delete:hover {
        background: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .no-markers {
        padding: 3rem;
        text-align: center;
        color: #666;
    }

    .no-markers i {
        font-size: 3rem;
        color: rgb(0, 200, 227);
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .marker-item {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .marker-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .marker-actions {
            flex-direction: row;
            align-items: center;
            justify-content: flex-start;
        }

        .marker-form {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            max-width: 400px;
        }
    }
</style>
@endsection

@section('content')
<div class="markers-container">
    <div class="markers-header">
        <h1 class="markers-title">Markers</h1>
        <button class="create-marker-btn" id="createMarkerBtn">
            <i class="bi bi-plus-lg"></i> Create New Marker
        </button>
    </div>

    <div class="map-container">
        <div id="map"></div>
        <div id="markerForm" class="marker-form">
            <form id="newMarkerForm">
                @csrf
                <div class="form-group">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter marker name">
                </div>
                <div class="form-group">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter marker description"></textarea>
                </div>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
                <div class="location-preview">
                    Selected location: <span id="locationPreview">Click on the map to select a location</span>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-submit">
                        <i class="bi bi-check-lg"></i> Save
                    </button>
                    <button type="button" class="btn btn-cancel" id="cancelMarker">
                        <i class="bi bi-x-lg"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="markers-list">
        @forelse($markers as $marker)
        <div class="marker-item">
            <div class="marker-icon">
                <i class="bi bi-geo-alt-fill"></i>
            </div>
            <div class="marker-content">
                <a href="{{ route('markers.show', $marker) }}" class="marker-name">{{ $marker->name }}</a>
                <div class="marker-meta">
                    <span class="marker-coordinates">{{ $marker->latitude }}, {{ $marker->longitude }}</span>
                    <span>{{ $marker->added->diffForHumans() }}</span>
                </div>
            </div>
            <div class="marker-actions">
                <a href="{{ route('markers.show', $marker) }}" class="action-btn btn-view">
                    <i class="bi bi-eye"></i> View
                </a>
                <a href="{{ route('markers.edit', $marker) }}" class="action-btn btn-edit">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('markers.destroy', $marker) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="action-btn btn-delete" onclick="return confirm('Are you sure?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="no-markers">
            <i class="bi bi-geo-alt"></i>
            <p>No markers yet. Click on the map to create your first marker!</p>
        </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kuressaareLat = 58.2478;
        const kuressaareLng = 22.5087;
        const defaultZoom = 8;
        
        const map = L.map('map').setView([kuressaareLat, kuressaareLng], defaultZoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);
        
        const markers = @json($markers);
        markers.forEach(marker => {
            L.marker([marker.latitude, marker.longitude])
                .addTo(map)
                .bindPopup(`<b>${marker.name}</b><br>${marker.description || ''}`)
                .on('click', function() {
                    this.openPopup();
                });
        });

        let selectedMarker = null;
        const markerForm = document.getElementById('markerForm');
        const locationPreview = document.getElementById('locationPreview');
        
        map.on('click', function(e) {
            markerForm.classList.add('active');
            
            document.getElementById('latitude').value = e.latlng.lat;
            document.getElementById('longitude').value = e.latlng.lng;
            
            locationPreview.textContent = 
                `${e.latlng.lat.toFixed(4)}, ${e.latlng.lng.toFixed(4)}`;
            
            if (selectedMarker) {
                map.removeLayer(selectedMarker);
            }
            
            selectedMarker = L.marker(e.latlng, {
                icon: L.divIcon({
                    className: 'selected-marker',
                    html: '<div style="background: var(--primary-color); width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px rgba(0, 200, 227, 0.5);"></div>'
                })
            }).addTo(map);
        });
        
        document.getElementById('cancelMarker').addEventListener('click', function() {
            markerForm.classList.remove('active');
            document.getElementById('newMarkerForm').reset();
            locationPreview.textContent = 'Click on the map to select a location';
            
            if (selectedMarker) {
                map.removeLayer(selectedMarker);
                selectedMarker = null;
            }
        });

        document.getElementById('newMarkerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            fetch('{{ route("markers.storeFromMap") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    L.marker([data.marker.latitude, data.marker.longitude])
                        .addTo(map)
                        .bindPopup(`<b>${data.marker.name}</b><br>${data.marker.description || ''}`)
                        .openPopup();
                        
                    markerForm.classList.remove('active');
                    document.getElementById('newMarkerForm').reset();
                    locationPreview.textContent = 'Click on the map to select a location';
                    
                    if (selectedMarker) {
                        map.removeLayer(selectedMarker);
                        selectedMarker = null;
                    }

                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the marker');
            });
        });

        document.getElementById('createMarkerBtn').addEventListener('click', function() {
            const markerForm = document.getElementById('markerForm');
            markerForm.classList.add('active');
            document.getElementById('latitude').value = '';
            document.getElementById('longitude').value = '';
            document.getElementById('locationPreview').textContent = 'Click on the map to select a location';
        });
    });
</script>
@endsection 