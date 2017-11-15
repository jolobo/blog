<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    protected $fillable = [
        'title',
        'alias',
        'language',
    ];


    public function posts()
    {
        return $this->belongsToMany('Atsys\Blog\Post');
    }

    public function postCategoriesGroup()
    {
        return $this->belongsTo('Atsys\Blog\PostCategoriesGroup');
    }

    public function getTitleTranslatedAttribute()
    {
        return $this->title;
    }

    public function getAliasTranslatedAttribute()
    {
        return $this->alias;
    }

    public function getRouteAttribute()
    {
        return "blog/{$this->alias_translated}";
    }
}
