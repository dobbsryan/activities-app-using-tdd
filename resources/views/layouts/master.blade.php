<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', $title)</title>

        <link rel="stylesheet" href="{{ elixir('css/app.css') }}">
        {{-- @include('scripts.app') --}}
    </head>
    <body class="font-sans text-grey-darkest bg-grey-lighter">
        <div id="app">
            @yield('body')
        </div>

        @stack('beforeScripts')
        <script src="{{ elixir('js/app.js') }}"></script>
        @stack('afterScripts')
        {{-- {{ svg_spritesheet() }}  --}}
    </body>
</html>