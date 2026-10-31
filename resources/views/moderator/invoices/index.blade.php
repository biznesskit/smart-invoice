@extends('moderator.layouts.admin')

@section('content')
<h2>Invoices</h2>

<form id="customer-search-form" action="{{route('moderator.invoices.search')}}" onsubmit="confirm_submit(event,this.id)" method="post">
  <!--Search form start-->
  @csrf
  <div class="row">
    <div class="col-md-4 col-lg-3">
      <input type="text" class="form-control" name='invoice_number' placeholder="Invoice No.">
    </div>
    <div class="col-md-4 col-lg-3">
      <input type="text" class="form-control" name='customer_value' placeholder="Company name/ Email/ ID">
    </div>
    <div class="col-md-4 col-lg-3">
      <button type="submit" class="btn btn-outline-secondary">Search</button>
      <a href="{{route('moderator.invoices.index')}}" class="btn btn-outline-secondary mx-5">Cancel</a>
    </div>
  </div>
</form>
<!--Search form end-->

<div class="table-responsive mt-2">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Invoice No.</th>
        <th scope="col">Date</th>
        <th scope="col">Amount Due</th>
        <th scope="col">Amount Paid</th>
        <th scope="col">Customer</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>


      @if(count($invoices))
      @foreach($invoices as $index=>$invoice)
      <tr>
        <th scope="row">{{$index+1}}</th>
        <td>INV - {{$invoice->invoice_number}}</td>
        <td>
          <span class=""> {{ Carbon\Carbon::parse($invoice->created_at)->format('d-m-Y ')   }}</span> <br />
          <span class="text-muted small"> {{ Carbon\Carbon::parse($invoice->created_at)->format(' h:i a')   }}</span>
        </td>
        <td>{{$invoice->amount_due}}</td>
        <td>{{$invoice->amount_paid}}</td>
        <td class="text-capitalize">
          {{$invoice->tenant ? $invoice->tenant->name : 'unkown'}}
        </td>
        <td> <a href="{{route('moderator.invoices.edit',$invoice->id)}}">Edit</a> </td>
      </tr>
      @endforeach
      @else <tr>
        <td colspan="5" class='text-muted p-5 m-5'> No records to show</td>
      </tr>
      @endif

    </tbody>
  </table>
  <div class="px-5">
    {{$invoices->links('pagination::bootstrap-5')}}
  </div>
</div>
@endsection