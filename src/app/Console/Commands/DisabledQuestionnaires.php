<?php

namespace App\Console\Commands;

use App\Models\Questionnaire;
use Illuminate\Console\Command;

class DisabledQuestionnaires extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disabled:questionnaires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando verifica a validade de enquetes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $publiQuestionnaires = Questionnaire::where('published_at', '<', now())->get();

        foreach ($publiQuestionnaires as $publiQuestionnaire) {
            $publiQuestionnaire->where('published_at', '<', now())->where('status_id', 0)->update(['status_id' => 1]);
        }

        $questionnaires = Questionnaire::where('disabled_at', '<', now())->get();

        foreach ($questionnaires as $questionnaire) {
            $questionnaire->where('disabled_at', '<', now())->where('status_id', 1)->update(['status_id' => 2]);
        }
    }
}
