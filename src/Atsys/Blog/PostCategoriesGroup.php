<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostCategoriesGroup extends Model
{
    //
    public function postCategories()
    {
        return $this->hasMany('Atsys\Blog\PostCategory');
    }
}
