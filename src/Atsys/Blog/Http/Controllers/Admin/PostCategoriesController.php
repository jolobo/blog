<?php

namespace Atsys\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Atsys\Blog\PostCategory;
use Atsys\Blog\PostCategoryGroup;
use Atsys\Blog\Http\Requests\PostCategoryRequest;
use Illuminate\Http\Request;

class PostCategoriesController extends Controller
{
    public function index(Request $request)
    {
        $query = PostCategory::query();

        //TODO: check what is this for
        if ($q = $request->get('q', '')) {
            $query->where('id', 'like', "%$q%")->orWhere('title->' . app()->getLocale(), 'like', "%$q%");
        }
        //****
        $categories = $query->get()->groupBy("post_categories_group_id");

        return view('blog::admin.post_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('blog::admin.post_categories.create');
    }

    public function store(PostCategoryRequest $request)
    {

        $post_categories_group = new PostCategoryGroup();
        $post_categories_group->save();

        foreach (config('blog.languages') as $key => $language) {

            $post_category = new PostCategory();

            $post_category->title = $request->title[$key];
            $post_category->alias = $request->alias[$key];
            $post_category->language = $key;

            $post_category->postCategoriesGroup()->associate($post_categories_group);
            $post_category->save();

        }

        return redirect('admin/post_categories')->with('success', trans('blog::blog.category_created'));
    }

    public function edit(PostCategory $post_category)
    {


        $post_category->title = array($post_category->language =>$post_category->title);
        $post_category->alias = array($post_category->language =>$post_category->alias);

        $query = PostCategory::query();
        $other_local_categories = $query->where("id", "<>", "$post_category->id")->where("post_categories_group_id", '=', "$post_category->post_categories_group_id")->get();

        foreach ($other_local_categories as $category){

            $post_category->title += array($category->language => $category->title);
            $post_category->alias += array($category->language => $category->alias);
        }

        return view('blog::admin.post_categories.edit', compact('post_category'));
    }

    public function update(PostCategoryRequest $request, PostCategory $post_category)
    {

        //dd($request->title[$post_category->language]);
        //dd($post_category);

        $post_category->title = $request->title[$post_category->language];
        $post_category->alias = $request->alias[$post_category->language];
        $post_category->save();

        //$post_category->update($request->all());
        $query = PostCategory::query();
        $other_local_categories = $query->where("id", "<>", "$post_category->id")->where("post_categories_group_id", '=', "$post_category->post_categories_group_id")->get();
        foreach ($other_local_categories as $category){

            $category->title = $request->title[$category->language];
            $category->alias = $request->alias[$category->language];
            $category->save();
        }


        return redirect('admin/post_categories')->with('success', trans('blog::blog.category_updated'));
    }

    public function destroy(PostCategory $post_category)
    {
        $post_categories_group = $post_category->postCategoriesGroup()->first();

        $post_categories = $post_categories_group->postCategories()->get();

        foreach($post_categories as $post_category) {
            $post_category->delete();
        }
        $post_categories_group->delete();

        return redirect('admin/post_categories')->with('success', trans('blog::blog.category_deleted'));
    }
}