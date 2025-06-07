<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {

        $tags = Tag::all();

        $search = $request->query('search');
        $tag_id = $request->query('tag_id');

        $query = Post::where('is_active', 1)
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when($tag_id, function ($query) use ($tag_id) {
                $query->whereHas('tags', function ($q) use ($tag_id) {
                    $q->where('tags.id', $tag_id);
                });
            });

        $posts = $query->orderBy('created_at', 'desc')->paginate(4);

        $recentPosts = Post::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('frontend.post.index', compact('posts', 'tags', 'search', 'tag_id', 'recentPosts'));
    }
    
    public function show($id)
    {
        $tags = Tag::all();
        $search = request()->query('search');
        $tag_id = request()->query('tag_id');
        $query = Post::where('is_active', 1)
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'LIKE', "%{$search}%");
            })
            ->when($tag_id, function ($query) use ($tag_id) {
                $query->whereHas('tags', function ($q) use ($tag_id) {
                    $q->where('tags.id', $tag_id);
                });
            });

        $post = Post::where('is_active', 1)->findOrFail($id);

        $recentPosts = Post::where('is_active', 1)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('frontend.post.detail', compact('post', 'recentPosts', 'tags', 'search', 'tag_id'));
    }
}
