

    @if (session('success'))
        <div class="alert alert-success alert-dismissible my-1 fade w-50 show" role="alert">
            {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

        </div>
    @endif
    @if (session('error'))
    <div class="alert alert-danger alert-dismissible my-1 fade w-50 show" role="alert">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible my-1 fade w-50 show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li >{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
