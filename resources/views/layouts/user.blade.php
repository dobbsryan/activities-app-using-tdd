@extends('layouts.master')

@section('body')
<header class="bg-white border-b">
    <div class="flex justify-between items-center py-6 px-6">
        <div class="flex items-center">
            {{-- @icon('menu', 'w-6 h-6 mr-6') --}}
            <div class="w-6 h-6 mr-6">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/></svg>
            </div>
            <div class="text-lg">{{ $title }}</div>
        </div>
        <div>
            {{-- @icon('user', 'w-4 h-4') --}}
            <div class="w-4 h-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M5 5a5 5 0 0 1 10 0v2A5 5 0 0 1 5 7V5zM0 16.68A19.9 19.9 0 0 1 10 14c3.64 0 7.06.97 10 2.68V20H0v-3.32z"/></svg>
            </div>
        </div>
    </div>
</header>

{{-- <div class="flex-fit">
    @yield('userContent')
</div> --}}
@endsection