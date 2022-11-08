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
        Schema::table('post_comments', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')->after('comment')->default(0);
            $table->unsignedBigInteger('approved_by')->after('status_id')->nullable();
            $table->dateTime('approved_at')->after('approved_by')->nullable();

            $table->foreign('status_id')->references('status')->on('status');
            $table->foreign('approved_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_comments', function (Blueprint $table) {
            //
        });
    }
};
