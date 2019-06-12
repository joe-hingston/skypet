<ul class="nav justify-content-end">

    @if (Route::has('login'))

            @auth
                <li class="nav-link">
                    <a href="{{ url('/home') }}">Home</a>
                </li>
            <li class="nav-link">
                <a href="{{ url('/status') }}"> Server Status</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/horizon">Horizon</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/telescope">Telescope</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/output/search">Search</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
            @else
                <a href="{{ route('login') }}">Login</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth

    @endif

</ul>
