<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;

class PostCategoryGroup extends Model
{
    //
    public function postCategories()
    {
        return $this->hasMany('Atsys\Blog\PostCategory');
    }

    public function postGroups(){
        return $this->belongsToMany('Atsys\Blog\PostGroup');
    }
}
