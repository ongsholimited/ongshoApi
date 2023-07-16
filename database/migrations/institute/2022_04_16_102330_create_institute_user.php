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
        Schema::connection('institute')->create('inst_users', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('ongsho_id');
            $table->string('bio',200)->nullable();
            $table->text('education',500)->nullable();
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
        Schema::connection('institute')->dropIfExists('inst_users');
    }
};
