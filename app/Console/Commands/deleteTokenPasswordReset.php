<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PasswordReset;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class deleteTokenPasswordReset extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:tokenPassword';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'deleteToken';

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
        $delete = PasswordReset::where('expired', '<', Carbon::now())->delete();
        if($delete){
            Log::channel('cron')->info('cron delete token password running');
        }
        return Command::SUCCESS;
    }
}
