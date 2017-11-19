<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePostGroupPostCategoryGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_group_post_category_group', function (Blueprint $table) {
            $table->integer('post_category_group_id')->unsigned();
            $table->integer('post_group_id')->unsigned();

            $table->foreign('post_category_group_id')
                  ->references('id')->on('post_category_groups')
                  ->onDelete('cascade');

            $table->foreign('post_group_id')
                  ->references('id')->on('post_groups')
                  ->onDelete('cascade');
        });

/*
        $data = DB::table('post_groups')->get()->map(function ($post_group) {
            return [
                'post_category_group_id' => $post_group->post_category_group_id,
                'post_group_id' => $post_group->id,
            ];
        });

        DB::table('post_group_post_category_group')->insert($data->toArray());
*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::drop('post_group_post_category_group');
    }
}
