
{{-- NOTE: currently not being used (2017-12-10); replaced by index.blade.php --}}

@extends('layouts.app', ['title' => 'Activity Attendance'])
{{-- @extends('layouts.app', ['title' => $scheduledActivity->activity->name]) --}}
{{-- @extends('layouts.user', ['title' => $scheduledActivity->activity]) --}}

@section('content')
{{-- @section('userContent') --}}
{{-- @yield('user') --}}

{{-- <div class="flex-fit"> --}}
    <div class="flex flex-col items-center">
        <div class="bg-white px-4 py-6 mt-4 w-5/6 xl:w-1/2 lg:w-1/2 rounded border-b">
            {{-- <div class="mb-6 text-xl">{{ $scheduledActivity->activity }}</div> --}}
            <span>{{ $scheduledActivity->formatted_date_time }}</span>
        </div>
        <ul class="list-reset bg-white px-4 py-6 mt-4 w-5/6 xl:w-1/2 lg:w-1/2 rounded border-b">
            @foreach ($residents as $resident)
                @if ($loop->last)
                    {{-- no margin on last --}}
                    {{-- <li class="text-grey-darkest py-4 px-4 bg-grey-lighter hover:bg-grey-light border-b rounded text-dark-muted cursor-pointer">{{ $resident->name }}</li> --}}
                    <attendance-button
                        class="text-grey-darkest py-4 px-4 bg-grey-lighter hover:bg-grey-light border-b rounded text-dark-muted cursor-pointer"
                        :resident="{{ $resident }}"
                        :scheduled-activity="{{ $scheduledActivity }}"
                    ></attendance-button>
                @else
                    {{-- margin on all others --}}
                    {{-- <li class="text-grey-darkest py-4 px-4 mb-6 bg-grey-lighter hover:bg-grey-light border-b rounded text-dark-muted cursor-pointer">{{ $resident->name }}</li> --}}
                    <attendance-button
                        class="text-grey-darkest py-4 px-4 mb-6 bg-grey-lighter hover:bg-grey-light border-b rounded text-dark-muted cursor-pointer"
                        :resident="{{ $resident }}"
                        :scheduled-activity="{{ $scheduledActivity }}"
                    ></attendance-button>
                @endif
            @endforeach
        </ul>
    </div>
{{-- </div> --}}
@endsection