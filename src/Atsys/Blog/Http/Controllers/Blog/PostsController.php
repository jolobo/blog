<?php

namespace Atsys\Blog\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Atsys\Blog\Post;
use Atsys\Blog\PostCategory;

class PostsController extends Controller
{
    public function index()
    {

        $posts = Post::with('postCategories')->latest()->paginate(config('blog.pagination'));
        dd($posts);

        return view('blog::frontend.posts.index', compact('posts'));
    }

    public function show($category_slug, $post_slug)
    {
        $post = Post::where('alias', '=', $post_slug)->first();

        $locale = app()->getLocale();

        if($post->language != $locale){

            $post = Post::where('post_group_id','=',$post->post_group_id)->where('language','=',$locale)->get()->first();
        }

        if (!$post) {
            abort(404);
        }

        return view('blog::frontend.posts.show', compact('post'));
    }
}