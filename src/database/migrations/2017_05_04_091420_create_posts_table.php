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
            $table->integer('post_group_id')->unsigned();
            $table->boolean('published')->default(false);
            $table->string('title');
            $table->string('alias')->unique();
            $table->text('subtitle');
            $table->text('description');
            $table->text('short_description');
            $table->text('meta_description');
            $table->text('meta_title');
            $table->string('language');
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->foreign('post_group_id')->references('id')->on('post_groups')->onDelete('cascade');
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
