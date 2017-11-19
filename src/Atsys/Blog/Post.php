<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;


class Post extends Model
{

    public $timestamps = false;

    protected $fillable = [
        'published',
        'title',
        'alias',
        'subtitle',
        'description',
        'short_description',
        'meta_description',
        'meta_title',
        'image',
        'thumb',
        'language',
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    /*
    public function postCategories()
    {
        return $this->belongsToMany('Atsys\Blog\PostCategory');
    }
    */

    public function postGroup()
    {
        return $this->belongsTo('Atsys\Blog\PostGroup');
    }

    public function getTitleTranslatedAttribute()
    {
        return $this->title;
    }

    public function getSubtitleTranslatedAttribute()
    {
        return $this->subtitle;
    }

    public function getAliasTranslatedAttribute()
    {
        return $this->alias;
    }

    public function getShortDescriptionTranslatedAttribute()
    {
        return $this->short_description;
    }

    public function getDescriptionTranslatedAttribute()
    {
        return $this->description;
    }

    public function getMetaTitleTranslatedAttribute()
    {
        return $this->meta_title;
    }

    public function getMetaDescriptionTranslatedAttribute()
    {
        return $this->meta_description;
    }

    public function getRouteAttribute()
    {
        return $this->postCategories->first()->route . '/' . $this->alias_translated;
    }


    public function updateImage($image, $thumb)
    {
        $this->image = "/$image";
        $this->thumb = "/$thumb";
        $this->save();
    }
}
