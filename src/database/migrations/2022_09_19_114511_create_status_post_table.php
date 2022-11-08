<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('status')->index();
            $table->string('description');
            $table->timestamps();
        });

        DB::table('status')->insert([
            ['status' => 0, 'description' => 'Aguardando aprovação'],
            ['status' => 1, 'description' => 'Ativo'],
            ['status' => 2, 'description' => 'Expirado'],
            ['status' => 3, 'description' => 'Inativo']
        ]);

        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->unsignedBigInteger('status_id');
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
        Schema::dropIfExists('status');
    }
};
