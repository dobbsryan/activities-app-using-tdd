@extends('layouts.app', ['title' => "Schedule Activities: $locationName"])
{{-- @extends('layouts.user', ['title' => 'Schedule Activities']) --}}

@section('content')
{{-- @section('userContent') --}}
{{-- @yield('user') --}}

<div class="container mx-auto">
    <schedule-and-activities-list
        date="{{ $date }}"
        :activities="{{ $activities }}"
        :times-and-scheduled-activities="{{ $timesAndScheduledActivities }}"
    >        
    </schedule-and-activities-list>
</div>
@endsection
