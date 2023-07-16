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
        Schema::connection('institute')->table('courses', function (Blueprint $table) {
            $table->decimal('price',20,2)->default(0)->after('thumbnail');
            $table->string('slug',200)->nullable()->after('price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('institute')->table('courses', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropColumn('slug');
        });
    }
};
