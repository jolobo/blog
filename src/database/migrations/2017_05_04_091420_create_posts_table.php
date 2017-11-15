<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_category_id')->unsigned()->nullable();
            $table->integer('posts_group_id')->unsigned();
            $table->boolean('published')->default(false);
            $table->text('title');
            $table->text('alias');
            $table->text('subtitle');
            $table->text('description');
            $table->text('short_description');
            $table->text('meta_description');
            $table->text('meta_title');
            $table->text('language');
            $table->timestamps();

        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('posts_group_id')->references('id')->on('posts_groups');
            $table->foreign('post_category_id')->references('id')->on('post_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('posts');
    }
}
