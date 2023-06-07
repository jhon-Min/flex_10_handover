@extends('layouts.app_auth')

@section('content')
<div class="container">
    <form class="sign-in-form" action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body">

                <a href="javascript:void(0)" class="brand text-center d-block m-b-20">
                    <img src="{{asset('images/Logo.png')}}" alt="FlexibleDrive" />
                </a>
                <h5 class="sign-in-heading text-center">Forgotten Password?</h5>
                <div class="form-group">
                    <p class="text-center text-muted">Enter your email to reset your password</p>
                    <input type="email" id="inputEmail" class="form-control @error('email') is-invalid @enderror" placeholder="Email address" required="" name="email" value="{{ old('email') }}">
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <button class="btn btn-primary btn-rounded btn-floating btn-lg btn-block" type="submit">Reset Password</button>
                <p class="text-muted m-t-25 m-b-0 p-0">Remebered Your Password?<a href="{{Route('login')}}"> Login </a></p>

            </div>

        </div>
    </form>
</div>
@endsection