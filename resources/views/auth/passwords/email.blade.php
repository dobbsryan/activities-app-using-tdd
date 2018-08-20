@extends('layouts.app', ['title' => 'Activities Application'])
{{-- @extends('layouts.app') --}}

@section('content')

<div class="flex justify-center container mx-auto mt-8">
    <div class="bg-white p-6 border-b rounded">
        <div class="mb-4">Reset Password</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}

                <div class="mb-4 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="">E-Mail Address</label>

                    <div class="">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <div class="">
                        <button type="submit" class="bg-transparent hover:bg-grey-lighter py-2 px-4 border border-grey hover:border-transparent rounded">
                            Send Password Reset Link
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
