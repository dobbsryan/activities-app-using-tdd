@extends('layouts.app', ['title' => "Reports: $locationName"])
{{-- @extends('layouts.user', ['title' => 'Schedule Activities']) --}}

@section('content')
{{-- @section('userContent') --}}
{{-- @yield('user') --}}

{{-- <div class="container mx-auto">
    @foreach ($residents as $resident)
        <div>{{ $resident->first_name }} {{ $resident->last_name}}</div>
    @endforeach
    @foreach ($domains as $domain)
        <div>{{ $domain->name }}</div>
    @endforeach

</div> --}}
<div class="container mx-auto">
{{-- <div> --}}
    <reports-wrapper
        date="{{ $date }}"
        :prop-residents="{{ $residents }}"
        {{-- :prop-domains="{{ $domains }}" --}}
    >        
    </reports-wrapper>
</div>
@endsection