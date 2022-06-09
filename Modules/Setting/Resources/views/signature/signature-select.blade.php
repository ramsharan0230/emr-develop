<label for="" class="col-sm-3">{{ $positionTitle }} Signature</label>
<div class="col-sm-9">
    <select name="{{ $position }}" class="form-control">
        <option value="">--Select--</option>
        @if(count($users))
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ isset($signature_left->user_id) && $signature_left->user_id == $user->id? 'selected':''  }}>{{ $user->firstname }} {{ $user->middlename }} {{ $user->lastname }}</option>
            @endforeach
        @endif
    </select>
    <small class="help-block text-danger">{{$errors->first('left_signature')}}</small>
</div>
