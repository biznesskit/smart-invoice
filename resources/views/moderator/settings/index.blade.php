@extends('moderator.layouts.admin')

@section('content')
<h2>Settings <a href="{{route('moderator.settings.create')}}" class="btn btn-primary btn-lg float-end mt-3 me-5"> New</a> </h2>

<form id="p-search2-form" action="{{route('moderator.settings.search')}}" onsubmit="confirm_submit(event,this.id)" method="post" class="btn">
    @csrf
    <div>
        <input type='text' name='keyword' placeholder='Search' class="p-2" />

        <button type="submit" class='btn-secondary'>Search</button>
        <a href="{{route('moderator.settings.index')}}" class='btn btn-secondary'>Refresh</a>
    </div>
    @if($errors->any())
    <ul class='text-danger'>
        {!! implode('', $errors->all('<li>:message</li>')) !!}
    </ul>
    @endif
</form>


<div class="mt-3">
    <table class="table table-stripped table-hover">
        <thead>
            <th>#</th>
            <th>Name</th>
            <th>Value</th>
            <th>Action</th>
        </thead>
        @if(count($settings))
            @foreach($settings as $index=> $item)
            <tr>
                <td>
                    {{$index+1}}
                </td>
                <td>
                    {{$item->key}}
                </td>
                <td class="text-capitalize">
                    {{json_decode($item->value)}}
                </td>
                <td>
                    <a href="{{route('moderator.settings.edit', $item->id)}}" class="btn btn-primary"> Edit </a>
                    <a href="{{route('moderator.settings.destroy', $item->id)}}" method="DELETE" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete?')"> Delete</a>
                </td>
            </tr>
            @endforeach
        @else <tr>
            <td colspan='7' class="text-muted text-center py-5">No records found</td>
        </tr>
        @endif

    </table>


</div>

</div>
@endsection