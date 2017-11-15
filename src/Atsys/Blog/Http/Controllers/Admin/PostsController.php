<?php

namespace Atsys\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Atsys\Blog\Post;
use Atsys\Blog\PostCategory;
use Atsys\Blog\Http\Requests\PostRequest;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query()->with('postCategories');

        if ($q = $request->get('q', '')) {
            $query->where('id', 'like', "%$q%")->orWhere('title->' . app()->getLocale(), 'like', "%$q%");
        }

        $posts = $query->get();

        return view('blog::admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::get()->pluck('title_translated', 'id');

        return view('blog::admin.posts.create', compact('categories'));
    }

    public function store(PostRequest $request)
    {

        $posts_group = new PostsGroup();
        $posts_group->save();

        foreach (config('blog.languages') as $key => $language) {

            $post = new Post();

            $post->published = $request->published;
            $post->title = $request->title[$key];
            $post->alias = $request->alias[$key];
            $post->subtitle = $request->subtitle[$key];
            $post->description =$request->description[$key];
            $post->short_description =$request->short_description[$key];
            $post->meta_description =$request->meta_description[$key];
            $post->meta_title =$request->meta_title[$key];
            $post->language = $key;

            $post->postCategories()->sync($request->get('post_categories'));
            $post->postsGroup()->associate($posts_group);
            $post->save();

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $post->updateImage($request->file('image'));
            }
        }

        return redirect('admin/posts')->with('success', trans('blog::blog.post_created'));
    }

    public function show(Post $post)
    {
        return view('blog.frontend.post', compact('post'));
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::get()->pluck('title_translated', 'id');

        return view('blog::admin.posts.edit', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $post->update($request->except(['image']));
        $post->postCategories()->sync($request->get('post_categories'));

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $post->updateImage($request->file('image'));
        }

        return redirect('admin/posts')->with('success', trans('blog::blog.post_updated'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect('admin/posts')->with('success', trans('blog::blog.post_deleted'));
    }

}
