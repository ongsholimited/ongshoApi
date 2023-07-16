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
        Schema::connection('institute')->table('course_categories', function (Blueprint $table) {
            $table->string('slug',200)->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('institute')->table('course_categories', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
