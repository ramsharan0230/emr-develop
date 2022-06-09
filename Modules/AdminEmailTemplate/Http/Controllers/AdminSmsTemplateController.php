<?php

namespace Modules\AdminEmailTemplate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Utils\Options;

class AdminSmsTemplateController extends Controller
{
    public function sendSms($smsdata)
    {
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
			'mobileNumber' => "+977{$smsdata['to']}",
			'message' => $smsdata['text'],
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
    }
}
