@extends('moderator.layouts.admin')

@section('content')


<div class="mt-3">
    <form id="p-edit-form" action="{{route('moderator.products.update', $product->id)}}" onsubmit="confirm_submit(event,this.id)" method="post" class="card w-50 px-5 py-5" enctype="multipart/form-data">
        @csrf
        <h2 class="text-center border-bottom">Edit product</h2>

        @if($errors->any())
        <ul class='text-danger'>
            {!! implode('', $errors->all('<li>:message</li>')) !!}
        </ul>
        @endif
        <div class='row'>
            <div class="col-md-6">
                <div class=" py-3">
                    <label for="name">Name:</label> <br>
                    <input type='text' id='name' name='name' class='border p-2 w-100' required placeholder="Name" class="form-input" value='{{$product->name}}' />
                </div>
                <div class=" py-3">
                    <label for="sku">SKU:</label> <br>
                    <input type='text' id='sku' name='sku' class='border p-2 w-100' required placeholder="SKU" class="form-input" value='{{$product->sku}}' />
                </div>
                <div class=" py-3">
                    <label for="sku">Barcode:</label> <br>
                    <input type='number' id='barcode' name='barcode' class='border p-2 w-100' placeholder="Barcode" class="form-input" value='{{$product->barcode_no}}' />
                </div>
            </div>
            <div class="col-md-6">
                <div class=" py-3">
                    <label for="sku">Category:</label> <br>
                    <select name='category' class='border p-2 w-100 w-100' value='{{$product->category}}'>
                        <option value =''>- Select -</option>
                        <option value='category 1'>Category 1</option>
                    </select>
                </div>
                <div class=" py-3">
                    <label for="sku">Taxable:</label> <br>
                    <select name=' vatable' class='border p-2 w-100 w-100' value='{{$product->vatable}}>
                        <option value=''>- Select -</option>
                        <option value=' 1'> Yes </option>
                        <option value='0'> No </option>
                    </select>
                </div>
                <div class=" py-3">
                    <label for="sku">Tax percentage:</label> <br>
                    <input type='text' id='category' name='category' class='border p-2 w-100' placeholder="Category" class="form-input" value='{{$product->tax_percentage}}' />
                </div>
            </div>
        </div>

        <div>
            <label for='description'>Description:</label> <br />
            <textarea name='description' id='description' placeholder="Description" class="p-3 w-100" value='{{$product->description}}'></textarea>
        </div>

        <button type="submit" class="btn btn-primary px-3 my-3">Update</button>
    </form>


</div>

</div>
@endsection