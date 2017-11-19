<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\User;

class PostGroup extends Model
{

    //Gets the posts associated
    public function posts()
    {
        return $this->hasMany('Atsys\Blog\Post');
    }

    public function user(){
        return $this->belongsTo('App\User','user_id');
    }

    public function postCategoryGroups(){
        return $this->belongsToMany('Atsys\Blog\PostCategoryGroup', 'post_group_post_category_group');
    }

    public function updateImage($file)
    {
        $this->deleteImageFile();
        $image = 'images/blog_posts/' . str_random(15) . '.' . $file->getClientOriginalExtension();
        $path = public_path($image);

        Image::make($file->path())->resize(800, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path, 70);

        $thumb = 'images/blog_posts/' . str_random(15) . '.' . $file->getClientOriginalExtension();
        $path = public_path($thumb);
        Image::make($file->path())->fit(64, 64)->save($path, 70);
        $this->image = "/$image";
        $this->thumb = "/$thumb";
        $this->save();
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