<?php

namespace Modules\ItemMaster\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemMasterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if(last(request()->segments()) == 'update') {
            return [
                'items' => 'required',
                'items.0.fldbillingset_id' => 'required',
                'items.0.fldbillingset' => 'required',
                'items.0.flditemname' => 'required',
                'items.0.flditemcost' => 'required',
                'items.0.fldstatus' => 'required',
            ];
        } else {
            return [
                'items' => 'required',
                'items.*.fldbillingset_id' => 'required',
                'items.*.fldbillingset' => 'required',
                'items.*.flditemname' => 'required',
                'items.*.flditemcost' => 'required',
                'fldstatus' => 'required',
            ];
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'items.required' => 'Please add atleast one item details.',
            'items.*.flditemname.unique' => 'The item name already exists.'
        ];
    }
}
