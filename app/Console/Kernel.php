<?php

namespace App\Console;

use App\Models\Comment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $posts = Post::where('created_at', '<', Carbon::now()->subHours(3)->toDateTimeString())->get();
            foreach ($posts as $post) {
                $post->delete();
            }
            $comments = Comment::where('created_at', '<', Carbon::now()->subHours(3)->toDateTimeString())->get();
            foreach ($comments as $comment) {
                $comment->delete();
            }
        })->everyThreeHours();
    
    }
 
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
