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
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->unsignedBigInteger('status_id')->default(0);
            $table->dateTime('published_at')->nullable();
            $table->dateTime('disabled_at')->nullable();
            $table->string('thumb')->nullable();
            $table->bigInteger('associate')->default(0);

            $table->foreign('status_id')->references('status')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropColumn(['thumb', 'published_at', 'disabled_at', 'status_id', 'associate']);
        });
    }
};
