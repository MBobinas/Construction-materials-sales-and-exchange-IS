<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteArchive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly:archiveDeletion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all archived files older than 30 days';

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
        DB::table('orders')->where('order_status', '=', 'šalinti')->delete();
        DB::table('trades')->where('status', '=', 'šalinti')->delete();
        DB::table('listings')->where('status', '=', 'deaktyvuotas')
                             ->orWhere('status', '=', 'negaliojantis')->delete();
    }
}
