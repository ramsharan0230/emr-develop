<?php

namespace Modules\BloodBank\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

use App\BagMaster;

class BagMasterController extends Controller
{
    public function index(Request $request)
    {
        $errors = [];
        if ($request->isMethod('post')) {

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'description' => ['required'],
                'component' => ['required', 'numeric'],
            ]);

            if ($validator->fails()) {
                \Log::info($validator->getMessageBag()->messages());
                $errors = [];
                foreach ($validator->getMessageBag()->messages() as $key => $value)
                    $errors[$key] = $value[0];
            } else {
                try {
                    $id = $request->get('id');
                    $messsage = __('Bag master added successfully!');
                    $data = [
                        'description' => $request->get('description'), 
                        'component' => $request->get('component'),
                    ];
                    if ($id) {
                        $messsage = __('Bag master updated successfully!');
                        BagMaster::where('id', $id)->update($data);
                    } else
                        BagMaster::create($data);
                    return redirect()->route('bloodbank.bag-master.index')->with('success', $messsage);
                } catch (\Exception $e) {
                    Helpers::logStack([$e->getMessage() . ' in bag master update', "Error"]);
                    session()->flash('error_message', __('Error while adding bag master'));
                }
            }
        }

        return view('bloodbank::bagmaster', [
            'form_errors' => $errors,
            'bags' => BagMaster::all(),
        ]);
    }
}
