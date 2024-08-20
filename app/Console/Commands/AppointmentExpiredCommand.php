<?php

namespace App\Console\Commands;

use App\Http\Repositories\Eloquent\AppointmentRepository;
use Illuminate\Console\Command;

class AppointmentExpiredCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $appointments = new AppointmentRepository(app());
        $appointments->queryModel()
            ->whereDate("date","<",now()->toDate())
            ->where("status","pending")
            ->update([
                "status" => "expired",
            ]);
        return Command::SUCCESS;
    }
}
