<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->middleware('admin')->only(['destroy']);
    }

    public function index()
    {
        $posts = Blog::with('user')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalPosts = Blog::count();
        $totalUsers = User::count();
        $latestUser = User::latest()->first()->name ?? 'No members yet';

        return view('blogs.index', compact('posts', 'totalPosts', 'totalUsers', 'latestUser'));
    }

    public function create()
    {
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        $validated['user_id'] = Auth::id();
        Blog::create($validated);
        
        return redirect()->route('blogs.index')->with('success', 'Blog created successfully');
    }

    public function show(Blog $blog)
    {
        $comments = $blog->comments()->with('user')->latest()->get();
        return view('blogs.show', compact('blog', 'comments'));
    }

    public function edit(Blog $blog)
    {
        $this->authorize('update', $blog);
        return view('blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $this->authorize('update', $blog);
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        
        $blog->update($validated);
        
        return redirect()->route('blogs.index')->with('success', 'Blog updated successfully');
    }

    public function destroy(Blog $blog)
    {
        $this->authorize('delete', $blog);
        $blog->delete();
        return redirect()->route('blogs.index')->with('success', 'Blog deleted successfully');
    }
}
