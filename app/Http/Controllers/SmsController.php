<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function sendSMS($number, $remarks)
    {

        $url = '';
        $handle = curl_init();

        curl_setopt_array(
            $handle,
            array(
                CURLOPT_URL => $url,
                CURLOPT_POST => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            )
        );

        $response = curl_exec($handle);
        curl_close($handle);

        $check_string = '+OK Message received';

        return (strpos($response, $check_string)) ? TRUE : FALSE;
    }
}
