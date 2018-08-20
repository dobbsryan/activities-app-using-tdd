@extends('layouts.app', ['title' => 'Activities Application'])
{{-- @extends('layouts.app') --}}

@section('content')

<div class="flex justify-center container mx-auto mt-8">
    <div class="bg-white p-6 border-b rounded">
        <div class="mb-4">Register</div>

        <div class="panel-body">
            <form class="" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}

                <div class="mb-4 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                    <label for="name" class="">Full Name</label>

                    <div class="">
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                        @if ($errors->has('name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-4 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="">E-Mail Address</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-4 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="col-md-4 control-label">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-4 form-group">
                    <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                    <div class="col-md-6">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>
                </div>

                <div class="mb-4 form-group">
                    <div class="">
                        <button type="submit" class="bg-transparent hover:bg-grey-lighter py-2 px-4 border border-grey hover:border-transparent rounded">
                            Register
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
    
@endsection
