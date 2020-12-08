@extends('layout')
@section('content')
<section class="site-content contact-page">
	<!-- <div id="googleMap" style="width:100%;height:380px; margin-top:-30px; margin-bottom:30px; "></div> -->
	<div class="container">
  		<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Contact Us')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
            		<li class="breadcrumb-item active">@lang('website.Contact Us')</li>
                </ol>
            </div>
        </div>
        <div class="contact-area">
        	<h2><strong>Contact Us</strong></h2>
        	<div class="row contact_maincontent" style="clear:both;">
                <div class="col-12 col-md-12 col-lg-8">
					<div class="et_pb_row et_pb_row_0 contact_top et_pb_row_4col contact-top1">
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_0    et_pb_css_mix_blend_mode_passthrough">
							<div class="et_pb_module et_pb_blurb et_pb_blurb_0 et_pb_bg_layout_light  et_pb_text_align_left  et_pb_blurb_position_top">
								<div class="et_pb_blurb_content">
									<div class="et_pb_blurb_container">
										<h4 class="et_pb_module_header">Customer enquiries</h4>
										<div class="et_pb_blurb_description">
											<a href="tel:1300503013"><p>1300 503 013</p></a></p>
										</div><!-- .et_pb_blurb_description -->
									</div>
								</div> <!-- .et_pb_blurb_content -->
							</div> <!-- .et_pb_blurb -->

						</div> <!-- .et_pb_column -->
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_1    et_pb_css_mix_blend_mode_passthrough">
							<div class="et_pb_module et_pb_blurb et_pb_blurb_1 et_pb_bg_layout_light  et_pb_text_align_left  et_pb_blurb_position_top">
								<div class="et_pb_blurb_content">
									<div class="et_pb_blurb_container">
										<h4 class="et_pb_module_header">Address:</h4>
										<div class="et_pb_blurb_description">
											<p>108 Bridge Road<br />
                                            Glebe, NSW 2037</p>
										</div><!-- .et_pb_blurb_description -->
									</div>
								</div> <!-- .et_pb_blurb_content -->
							</div> <!-- .et_pb_blurb -->
						</div> <!-- .et_pb_column -->
						<div class="et_pb_column et_pb_column_1_4 et_pb_column_2    et_pb_css_mix_blend_mode_passthrough">
							
							<div class="et_pb_module et_pb_blurb et_pb_blurb_2 et_pb_bg_layout_light  et_pb_text_align_left  et_pb_blurb_position_top">
								<div class="et_pb_blurb_content">
									<div class="et_pb_blurb_container">
										<h4 class="et_pb_module_header">Postal Address:</h4>
										<div class="et_pb_blurb_description">
                                        <p>PO Box 7803,<br />
                                        Bondi Beach NSW 2026</p>
                                        </div><!-- .et_pb_blurb_description -->
									</div>
								</div> <!-- .et_pb_blurb_content -->
							</div> <!-- .et_pb_blurb -->
						</div> <!-- .et_pb_column -->
					</div>

					<div class="contact-form">
						<h2> @lang('website.contact title')</h2>
	                	<p>
	                    @lang('website.Dummy Text')</p>
	                     @if(session()->has('success') )
	                        <div class="alert alert-success">
	                            {{ session()->get('success') }}
	                        </div>
	                     @endif
	                    <form name="signup" class="form-validate" enctype="multipart/form-data" action="{{ URL::to('/processContactUs')}}" method="post" id="contact-form">
	                        <div class="col-6 col-md-6 col-lg-6">
								<div class="form-group">
		                           <!-- <label for="firstName">@lang('website.First Name')</label> -->
		                            <input type="text" class="form-control field-validate" id="name" name="firstname" placeholder="First Name*" required>
									<span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>
		                        </div>
							</div>
	                        <div class="col-6 col-md-6 col-lg-6">
								<div class="form-group">
		                           <!-- <label for="firstName">@lang('website.Last Name')</label> -->
		                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
									<span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span>
		                        </div>
							</div>
	                        <div class="col-6 col-md-6 col-lg-6">
								<div class="form-group">
		                           <!-- <label for="firstName">@lang('website.Phone')</label> -->
		                            <input type="text" class="form-control field-validate" id="lastname" name="phone" placeholder="Phone*" required>
									<span class="help-block error-content" hidden>@lang('website.Please enter your phone')</span>
		                        </div>
							</div>
							<div class="col-6 col-md-6 col-lg-6">
							 <div class="form-group">
	                            <!-- <label for="inputEmail4" class="col-form-label">@lang('website.Email')</label> -->
	                            <input type="email" class="form-control email-validate" id="inputEmail4" name="email" placeholder="Email*" required>
								<span class="help-block error-content" hidden>@lang('website.Please enter your valid email address')</span>
	                        </div>
							
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
	                            <!-- <label for="firstName">@lang('website.Company')</label> -->
	                            <input type="text" class="form-control" id="company" name="company" placeholder="Company">
								<span class="help-block error-content" hidden>@lang('website.Please enter your subject')</span>
	                        	</div>
							</div>
							{{-- <div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
	                            <!-- <label for="firstName">@lang('website.Subject')</label> -->
	                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Subject">
								<span class="help-block error-content" hidden>@lang('website.Please enter your subject')</span>
	                        	</div>
							</div> --}}
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group">
									<!-- <label for="subject" class="col-form-label">@lang('website.Message')</label> -->
									<textarea type="text" class="form-control" id="message" rows="5" name="message" placeholder="Message*" required></textarea>
									<span class="help-block error-content" hidden>@lang('website.Please enter your message')</span>
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12">
								<div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
									{!! app('captcha')->display() !!}
									@if ($errors->has('g-recaptcha-response'))
										<span class="help-block text-danger">
											<strong>{{ $errors->first('g-recaptcha-response') }}</strong>
										</span>
									@endif
								</div>
							</div>
	                        <div class="col-12 col-md-12 col-lg-12"><div class="button">
	                            <button type="submit" class="btn btn-dark">@lang('website.Send')</button>
	                            
	                        </div>
	                        </div>
	                    </form>
	                </div>
                </div>
                
                <div class="col-12 col-md-12 col-lg-4 d-none">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3310.387535402467!2d151.01342791482736!3d-33.93115942972868!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6b12be9a7b2b451d%3A0x4bde50e1cb971ff4!2s103%20Eldridge%20Rd%2C%20Condell%20Park%20NSW%202200%2C%20Australia!5e0!3m2!1sen!2skh!4v1574434063447!5m2!1sen!2skh" width="100%" height="460" frameborder="0" style="border:0" allowfullscreen=""></iframe>
                </div>
            </div>
        </div>
	</div>
</section>
@endsection 