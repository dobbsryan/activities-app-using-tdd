@extends('layouts.app', ['title' => 'Activities Application'])
{{-- @extends('layouts.app') --}}

@section('content')

<div class="flex justify-center container mx-auto mt-8">
    <div class="bg-white p-6 border-b rounded">
        <div class="mb-4">Login</div>

        <div class="">
            <form class="" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}

                <div class="mb-4 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                    <label for="email" class="">E-Mail Address</label>

                    <div class="">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-4 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                    <label for="password" class="">Password</label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control" name="password" required>

                        @if ($errors->has('password'))
                            <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group mb-4">
                    <div class="">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="">
                        <button type="submit" class="mr-2 bg-transparent hover:bg-grey-lighter py-2 px-4 border border-grey hover:border-transparent rounded">
                            Login
                        </button>

                        {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a> --}}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
