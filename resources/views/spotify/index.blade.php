@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Add Songs to Playlist</span>
                    <a href="{{ route('spotify.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Refresh Playlists
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('spotify.add-tracks') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="playlist_id" class="form-label">Select Playlist</label>
                            <select name="playlist_id" id="playlist_id" class="form-select" required>
                                <option value="">Choose a playlist...</option>
                                @foreach($playlists->items as $playlist)
                                    <option value="{{ $playlist->id }}">{{ $playlist->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="songs" class="form-label">Paste Song Titles (one per line)</label>
                            <textarea name="songs" id="songs" class="form-control" rows="10" required
                                placeholder="Enter song titles here, one per line..."></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Add Songs to Playlist
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 