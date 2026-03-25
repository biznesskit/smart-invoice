@extends('moderator.layouts.admin')

@section('content')
<h2>Dashboard</h2>

<div class="mt-3">
    <div class="row">

        <div class="col-6 col-md-4 col-lg-3  mb-2">
            <x-moderator.card title='New customers' number='{{$new_customers ? count($new_customers) : 0}}' footer='this month' />
        </div>

        <div class="col-6 col-md-4 col-lg-3  mb-2">
            <x-moderator.card title='Inactive customers' number='{{$dormant_users ? count($dormant_users) : 0}}' footer='total' />
        </div>

        <div class="col-6 col-md-4 col-lg-3  mb-2">

            <x-moderator.card title='Total Sales' number='{{$total_sales}}' footer='this month' />
        </div>

        <div class="col-6 col-md-4 col-lg-3  mb-2">

            <x-moderator.card title='Active today' number='{{isset($active_today) ? $active_today : 0}}' footer='today' />
        </div>

        

    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <span class='d-flex align-items-center'>
                <h3>New Customers </h3> <span class="small"> &nbsp; (This month)</span>
            </span>

            <div class="table-responsive mt-2">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Code</th>
                            <th scope="col">Company</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($monthly_customers))
                        @foreach($monthly_customers as $customer)
                        <tr>
                            <th scope="row">{{$customer->id}}</th>
                            <td class='text-capitalize'>{{$customer->business_code}}</td>
                            <td class='text-capitalize'>{{$customer->name}}</td>
                            <td>{{$customer->subscription_active ? 'Active' : 'Inactive'}}</td>
                            <td>{{\Carbon\Carbon::parse($customer->created_at)->diffForHumans()}}</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">
                                {{$monthly_customers->links('pagination::bootstrap-5')}}
                            </td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <span class='d-flex align-items-center'>
                <h3>Payments</h3>
            </span>

            <div class="table-responsive mt-2">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Method</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($payments))
                        @foreach($payments as $payment)

                        @php 
                            $invoice = $payment->invoice ? $payment->invoice : null;
                            $tenant = $invoice->tenant ? $invoice->tenant : null;
                        @endphp
                        <tr>
                            <th scope="row">{{$payment->id}}</th>
                            <td class='text-capitalize'>{{$payment->method}}</td>
                            <td class='text-capitalize'>{{$tenant ? $tenant->name : ''}}</td>
                            <td>{{number_format($payment->amount,2)}}</td>
                            <td>{{ $payment->status}}</td>
                            <td>{{ $payment->created_at}}</td>
                        </tr>
                        @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection