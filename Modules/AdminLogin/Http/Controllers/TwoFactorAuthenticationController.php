<?php

namespace Modules\AdminLogin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use OTPHP\TOTP;
use ParagonIE\ConstantTime\Encoding;

class TwoFactorAuthenticationController extends Controller
{
    public function createOtp(string $secretKey, string $label = null, string $issuer = null)
    {
        $encodedSecretKey = Encoding::base32Encode($secretKey);
        $otp = TOTP::create($encodedSecretKey);
        if ($label) {
            $otp->setLabel($label);
        }
        if ($issuer) {
            $otp->setIssuer($issuer);
        }
        return $otp;
    }

    public function verify(string $secretKey, string $userOtp)
    {
        $otp = $this->createOtp($secretKey);
        return $otp->verify($userOtp);
    }
}
