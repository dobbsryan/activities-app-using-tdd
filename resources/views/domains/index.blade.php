@extends('layouts.app', ['title' => 'Domains'])
{{-- @extends('layouts.user', ['title' => 'Schedule Activities']) --}}

@section('content')
{{-- @section('userContent') --}}
{{-- @yield('user') --}}

<main-domains-table :prop-domains="{{ $domains }}"></main-domains-table>
{{-- <div class="bg-white w-full lg:w-1/2 md:w-5/6 mx-auto mt-6 border-b rounded">
    <table class="table">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th></th>
            </tr>

            <tr is="row-new-resident"></tr>
            
            @foreach ($residents as $resident)
            <tr is="row-edit-resident" 
                :resident="{{ $resident }}">
            </tr>
            @endforeach
        </thead>
    </table>
</div> --}}
@endsection