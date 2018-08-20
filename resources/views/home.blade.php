@extends('layouts.app', ['title' => 'Activities Application'])

@section('content')
<div class="flex w-full sm:w-1/2 justify-center container mx-auto my-6">
    <div class="w-full bg-white p-6 border-b rounded">
        <div class="mb-4">Dashboard</div>

        <div class="">

            {{-- @foreach ($locations as $location)
                <div>{{ $location->name }}</div>
            @endforeach --}}

            <locations-wrapper
                :prop-locations="{{ $locations }}"
                :prop-location-id="{{ $location_id }}"
                prop-location-name="{{ $location_name }}"
            ></locations-wrapper>

        </div>
    </div>
</div>
@endsection
