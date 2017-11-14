<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostsGroup extends Model
{
    //Gets the Categories associated
    public function postCategories()
    {
        return $this->ToMany('Atsys\Blog\Post');
    }
}
