<?php

namespace App\Http\Controllers;

use App\Models\Anime;
use App\Models\Post;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'anime.count'  => Anime::count(),
            'posts.count'  => Post::count(),
            'users.count'  => User::count(),
            'admins.count' => User::where('role', 'admin')->count(),
        ];

        $latestAnime = Anime::latest()->take(5)->get();
        $latestPosts = Post::latest()->take(5)->get();
        $latestUsers = User::latest()->take(5)->get();

        return view('admin.index', compact('stats', 'latestAnime', 'latestPosts', 'latestUsers'));
    }
}
