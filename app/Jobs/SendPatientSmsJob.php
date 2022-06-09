<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Utils\Options;

class SendPatientSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	protected $user_data ;
    protected $sms_name;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user_data,$sms_name)
    {
        $this->user_data = $user_data;
        $this->sms_name=$sms_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
		try{
			$folderpath = storage_path('message_log');
			if(is_dir($folderpath) === false)
				mkdir($folderpath, 0655);
	
			$fp = fopen(storage_path('message_log/' . date('Y-m-d') . "_log.txt"), "a");
			$queryString =  http_build_query([
				'guid' => Options::get('token'),
				'username' => Options::get('username'),
				'password' => Options::get('password'),
				'countryCode' => 'np',
				// 'from' => Options::get('siteconfig')['system_name'],
				'mobileNumber' => "+977{$this->user_data['fldptcontact']}",
				'message' => $this->sms_name,
			]);
			$url = Options::get('url') . "?" . $queryString;
	
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
	
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'Content-Type: application/json',
			]);
			$result = curl_exec($ch);
			curl_close($ch);
	
			fwrite($fp, $queryString . PHP_EOL);
			fwrite($fp, "Response: {$result}" . PHP_EOL);
			fwrite($fp, "=============================================" . PHP_EOL . PHP_EOL);
			fclose($fp);

		} catch (\Exception $e) {
			dd($e);
		}
    }  
}
