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
        Schema::connection('ongsho_news')->create('slugs', function (Blueprint $table) {
            $table->id();
            $table->string('slug_name',250)->unique();
            $table->string('slug_type',250);
            $table->unsignedBigInteger('post_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
             $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
             $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ongsho_news')->dropIfExists('slugs');
    }
};