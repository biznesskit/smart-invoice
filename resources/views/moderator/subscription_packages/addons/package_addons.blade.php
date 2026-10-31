@extends('moderator.layouts.admin')

@section('content')
<h2 class='my-5'>Package Addons <a class='float-end btn btn-primary ' href="{{route('moderator.subscription.packages.addons.create')}}">Create new addon</a></h2>
<p class="text-muted fw-bold"> 
  Expected addon names: <br/> [ 'branches', 'users', 'accounting reports','etims', etc... ]
</p>
    <div class="table-responsive mt-2 text-capitalize">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Addon Name</th>
        <th scope="col">Active</th>
        <th scope="col">No of Subscribers</th>
        <th scope="col">Price (KES)</th>
        <th scope="col">Validity (days)</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse( $subscriptions as $subscription )
      <tr>
        <th scope="row">{{$subscription->id}}</th>
        <td class="text-capitalize">{{$subscription->name}}</td>
        <th scope="row">
           <span class="text-success">  {{$subscription->active ? 'Yes' : ''}}</span>
           <span class='text-danger'>  {{$subscription->active ? '' : 'No'}}</span>
        </th>
        <td>{{number_format($subscription->subscribers->count())}}</td>
        <td>{{number_format($subscription->price,2)}}</td>
        <td>{{$subscription->validity}}</td>
        <td> 
            <a href="{{route('moderator.subscription.packages.addons.edit',$subscription->id)}}">Edit</a> 
            <a href="{{route('moderator.subscription.packages.addons.destroy',$subscription->id)}}"  class='text-danger ms-3' onclick = "if (! confirm('Are you sure you want to delete this addon?')) { return false; }">Delete</a> 
        </td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class='text-muted'>No Records found! <br/> <a href="{{route('moderator.subscription.packages.addons.create')}}">Create new addon</a> </td>
      </tr>
      @endforelse
    </tbody>
  </table>
</div>
<div>
  {{$subscriptions->links('pagination::bootstrap-5')}}
</div>
    
@endsection