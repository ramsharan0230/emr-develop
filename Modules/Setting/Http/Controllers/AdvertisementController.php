<?php

namespace Modules\Setting\Http\Controllers;

use App\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Intervention\Image\Facades\Image;
use Validator;
use Session;
use Auth;
use App\User;
use File;
use Hash;
use Illuminate\Support\Facades\Input;
use App\Utils\Options;
/**
 * Class BedController
 * @package Modules\Setting\Http\Controllers
 */
class AdvertisementController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $data['advertisement'] = Advertisement::get();
        return view('setting::advertisement-setting', $data);
    }


    public function add()
    {
        
        return view('setting::advertisement-add');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = array(
            'image' => 'required',
          
        );

        $validator = Validator::make($request->all(), $rules);

     

        if ($validator->fails()) {
            return redirect()->route('advertisement.add')->withErrors($validator)->withInput();
        }


        
        $user_data = [
            'title' => $request->get('title'),
            'description' => $request->get('description'),

        ];
        if ($request->hasFile('image')) {
            /*profile image crop*/
            if ($request->hasFile('image')) {
                if ($request->x2 == NULL) {
                    $request->x2 = 900;
                }
                if ($request->y2 == NULL) {
                    $request->y2 = 632;
                }

                $width = $request->w;
                if ($width == 0) {
                    $width = 900;
                }
                $height = $request->h;
                if ($height == 0) {
                    $height = 632;
                }
                $file = $request->file('image');
                $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                if (!file_exists(public_path('uploads/images/adv/fullimage')))
                    mkdir(public_path('uploads/images/adv/fullimage'), 0777, true);
                if (!file_exists(public_path('uploads/images/croppedimage')))
                    mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                $fullimagedestination = public_path() . '/uploads/images/fullimage';
                $file->move($fullimagedestination, $filename);

                $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                $image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
                @unlink(public_path('uploads/images/fullimage/' . $filename));
                @unlink(public_path('uploads/images/croppedimage/' . $filename));
            }

            /*profile image crop*/
            $user_data['image'] = $image;
        }

        Advertisement::insert($user_data);
        return redirect()->route('advertisement');

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $data['advertisement'] = Advertisement::find($id);
        return view('setting::advertisement-edit',$data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id,Request $request)
    {
        try{
            $user_data = [
                'title' => $request->get('title'),
                'description' => $request->get('description'),

            ];
            if ($request->hasFile('image')) {
                /*profile image crop*/
                if ($request->hasFile('image')) {
                    if ($request->x2 == NULL) {
                        $request->x2 = 900;
                    }
                    if ($request->y2 == NULL) {
                        $request->y2 = 632;
                    }

                    $width = $request->w;
                    if ($width == 0) {
                        $width = 900;
                    }
                    $height = $request->h;
                    if ($height == 0) {
                        $height = 632;
                    }
                    $file = $request->file('image');
                    $filename = time() . '-' . rand(111111, 999999) . '.' . $file->getClientOriginalExtension();

                    if (!file_exists(public_path('uploads/images/adv/fullimage')))
                        mkdir(public_path('uploads/images/adv/fullimage'), 0777, true);
                    if (!file_exists(public_path('uploads/images/croppedimage')))
                        mkdir(public_path('uploads/images/croppedimage'), 0777, true);

                    $fullimagedestination = public_path() . '/uploads/images/fullimage';
                    $file->move($fullimagedestination, $filename);

                    $croppedimage = Image::make(public_path('uploads/images/fullimage/' . $filename));
                    $croppedimage->crop((int)$width, (int)$height, (int)$request->x1, (int)$request->y1);
                    $croppedimage->save(public_path('uploads/images/croppedimage/' . $filename), 70);
                    $image = base64_encode(file_get_contents(public_path('uploads/images/croppedimage/' . $filename)));
                    @unlink(public_path('uploads/images/fullimage/' . $filename));
                    @unlink(public_path('uploads/images/croppedimage/' . $filename));
                }

                /*profile image crop*/
                $user_data['image'] = $image;
            }

            Advertisement::find($id)->update($user_data);
            return redirect()->route('advertisement');
        }catch(\Exception $e){
            dd($e);
        }
        

    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        Advertisement::where('id', $id)->delete();
        return redirect()->route('advertisement');
        
    }

    
}
