<form id="subscription-form" action="{{isset($action) ? $action : '' }}" onsubmit="confirm_submit(event,'subscription-form')" method="post" class="bg-light edit_form">
    @csrf
  <div class="row">
    <div class="form-group col-md-6 mb-2">
      <label for="name">Package Name</label>
      <input type="text" name="name" class="form-control" placeholder="E.g. Basic" value="{{isset($subscription) && is_null(old('name')) ? $subscription->name : old('name') }}" required>
    </div>
    <div class="form-group col-md-6 mb-2">
      <label for="maxNumEmployees">Max no of cashiers</label>
      <input type="number" name="maxNumEmployees" min="1" class="form-control" placeholder="Enter digits" value="{{isset($subscription) && is_null(old('maxNumEmployees')) ? $subscription->maxNumEmployees : old('maxNumEmployees') }}" required>
    </div>
    <!-- <div class="form-group col-md-6 mb-2">
      <label for="maxNumEmployees">Max no of products</label>
      <input type="number" name="max_number_of_products" min="1" class="form-control" placeholder="Enter digits" value="{{isset($subscription) && is_null(old('max_number_of_products')) ? $subscription->max_number_of_products : old('max_number_of_products') }}" required>
    </div> -->
    <div class="form-group col-md-6 mb-2">
      <label for="price">Price</label>
      <input type="number" name="price" min="0" class="form-control" placeholder="Enter digits" value="{{isset($subscription) && is_null(old('price')) ? $subscription->price : old('price') }}" required>
    </div>
    <div class="form-group col-md-6 mb-2">
      <label for="validityInDays">Validity in days</label>
      <input type="number" name="validityInDays" min="1" class="form-control" placeholder="Enter digits"  value="{{isset($subscription) && is_null(old('validityInDays')) ? $subscription->validityInDays : old('validityInDays') }}" required >
    </div>
  </div>

  <div class="row">
    <div class="form-group col-md-12 mb-2">
      <label for="description">Description</label>
      <textarea name="description" type="number" class="form-control"  placeholder="">{{isset($subscription) && is_null(old('description')) ? $subscription->description : old('description') }}</textarea>
    </div>
  </div>


  <div class="form-group">
    <div class="form-check">
        @php 
            $checked = old('active') == '1' ? 'checked' : null; 
            $checked = isset($subscription) && is_null($checked) ? $subscription->active == '1' ? 'checked' : null : null;
        @endphp
      <input name="active" class="form-check-input" id='active' type="checkbox" {{ $checked }} value="1">
      <label class="form-check-label" for="active" >
        Make Active
      </label>
    </div>
  </div>
  
  
  <div class="text-right">
    <button type="submit" class="btn btn-outline-secondary">Submit</button>
  </div>

</form>