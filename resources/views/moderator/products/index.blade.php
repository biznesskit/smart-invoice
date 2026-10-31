@extends('moderator.layouts.admin')

@section('content')
<h2>Products Catalogue <a href="{{route('moderator.products.create')}}" class="btn btn-primary btn-lg float-end mt-3 me-5">New</a> </h2>

<form id="p-search-form" action="{{route('moderator.products.search')}}" onsubmit="confirm_submit(event,this.id)" method="post" class="btn">
    @csrf
    <div>
        <input type='text' name='keyword' placeholder='Search' class="p-2" />

        <button type="submit" class='btn-secondary'>Search</button>
    </div>
    @if($errors->any())
    <ul class='text-danger'>
        {!! implode('', $errors->all('<li>:message</li>')) !!}
    </ul>
    @endif
</form>

<a href="{{route('moderator.products.index')}}" class="btn btn-secondary mr-2">Cancel</a>



<div class="mt-3">
    <table class="table table-stripped table-hover">
        <thead>
            <th>#</th>
            <th>Name</th>
            <th>SKU</th>
            <th>Barcode</th>
            <th>Category</th>
            <th>Taxable</th>
            <th>Tax percentage</th>
            <th>Action</th>
        </thead>
        <tbody>
            @if(count($products))
            @foreach($products as $index => $product)
            <tr>
                <th>{{$index+1}}</th>
                <td class="text-capitalize">{{$product->name}}</td>
                <td>{{$product->sku}}</td>
                <td>{{$product->barcode_no}}</td>
                <td>{{$product->category}}</td>
                <td>{{$product->vatable ? 'Yes' :' No'}}</td>
                <td>{{$product->tax_percentage}}</td>
                <td>
                    <a href="{{route('moderator.products.edit', $product->id)}}" class="btn btn-primary mr-2">Edit</a>
                    <a href="{{route('moderator.products.destroy', $product->id)}}" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                </td>
            </tr>
            @endforeach
            @else <tr>
                <td colspan='7' class="text-muted text-center py-5">No products found</td>
            </tr>
            @endif



        </tbody>
    </table>


</div>

</div>
@endsection