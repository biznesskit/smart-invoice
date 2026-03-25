<form id="customer-password-form" action="{{isset($action) ? $action : '' }}" onsubmit="confirm_submit(event,this.id)" method="post" class="bg-light edit_form m-3 border-5 border-top">
  @csrf

  <h4 class='text-center'>Password</h4>

  @if($errors->any())
  <ul class='text-danger'>
    {!! implode('', $errors->all('<li>:message</li>')) !!}
  </ul>
  @endif

  <div class="form-group  mb-3">
    <label for="inputEmail4">Registered Email</label>
    <input type="email" name="email" class="form-control " placeholder="Registered email" value="{{$customer->business_email}}" required>
  </div>
  <div class="row">
    <div class="form-group col-md-6 mb-2">
      <label for="inputEmail4">New password</label>
      <input type="text" name="new_password" class="form-control" placeholder="New password" value="" required>
    </div>
    <div class="form-group col-md-6 mb-2">
      <label for="inputEmail4">Confirm password</label>
      <input type="text" name="confirm_password" class="form-control" placeholder="Confirm password" value="" required>
    </div>
    <p class='py-4'></p>
  </div>



  <div class="text-right">
    <button type="submit" class="btn btn-outline-secondary">Change password</button>
  </div>

</form>