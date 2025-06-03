<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Monster;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;

class MonsterController extends Controller
{
    public function index(): JsonResponse
    {
        $monsters = Monster::all();
        return response()->json($monsters, 200, [], JSON_PRETTY_PRINT);
    }

    public function store(Request $request)
    {
    }

    public function show($id): JsonResponse
    {
        $monster = Monster::find($id);
        
        if (!$monster) {
            return response()->json([
                'message' => 'Monster not found'
            ], 404, [], JSON_PRETTY_PRINT);
        }
        
        return response()->json($monster, 200, [], JSON_PRETTY_PRINT);
    }

    public function update(Request $request, string $id)
    {
    }

    public function destroy(string $id)
    {
    }
}
