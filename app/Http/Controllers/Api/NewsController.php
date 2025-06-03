<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display a listing of the news articles with filtering, sorting, limit, and search.
     */
    public function index(Request $request)
    {
        $query = News::query();

        // Search by title or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by author
        if ($request->filled('author')) {
            $query->where('author', $request->author);
        }

        // Sort
        if ($request->filled('sort')) {
            $sort = $request->sort;
            $direction = $request->get('direction', 'asc');
            $query->orderBy($sort, $direction);
        }

        // Limit
        $limit = $request->get('limit', 10);

        return response()->json($query->paginate($limit));
    }

    /**
     * Store a newly created news article.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|url',
            'description' => 'required|string',
            'author' => 'required|string|max:255',
            'published_at' => 'required|date',
        ]);
        $news = News::create($validated);
        return response()->json($news, 201);
    }

    /**
     * Display the specified news article.
     */
    public function show($id)
    {
        $news = News::findOrFail($id);
        return response()->json($news);
    }

    /**
     * Update the specified news article.
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'image' => 'nullable|url',
            'description' => 'sometimes|required|string',
            'author' => 'sometimes|required|string|max:255',
            'published_at' => 'sometimes|required|date',
        ]);
        $news->update($validated);
        return response()->json($news);
    }

    /**
     * Remove the specified news article.
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}