@extends('moderator.layouts.admin')

@section('content')
<h2>Settings </h2>

<form id="p-search-form" action="{{route('moderator.settings.update', $setting->id)}}" onsubmit="confirm_submit(event,this.id)" method="post" class="card p-5 w-75">
    @csrf
    @method('PUT')

    <h3> Edit </h3>
    @if($errors->any())
    <ul class='text-danger'>
        {!! implode('', $errors->all('<li>:message</li>')) !!}
    </ul>
    @endif

    <div class="form-group py-3">
        <label class='label' for="key">Key:</label>
        <input type='text' name="key" class='form-control p-3' placeholder="Key" required value="{{$setting->key}}">
    </div>

    <div class="form-group py-3">
        <label class='label' for="key">Value:</label>
        <textarea class='form-control p-3' name="value" placeholder="Value" cols='5' rows='10' required> {{json_decode($setting->value)}}
        </textarea>
    </div>

    <button type="submit" class="btn btn-primary w-50 mx-auto">Submit</button>

</form>




</div>
@endsection