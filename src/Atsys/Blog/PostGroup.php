<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;
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
        return $this->belongsToMany('Atsys\Blog\PostCategoryGroup', 'post_group_post_category_group','post_category_group_id');
    }
}