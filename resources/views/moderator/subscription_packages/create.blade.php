@extends('moderator.layouts.admin')

@section('content')
<a href="{{route('moderator.subscription.packages.index')}}">< Back</a>
<h2 class="mt-2">Create package</h2>

<x-moderator.subscription_package_form  action="{{route('moderator.subscription.packages.store')}}" />

@endsection