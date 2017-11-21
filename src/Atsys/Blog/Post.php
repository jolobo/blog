<?php

namespace Atsys\Blog;

use Illuminate\Database\Eloquent\Model;

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

    public function postGroup()
    {
        return $this->belongsTo('Atsys\Blog\PostGroup');
    }

    //Gets all categories associated to that post. A post should have only categories with the same language as itself
    public function postCategories()
    {

        $postCategoryGroups = $this->postgroup->postCategoryGroups()->get();

        $postCategories = new Collection();
        foreach ($postCategoryGroups as $key => $postCategoryGroup){

            $postCategories = $postCategories->concat($postCategoryGroup->postCategories->where("language", "=", $this->language));
        }

        return $postCategories;
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
        return "blog/".$this->alias_translated;
    }

}
