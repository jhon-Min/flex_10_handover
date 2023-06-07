@extends('layouts.app_auth')

@section('content')
<div class="container">
	<form class="sign-in-form" action="{{ route('login') }}" method="POST">
		@csrf
		<div class="card">
			<div class="card-body">
				<a href="javascript:void(0)" class="brand text-center d-block m-b-20">
					<img src="{{asset('images/Logo.png')}}" alt="FlexibleDrive" />
				</a>
				<h5 class="sign-in-heading text-center m-b-20">Sign in to your account</h5>
				<div class="form-group">
					<label for="inputEmail" class="sr-only">Email address</label>
					<input type="email" id="inputEmail" class="form-control @error('email') is-invalid @enderror" placeholder="Email address" required="" name="email" value="{{ old('email') }}">
					@error('email')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>

				<div class="form-group">
					<label for="inputPassword" class="sr-only">Password</label>
					<input type="password" id="inputPassword" class="form-control  @error('password') is-invalid @enderror" placeholder="Password" name="password" required="">
					@error('password')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
				<div class="checkbox m-b-10 m-t-20">
					<!-- <div class="custom-control custom-checkbox checkbox-primary form-check">
						<input type="checkbox" name="remember" class="custom-control-input" id="remember" {{ old('remember') ? 'checked' : '' }}>
						<label class="custom-control-label" for="stateCheck1"> Remember me</label> 
					</div>-->
					@if (Route::has('password.request'))
					<a href="{{ route('password.request') }}" class="float-right">Forgot Password?</a><br/>
					@endif
				</div>
				<button class="btn btn-primary btn-rounded btn-floating btn-lg btn-block" type="submit">Sign In</button>

			</div>

		</div>
	</form>
</div>
@endsection