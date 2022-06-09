<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetMacAddress extends Controller
{
    public function GetMacAddr($os_type)
    {
        switch (strtolower($os_type)) {
            case "linux":
                $macaddressnew = $this->forLinux();
                break;
            case "solaris":
                break;
            case "unix":
                break;
            case "aix":
                break;
            default:
                $macaddressnew = $this->forWindows();
                break;
        }

        return $macaddressnew;
    }

    function forWindows()
    {
        /*returns local connection mac*/
        /*$mac = 'UNKNOWN';
        foreach (explode("\n", str_replace(' ', '', trim(`getmac`, "\n"))) as $i)
            if (strpos($i, 'Tcpip') > -1) {
                $mac = substr($i, 0, 17);
                break;
            }
        return $mac;*/
        try {

            /** WINDOWS */
            ob_start(); // Turn on output buffering
            system('ipconfig /all'); //Execute external program to display output
            $mycom = ob_get_contents(); // Capture the output into a variable
            ob_end_clean(); // Clean (erase) the output buffer

            $findme = "Physical";
            $pmac = strpos($mycom, $findme); // Find the position of Physical text
            $mac = substr($mycom, ($pmac + 36), 17); // Get Physical Address
            return $mac == "" ? false : $mac;

        } catch (\Exception $e) {
            return false;
        }

    }

    function forLinux()
    {
        try {

            ob_start();
            system('ifconfig -a');
            $mycom = ob_get_contents(); // Capture the output into a variable
            ob_end_clean(); // Clean (erase) the output buffer
            $findme = "ether";
            $pmac = strpos($mycom, $findme);
            $mac = substr($mycom, ($pmac + 6), 17);

            return $mac == "" ? false : $mac;

        } catch (\Exception $e) {
            return false;
        }
    }
}
