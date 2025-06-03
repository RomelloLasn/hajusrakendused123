<?php

namespace App\Http\Controllers;

use App\Services\SpotifyService;
use Illuminate\Http\Request;

class SpotifyController extends Controller
{
    private $spotifyService;

    public function __construct(SpotifyService $spotifyService)
    {
        $this->spotifyService = $spotifyService;
    }

    public function index()
    {
        // Debug: Check if config values are loaded
        $config = [
            'client_id' => "5e7761d5c95b4aada1139bceefa9c227",
            'client_secret' => "55bea4455e8a4572b7115c91dcc7df80",
            'redirect' => "https://hajusrakendused.tak22parnoja.itmajakas.ee/current/public/index.php/spotify/callback"
        ];

        if (!session('spotify_token')) {
            return view('spotify.login', [
                'authUrl' => $this->spotifyService->getAuthUrl()
            ]);
        }

        $this->spotifyService->setAccessToken(session('spotify_token'));
        $playlists = $this->spotifyService->getUserPlaylists();

        return view('spotify.index', [
            'playlists' => $playlists
        ]);
    }

    public function callback(Request $request)
    {
        $token = $this->spotifyService->getAccessToken($request->code);
        session(['spotify_token' => $token]);

        return redirect()->route('spotify.index');
    }

    public function addTracks(Request $request)
    {
        $request->validate([
            'songs' => 'required|string',
            'playlist_id' => 'required|string'
        ]);

        $this->spotifyService->setAccessToken(session('spotify_token'));

        $songs = array_filter(explode("\n", $request->songs));
        $trackUris = [];

        foreach ($songs as $song) {
            $track = $this->spotifyService->searchTrack($song);
            if ($track) {
                $trackUris[] = $track->uri;
            }
        }

        if (!empty($trackUris)) {
            $this->spotifyService->addTracksToPlaylist($request->playlist_id, $trackUris);
        }

        return redirect()->route('spotify.index');
    }
} 