<?php

namespace Atsys\Blog\Http\ViewComposers;

use Atsys\Blog\Post;
use Illuminate\View\View;
use Atsys\Blog\PostGroup;

class LatestPostsComposer
{
    /**
     * The post model implementation.
     *
     * @var Post
     */
    protected $post;

    /**
     * Create a new post composer.
     *
     * @param  Post  $post
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $post_groups = PostGroup::latest()->take(5)->get();

        $latest_posts = array();
        foreach($post_groups as $post_group) {

            $latest_posts[] = $post_group->posts()->where('language', '=', app()->getLocale())->get()->first();

        }

        $view->with('latest_posts', $latest_posts);
    }
}
