
<nav class="navbar navbar-expand-md navbar-light navbar-laravel">



    <div class="container">

        <a class="navbar-brand" href="{{ url('/home') }}">
            {{ config('app.name', 'Skypet') }}
        </a>


        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
            @auth()


                @can('view_posts')
                    <li class="nav-item {{ Request::is('posts*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('posts.index') }}">
                            🗒 Posts
                        </a>
                    </li>
                @endcan


            @endauth
            {{--                Guest Functions--}}
            <li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('journals.index') }}">
                    📰 Journals
                </a>
            </li>
            <li class="nav-item {{ Request::is('users*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('outputs.index') }}">
                    💻 Outputs
                </a>
            </li>

        </ul>

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else


                <li class="nav-item dropdown">


                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Notifications
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @if(empty($notifications))
                            <a class="dropdown-item"> No notifications</a>
                        @else
                            @foreach ($notifications as $notification)
                                @if($notification['type']='journal')
                                    <a class="dropdown-item" href="/journals/show/{{$notification['id']}}"><i class="glyphicon glyphicon-home" style = "color : #e98fa0;"></i> {{$notification['text']}}</a>
                                @endif
                            @endforeach

                        @endif


                    </div>
                </li>





        <ul class="nav navbar-nav navbar-right">
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                    {{ auth()->user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    @can('view_users')

                        <a class="nav-link" href="{{ route('users.index') }}">
                            😎 Users
                        </a>
                    @endcan
                    @can('view_roles')
                        <a class="nav-link" href="{{ route('roles.index') }}">
                            🔒 Roles
                        </a>

                    @endcan
                    @can('view_healths')

                        <a class="nav-link" href="{{ route('healths.index') }}">
                            💊 Server Health
                        </a>

                    @endcan


                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>


                </div>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">

            @if($notifications)
                <li class="dropdown" style='color:red'>
                    @endif


                    <a class="dropdown-toggle" id="notifications" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">

                    <span class="glyphicon glyphicon-bell ">
                    <span class="rw-number-notification">
                        @if(empty($notifications))
                            0
                        @else
                        {{count($notifications)}}
                        @endif
                    </span>

                    </span>
                    </a>



                    <ul class="dropdown-menu" aria-labelledby="notificationsMenu" id="notificationsMenu">


                        @if(empty($notifications))
                            <a class="dropdown-item"> No notifications</a>
                        @else
                            @foreach ($notifications as $notification)
                              @if($notification['type']='journal')
                                    <a class="dropdown-item" href="/journals/{{$notification['id']}}"><i class="glyphicon glyphicon-home" style = "color : #e98fa0;"></i> {{$notification['text']}}</a>
                              @endif
                            @endforeach

                        @endif
                    </ul>


                </li>
        </ul>
        </ul>
        @endif
    </div>
</nav>
