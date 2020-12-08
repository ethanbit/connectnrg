@extends('layout')
@section('content')
<section class="site-content">
	<div class="container">
    	<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.myProfile')</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item active">@lang('website.myProfile')</li>
                </ol>
            </div>
        </div>

        <div class="registration-area">
            
            
            <div class="row">            	
                <div class="col-12 col-lg-3">
                    @include('common.sidebar_account')
                </div>
            	<div class="col-12 col-lg-9 new-customers">
                	<div class="col-12">
                    	<div class="heading">
                            <h2>@lang('website.myProfile')</h2>
                            <hr>
                        </div>
                        
                         <div class="row">
                            <div class="col-sm-12">
                                <form name="updateMyProfile" class="form-validate" enctype="multipart/form-data" action="{{ URL::to('updateMyProfile')}}" method="post">
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
                                    
                                    @if(session()->has('error'))
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session()->get('error') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                    
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                            <span class="sr-only">@lang('website.Error'):</span>
                                            {{ session()->get('error') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                
                                    @if(Session::has('error'))
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                            <span class="sr-only">@lang('website.Error'):</span>
                                            {!! session('loginError') !!}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif
                                
                                    @if(session()->has('success') )
                                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session()->get('success') }}
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    @endif                                    
                                    
                                	<h5 class="title-h5">@lang('website.Personal Information')</h5>
                        			<hr class="featurette-divider">
                                    
                                    <div class="form-group row">
                                        <label for="firstName" class="col-sm-4 col-form-label">@lang('website.First Name')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="customers_firstname" class="form-control field-validate" placeholder="@lang('website.First Name')" id="firstName" value="{{ auth()->guard('customer')->user()->customers_firstname }}">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="lastName" class="col-sm-4 col-form-label">@lang('website.Last Name')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="customers_lastname" placeholder="@lang('website.Last Name')" class="form-control field-validate" id="lastName" value="{{ auth()->guard('customer')->user()->customers_lastname }}">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span>
                                        </div>
                                    </div>
									<div class="form-group row">
                                        <label for="lastName" class="col-sm-4 col-form-label">Email</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="" placeholder="Email" class="form-control field-validate___1" id="customers_email_address" value="{{ auth()->guard('customer')->user()->email }}" disabled="">
                                            <span class="help-block error-content" hidden>Please enter your email</span>
                                        </div>
                                    </div>
									
									<div class="form-group row">
                                        <label for="company" class="col-sm-4 col-form-label">Company</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="company" placeholder="Company" class="form-control field-validate" id="Company" value="{{ auth()->guard('customer')->user()->company }}">
											
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your company')</span>
                                        </div>
                                    </div>
                                    
                                   
                                  
									<!-- <div class="form-group row">
                                        <label for="address" class="col-sm-4 col-form-label">@lang('website.Address')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="customers_address" placeholder="@lang('website.Address')" class="form-control field-validate" id="Address" value="{{ auth()->guard('customer')->user()->customers_address }}">
											
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span>
                                        </div>
                                    </div>

									<div class="form-group row">
                                        <label for="city" class="col-sm-4 col-form-label">@lang('website.City')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="customers_city" placeholder="@lang('website.City')" class="form-control field-validate" id="City" value="{{ auth()->guard('customer')->user()->customers_city }}">
											
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your City')</span>
                                        </div>
                                    </div>

									<div class="form-group row">
                                        <label for="postcode" class="col-sm-4 col-form-label">@lang('website.Zip/Postal Code')</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="customers_postcode" placeholder="@lang('website.Zip/Postal Code')" class="form-control field-validate" id="postcode" value="{{ auth()->guard('customer')->user()->customers_postcode }}">
											
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your postcode')</span>
                                        </div>
                                    </div>


									-->
								  
                                   <!-- <div class="form-group row">
                                        <label for="gender" class="col-sm-4 col-form-label">@lang('website.Gender')</label>
                                        <div class="col-sm-8">
                                            <select class="custom-select field-validation" name="customers_gender" id="gender">
                                                <option value="0" @if(auth()->guard('customer')->user()->customers_gender == 0) selected @endif>@lang('website.Male')</option>
                                                <option value="1"  @if(auth()->guard('customer')->user()->customers_gender == 1) selected @endif>@lang('website.Female')</option>
                                            </select>
                                            <span class="help-block error-content" hidden>@lang('website.Please select your gender')</span>
                                        </div>                                        
                                    </div>
                                                                 
                                    <div class="form-group row">
                                        <label for="datepicker" class="col-sm-4 col-form-label">@lang('website.Date of Birth')</label>
                                        <div class="col-sm-8">
                                            <input readonly name="customers_dob" type="text" class="form-control" id="datepicker" placeholder="@lang('website.Date of Birth')" value="{{ auth()->guard('customer')->user()->customers_dob }}">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your date of birth.')</span>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-4 col-form-label">@lang('website.Phone Number')</label>
                                        <div class="col-sm-8">
                                            <input name="customers_telephone" type="tel" class="form-control number-validate" id="phone" placeholder="@lang('website.Phone Number')" value="{{ auth()->guard('customer')->user()->customers_telephone }}">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                                        </div>
                                    </div>
                                    <div class="button btn_checkout2 btn_checkout">
                                        <button type="submit" class="btn btn-dark">@lang('website.Update')</button>
                                    </div>
                                </form>
                                                                
                                <h5 class="title-h5" style="margin-top: 30px;    clear: both;    float: left;">@lang('website.Change Password')</h5>
                                <hr class="featurette-divider" style="    clear: both;
    margin-top: 22px;
    width: 100%;
    float: left;">
                                <form name="updateMyPassword" class="" enctype="multipart/form-data" action="{{ URL::to('/updateMyPassword')}}" method="post" style="    clear: both;">
                                    <div class="form-group row">
                                        <label for="new_password" class="col-sm-4 col-form-label">@lang('website.New Password')</label>
                                        <div class="col-sm-8">
                                            <input name="new_password" type="password" class="form-control" id="new_password" placeholder="@lang('website.New Password')">
                                            <span class="help-block error-content" hidden>@lang('website.Please enter your password and should be at least 6 characters long')</span>
                                        </div>
                                    </div>
                                    <div class="button btn_checkout btn_checkout2">
                                        <button type="submit" class="btn btn-dark">@lang('website.Update')</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
		</div>		
	</div>
</section>
@endsection 	


