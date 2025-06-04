<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('published_at', 'desc')->paginate(15);
        return view('news.index', compact('news'));
    }
    
    public function monsters()
    {
        $news = News::orderBy('published_at', 'desc')->paginate(15);
        return view('monsters.index', compact('news'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'description' => 'required|string',
        ]);
        
        $news = News::create([
            'title' => $validated['title'],
            'author' => $validated['author'],
            'description' => $validated['description'],
            'published_at' => now(),
        ]);
        
        return redirect('/news')->with('success', 'News created successfully.');
    }
    
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }
    
    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'description' => 'required|string',
        ]);
        
        $news->update($validated);
        
        return redirect('/news')->with('success', 'News updated successfully.');
    }
    
    public function destroy(News $news)
    {
        $news->delete();
        
        return redirect('/news')->with('success', 'News deleted successfully.');
    }
}