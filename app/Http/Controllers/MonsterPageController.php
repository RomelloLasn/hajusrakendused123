<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Monster;

class MonsterPageController extends Controller
{
    public function index()
    {
        $monsters = Monster::latest()->get(); 
        return view('monsters.index', compact('monsters'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|url|max:2048',
            'behavior' => 'required|string',
            'habitat' => 'nullable|string|max:255',
        ]);

        Monster::create($validatedData);

        return redirect()->route('monsters.index')
                         ->with('success', 'Monster added successfully!');
    }

    public function destroy(Monster $monster)
    {
        $monster->delete();

        return redirect()->route('monsters.index')
                         ->with('success', 'Monster deleted successfully!');
    }
}
