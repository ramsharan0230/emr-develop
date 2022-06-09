@if(isset($patbillingDetails))
<p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$patbillingDetails->flduserid)) }}
<br>Created Date Time: {{ \Carbon\Carbon::parse($patbillingDetails->fldtime)->toDateString()}}({{ \App\Utils\Helpers::dateToNepali($patbillingDetails->fldtime) }})
<br>Printed By: {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}
<br>Printed Date Time: {{ \Carbon\Carbon::now()->toDateString() }}({{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s')) }})</p>
@elseif(isset($patbilling))
<p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$billItem->flduserid)) }}
<br>Created Date Time: {{ \Carbon\Carbon::parse($billItem->fldtime)->toDateString()}}({{ \App\Utils\Helpers::dateToNepali($billItem->fldtime) }})
<br>Printed By: {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}
<br>Printed Date Time: {{ \Carbon\Carbon::now()->toDateString() }}({{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s')) }})</p>
@elseif(isset($patbilldata))
<p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$patbilldata->flduserid)) }}
<br>Created Date Time: {{ \Carbon\Carbon::parse($patbilldata->fldtime)->toDateString()}}({{ \App\Utils\Helpers::dateToNepali($patbilldata->fldtime) }})
<br>Printed By: {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}
<br>Printed Date Time: {{ \Carbon\Carbon::now()->toDateString() }}({{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s')) }})</p>
@elseif(isset($depositDetail))
<p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$depositDetail->flduserid)) }}
<br>Created Date Time: {{ \Carbon\Carbon::parse($depositDetail->fldtime)->toDateString()}}({{ \App\Utils\Helpers::dateToNepali($depositDetail->fldtime) }})
<br>Printed By: {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}
<br>Printed Date Time: {{ \Carbon\Carbon::now()->toDateString() }}({{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s')) }})</p>
@elseif(isset($dispensemedicine))
<p>Created By: {{ ucwords(preg_replace('/\s+/', ' ',$med->flduserid)) }}
<br>Created Date Time: {{ \Carbon\Carbon::parse($med->fldtime)->toDateString()}}({{ \App\Utils\Helpers::dateToNepali($med->fldtime) }})
<br>Printed By: {{ Auth::guard('admin_frontend')->user()->firstname }} {{ Auth::guard('admin_frontend')->user()->lastname }}
<br>Printed Date Time: {{ \Carbon\Carbon::now()->toDateString() }}({{ \App\Utils\Helpers::dateToNepali(date('Y-m-d H:i:s')) }})</p>
@endif