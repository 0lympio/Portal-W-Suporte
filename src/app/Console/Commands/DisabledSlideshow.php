<?php

namespace App\Console\Commands;

use App\Models\Slideshow;
use Illuminate\Console\Command;

class DisabledSlideshow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disabled:slideshow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando verifica a validade de slideshow (destaques)';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Slideshow::where('published_at', '<', now())
            ->where('status_id', 0)
            ->update(['status_id' => 1]);

        Slideshow::where('disabled_at', '<', now())
            ->where('status_id', 1)
            ->update(['status_id' => 2]);
    }
}
