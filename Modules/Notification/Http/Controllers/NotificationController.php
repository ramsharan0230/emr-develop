<?php

namespace Modules\Notification\Http\Controllers;

use App\CogentUsers;
use App\PatLabTest;
use App\PermissionGroup;
use App\User;
use App\UserGroup;
use App\Utils\Helpers;
use App\Utils\Options;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Routing\Controller;
use App\Notifications\NearExpiryMedicine;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $html = '';
        if ($request->ajax()) {

            if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 1) {
                $notifications = DatabaseNotification::whereNull('read_at')->paginate(10);
            } else {
                $notifications = \Auth::guard('admin_frontend')->user()->unreadNotifications()->paginate(10);
            }
            foreach ($notifications as $notification) {
                $notification_id=$notification->id??'';
                $notification_message=( isset($notification->data) && isset($notification->data['data']) && isset($notification->data['data']['message'])) ? $notification->data['data']['message'] : '';
                $html .= "<div class='iq-sub-card notify-bak mark-read' data-id='" . $notification_id . "' data-url='" . route('notification.mark.read', $notification_id) . "'>";
                $html .= "<div class='media align-items-center'><label>" . $notification_message . "</label>";
                $html .= "</div>";
                $html .= "</div>";
            }
            return \response()->json(['html' => $html]);
        }
        $notifications = \Auth::guard('admin_frontend')->user()->unreadNotifications;
        $view = (string)view('notification::notification', compact('notifications'))->render();
        return \response()->json($view);
    }

    public function viewAllNotifications(Request $request)
    {

        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 1) {
            $data['notifications'] = DatabaseNotification::whereNull('read_at')->paginate(10);
        } else {
            $data['notifications'] = \Auth::guard('admin_frontend')->user()->unreadNotifications()->paginate(10);
        }
        return view('notification::view-all-notifications', $data);

    }

    //30 days
    public function nearExpiry()
    {
        $message = Options::get('low_order_notification_message');
        $expiry_medicines = [];
        $date = now()->addDays(30); //Medicines expiring in 30 days
        $expiry_limit = \App\Utils\Options::get('dispensing_expiry_limit', 60);
        $expiry_limit = ($date) ? $date : date('Y-m-d', strtotime("+{$expiry_limit} days"));
        $expiry_limit = $expiry_limit . ' 00:00:00';

        $medicines = \App\Entry::select('fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
            ->where([
                ['fldexpiry', '<=', $expiry_limit],
                ['fldexpiry', '>=', date('Y-m-d H:i:s')],
                ['fldstatus', '<>', '0'],
            ])->orderBy('fldexpiry')->get();

        if ($medicines) {
            foreach ($medicines as $medicine) {
                $expiry_medicines[] = [
                    'stock' => $medicine->fldstockid,
                    'batch' => $medicine->fldbatch,
                    'expiry' => $medicine->fldexpiry,
                    'Quantity' => $medicine->fldqty,
                    'category' => $medicine->fldcategory,
                    'message' => $message ? strtr($message, ['{$item-name}' => $medicine->fldstockid]) : $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly',
//                    'message' => $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly'
                ];
            }
        }
        foreach ($expiry_medicines as $expiry) {
            $users = CogentUsers::where('fldcategory', 'Pharmacy Officer')->where('status', 'Active')->get();
            foreach ($users as $user) {
                $user->notify(new NearExpiryMedicine($user, $expiry));
            }
        }

    }

    //For 60 days
    public function nearExpirySixtyDays()
    {
        $message = Options::get('low_order_notification_message');
        $expiry_medicines = [];
        $date = now()->addDays(60); //Medicines expiring in 30 days
        $expiry_limit = \App\Utils\Options::get('dispensing_expiry_limit', 60);
        $expiry_limit = ($date) ? $date : date('Y-m-d', strtotime("+{$expiry_limit} days"));
        $expiry_limit = $expiry_limit . ' 00:00:00';

        $medicines = \App\Entry::select('fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
            ->where([
                ['fldexpiry', '<=', $expiry_limit],
                ['fldexpiry', '>=', date('Y-m-d H:i:s')],
                ['fldstatus', '<>', '0'],
            ])->orderBy('fldexpiry')->get();

        if ($medicines) {
            foreach ($medicines as $medicine) {
                $expiry_medicines[] = [
                    'stock' => $medicine->fldstockid,
                    'batch' => $medicine->fldbatch,
                    'expiry' => $medicine->fldexpiry,
                    'Quantity' => $medicine->fldqty,
                    'category' => $medicine->fldcategory,
                    'message' => $message ? strtr($message, ['{$item-name}' => $medicine->fldstockid]) : $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly',
//                    'message' => $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly'
                ];
            }
        }
        foreach ($expiry_medicines as $expiry) {
            $users = CogentUsers::where('fldcategory', 'Pharmacy Officer')->where('status', 'Active')->get();
            foreach ($users as $user) {
                $user->notify(new NearExpiryMedicine($user, $expiry));
            }
        }

    }

    // For 90 days
    public function nearExpiryNintyDays()
    {
        $message = Options::get('low_order_notification_message');
        $expiry_medicines = [];
        $date = now()->addDays(90); //Medicines expiring in 90 days
        $expiry_limit = \App\Utils\Options::get('dispensing_expiry_limit', 60);
        $expiry_limit = ($date) ? $date : date('Y-m-d', strtotime("+{$expiry_limit} days"));
        $expiry_limit = $expiry_limit . ' 00:00:00';

        $medicines = \App\Entry::select('fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
            ->where([
                ['fldexpiry', '<=', $expiry_limit],
                ['fldexpiry', '>=', date('Y-m-d H:i:s')],
                ['fldstatus', '<>', '0'],
            ])->orderBy('fldexpiry')->get();

        if ($medicines) {
            foreach ($medicines as $medicine) {
                $expiry_medicines[] = [
                    'stock' => $medicine->fldstockid,
                    'batch' => $medicine->fldbatch,
                    'expiry' => $medicine->fldexpiry,
                    'Quantity' => $medicine->fldqty,
                    'category' => $medicine->fldcategory,
                    'message' => $message ? strtr($message, ['{$item-name}' => $medicine->fldstockid]) : $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly',
//                    'message' => $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly'
                ];
            }
        }
        foreach ($expiry_medicines as $expiry) {
            $users = CogentUsers::where('fldcategory', 'Pharmacy Officer')->where('status', 'Active')->get();
            foreach ($users as $user) {
                $user->notify(new NearExpiryMedicine($user, $expiry));
            }
        }

    }

    // For 180 days
    public function nearExpiryOneEightyDays()
    {
        $message = Options::get('low_order_notification_message');
        $expiry_medicines = [];
        $date = now()->addDays(180); //Medicines expiring in 180 days
        $expiry_limit = \App\Utils\Options::get('dispensing_expiry_limit', 60);
        $expiry_limit = ($date) ? $date : date('Y-m-d', strtotime("+{$expiry_limit} days"));
        $expiry_limit = $expiry_limit . ' 00:00:00';

        $medicines = \App\Entry::select('fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
            ->where([
                ['fldexpiry', '<=', $expiry_limit],
                ['fldexpiry', '>=', date('Y-m-d H:i:s')],
                ['fldstatus', '<>', '0'],
            ])->orderBy('fldexpiry')->get();

        if ($medicines) {
            foreach ($medicines as $medicine) {
                $expiry_medicines[] = [
                    'stock' => $medicine->fldstockid,
                    'batch' => $medicine->fldbatch,
                    'expiry' => $medicine->fldexpiry,
                    'Quantity' => $medicine->fldqty,
                    'category' => $medicine->fldcategory,
                    'message' => $message ? strtr($message, ['{$item-name}' => $medicine->fldstockid]) : $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly',
//                        $medicine->fldstockid . ' ' . ' is low on order,please make the purchase accordingly'
                ];
            }
        }

        foreach ($expiry_medicines as $expiry) {
            $users = CogentUsers::where('fldcategory', 'Pharmacy Officer')->where('status', 'Active')->get();
            foreach ($users as $user) {
                $user->notify(new NearExpiryMedicine($user, $expiry));
            }
        }

    }

    //For expired Items
    public function ExpiredItems()
    {

        $expired_medicines = [];
        $message = Options::get('expiry_items_notification_message');
        $expired_items = \App\Entry::select('fldstockid', 'fldbatch', 'fldexpiry', 'fldqty', 'fldsellpr', 'fldcategory')
            ->where([
                ['fldexpiry', '<=', date('Y-m-d') . ' 00:00:00'],
                ['fldstatus', '<>', '0'],
            ])->orderBy('fldexpiry')->get()->take(100);

        if ($expired_items) {
            foreach ($expired_items as $expired_item) {
                $expired_medicines[] = [
                    'stock' => $expired_item->fldstockid,
                    'batch' => $expired_item->fldbatch,
                    'expiry' => $expired_item->fldexpiry,
                    'Quantity' => $expired_item->fldqty,
                    'category' => $expired_item->fldcategory,
                    'message' => $message ? strtr($message, ['{$no}' => $expired_item->fldqty, '{$item-name}' => $expired_item->fldstockid]) : $expired_item->fldqty . ' no of ' . $expired_item->fldstockid . 'have expired,Please verify',
                ];
            }
        }

        foreach ($expired_medicines as $expired) {
            $users = CogentUsers::where('fldcategory', 'Pharmacist')->where('status', 'Active')->get();
            foreach ($users as $user) {
                $user->notify(new NearExpiryMedicine($user, $expired));
            }
        }

    }


    //For Reports OPD
    public function opdNotifications()
    {
        $users = CogentUsers::where('fldcategory', 'Pharmacist')->where('status', 'Active')->get();
    }

    //For Reports
    public function ipdNotifications()
    {
        $users = CogentUsers::where('fldcategory', 'Pharmacist')->where('status', 'Active')->get();
    }


    //For Pending Lab Radio

    public function PendingLabRadio()
    {
        $data = [];
        $message = Options::get('pending_lab_notification_message');
        $pendings_count = PatLabTest::where('fldstatus', 'Ordered')->count();
        $group_ids = PermissionGroup::where('code', 'test-sampling')
            ->join('permission_references', 'permission_groups.permission_reference_id', 'permission_references.id')
            ->get()->pluck('group_id');
        $user_ids = UserGroup::select('user_id')->whereIn('group_id', $group_ids)->get()->pluck('user_id');
        $data = [
            'count' => $pendings_count,
            'message' => $message ? strtr($message, ['{$no}' => $pendings_count]) : $pendings_count . 'of tests are pending. Please report.',
        ];
        foreach ($user_ids as $id) {
            $user = CogentUsers::find($id);
            if($user){
                $user->notify(new NearExpiryMedicine($user, $data));
            }
        }
    }


    // Mark read particular notification
    public function MarkRead($notificationid)
    {
        if (!$notificationid) {
            return \response()->json(['message' => 'something went wrong']);
        }

        // $notification = \Auth::guard('admin_frontend')->user()->notifications()->find($notificationid);
        $notification = DatabaseNotification::find($notificationid);
        if ($notification) {
            $notification->markAsRead();
        }
        $count = 0;
        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 1) {
            $notifications = DatabaseNotification::whereNull('read_at')->paginate(10);

            if ($notifications) {
                $count = DatabaseNotification::whereNull('read_at')->get()->count();
                $count = ($count && $count > 100) ? '99+' : $count;
                $view = (string)view('notification::notification', compact('notifications'))->render();
            }

        } else {
            $notifications = \Auth::guard('admin_frontend')->user()->unreadNotifications()->paginate(10);

            if ($notifications) {
                $count = \Auth::guard('admin_frontend')->user()->unreadNotifications()->count();
                $count = ($count && $count > 100) ? '99+' : $count;
                $view = (string)view('notification::notification', compact('notifications'))->render();
            }

        }
//        $notifications = \Auth::guard('admin_frontend')->user()->unreadNotifications()->paginate(10);

        return \response()->json(['view' => $view, 'message' => "Notification marked as read", 'count' => $count]);
    }

    //Mark all notification read
    public function markAllRead()
    {
        
        $user = CogentUsers::find(\Auth::guard('admin_frontend')->user()->id);
        if ($user) {
            foreach ($user->unreadNotifications as $notification) {
                $notification->markAsRead();
            }
        }
        $count = 0;

        if (count(\Auth::guard('admin_frontend')->user()->user_is_superadmin) == 1) {
            $notifications = DatabaseNotification::whereNull('read_at')->paginate(10);

            if ($notifications) {
                $count = DatabaseNotification::whereNull('read_at')->get()->count();
                $count = ($count && $count > 100) ? '99+' : $count;
                $view = (string)view('notification::notification', compact('notifications'))->render();
            }

        } else {
            $notifications = \Auth::guard('admin_frontend')->user()->unreadNotifications()->paginate(10);
            if ($notifications) {
                $count = \Auth::guard('admin_frontend')->user()->unreadNotifications()->count();
                $count = ($count && $count > 100) ? '99+' : $count;
                $view = (string)view('notification::notification', compact('notifications'))->render();
            }
        }
        return \response()->json(['view' => $view, 'message' => "Notifications marked as read", 'count' => $count]);

    }

    //Save device token of Notification
    public function saveToken(Request $request)
    {

        if (!$request->device_token) {
            return false;
        }
        try {
            $token = \Auth::guard('admin_frontend')->user()->device_token;

            if (!$token) {
                \Auth::guard('admin_frontend')->user()->update(['device_token' => $request->device_token]);
                return response()->json([" Thank You, You're ready to receive notifications."]);
            }

        } catch (\Exception $exception) {
            return response()->json(['Something went wrong about notification']);
            dd($exception);
        }

    }

    //Send Firebase Cloud Notifications

    public function sendNotifications()
    {
        $redirect = route('neuro');
        Helpers::sendNotification('Hello dynamic Title', 'This is test message', $redirect);
    }


}
