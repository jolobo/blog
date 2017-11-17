<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PostGroup extends Model
{
    //Gets the Categories associated
    public function posts()
    {
        return $this->hasMany('Atsys\Blog\Post');
    }

    public function user(){
        return $this->belongTo('User');
    }
}
