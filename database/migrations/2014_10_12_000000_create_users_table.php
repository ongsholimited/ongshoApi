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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->tinyInteger('gender')->nullable();
            $table->string('birth_date',200)->nullable();
            $table->string('photo')->nullable();
            $table->rememberToken();
            $table->timestamp('id_card_verified_at')->nullable();
            $table->unsignedBigInteger('id_card_verified_by')->nullable();
            $table->boolean('terms_agreed');
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
        Schema::create('users', function (Blueprint $table) {
            Schema::dropForeign('inst_users_ongsho_id_foreign');
        });
        Schema::dropIfExists('users');
        
    }
};