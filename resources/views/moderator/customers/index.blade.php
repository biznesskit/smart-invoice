@extends('moderator.layouts.admin')

@section('content')
<h2>Customers</h2>

<!-- <form id="customer-search-form"  onsubmit="confirm_submit(event,this.id)" method="post">
  @csrf
  <div class="row">
    <div class="col-md-4 col-lg-3">
      <input type="search" class="form-control" name='keyword' required placeholder="Search customer...">
    </div>
    <div class="col-md-4 col-lg-3">
      <button type="submit" class="btn btn-outline-secondary">Search</button>
      <a href="{{route('moderator.customers.index')}}" class="btn btn-outline-secondary mx-5">Cancel</a>
    </div>
  </div>
</form> -->
<!--Search form end-->

<div class="table-responsive mt-2">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">Business Code</th>
        <th scope="col">Company</th>
        <th scope="col">Subscription Amount</th>
        <th scope="col">Status</th>
        <th scope="col">Subscription End Date</th>
        <th scope="col">Registration Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse($customers as $customer)
      <tr>
        <th scope="row">{{$customer->business_code}}</th>
        <td class='text-capitalize'>{{$customer->name}}</td>
        <td class='text-capitalize'>KES {{number_format($customer->subscription_amount)}}</td>
        <td>{{$customer->subscription_active ? 'Active' : 'Inactive' }}</td>
        <td> {{$customer->subscription_start_date}} </td>
        <td> {{$customer->created_at}} </td>
      </tr>
      @empty
      <tr>
        <td colspan="7">No customer found!</td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div>
  {{$customers->links('pagination::bootstrap-5')}}
</div>
@endsection