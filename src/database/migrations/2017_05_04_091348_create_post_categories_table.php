<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('post_categories_group_id')->unsigned();
            $table->text('title');
            $table->text('alias');
            $table->text('language');
        });

        Schema::table('post_categories', function (Blueprint $table) {
            $table->foreign('post_categories_group_id')->references('id')->on('post_categories_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('post_categories');
    }
}
