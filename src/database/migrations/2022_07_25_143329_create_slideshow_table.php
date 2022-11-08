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
        Schema::create('slideshow', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('upload_id');
            $table->string('path');
            $table->integer('duration')->default(5);
            $table->integer('position');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('upload_id')->references('id')->on('uploads');
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
            $table->dropForeign(['upload_id']);
        });

        Schema::dropIfExists('slideshow');
    }
};
