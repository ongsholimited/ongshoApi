<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('ongsho_news')->create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('date',200);
            $table->string('feature_image',250);
            $table->string('title',250);
            $table->string('short_description',250)->nullable();
            $table->text('content',1000);
            $table->string('slug',250);
            $table->tinyInteger('post_type');
            $table->string('tags',250)->nullable();
            $table->unsignedBigInteger('author_id');
            $table->tinyInteger('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ongsho_news')->dropIfExists('posts');
    }
};
