@extends('layout')
@section('content')
<section class="site-content login-page">
<div class="container">
<div class="registration-area">
		<div class="row justify-content-center">
			<div class="col-12 col-md-6 col-lg-5 new-customers registered-customers">
				<!-- <h1 class="page_logo_login">
					<img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
				</h1> -->
			<div class="login-form">	
				@if( count($errors) > 0)
					@foreach($errors->all() as $error)
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                            <span class="sr-only">@lang('website.Error'):</span>
                            {{ $error }}
                          	<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
						</div>
					 @endforeach
				@endif

				@if(Session::has('error'))
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">@lang('website.Error'):</span>
						  {!! session('error') !!}
                          
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
					</div>
				@endif

				@if(Session::has('success'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						  <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
						  <span class="sr-only">@lang('website.Success'):</span>
						  {!! session('success') !!}
                          
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          		<span aria-hidden="true">&times;</span>
                          </button>
					</div>
				@endif
				<h5 class="title-h3">@lang('website.Create your account')</h5>
				
				<form name="signup" enctype="multipart/form-data" class="form-validate" action="{{ URL::to('/signupProcess')}}" method="post">
                
                	
                    <div class="form-group row input-group">
				        <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="far fa-user"></i></div>
				        </div>
				        <input type="text" name="company" id="company" class="form-control field-validate" value="{{ old('company') }}" placeholder="Company">
				        <span class="help-block error-content" hidden>Please enter your Company name</span>
			      	</div>
                    <div class="form-group row input-group">
				        <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="far fa-user"></i></div>
				        </div>
				        <input type="text" name="firstName" id="firstName" class="form-control field-validate" value="{{ old('firstName') }}" placeholder="@lang('website.First Name')">
				        <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>
			      	</div>
                    <div class="form-group row input-group">
				        <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="far fa-user"></i></div>
				        </div>
				        <input type="text" name="lastName" id="lastName" class="form-control field-validate"  value="{{ old('lastName') }}" placeholder="@lang('website.Last Name')">
						<span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
			      	</div>
                    <div class="form-group row input-group">
				        <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="far fa-user"></i></div>
				        </div>
				        <input type="text" name="customers_telephone" id="customers_telephone" class="form-control field-validate"  value="{{ old('customers_telephone') }}" placeholder="Phone Number">
						<span class="help-block error-content" hidden>Please enter your Phone number</span> 
			      	</div>
                    <div class="form-group row input-group">
				        <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="far fa-envelope"></i></div>
				        </div>
				        <input type="text" name="email" id="email" class="form-control email-validate" value="{{ old('email') }}" placeholder="@lang('website.Email Address')">
						<span class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</span>
			      	</div>
                    <div class="form-group row input-group">
				        <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="fas fa-unlock-alt"></i></div>
				        </div>
				        <input type="password" placeholder="@lang('website.Password')" class="form-control password" name="password" id="password">
						<span class="help-block error-content" hidden>@lang('website.Please enter your password')</span>
			      	</div>
                    <div class="form-group row input-group">
				        <div class="input-group-prepend">
				          	<div class="input-group-text"><i class="fas fa-unlock-alt"></i></div>
				        </div>
				        <input type="password" class="form-control password" name="re_password" id="re_password" placeholder="@lang('website.Confirm Password')">
						<span class="help-block error-content" hidden>@lang('website.Please re-enter your password')</span>
						<span class="help-block error-content-password" hidden>@lang('website.Password does not match the confirm password')</span>
			      	</div>
					<div class="form-group row input-group">
						<!-- <label class="col-sm-3 col-form-label"></label> -->
						<div class="col-sm-12">
							<div class="form-check checkbox-parent">
								<!-- <label class="form-check-label">
								<?php 								/* echo "<pre>";								print_r( $result['commonContent']['pages']);								 die("stop");								 */								?>
									<input class="form-check-input checkbox-validate" type="checkbox">
									<span class="changeColor">@lang('website.Creating an account means you are okay with our')  </span>@if(!empty($result['commonContent']['pages'][5]->slug))									<a target="_blank"  href="{{ URL::to('/page?name='.$result['commonContent']['pages'][11]->slug)}}">
									@endif @lang('website.Terms and Services')
									@if(!empty($result['commonContent']['pages'][5]->slug))</a>@endif 									
									<span class="changeColor">and </span>									@if(!empty($result['commonContent']['pages'][2]->slug))
									<a target="_blank" href="{{ URL::to('/page?name='.@$result['commonContent']['pages'][6]->slug)}}">@endif @lang('website.Privacy Policy')@if(!empty($result['commonContent']['pages'][2]->slug))</a> @endif
								</label> -->
							
								<label class="form-check-label">
								<?php 								/* echo "<pre>";								print_r( $result['commonContent']['pages']);								 die("stop");								 */								?>
									<input class="form-check-input checkbox-validate" type="checkbox">
									<span class="changeColor">@lang('website.Creating an account means you are okay with our')  </span>	
									<a style="color:#25a9e0" href="{{ URL::to('/page?name=terms-and-conditions')}}">@lang('website.Terms and Services')</a>
									<span class="changeColor">and </span>
									<a style="color:#25a9e0" href="{{ URL::to('/page?name=privacy-policy')}}">@lang('website.Privacy Policy')</a>
								</label>
								
								<span class="help-block error-content" hidden>@lang('website.Please accept our terms and conditions')</span>
							</div>
                            
						</div>
					</div>
					<div class="button">
                    	<button type="submit" class="btn btn-dark login_btn pull-right">@lang('website.Create account')</button>
                    </div>
                   
				</form>
			</div>
			<div class="bottom-form-login">
			 <div class="have_an_acc">
                    	<p>Already have an account? <a href="{{ URL::to('/login')}}">@lang('website.Login')</a></p>
                    </div>
			</div>
			</div>
		</div>
	</div>		
	</div>
   </section>
		
@endsection 	


