<?php

namespace Atsys\Blog\Http\Controllers\Blog;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Atsys\Blog\Post;
use \Illuminate\Database\Eloquent\Collection;

class PostsController extends Controller
{
    public function index()
    {

        App::abort(404);

        /*
        $posts = Post::with("post_categories")->join('post_groups', function($join) {
                $join->on('posts.post_group_id', '=', 'post_groups.id')
                    ->where('posts.language', '=', app()->getLocale());
            })->latest()->paginate(config('blog.pagination'));

        return view('blog::frontend.posts.index', compact('posts'));
        */
    }

    public function show($post_slug)
    {

        $post = Post::where('alias', '=', $post_slug)->first();
        $post_group = $post->postGroup()->first();
        $user = $post_group->user()->first();
        $user_name = $user->name;

        if (!$post) {
            abort(404);
        }

        $locale = app()->getLocale();

        if($post->language != $locale){

            $post = Post::where('post_group_id','=',$post->post_group_id)->where('language','=',$locale)->get()->first();
            return redirect()->action('\Atsys\Blog\Http\Controllers\Blog\PostsController@show', ['post_alias'=> $post->alias]);
        }

        $nPosts = '5';
        return view('blog::frontend.posts.show', compact('post', 'user_name', 'nPosts' ));
    }
}