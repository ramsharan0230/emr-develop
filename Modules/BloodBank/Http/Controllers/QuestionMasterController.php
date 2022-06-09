<?php

namespace Modules\BloodBank\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

use App\QuestionMaster;

class QuestionMasterController extends Controller
{
    public function index(Request $request)
    {
        $errors = [];
        if ($request->isMethod('post')) {
            $parent_id = $request->get('parent_id');
            $id = $request->get('id');

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
                'question' => ['required'],
                'order' => ['required', 'numeric', Rule::unique('question_masters')->where(function ($query) use ($parent_id, $id) {
                    if ($parent_id)
                        $query->where('parent_id', $parent_id)->where('id', '<>', $id);
                    else
                        $query->whereNull('parent_id')->where('id', '<>', $id);
                })],
            ]);

            if ($validator->fails()) {
                \Log::info($validator->getMessageBag()->messages());
                $errors = [];
                foreach ($validator->getMessageBag()->messages() as $key => $value)
                    $errors[$key] = $value[0];
            } else {
                try {

                    $messsage = __('Question added successfully!');
                    $data = [
                        'parent_id' => $request->get('parent_id'),
                        'question' => $request->get('question'),
                        'order' => $request->get('order'),
                    ];
                    if ($id) {
                        $messsage = __('Question updated successfully!');
                        QuestionMaster::where('id', $id)->update($data);
                    } else
                        QuestionMaster::create($data);
                    return redirect()->route('bloodbank.question-master.index')->with('success', $messsage);
                } catch (\Exception $e) {
                    Helpers::logStack([$e->getMessage() . ' in question master create/update', "Error"]);
                    session()->flash('error_message', __('Error while adding question'));
                }
            }
        }

        $questions =  QuestionMaster::whereNull('parent_id')
            ->with('childs')
            ->orderBy('order')
            ->get();
        return view('bloodbank::questionmaster', [
            'questions' => $questions,
            'form_errors' => $errors,
        ]);
    }

    public function changeStatus(Request $request)
    {
        try {
            QuestionMaster::where([
                'id' => $request->get('id'),
            ])->update([
                'is_active' => $request->get('status'),
            ]);

            return response()->json([
                'status' => TRUE,
                'message' => __(__('messages.update', ['name' => 'Status'])),
            ]);
        } catch (\Exception $e) {
            Helpers::logStack([$e->getMessage() . ' in question master update', "Error"]);
            return response()->json([
                'status' => FALSE,
                'message' => __('Error while updated status!'),
            ]);
        }
    }
}
