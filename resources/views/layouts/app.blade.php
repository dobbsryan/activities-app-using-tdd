<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>
    {{-- <title>{{ config('app.name', 'Activities') }}</title> --}}
    
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        window.App = {!! json_encode([
            //'csrfToken' => csrf_token(),
            //'showLeftNavMenu' => true
        ]) !!};
    </script>

    <style type="text/css">
        @media print {
            * {
                color: black !important;
                font-size: small;
                margin: 0 !important;
                padding: 0 !important;
                /*box-sizing: border-box !important;*/
            }
            div.container {
                margin: 10mm 10mm 10mm 40mm !important;
            }
        }
    </style>

</head>
{{-- <body class="font-sans text-grey-darkest bg-grey-lighter absolute w-full h-full"> --}}
<body class="font-sans text-grey-darkest bg-grey-lighter absolute w-full">
    <div id="app" class="h-full">
    {{-- <div id="app"> --}}
        <left-nav-menu></left-nav-menu>
        <header class="bg-white border-b print:hidden">
            <div class="flex justify-between items-center py-6 px-6">
                <div class="flex items-center">
                    <div class="w-6 h-6 mr-6">
                        {{-- transparent svg; only using as placeholder for proper nav height --}}
                        {{-- LeftNavMenu component currently (2017-12-18) is the actual svg menu button that gets clicked on --}}
                        <svg color="transparent" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
                    </div>
                    <div class="text-lg select-none ml-4">{{ $title }}</div>
                </div>
                <div class="top-right-login-register links-login-register">
                    {{-- @icon('user', 'w-4 h-4') --}}
                    {{-- <div class="w-4 h-4">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 5a5 5 0 0 1 10 0v2A5 5 0 0 1 5 7V5zM0 16.68A19.9 19.9 0 0 1 10 14c3.64 0 7.06.97 10 2.68V20H0v-3.32z"/></svg>
                    </div> --}}
                    @guest
                        <a href="{{ route('login') }}">Login</a>
                        {{-- <a href="{{ route('register') }}">Register</a> --}}
                    @else
                        <li class="list-reset">
                            {{-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a> --}}

                            <ul class="list-reset" role="">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </div>
            </div>
        </header>
        @yield('content')
        <flash message="{{ session('flash') }}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
