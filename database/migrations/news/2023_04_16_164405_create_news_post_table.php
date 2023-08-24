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
            $table->string('date',200);
            $table->string('feature_image',250)->nullable();
            $table->text('title',1000)->nullabl;
            $table->text('meta_description',1000)->nullable();
            $table->longText('content',1000);
            $table->string('slug',250)->unique();
            $table->tinyInteger('post_type');
            $table->text('focus_keyword',1000)->nullable();
            $table->decimal('views',20,2)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('visibility')->default(0);
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