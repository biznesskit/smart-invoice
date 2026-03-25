@extends('moderator.layouts.admin')

@section('content')
<h2>Upload products System catalogue</h2>

<div class="mt-3">
    <form id="upload-form" action="{{route('moderator.uploads.products.post')}}" onsubmit="confirm_submit(event,this.id)" method="post" class="card w-50 px-5 py-5" enctype="multipart/form-data">
        @csrf
        @if($errors->any())
        <ul class='text-danger'>
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </ul>
        @endif

        <label for="file">Select an excel files to upload:</label>
        <input type='file' id='file' name='file' accept=".xlsx, .xls, .csv" class='border p-2' required multiple />
        <br />
        <button type="submit" class="btn btn-primary px-3 my-3">Upload</button>
    </form>


</div>

</div>
@endsection