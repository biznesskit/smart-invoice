@extends('moderator.layouts.admin')

@section('content')
<h2>Upload Excel products to Customer</h2>

<div class="mt-3">
    <form id="upload-form" action="{{route('moderator.uploads.products.tenant')}}" onsubmit="confirm_submit(event,this.id)" method="post" class="card w-50 px-5 py-5" enctype="multipart/form-data">
        @csrf
        <!-- @if($errors->any())
        <ul class='text-danger'>
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </ul>
        @endif -->

        <label for="code">Customer Business Code:</label>
        <input type='number' id='code' name='business_code' class='border p-2' pattern=".{5,}"  required placeholder="Enter tenant business code" value="{{old('business_code')}}" />
        <br />
        <label for="file">Select an excel files to upload:</label>
        <input type='file' id='file' name='file' accept=".xlsx, .xls, .csv" class='border p-2' required multiple placeholder="Select file" />
        <br />
        <button type="submit" class="btn btn-primary px-3 my-3" onclick="startSpinner()">
                <span id="spinner" class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display:none;"></span>    
                Upload
            </button>
    </form>

</div>

</div>

<script>
        function startSpinner() {
            document.getElementById('spinner').style.display = 'inline-block';
        }
    </script>
@endsection