<?php

namespace Atsys\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Atsys\Blog\Post;
use Atsys\Blog\PostCategory;
use Atsys\Blog\PostGroup;
use Illuminate\Support\Facades\Auth;
use Atsys\Blog\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class PostsController extends Controller
{
    public function index(Request $request)
    {

        $query = Post::query()->where('language','=',app()->getLocale() );

        if ($q = $request->get('q', '')) {
            $query->where('id', 'like', "%$q%")->orWhere('title->' . app()->getLocale(), 'like', "%$q%");
        }

        $posts = $query->get()->groupBy("post_group_id");

        return view('blog::admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::where('language','=',app()->getLocale() )->get()->pluck('title_translated', 'id');
        return view('blog::admin.posts.create', compact('categories'));
    }

    public function store(PostRequest $request)
    {
        foreach ($request->alias as $key => $alias){

            Validator::make(['alias'=>$alias], [
                'alias' => 'unique:posts,alias'
            ])->validate();
        }

        $post_group = new PostGroup();

        $post_group->user_id = Auth::id();

        $post_category = PostCategory::where("id", "=", current($request->post_categories))->get()->first();

        $post_category_group = $post_category->postCategoryGroup()->first();

        $post_group->save();

        $post_group->postCategoryGroups()->attach($post_category_group);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $post_group->updateImage($request->file('image'));
        }

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

            $post->postGroup()->associate($post_group);

            $post->save();

        }

        return redirect('admin/posts')->with('success', trans('blog::blog.post_created'));
    }

    public function show(Post $post)
    {
        return view('blog.frontend.post', compact('post'));
    }

    public function edit(Post $post)
    {
        $categories = PostCategory::where('language','=',app()->getLocale() )->get()->pluck('title_translated', 'id');

        $post->title = array($post->language =>$post->title);
        $post->alias = array($post->language =>$post->alias);
        $post->subtitle = array($post->language =>$post->subtitle);
        $post->description = array($post->language =>$post->description);
        $post->short_description = array($post->language =>$post->short_description);
        $post->meta_description = array($post->language =>$post->meta_description);
        $post->meta_title = array($post->language =>$post->meta_title);


        $query = Post::query();
        $other_local_posts = $query->where("id", "<>", "$post->id")->where("post_group_id", '=', "$post->post_group_id")->get();

        foreach ($other_local_posts as $local_post){

            $post->title += array($local_post->language => $local_post->title);
            $post->alias += array($local_post->language => $local_post->alias);
            $post->subtitle += array($local_post->language => $local_post->subtitle);
            $post->description += array($local_post->language => $local_post->description);
            $post->short_description += array($local_post->language => $local_post->short_description);
            $post->meta_description += array($local_post->language => $local_post->meta_description);
            $post->meta_title += array($local_post->language => $local_post->meta_title);
        }

        return view('blog::admin.posts.edit', compact('post', 'categories'));
    }

    public function update(PostRequest $request, Post $post)
    {


        foreach ($request->alias as $key => $alias){

            $validator = Validator::make(['alias'=>$alias], [
                'alias' => Rule::unique('posts','alias')->where(function ($query) use($post) {
                    return $query->where('post_group_id', '<>', $post->post_group_id);
                })
            ]);

            if($validator->fails()){
                $validator->errors()->add('alias', 'The alias in '. config('blog.languages')[$key].' is already taken!');
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }

        $query = Post::query();
        $local_posts = $query->where("id", "<>", "$post->id")->where("post_group_id", '=', "$post->post_group_id")->get();
        $local_posts->prepend($post);

        $post_group = $post->postGroup();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $post_group->updateImage($request->file('image'));
        }

        $post_category = PostCategory::where("id", "=", current($request->post_categories)->get()->first());
        $post_categories_group = $post_category->postCategoriesGroup();
        $post_group->postCategoriesGroups()->sync($post_categories_group);
        $post_group->save();

        foreach ($local_posts as $local_post){
            $local_post->title = $request->title[$local_post->language];
            $local_post->alias = $request->alias[$local_post->language];
            $local_post->subtitle = $request->subtitle[$local_post->language];
            $local_post->description = $request->description[$local_post->language];
            $local_post->short_description = $request->short_description[$local_post->language];
            $local_post->meta_description = $request->meta_description[$local_post->language];
            $local_post->meta_title = $request->meta_title[$local_post->language];

            $local_post->save();
        }

        return redirect('admin/posts')->with('success', trans('blog::blog.post_updated'));
    }

    public function destroy(Post $post)
    {
        $post_group = $post->postGroup()->first();

        $posts = $post_group->posts()->get();

        foreach($posts as $post) {
            $post->delete();
        }
        $post_group->delete();

        return redirect('admin/posts')->with('success', trans('blog::blog.post_deleted'));
    }

}
