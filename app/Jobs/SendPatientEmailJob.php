<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\PatientEmail;
use Mail;

class SendPatientEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $user;
    protected $email_name;

    public function __construct($user,$email_name)
    {
        $this->user = $user;
        $this->email_name = $email_name;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // dd($this->user['fldemail']);
        $email = new PatientEmail($this->user,$this->email_name);
        Mail::to($this->user['fldemail'])->send($email);
    }
}