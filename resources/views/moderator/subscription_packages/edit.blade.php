@extends('moderator.layouts.admin')

@section('content')
<a class='btn btn-primary mx-5 mt-3' href="{{route('moderator.subscription.packages.index')}}">< Back</a>
<h2 class="mt-2">Edit package</h2>

<div class="d-flex">
    <x-moderator.subscription_package_form :subscription="$subscription"  action="{{route('moderator.subscription.packages.update',$subscription->id)}}" />
    <x-moderator.package_addons :subscription="$subscription"  action="{{route('moderator.subscription.packages.update',$subscription->id)}}" />
</div>

<h2 class="mt-2">Subscribers</h2>

@php 
 $subscribers = $subscription->subscribers()->paginate(10);
@endphp

<div class="table-responsive mt-2">
        <table class="table" style="max-width:500px">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Company</th>
                <th scope="col">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse( $subscribers as $subscriber )
            <tr>
                <th scope="row">{{$subscriber->id}}</th>
                <td>{{$subscriber->name}}</td>
                <td>KES {{number_format($subscriber->account,2)}}</td>
            </tr>
            @empty
            <tr>
              <td colspan="2" class="text-muted">No records found!</td>
            </tr>
            @endif
            
        </tbody>
        </table>
    </div>

    <div class="text-right">
      {{$subscribers->links('pagination::bootstrap-5')}}
    </div>

@endsection