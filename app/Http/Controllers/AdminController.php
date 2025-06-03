<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function dashboard()
    {
        $stats = [
            'total_blogs' => Blog::count(),
            'total_comments' => Comment::count(),
            'total_users' => User::count(),
            'recent_blogs' => Blog::with('user')->latest()->take(5)->get(),
            'recent_comments' => Comment::with(['user', 'blog'])->latest()->take(5)->get(),
            'users' => User::latest()->paginate(10),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function banUser(User $user)
    {
        $user->ban();
        return redirect()->route('admin.dashboard')->with('success', 'User has been banned.');
    }

    public function unbanUser(User $user)
    {
        $user->unban();
        return redirect()->route('admin.dashboard')->with('success', 'User has been unbanned.');
    }

    public function deleteUser(User $user)
    {
        $user->delete();
        return redirect()->route('admin.dashboard')->with('success', 'User has been deleted.');
    }
} 