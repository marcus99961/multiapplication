<?php

namespace App\Jobs;

use App\Mail\CloseMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCloseMailQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $receipients;
    protected $cc;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receipients, $data,$cc)
    {
        $this->receipients = $receipients;
        $this->data = $data;
        $this->cc = $cc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new CloseMail($this->data);
        Mail::to($this->receipients)->cc($this->cc)->send($email);
    }
}
