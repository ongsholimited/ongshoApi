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
        Schema::connection('ongsho_news')->create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name',200);
            $table->string('slug',250);
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('author_id');
            $table->tinyInteger('status');
            $table->integer('serial');
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
        Schema::dropIfExists('menus');
    }
};
