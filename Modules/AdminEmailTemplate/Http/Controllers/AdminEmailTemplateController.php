<?php

namespace Modules\AdminEmailTemplate\Http\Controllers;

use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Auth;
use Session;
use Validator;
use Input;
use App\Utils\Permission;
use App\EmailTemplate;
use App\Utils\Options;

class AdminEmailTemplateController extends Controller
{
    public function index()
    {
        /*if ( !Permission::checkPermission( 'email-templates' ) )
            return redirect()->route('access-forbidden');*/

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li>Email Templates</li>';
        $data['title'] = "Email Templates Configurations - " . isset(Options::get('siteconfig')['system_name'])??"";
        $data['side_nav'] = 'master_config';
        $data['side_sub_nav'] = 'email_template';
        $data['email_templates'] = EmailTemplate::orderBy('id', 'desc')->get();
        return view('adminemailtemplate::index', $data);
    }

    public function edit($id)
    {
        /*if ( !Permission::checkPermission( 'email-templates' ) )
            return redirect()->route('access-forbidden');*/

        $data = array();
        $data['breadcrumbs'] = '<li><a href="' . route('admin.dashboard') . '">Home</a></li><li><a href="' . route('admin.emailtemplate') . '">Email Templates</a></li><li>Update</li>';
        $data['title'] = "Email Templates Configurations - " . isset(Options::get('siteconfig')['system_name'])??"";
        $data['side_nav'] = 'master_config';
        $data['side_sub_nav'] = 'email_template';

        $data['email_template'] = EmailTemplate::where('id', $id)->first();
        return view('adminemailtemplate::edit', $data);
    }

    public function update(Request $request)
    {
        /*if ( !Permission::checkPermission( 'email-templates' ) )
            return redirect()->route('access-forbidden');*/

        $request->validate([
            'title' => 'required',
            'subject' => 'required',
            'description' => 'required',
        ]);

        $email_data = [
            'title' => $request->get('title'),
            'subject' => $request->get('subject'),
            'description' => $request->get('description'),
            'created_at' => config('constants.current_date'),
            'updated_at' => config('constants.current_date')
        ];


        EmailTemplate::where('id', $request->get('_id'))->update($email_data);
        Session::flash('success_message', 'Record updated successfully');
        return redirect()->route('admin.emailtemplate');
    }

    public function sendEmail($file_name = null, $email_payload = null)
    {
        try {
            $email_template = EmailTemplate::find($email_payload['template_id']);
            $email_body = $email_template->description;
            $email_to = $email_payload['email'];
            $full_name = $email_payload['full_name'];
            if ($file_name != null) {
                $email_job = [
                    'subject' => $email_template->subject,
                    'to' => $email_to,
                    'body' => $email_body,
                    'has_attachment' => $file_name,
                ];
            } else {
                $email_job = [
                    'subject' => $email_template->subject,
                    'to' => $email_to,
                    'body' => `$email_body`,
                ];
            }


            $vars = array(
                '[[FULL_NAME]]' => $full_name,
//                '[[BODY]]' => 'PATIENT DATA CREATED'
            );

            $email_job['vars'] = $vars;

            SendEmail::dispatch($email_job);
//            dd('email sent');
        } catch (\Exception $exception) {
            dd($exception);
        }

    }
}
