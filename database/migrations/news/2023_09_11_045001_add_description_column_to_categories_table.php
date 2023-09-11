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
        Schema::connection('ongsho_news')->table('categories', function (Blueprint $table) {
            $table->string('description',250)->nullable()->after('name');
            $table->string('keyword',250)->nullable()->after('description');
            $table->string('image',250)->nullable()->after('keyword');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ongsho_news')->table('categories', function (Blueprint $table) {
            $table->dropColumn('description');
            $table->dropColumn('keyword');
            $table->dropColumn('image');
        });
    }
};