<?php

namespace Atsys\Blog\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use Atsys\Blog\PostCategory;

class CategoryPostsController extends Controller
{
    public function index($category_slug)
    {
        $category = PostCategory::where('alias', '=', $category_slug)->where('language','=',app()->getLocale())->first(); //where('alias->' . app()->getLocale(), $category_slug)->first();

        // dd($category);

        if (!$category) {
            abort(404);
        }

        $posts = $category->posts()->with('postCategories')->latest()->paginate(config('blog.pagination'));

        $page_title = $category->title_translated;

        return view('blog::frontend.posts.index', compact('posts', 'page_title'));
    }
}