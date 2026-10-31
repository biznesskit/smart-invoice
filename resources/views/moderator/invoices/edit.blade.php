@extends('moderator.layouts.admin')

@section('content')
<a href="{{route('moderator.invoices.index')}}">
  < Back</a>
    <h2 class="mt-2">Edit Invoice</h2>

    <form id="invoice-form" class="bg-light edit_form" action="{{route('moderator.invoices.update',$invoice->id)}}" onsubmit="confirm_submit(event,this.id)" method="POST">
      @csrf
      
      @if($errors->any())
      <ul class='text-danger'>
        {!! implode('', $errors->all('<li>:message</li>')) !!}
      </ul>
      @endif

      <div class="row">
        <div class="form-group col-md-6 mb-2">
          <label for="inputEmail4">Total Amount Due</label>
          <input type="number" class="form-control" placeholder="Enter digits only" disabled value='{{$invoice->amount_due}}'>
        </div>
        <div class="form-group col-md-6 mb-2">
          <label for="inputEmail4">Total Amount Paid</label>
          <input type="number" class="form-control" placeholder="Enter digits only" disabled value='{{$invoice->amount_paid}}'>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6 mb-2">
          <label for="inputAddress">New Amount Received</label>
          <input type="number" name='amount_recieved' class="form-control" placeholder="Enter digits only">
        </div>
        <div class="form-group col-md-6 mb-2">
          <label for="inputAddress">Payment Method</label>
          <select class="form-control" id="payment_method" name='payment_method' placeholder="E.g. Mpesa">
            <option value='mpesa' selcted>Mpesa</option>
            <option value='card'>Card</option>
            <option value='cash'>Cash</option>
          </select>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-md-6 mb-2">
          <label for="inputAddress">Transaction Reference</label>
          <input type="text" class="form-control" name="transaction_reference" placeholder="E.g Mpesa code">
        </div>
        <div class="form-group col-md-6 mb-2">
          <label for="inputAddress">New Due Date</label>
          <input type="date" class="form-control" name="due_date" id="inputAddress" placeholder="dd/mm/yy">
        </div>
      </div>

      <div class="form-group">
        <div class="form-check">
          <input class="form-check-input" name='send_notification' type="checkbox" id="gridCheck">
          <label class="form-check-label" for="gridCheck">
            Send notification to customer
          </label>
        </div>
      </div>


      <div class="text-right">
        <button type="submit" class="btn btn-outline-secondary">Submit</button>
      </div>

    </form>

    @endsection