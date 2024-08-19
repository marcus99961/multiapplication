<?php

namespace App\Jobs;

use App\Mail\MessageMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailQueueJob implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $receipients;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receipients, $data)
    {
        $this->receipients = $receipients;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new MessageMail($this->data);
        Mail::to($this->receipients)->send($email);
    }
}
