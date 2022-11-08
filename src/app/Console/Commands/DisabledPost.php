<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class DisabledPost extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'disabled:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando verifica a validade de postagem';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $publiPosts = Post::where('published_at', '<', now())->get();

        foreach ($publiPosts as $publiPost) {
            $publiPost->where('published_at', '<', now())->where('status_id', 0)->update(['status_id' => 1]);
        }

        $posts = Post::where('disabled_at', '<', now())->get();

        foreach ($posts as $post) {
            $post->where('disabled_at', '<', now())->where('status_id', 1)->update(['status_id' => 2]);
        }
    }
}
