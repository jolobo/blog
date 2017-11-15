<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;

class PostGroup extends Model
{
    //Gets the Categories associated
    public function postCategories()
    {
        return $this->ToMany('Atsys\Blog\Post');
    }
}
