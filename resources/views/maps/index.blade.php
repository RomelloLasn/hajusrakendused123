@extends('layouts.app')

@section('title', 'Markers - Hajusrakendused')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    body {
        background: #f6f0eb !important;
    }
    .markers-container {
        max-width: 1200px;
        margin: 3rem auto;
        padding: 0 1rem;
    }
    .markers-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
    }
    .markers-title {
        font-size: 2.1rem;
        font-weight: 700;
        color: #181818;
    }
    .create-marker-btn {
        background: #ff2222;
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 7px;
        font-weight: 700;
        text-decoration: none;
        transition: background 0.2s;
        border: none;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    }
    .create-marker-btn:hover {
        background: #b80000;
        color: #fff;
    }
    .map-container {
        margin-bottom: 2rem;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        position: relative;
        background: #fff;
    }
    #map {
        height: 400px;
        width: 100%;
        border-radius: 12px;
        border: 2px solid #ff2222;
        box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    }
    /* --- FIXED: Popup form hovers over map and page --- */
    .marker-form {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        width: 320px;
        z-index: 2000;
        display: none;
        border: 2px solid #ff2222;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        max-width: 95vw;
    }
    .marker-form.active {
        display: block;
        animation: popup-fade-in 0.2s;
    }
    @keyframes popup-fade-in {
        from { opacity: 0; transform: translate(-50%, -60%);}
        to { opacity: 1; transform: translate(-50%, -50%);}
    }
    .form-group {
        margin-bottom: 1rem;
    }
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #181818;
        font-weight: 600;
    }
    .form-control {
        width: 100%;
        background: #f6f0eb !important;
        border: 1.5px solid #ececec !important;
        border-radius: 7px;
        padding: 0.75rem;
        color: #181818 !important;
        font-size: 1rem;
        transition: border-color 0.3s;
    }
    .form-control:focus {
        border-color: #ff2222 !important;
        background: #f3e7df !important;
        color: #181818 !important;
        box-shadow: 0 0 0 2px #ffeaea;
    }
    .location-preview {
        margin-top: 1rem;
        padding: 0.75rem;
        background: #fbeeee;
        border-radius: 7px;
        font-size: 0.95rem;
        color: #ff2222;
        font-weight: 600;
    }
    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }
    .btn {
        flex: 1;
        padding: 0.75rem;
        border-radius: 7px;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    .btn-submit {
        background: #ff2222;
        color: #fff;
    }
    .btn-submit:hover {
        background: #b80000;
        color: #fff;
    }
    .btn-cancel {
        background: #fff;
        color: #ff2222;
        border: 2px solid #ff2222;
    }
    .btn-cancel:hover {
        background: #ff2222;
        color: #fff;
    }
    .markers-list {
        background: #fff;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 6px 32px rgba(0,0,0,0.08);
        margin-top: 2rem;
    }
    .marker-item {
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 1.5rem;
        padding: 1.5rem;
        border-bottom: 1px solid #f3e7df;
        transition: background 0.3s;
    }
    .marker-item:hover {
        background: #fbeeee;
    }
    .marker-item:last-child {
        border-bottom: none;
    }
    .marker-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fbeeee;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #ff2222;
    }
    .marker-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .marker-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: #181818;
        margin-bottom: 0.5rem;
        text-decoration: none;
        transition: color 0.2s;
    }
    .marker-name:hover {
        color: #ff2222;
    }
    .marker-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #888;
        font-size: 0.95rem;
    }
    .marker-coordinates {
        color: #ff2222;
        font-weight: 600;
    }
    .marker-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        gap: 0.5rem;
    }
    .action-btn {
        padding: 6px 16px;
        border-radius: 7px;
        font-size: 0.95rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        border: none;
        text-decoration: none;
        background: #fff;
        color: #ff2222;
        border: 2px solid #ff2222;
    }
    .action-btn i {
        font-size: 1rem;
    }
    .action-btn:hover {
        background: #ff2222;
        color: #fff;
    }
    .btn-view {
        border-color: #ff2222;
    }
    .btn-edit {
        border-color: #ffc107;
        color: #ffc107;
    }
    .btn-edit:hover {
        background: #ffc107;
        color: #fff;
    }
    .btn-delete {
        border-color: #dc3545;
        color: #dc3545;
    }
    .btn-delete:hover {
        background: #dc3545;
        color: #fff;
    }
    .no-markers {
        padding: 3rem;
        text-align: center;
        color: #888;
        background: #fff;
    }
    .no-markers i {
        font-size: 3rem;
        color: #ff2222;
        margin-bottom: 1rem;
    }
    @media (max-width: 900px) {
        .markers-container {
            max-width: 100%;
        }
        .marker-form {
            width: 95vw;
            max-width: 400px;
        }
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
            width: 90vw;
            max-width: 400px;
        }
        #map {
            height: 220px;
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
    </div>
    <!-- Move the markerForm outside of map-container for proper overlay -->
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
                    html: '<div style="background: #ff2222; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 10px #ff2222;"></div>'
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