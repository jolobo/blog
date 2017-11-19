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

    public function postCategoriesGroups(){
        return $this->belongsToMany('Atsys\Blog\PostCategoriesGroup');
    }
}