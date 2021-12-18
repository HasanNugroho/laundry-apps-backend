<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invite;

class deleteInvite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:invite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete token invite';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron is working fine!");
        Invite::truncate();
        return Command::SUCCESS;
    }
}
