@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Spotify Login</div>
                <div class="card-body text-center">
                    <h4 class="mb-4">Welcome to Spotify Playlist Auto-Adder</h4>
                    <p class="mb-4">Connect your Spotify account to start adding songs to your playlists.</p>
                    <a href="{{ $authUrl }}" class="btn btn-success">
                        <i class="bi bi-spotify me-2"></i>Login with Spotify
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 