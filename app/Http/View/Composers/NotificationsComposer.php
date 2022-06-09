<?php


namespace App\Http\View\Composers;


use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class NotificationsComposer
{
    public function compose(View $view)
    {
        $addresses = $this->_getAllAddress();
        $districts = \App\Municipal::select("flddistrict", "fldprovince")->groupBy("flddistrict")->orderBy("flddistrict")->get();
        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 1) {

            $unread_count = DatabaseNotification::whereNull('read_at')->get()->count();
            $notifications = DatabaseNotification::whereNull('read_at')->paginate(10);
            $view->with(
                ['notifications' => $notifications,
                    'unread_notification_count' => $unread_count,
                    'addresses' =>$addresses,
                    'districts' =>$districts,
                    ]
            );
        } else {
            $view->with(
                ['notifications' => \Auth::guard('admin_frontend')->user()->unreadNotifications()->paginate(10),
                    'unread_notification_count' => \Auth::guard('admin_frontend')->user()->unreadNotifications()->count(),
                    'addresses' =>$addresses,
                    'districts' =>$districts,
                    ]
            );
        }

    }


    private function _getAllAddress($encode = TRUE)
    {
        $all_data = \App\Municipal::all();
        $addresses = [];
        foreach ($all_data as $data) {
            $fldprovince = $data->fldprovince;
            $flddistrict = $data->flddistrict;
            $fldpality = $data->fldpality;
            if (!isset($addresses[$fldprovince])) {
                $addresses[$fldprovince] = [
                    'fldprovince' => $fldprovince,
                    'districts' => [],
                ];
            }

            if (!isset($addresses[$fldprovince]['districts'][$flddistrict])) {
                $addresses[$fldprovince]['districts'][$flddistrict] = [
                    'flddistrict' => $flddistrict,
                    'municipalities' => [],
                ];
            }

            $addresses[$fldprovince]['districts'][$flddistrict]['municipalities'][] = $fldpality;
        }

        if ($encode)
            return json_encode($addresses);

        return $addresses;
    }

}
