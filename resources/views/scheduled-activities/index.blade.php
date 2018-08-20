@extends('layouts.app', ['title' => "Group Attendance: $locationName"])
{{-- @extends('layouts.user', ['title' => 'Schedule Activities']) --}}

@section('content')
{{-- @section('userContent') --}}
{{-- @yield('user') --}}

<div class="container mx-auto">
    <scheduled-activities-and-residents-list
        date="{{ $date }}"
        {{-- only passing thru currently to prevent flicker --}}
        {{-- resident are immediately updated on create() --}}
        {{-- to include attendance records --}}
        {{-- :residents="{{ $residents }}" --}}
        :times-and-scheduled-activities="{{ $timesAndScheduledActivities }}"
    >        
    </scheduled-activities-and-residents-list>
</div>
@endsection