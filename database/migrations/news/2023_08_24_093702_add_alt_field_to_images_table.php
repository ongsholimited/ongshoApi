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
        Schema::connection('ongsho_news')->table('images', function (Blueprint $table) {
            $table->string('alt')->nullable()->after('name');
            $table->decimal('size',20)->nullable()->after('alt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ongsho_news')->table('images', function (Blueprint $table) {
            $table->dropColumn('alt');
            $table->dropColumn('size');
        });
    }
};