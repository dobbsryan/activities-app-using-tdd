@extends('layouts.app', ['title' => "Individual Attendance: $locationName"])
{{-- @extends('layouts.user', ['title' => 'Schedule Activities']) --}}

@section('content')
{{-- @section('userContent') --}}
{{-- @yield('user') --}}

{{-- <div class="container mx-auto">
    @foreach ($activities as $activity)
        <div>{{ $activity->name }}</div>
    @endforeach

    @foreach ($residents as $resident)
        <div>{{ $resident->first_name }}</div>
    @endforeach
</div> --}}
<div class="container mx-auto">
    <main-individual-activity-attendance
        date="{{ $date }}"
        :prop-activities="{{ $activities }}"
        :prop-residents="{{ $residents }}"
    >        
    </main-individual-activity-attendance>
</div>
@endsection