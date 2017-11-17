<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;

class PostGroup extends Model
{
    //Gets the Categories associated
    public function posts()
    {
        return $this->hasMany('Atsys\Blog\Post');
    }
}
