@extends('layouts.app', ['title' => 'Activities'])
{{-- @extends('layouts.user', ['title' => 'Schedule Activities']) --}}

@section('content')
{{-- @section('userContent') --}}
{{-- @yield('user') --}}

<main-activities-table
    :prop-activities="{{ $activities }}"
    :prop-domains="{{ $domains }}"
></main-activities-table>
{{-- <div class="bg-white w-full lg:w-5/6 mx-auto mt-6 border-b rounded">
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Needed Supplies</th>
                <th>Special Instructions</th>
                <th></th>
            </tr>

            <tr is="row-new-activity"></tr>
            
            @foreach ($activities as $activity)
            <tr is="row-edit-activity" 
                :activity="{{ $activity }}">
            </tr>
            @endforeach
        </thead>
    </table>
</div> --}}
@endsection