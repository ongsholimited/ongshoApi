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
        Schema::connection('ongsho_news')->table('slugs', function (Blueprint $table) {
            $table->unsignedBigInteger('page_id')->nullable()->after('post_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('ongsho_news')->table('slugs', function (Blueprint $table) {
            $table->dropForeign('slugs_page_id_foreign');
            $table->dropColumn('page_id');
        });
    }
};
