<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::query();

        // Search by title
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filter by author
        if ($request->has('author')) {
            $query->where('author', $request->author);
        }

        // Sort
        if ($request->has('sort')) {
            $sort = $request->sort;
            $direction = $request->get('direction', 'asc');
            $query->orderBy($sort, $direction);
        }

        // Limit
        $limit = $request->get('limit', 10);

        return response()->json($query->paginate($limit));
    }

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

    public function show(News $news)
    {
        return response()->json($news);
    }
}