@extends('layout')
@section('content')
<section class="site-content login-page">
<div class="container">
    
<div class="registration-area">
	<div class="row justify-content-center">
		<div class="col-12 col-md-6 col-lg-5 registered-customers login-page-in">
				<h1 class="page_logo_login d-none">
					<img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
				</h1>
			<div class="login-form">	
				@if(Session::has('loginError'))
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">@lang('website.Error'):</span>
						{!! session('loginError') !!}
						
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif
				@if(Session::has('success'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						<span class="sr-only">@lang('website.success'):</span>
						{!! session('success') !!}
						
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif
				
				<h5 class="title-h5">
					@lang('website.Login into your account')
				</h5>

				<form name="signup" enctype="multipart/form-data" class="form-validate"  action="{{ URL::to('/process-login')}}" method="post">

					<div class="form-group row input-group">
						<label for="staticEmail" class="col-sm-3 col-form-label">@lang('website.Email')</label>
						<div class="input-group">
							<div class="input-group-prepend">
				          	<div class="input-group-text"><i class="far fa-user"></i></div>
				        </div>
							<input type="email" name="email" id="email" class="form-control email-validate" placeholder="Email">
							<span class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</span>
						</div>
					</div>
					<div class="form-group row input-group">
						<label for="inputPassword" class="col-sm-3 col-form-label">@lang('website.Password')</label>
						<div class="input-group">
							 <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="fas fa-unlock-alt"></i></div>
				        </div>
							<input type="password" name="password" id="password" class="form-control field-validate"  placeholder="Password">
							<span class="help-block error-content" hidden>@lang('website.This field is required')</span>
						</div>
					</div>

					<div class="button">
						<button type="submit" class="btn btn-dark login_btn" style="min-width:90px;">@lang('website.Login')</button>
					</div>
					
					
					
				</form>
				
			</div>
			<div class="bottom-form-login">
				{{-- <p>New to NRG Indigenous? <a href="{{ URL::to('/signup')}}">@lang('website.Signup')</a></p> --}}
			</div>
			<a href="{{ URL::to('/forgotPassword')}}" class="btn btn-link ml-1 mr-4 forgot_btn">@lang('website.Forgot Password')</a>
		</div>
		
	</div>
</div> 
</div>
</section>
	
@endsection 	


