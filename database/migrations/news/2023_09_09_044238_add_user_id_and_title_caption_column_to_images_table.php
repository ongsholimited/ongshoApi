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
            $table->unsignedBigInteger('user_id')->nullable()->after('author_id');
            $table->string('title',250)->after('alt')->nullable();
            $table->string('caption',250)->after('title')->nullable();
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
            $table->dropColumn('user_id');
            $table->dropColumn('title');
            $table->dropColumn('caption');
        });
    }
};