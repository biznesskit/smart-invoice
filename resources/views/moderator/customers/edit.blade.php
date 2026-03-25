@extends('moderator.layouts.admin')

@section('content')
<a href="{{route('moderator.customers.index')}}">
  < Back</a>
    <h2 class="mt-2">Edit Customer</h2>

    <div class='pb-5'>
      <x-moderator.customer_form :customer="$customer" :subscriptions="$subscriptions" action="{{route('moderator.customers.update',$customer->id)}}" />
      <x-moderator.customer_password_form :customer="$customer" :subscriptions="$subscriptions" action="{{route('moderator.customers.change.password',$customer->id)}}" />
    </div>

    @if($errors->any())
    <ul class='text-danger'>
      {!! implode('', $errors->all('<li>:message</li>')) !!}
    </ul>
    @endif

    <h2 class="mt-2">Statement</h2>

    <form id="statement-form" action="{{route('moderator.customers.statement.filter',$customer->id)}}" onsubmit="confirm_submit(event,this.id)" method="POST">
      @csrf
      <div class="row">
        <div class="col-md-4 col-lg-3">
          <label for="inputAddress">Start Date</label>
          <input type="date" class="form-control" name="start_date" required id="inputAddress" placeholder="Start date">
        </div>
        <div class="col-md-4 col-lg-3">
          <label for="inputAddress">End Date</label>
          <input type="date" class="form-control" name='end_date' required id="inputAddress" placeholder="End date">
        </div>
        <div class="col-md-4 col-lg-3 mt-4">
          <button type="submit" class="btn btn-outline-secondary">Filter</button>
          <a href="{{route('moderator.customers.edit', $customer->id)}}" class='btn btn-secondary mx-5' >Cancel</a>
        </div>
      </div>
    </form>
    <!--Search form end-->

    <div class="table-responsive mt-2">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Date</th>
            <th scope="col">Debit</th>
            <th scope="col">Credit</th>
            <th scope="col">Balance</th>
          </tr>
        </thead>
        <tbody>
          @if(count($account_history))
          @foreach($account_history as $index=>$item)
          <tr>
            <th scope="row">{{$index+1}}</th>
            <td> <span class=""> {{ Carbon\Carbon::parse($item->created_at)->format('d-m-Y ')   }}</span> <br />
              <span class="text-muted small"> {{ Carbon\Carbon::parse($item->created_at)->format(' h:i a')   }}</span>
            </td>
            <td>{{$item->debit}}</td>
            <td> {{$item->credit}}</td>
            <td>{{$item->balance}}</td>
          </tr>
          @endforeach
          @else <tr>
            <td colspan="5" class='text-muted p-5 m-5'> No records to show</td>
          </tr>
          @endif

        </tbody>
      </table>
      <div>
        {{$account_history->links('pagination::bootstrap-5')}}
      </div>
    </div>

    @endsection