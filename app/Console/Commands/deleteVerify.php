<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\verif;

class deleteVerify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:verif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'delete token verifikasi';

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
        $delete = verif::where('expired', '<', Carbon::now())->delete();
        // if($delete){
        //     Log::channel('cron')->info('cron delete token verify running');
        // }
        return Command::SUCCESS;
    }
}
