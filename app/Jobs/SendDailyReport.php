<?php

namespace App\Jobs;

use App\Mail\DailyReport;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDailyReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $to;
    protected $cc;
    public $data;
    public $selectedrooms;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to,$cc,$data,$selectedrooms)
    {
        $this->to = $to;
        $this->cc = $cc;
        $this->data = $data;
        $this->selectedrooms = $selectedrooms;


    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new DailyReport($this->data,$this->selectedrooms);
       // dd($this->data);
        Mail::to($this->to)->cc($this->cc)->send($email);
    }
}
