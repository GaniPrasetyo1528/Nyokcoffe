@extends('layouts.app')

@section('content')
	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap mt-5">
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<div class="login_box_img">
						<img class="img-fluid" src="{{ asset(('assets/img/login.jpg')) }}" alt="">
						<div class="hover">
							<h4>New to our website?</h4>
							<p>There are advances being made in science and technology everyday, and a good example of this is the</p>
							<a class="primary-btn" href="{{ route('register.index') }}">Create an Account</a>
						</div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="login_form_inner">
						<h3>Log in to enter</h3>
						<form class="row login_form" action="{{ route('login.store') }}" method="post" id="contactForm" novalidate="novalidate">
							@csrf
							@if (session('failed'))
								<div class="alert alert-danger">{{ session('failed') }}</div>
							@endif
							@error('username')
								<small class="text-danger">{{ $message }}</small>
							@enderror
							<div class="col-md-12 form-group">
								<input type="text" class="form-control" name="username" placeholder="Username" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Username'" value="{{ old('username') }}">
							</div>
							@error('password')
								<small class="text-danger">{{ $message }}</small>
							@enderror
							<div class="col-md-12 form-group">
								<input type="password" class="form-control" name="password" placeholder="Password" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
							</div>
							<div class="col-md-12 form-group">
								<div class="creat_account">
									<input type="checkbox" id="f-option2" name="remember">
									<label for="f-option2">Keep me logged in</label>
								</div>
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" class="primary-btn">Log In</button>
								<a href="#">Forgot Password?</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->
@endsection