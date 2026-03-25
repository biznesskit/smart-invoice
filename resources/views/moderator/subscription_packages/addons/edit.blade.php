@extends('moderator.layouts.admin')

@section('content')
<a href="{{route('moderator.subscription.packages.addons.index')}}">< Back</a>
<h2 class="mt-2">Edit addon</h2>

<form id="subscription-form" action="{{route('moderator.subscription.packages.addons.update', $addon->id) }}" onsubmit="confirm_submit(event,'subscription-form')" method="post" class="bg-light edit_form">
    @csrf
  <div class="row">
    <div class="form-group col-md-6 mb-2">
      <label for="name">Addon Name</label>
      <input type="text" name="name" class="form-control" placeholder="E.g. Basic" value="{{isset($addon) && is_null(old('name')) ? $addon->name : old('name') }}" required>
    </div>

    <div class="form-group col-md-6 mb-2">
      <label for="price">Price</label>
      <input type="number" name="price" min="0" class="form-control" placeholder="Enter digits" value="{{isset($addon) && is_null(old('price')) ? $addon->price : old('price') }}" required>
    </div>
    <div class="form-group col-md-6 mb-2">
      <label for="validityInDays">Validity in days</label>
      <input type="number" name="validity" min="1" class="form-control" placeholder="Enter digits"  value="{{isset($addon) && is_null(old('validity')) ? $addon->validity : old('validity') }}" required >
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-12 mb-2">
      <label for="description">Description</label>
      <textarea name="description" type="number" class="form-control"  placeholder="Description">{{isset($addon) && is_null(old('description')) ? $addon->description : old('description') }}</textarea>
    </div>
  </div>


  <div class="form-group">
    <div class="form-check">
        @php 
            $checked = old('active') == '1' ? 'checked' : null; 
            $checked = isset($addon) && is_null($checked) ? $addon->active == '1' ? 'checked' : null : null;
        @endphp
      <input name="active" class="form-check-input" id='active' type="checkbox" {{ $checked }} value="1">
      <label class="form-check-label" for="active" >
        Make Active
      </label>
    </div>
  </div>
  
  
  <div class="text-right">
    <button type="submit" class="btn btn-outline-secondary">Update</button>
  </div>

</form>

@endsection