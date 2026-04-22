<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CleanupSessions extends Command
{
    protected $signature = 'session:cleanup';
    protected $description = 'Membersihkan session yang sudah expired';

    public function handle()
    {
        $expired = time() - (config('session.lifetime') * 60);
        
        DB::table('sessions')->where('last_activity', '<', $expired)->delete();
        
        $this->info('Session expired telah dibersihkan!');
    }
}