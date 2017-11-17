<?php

namespace Atsys\Blog\Http\Controllers\Blog;

use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Atsys\Blog\PostCategory;

class CategoryPostsController extends Controller
{
    public function index($category_slug)
    {
        App::abort(404);
        /*
                $category = PostCategory::where('alias', '=', $category_slug)->where('language','=',app()->getLocale())->first(); //where('alias->' . app()->getLocale(), $category_slug)->first();



                if (!$category) {
                    abort(404);
                }

                $posts = $category->posts()->with('postCategories')->latest()->paginate(config('blog.pagination'));

                $page_title = $category->title_translated;

                return view('blog::frontend.posts.index', compact('posts', 'page_title'));
        */
    }
}