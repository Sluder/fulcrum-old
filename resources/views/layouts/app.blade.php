<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @yield('meta')

        <title>fulcrum | @yield('title')</title>

        {{-- Styles --}}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        @stack('styles')
    </head>
    <body>
        <nav class="navbar navbar-fixed-top">
            <div class="container-fluid">
                <div class="col-md-2 col-sm-2">
                    <a href="{{ route('welcome.view') }}" class="navbar-brand">
                        <i class="fab fa-fulcrum color-accent"></i> fulcrum
                    </a>
                </div>

                @auth
                    <div class="col-md-7 col-sm-6">
                        <ul class="nav navbar-nav">
                            <li class="nav-item @if (Request::is('bots')) active @endif">
                                <a href="{{ route('bots.view') }}" class="nav-link">Bots</a>
                            </li>
                        </ul>
                    </div>

                    <div class="col-md-3 col-sm-4 nav-user">
                        <p class="user-name">{{ Auth::user()->name }}</p>

                        <div class="dropdown">
                            <p data-toggle="dropdown">
                                <img class="user-avatar" src="{{ Auth::user()->avatar_url }}">
                            </p>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a class="dropdown-item" href="{{ route('profile.view') }}">
                                    <i class="fas fa-user color-accent"></i> Profile
                                </a>
                                <a class="dropdown-item" href="{{ route('access-requests.view') }}">
                                    <i class="fas fa-unlock-alt color-accent"></i> Access Requests
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="fas fa-sign-out-alt color-accent"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                @endauth
                @guest
                    <div class="col-md-10">
                        <a href="{{ route('login') }}" class="btn btn-login pull-right">Login</a>
                    </div>
                @endguest
            </div>
        </nav>

        <div class="content" id="app">
            @yield('content')

            @stack('modals')
        </div>

        {{-- Scripts --}}
        <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
        <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('[data-toggle="popover"]').popover();
            });
        </script>

        @stack('scripts')
    </body>
</html>