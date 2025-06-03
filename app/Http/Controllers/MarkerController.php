<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{

    public function index()
    {
        $markers = Marker::all();
        return view('maps.index', compact('markers'));
    }


    public function create()
    {
        return view('maps.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        
        Marker::create($validated);
        
        return redirect()->route('markers.index')->with('success', 'Marker created successfully');
    }

    public function show(Marker $marker)
    {
        return view('maps.show', compact('marker'));
    }

    public function edit(Marker $marker)
    {
        return view('maps.edit', compact('marker'));
    }

    public function update(Request $request, Marker $marker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        
        $marker->update($validated);
        $marker->edited = now();
        $marker->save();
        
        return redirect()->route('markers.index')->with('success', 'Marker updated successfully');
    }

    public function destroy(Marker $marker)
    {
        $marker->delete();
        return redirect()->route('markers.index')->with('success', 'Marker deleted successfully');
    }
    
    public function storeFromMap(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string',
        ]);
        
        $marker = Marker::create($validated);
        
        return response()->json(['success' => true, 'marker' => $marker]);
    }
}
