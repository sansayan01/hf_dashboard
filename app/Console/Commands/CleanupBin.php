<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupBin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hf:cleanup-bin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permanently delete users who have been in the BIN for more than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoffDate = Carbon::now()->subDays(30);

        $usersToDelete = User::onlyTrashed()
            ->where('deleted_at', '<=', $cutoffDate)
            ->get();

        $count = $usersToDelete->count();

        foreach ($usersToDelete as $user) {
            // Delete associated profile picture if exists
            if ($user->profile && $user->profile->profile_picture) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile->profile_picture);
            }
            $user->forceDelete();
        }

        $this->info("Successfully cleaned up {$count} users from the BIN.");
    }
}
