<div>
    <div class="mt-3">
        <a href="{{route('moderator.dashboard')}}">
            <img class="img img-thumbnail rounded-circle" src="{{asset('images/logo.png')}}" alt="bizkit-logo" style="max-height:80px">
        </a>
    </div>

    <aside class="mt-1 mb-4">
        <ul class="list-group list-group-flush border-radius">
            <li class="list-group-item @if(Route::is('moderator.dashboard')) current @endif">
                <a href="{{route('moderator.dashboard')}}">
                    Dashboard
                </a>
            </li>
            <li class="list-group-item @if(Route::is('moderator.customers.*')) current @endif">
                <a href="{{route('moderator.customers.index')}}">
                    Customers
                </a>
            </li>
           
            <li class="list-group-item @if(Route::is('moderator.logout')) current @endif">
                <a href="{{route('moderator.logout')}}">
                    Logout
                </a>
            </li>
        </ul>
    </aside>
</div>