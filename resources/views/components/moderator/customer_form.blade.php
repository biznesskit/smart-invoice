<form id="customer-form" action="{{isset($action) ? $action : '' }}" onsubmit="confirm_submit(event,'customer-form')" method="post" class="bg-light edit_form m-3 border-5 border-top">
  @csrf
  <h4 class='text-center'>Name</h4>
  <div class="row">
    
    <div class="form-group col-md-6 mb-2">
      <label for="inputEmail4">Company</label>
      <input type="text" name="name" class="form-control" placeholder="Company name" value="{{isset($customer) && is_null(old('name')) ? $customer->name : old('name') }}" required>
    </div>
    @if( $customer->subscription_active )
    <div class="form-group col-md-6 mb-2">
      <label for="inputEmail4">Subscribed To {{$customer->subscriptions()->latest()->first()->name}}</label>
      <input type="text" class="form-control" value="Expires on {{$customer->subscriptions()->latest()->first()->pivot->expires_on}}" disabled/>
    </div>
    @else 
    <div class="form-group col-md-6 mb-2">
      <label for="inputEmail4">Renew Subscription Package</label>
      <select name="new_subscription" id="" class="form-control">
        <option value="">None</option>
        @foreach( $subscriptions  as $subscription)
          <option value="{{$subscription->id}}">{{$subscription->name}}</option>
        @endforeach
      </select>
    </div>
    @endif

  </div>

  <div class="row">
    <div class="form-group col-md-12 mb-2">
      <label for="inputAddress">Notes</label>
      <textarea name="notes" class="form-control"  placeholder="">{{isset($customer) && is_null(old('notes')) ? $customer->notes : old('notes') }}</textarea>
    </div>
  </div>  
  
  <div class="text-right">
    <button type="submit" class="btn btn-outline-secondary">Update</button>
  </div>

</form>