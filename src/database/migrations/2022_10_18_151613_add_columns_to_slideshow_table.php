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
        Schema::table('slideshow', function (Blueprint $table) {
            $table->dateTime('published_at')->nullable()->after('position');
            $table->dateTime('disabled_at')->nullable()->after('published_at');
            $table->unsignedBigInteger('status_id')->default(0)->after('position');
            $table->string('link')->nullable()->after('position');

            $table->foreign('status_id')->references('status')->on('status');

            $table->dropColumn('path');
            $table->dropColumn('position');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('slideshow', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn(['published_at', 'disabled_at', 'status_id']);
            $table->string('path')->nullable();
        });
    }
};
