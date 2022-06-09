<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use App\Utils\Options;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email_data = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->email_data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            // common variables to all email templates
            $common_vars = [
                '[[SYSTEM_NAME]]' => \Options::get('siteconfig')['system_name']??'Cogent',
                '[[SYSTEM_ADDRESS]]' => "Cogent",
                '[[SYSTEM_EMAIL]]' => \Options::get('siteconfig')['system_email']??"noreply@cogent.com",
//                '[[SYSTEM_CONTACT]]' => "+ 977 1 5521015, 5522694",
                '[[SYSTEM_LINK]]' => url('/'),
                '[[DATE]]' => date('Y'),
                //'[[SYSTEM_FACEBOOK]]' => \Options::get('facebook_url'),
                //'[[SYSTEM_INSTAGRAM]]' => \Options::get('instagram_url'),
                //'[[SYSTEM_TWITTER]]' => \Options::get('twitter_url'),
                //'[[SYSTEM_YOUTUBE]]' => \Options::get('youtube_url'),
                //'[[PRIVACY_POLICY_LINK]]' => url('/'),
                //'[[UNSUBSCRIBE_LINK]]' => url('/'),
                //'[[SUPPORT_LINK]]' => url('/'),
            ];
            $mail_data = $this->email_data;

            $mail_data['vars'] = isset($mail_data['vars']) ? $mail_data['vars'] : [];

            //replacing the placeholders
            $var_to_replace = array_merge($common_vars,$mail_data['vars']);
            $mail_data['body'] = strtr($mail_data['body'], $var_to_replace);

            Mail::send('email', $mail_data, function($message) use($mail_data) {

                $message->to($mail_data['to']);
                $message->subject($mail_data['subject']);

                // If there is cc
                if ( isset( $mail_data['cc'] ) ) {
                    $message->cc($mail_data['cc']);
                }

                // If there is an attachemtn
                if ( isset( $mail_data['has_attachment'] ) ) {
                    $message->attach($mail_data['has_attachment']);
                }

            });

        } catch (\Exception $e) {
            return $e;
        }


    }
}
