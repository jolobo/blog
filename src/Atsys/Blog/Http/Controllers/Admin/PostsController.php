<?php

namespace Atsys\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Atsys\Blog\Post;
use Atsys\Blog\PostCategory;
use Atsys\Blog\PostGroup;
use Illuminate\Support\Facades\DB;
use Atsys\Blog\Http\Requests\PostRequest;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PostsController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query()->with('postCategories');

        if ($q = $request->get('q', '')) {
            $query->where('id', 'like', "%$q%")->orWhere('title->' . app()->getLocale(), 'like', "%$q%");
        }
        $posts = $query->get()->groupBy("post_group_id");
        //$posts = $query->get();

        return view('blog::admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = PostCategory::where('language','=',app()->getLocale() )->get()->pluck('title_translated', 'id');
        return view('blog::admin.posts.create', compact('categories'));
    }

    public function store(PostRequest $request)
    {

        $post_group = new PostGroup();

        $post_group->save();

        $image = null;
        $thumb = null;

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $this->createImage($request->file('image'), $image, $thumb);
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

            $query = PostCategory::query();
            $post_categories = array();
            foreach ($request->post_categories as $key => $post_category_id){
                $post_categories[] = $query->where("id", "=", "$post_category_id")->get();
            }
            $post->postGroup()->associate($post_group);

            $post->updateImage($image, $thumb);

            DB::transaction(function () use ($post, $request, $post_categories) {
                //$post->save();
                $post->postCategories()->sync($request->get('post_categories'));
                $post->save();
            });
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

    public function createImage($file, &$image, &$thumb)
    {
        $image = 'images/blog_posts/' . str_random(15) . '.' . $file->getClientOriginalExtension();
        $path = public_path($image);

        Image::make($file->path())->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path, 70);

        $thumb = 'images/blog_posts/' . str_random(15) . '.' . $file->getClientOriginalExtension();
        $path = public_path($thumb);

        Image::make($file->path())->fit(64, 64)->save($path, 70);

    }

    /**
     * Delete the image file if exists.
     *
     * @return void
     */
    private function deleteImageFile()
    {
        if ($this->image && File::exists(public_path($this->image))) {
            File::delete(public_path($this->image));
        }
    }

}
