<?php

namespace Atsys\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\PostCategoriesGroup;
use Atsys\Blog\PostCategory;
use Atsys\Blog\Http\Requests\PostCategoryRequest;
use Illuminate\Http\Request;

class PostCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $query = PostCategory::query();

        if ($q = $request->get('q', '')) {
            $query->where('id', 'like', "%$q%")->orWhere('title->' . app()->getLocale(), 'like', "%$q%");
        }

        $categories = $query->get();

        return view('blog::admin.post_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('blog::admin.post_categories.create');
    }

    public function store(PostCategoryRequest $request)
    {
        $post_categories_group = new PostCategoriesGroup();
        $post_categories_group->save();

        foreach (config('blog.languages') as $key => $language) {
            $post_category = PostCategory::create($request->all());

            $post_category->title = $post_category->title[$key];
            $post_category->alias = $post_category->alias[$key];
            $post_category->language = $key;


            $post_category->postCategoriesGroup()->associate($post_categories_group);
            $post_category->save();


        }
        return redirect('admin/post_categories')->with('success', trans('blog::blog.category_created'));
    }

    public function edit(PostCategory $post_category)
    {
        return view('blog::admin.post_categories.edit', compact('post_category'));
    }

    public function update(PostCategoryRequest $request, PostCategory $post_category)
    {
        $post_category->update($request->all());

        return redirect('admin/post_categories')->with('success', trans('blog::blog.category_updated'));
    }

    public function destroy(PostCategory $post_category)
    {
        $post_category->delete();

        return redirect('admin/post_categories')->with('success', trans('blog::blog.category_deleted'));
    }
}
