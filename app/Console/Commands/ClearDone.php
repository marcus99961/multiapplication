<?php

namespace App\Console\Commands;

use App\Models\Complaint;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ClearDone extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:closedone';

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
        // $subs = Subscription::wheredate('end_date', '<' ,Carbon::today()->addDays(15))->wheredate('end_date', '>' ,Carbon::today())->get();
        $oldDone= Complaint::wheredate('updated_at','<', Carbon::today()->subDay(5))
        ->where('status','done')->update(['status'=>'closed']);

        


        // foreach($subs as $key => $sub)

        // {

        //     $email = ['aungkyawmyint@inyalakehotel.com','thuriyalin@inyalakehotel.com','helpdesk.it@inyalakehotel.com'];


        //     Mail::to($email)->send(new ReminderMail($sub));

        // }
    }
}
