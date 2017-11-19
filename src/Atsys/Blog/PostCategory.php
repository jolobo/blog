<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'title',
        'alias',
        'language',
    ];

    public function postCategoryGroup()
    {
        return $this->belongsTo('Atsys\Blog\PostCategoryGroup');
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
