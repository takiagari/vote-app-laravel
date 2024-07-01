<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleImagesTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('article_images')) {
            Schema::create('article_images', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('article_id');
                $table->string('image_path');
                $table->string('image_title');
                $table->timestamps();

                $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('article_images');
    }
}