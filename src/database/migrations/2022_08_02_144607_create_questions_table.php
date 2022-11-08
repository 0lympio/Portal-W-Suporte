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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionnaire_id');
            $table->string('text');

            $table->enum('type', [
                'Múltipla escolha: texto',
                'Múltipla escolha: imagem',
                'Selecione todos os que se aplicam: texto',
                'Respostas baseadas em texto',
                'Usar pergunta como título'
            ]);

            $table->jsonb('options');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['questionnaire_id']);
        });

        Schema::dropIfExists('questions');
    }
};
