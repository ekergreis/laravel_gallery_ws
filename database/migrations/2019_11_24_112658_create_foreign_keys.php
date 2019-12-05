<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('group_user', function(Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('galerie_group', function(Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('galerie_id')->references('id')->on('galeries')->onDelete('cascade');
        });
        Schema::table('images', function(Blueprint $table) {
            $table->foreign('galerie_id')->references('id')->on('galeries')->onDelete('cascade');
        });
        Schema::table('comments', function(Blueprint $table) {
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('group_user', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['user_id']);
        });
        Schema::table('galerie_group', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropForeign(['galerie_id']);
        });
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['galerie_id']);
        });
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropForeign(['user_id']);
        });
    }
}
