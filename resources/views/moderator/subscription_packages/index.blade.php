@extends('moderator.layouts.admin')

@section('content')
<h2 class='my-5'>Subscription Packages <a class='float-end btn btn-primary ' href="{{route('moderator.subscription.packages.create')}}">Create new package</a></h2>

<div class="table-responsive mt-2 text-capitalize">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">No of Subscribers</th>
        <th scope="col">Price (KES)</th>
        <th scope="col">Max Users</th>
        <th scope="col">Validity (days)</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse( $subscriptions as $subscription )
      <tr>
        <th scope="row">{{$subscription->id}}</th>
        <td class="text-capitalize">{{$subscription->name}}</td>
        <td>{{number_format($subscription->subscribers->count())}}</td>
        <td>{{number_format($subscription->price,2)}}</td>
        <td>{{number_format($subscription->maxNumEmployees)}}</td>
        <td>{{$subscription->validityInDays}}</td>
        <td> 
          <a href="{{route('moderator.subscription.packages.edit',$subscription->id)}}">Edit</a>
          <a href="{{route('moderator.subscription.packages.destroy',$subscription->id)}}" class="text-danger ms-3" onclick = "if (! confirm('Are you sure you want to delete this addon?')) { return false; }">Delete</a>
       </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-muted">No Records found! <a href="{{route('moderator.subscription.packages.create')}}">Create new package</a> </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div>
  {{$subscriptions->links('pagination::bootstrap-5')}}
</div>
@endsection