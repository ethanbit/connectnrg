@extends('layout')
@section('content')

<style>
.checkout-left-in.purchase_order_number .col-md-3 {
    padding-right: 0;
}
.checkout-left-in.purchase_order_number .col-md-4 {
    padding-left: 0;
}
.checkout-left-in.purchase_order_number label {
    margin-bottom: 0;
}
</style>
<section class="site-content">
  <div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Checkout')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">@lang('website.Checkout')</a></li>
                    <li class="breadcrumb-item">
                      <a href="javascript:void(0)">
                        @if(session('step')==0)
                              @lang('website.Shipping Address')
                            @elseif(session('step')==1)
                              @lang('website.Billing Address')
                            @elseif(session('step')==2)
                              @lang('website.Shipping Methods')
                            @elseif(session('step')==3)
                              @lang('website.Order Detail')
                            @endif
                      </a>
                    </li>
                </ol>
            </div>
        </div>
    <div class="checkout-area">
        <div class="row">
            <div class="col-12 col-lg-9 col-9 checkout-left">
            <div class="checkout-left-in">
                    <input type="hidden" id="hyperpayresponse" value="@if(!empty(session('paymentResponse'))) @if(session('paymentResponse')=='success') {{session('paymentResponse')}} @else {{session('paymentResponse')}}  @endif @endif">
                    <h3><strong>Shipping</strong> details <a class="d-none" href="{{ URL::to('/shipping-address')}}">Change shipping address</a></h3>
                    <div class="alert alert-danger alert-dismissible" id="paymentError" role="alert" style="display:none;">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        @if(!empty(session('paymentResponse')) and session('paymentResponse')=='error') {{session('paymentResponseData') }} @endif
                    </div>                    
                        <div class="tab-content" id="pills-tabContent">
                          <!-- <div class="tab-pane fade @if(session('step') == 0) show active @endif" id="pills-shipping" role="tabpanel" aria-labelledby="shipping-tab"> -->
                            <form name="signup" id="dh_form_shipping" enctype="multipart/form-data" class="form-validate" action="{{ URL::to('/checkout_shipping_address')}}" method="post">
                              <input type="hidden" id="address_book_id" name="address_book_id" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->address_id}}@endif">
                                <div class="form-row">
                                  <div class="form-group col-md-12">
                                    <label class="col-md-2 col-sm-12 col-form-label" style="float:left;">Select Address: </label>
                                    <select id="d_shipping_address_book" class="form-control col-md-5 col-sm-12">
                                        <option value="">-- New Address --</option>
                                        <?php 
                                        //echo "<!-- Ducnb <pre>"; print_r($result['address_book']); echo "</pre>".__FILE__.":".__LINE__."-->";
                                        $addressBook = $result['address_book'];
										
										
										 $selectedopt = '';
                                        foreach($result['address_book'] as $add){
                                         
										  if(@session('shipping_address')->address_id)
										  {
                                          if(session('shipping_address')->address_id == $add->address_id){
                                            $selectedopt = "selected";
                                          }
										  }
                                        ?>
                                        <option {{ $selectedopt }} value="{{$add->address_id}}" data-firstname="{{ $add->firstname }}" data-lastname="{{ $add->lastname }}" data-street="{{ $add->street }}" data-suburb="{{ $add->suburb }}" data-postcode="{{ $add->postcode }}" data-city="{{ $add->city }}" data-zoneid="{{ $add->zone_id }}" data-company="{{ $add->company }}" data-phone="{{ $add->phone }}">{{ $add->firstname.' '.$add->lastname.', '.$add->street.', '.$add->suburb.' '.$add->postcode.' '.$add->city.' '.$add->zone_name }}</option>
                                        <?php
                                        }
                                        ?>                                
                                    </select>
                                  </div>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <!-- <label for="firstName">@lang('website.First Name')</label> -->
                                    <input type="text" class="form-control field-validate" id="firstname" name="firstname" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->firstname}}@endif" placeholder="@lang('website.First Name')">
                                     <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>  
                                  </div>
                                  <div class="form-group col-md-6">
                                    <!-- <label for="lastName">@lang('website.Last Name')</label> -->
                                    <input type="text" class="form-control field-validate" id="lastname" name="lastname" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->lastname}}@endif" placeholder="@lang('website.Last Name')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                    <!-- <label for="firstName">@lang('website.Company')</label> -->
                                    <input type="text" class="form-control field-validate" id="company" name="company" value="@if(count((array)session('shipping_address'))>0) {{session('shipping_address')->company}}@endif" placeholder ="@lang('website.Company')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your company name')</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                    <!-- <label for="firstName">@lang('website.Address')</label> -->
                                    <input type="text" class="form-control field-validate" id="street" name="street" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->street}}@endif" placeholder ="@lang('website.Address')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span> 
                                  </div>
                                  <div class="form-group col-md-6" hidden>
                                    <label for="lastName">@lang('website.Country')</label>
                                      <select class="form-control field-validate" id="entry_country_id" onChange="getZones();" name="countries_id">
                                          <option value="" selected>@lang('website.Select Country')</option>
                                          @if(count((array)$result['countries'])>0)
                                            @foreach($result['countries'] as $countries)
                                              <?php 
                                              $selected = '';
                                              if(count((array)session('shipping_address'))>0) {
                                                if(session('shipping_address')->countries_id == $countries->countries_id){
                                                  $selected = 'selected';
                                                }
                                              }elseif($countries->countries_id == 13){
                                                  $selected = 'selected';
                                              }
                                              ?>
                                                <option value="{{$countries->countries_id}}" {{ $selected }} >{{$countries->countries_name}}</option>
                                            @endforeach
                                          @endif
                                      </select>
                                    <span class="help-block error-content" hidden>@lang('website.Please select your country')</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                    <!-- <label for="firstName">Suburb</label> -->
                                    <input type="text" class="form-control field-validate" id="suburb" name="suburb" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->suburb}}@endif" placeholder="Suburb" />
                                    <span class="help-block error-content" hidden>@lang('website.Please select your suburb')</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                   <!--  <label for="firstName">@lang('website.State')</label> -->
                                    <select class="form-control field-validate" id="entry_zone_id" name="zone_id">
                       <!-- <option value="190" >Australian</option>  --> 
                                          <option value="Select State">Select State</option>
                                           @if(count((array)$result['zones'])>0)
                                            @foreach($result['zones'] as $zones)
                                                <option value="{{$zones->zone_id}}" @if(count((array)session('shipping_address'))>0) @if(session('shipping_address')->zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                                            @endforeach
                                          @endif
                                          
                                           <option value="Other" @if(count((array)session('shipping_address'))>0) @if(session('shipping_address')->zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>                      
                                    </select>
                                    <span class="help-block error-content" hidden>@lang('website.Please select your state')</span> 
                                  </div>
                                  {{-- <div class="form-group col-md-6 ">
                                    <label for="lastName">@lang('website.City')</label>
                                    <input autocomplete="use-new-city" type="text" class="form-control field-validate" id="city" name="city" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->city}}@endif" placeholder ="@lang('website.City')" />
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span> 
                                  </div> --}}
                                  <div class="form-group col-md-6">
                                    <!-- <label for="lastName">@lang('website.Zip/Postal Code')</label> -->
                                    <input type="text" class="form-control" id="postcode" name="postcode" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->postcode}}@endif" placeholder="@lang('website.Zip/Postal Code')" />
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</span> 
                                  </div>    
                                  <?php 
                                  //echo "<!-- Ducnb <pre>"; print_r(session('shipping_address')); echo "</pre>".__FILE__.":".__LINE__."-->";
                                  ?>
                                  <div class="form-group col-md-6">
                                   <!-- <label for="lastName">@lang('website.Phone Number')</label> -->
                                    <input type="text" class="form-control" id="delivery_phone" name="delivery_phone" value="@if(count((array)session('shipping_address'))>0){{session('shipping_address')->phone}}@endif" placeholder="@lang('website.Phone Number')" />
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span> 
                                  </div>      
                                  <div class="form-group col-md-6">                                
                                    <!-- <label for="lastName">Delivery Date</label> -->
                                    <!-- <input type="text" class="form-control field-validate" id="delivery_date" name="delivery_date" readonly value="@if(count((array)session('shipping_address'))>0){{@session('shipping_address')->delivery_date}}@endif" placeholder ="*Delivery Date" autocomplete="off" required /> -->
                                    <!-- <i>We will try our best to deliver your order on the specified date.</i>-->
                                    <!-- <i style="color: red; display: inline-block;">Note: Delivery is 7 days from the date of order placement.</i> -->
                                    <i class="form-control" style="color: red;display: inline-block;font-weight: 500;font-size: 14px;">Delivery timeframe is minimum 2 days from the order date.</i>
                                    <span class="help-block error-content" hidden>*Please select delivery date.</span> 
                                  </div> 
								 <div class="form-group col-md-6">                                
									
                                        <textarea name="delivery_note" id="order_comments_shiping" class="form-control" placeholder="Delivery Notes (optional)">@if(!empty(session('delivery_note'))){{session('delivery_note')}}@endif</textarea>
                                  </div> 

								
						
						
                                </div>    
                                <!-- <div class="button"><button type="submit" class="btn btn-dark">@lang('website.Continue')</button></div> -->

                                
						  
                                <div class="checkout-left-in purchase_order_number">
                                    <div class="row align-items-center">
                                        <div class="form-group col-md-3">
                                            <label for="lastName">Purchase Order Number*</label>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="text" class="form-control field-validate" id="purchase_order_no" name="purchase_order_no" value="" placeholder="" required>
                                            <span class="help-block error-content" hidden="">Please enter your Purchase Order number.</span>   
                                        </div>
                                    </div>
                                </div>
                          </form>
                          <!-- </div> -->
                          
                          </div></div>	
						<div class="checkout-left-in d-none">
						<div class="tab-content" id="">
                          
                          <!-- <div class="tab-pane fade @if(session('step') == 1) show active @endif" id="pills-billing" role="tabpanel" aria-labelledby="billing-tab"> -->
                            <form name="signup" id="dh_form_billing" class="" enctype="multipart/form-data" action="{{ URL::to('/checkout_billing_address')}}" method="post">                          
                              <div class="form-group">
                                  <div class="form-check">
                                    <label class="form-check-label">
                                        <input  class="form-check-input" id="same_billing_address" value="1" type="checkbox" name="same_billing_address" checked>
                                        <strong>Billing</strong> to same shipping address?
                                    </label>
                                  </div>
                              </div>
                              <div id="billing_wrapper" class="d-none">
                                <h3><strong>Billing</strong> details</h3>
                                <div class="form-row form-group">
                                    <!-- <label for="d_address_book" class="col-md-2 col-sm-12" style="text-align:right"><b>Select address: </b></label> -->
                                    <select id="d_address_book" name="d_address_book" class="form-control col-md-5 col-sm-12">
                                        <option value="">-- New Address --</option>
                                        <?php 
                                        //echo "<!-- Ducnb <pre>"; print_r($result['address_book']); echo "</pre>".__FILE__.":".__LINE__."-->";
                                        $addressBook = $result['address_book'];
                                        foreach($result['address_book'] as $add){
                                        ?>
                                        <option value="{{$add->address_id}}" data-firstname="{{ $add->firstname }}" data-lastname="{{ $add->lastname }}" data-street="{{ $add->street }}" data-suburb="{{ $add->suburb }}" data-postcode="{{ $add->postcode }}" data-city="{{ $add->city }}" data-zoneid="{{ $add->zone_id }}" data-company="{{ $add->company }}" data-phone="{{ $add->phone }}">{{ $add->firstname.' '.$add->lastname.', '.$add->street.', '.$add->suburb.', '.$add->postcode.', '.$add->city.', '.$add->zone_name.', '.$add->phone }}</option>
                                        <?php
                                        }
                                        ?>                                
                                    </select>
                                </div>
                                <div class="form-row">
                                  <div class="form-group col-md-6">
                                    <!-- <label for="firstName">@lang('website.First Name')</label> -->
                                    <input type="text" class="form-control same_address" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_firstname" name="billing_firstname" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_firstname}}@endif" placeholder="@lang('website.First Name')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your first name')</span>  
                                  </div>
                                  <div class="form-group col-md-6">
                                    <!-- <label for="lastName">@lang('website.Last Name')</label> -->
                                    <input type="text" class="form-control same_address" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_lastname" name="billing_lastname" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_lastname}}@endif" placeholder ="@lang('website.Last Name')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your last name')</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                   <!--  <label for="firstName">@lang('website.Company')</label> -->
                                    <input type="text" class="form-control same_address" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_company" name="billing_company" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_company}}@endif" placeholder="@lang('website.Company')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your company name')</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                   <!-- <label for="firstName">@lang('website.Address')</label> -->
                                    <input type="text" class="form-control same_address" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_street" name="billing_street" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_street}}@endif" placeholder="@lang('website.Address')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your address')</span>
                                  </div>
                                  <div class="form-group col-md-6 d-none">
                                    <!-- <label for="lastName">@lang('website.Country')</label> -->
                                      <select class="form-control same_address_select" id="billing_countries_id"  onChange="getBillingZones();" name="billing_countries_id" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif  >
                                          <option value=""  >@lang('website.Select Country')</option>
                                          @if(count((array)$result['countries'])>0)
                                            @foreach($result['countries'] as $countries)
                                                <option value="{{$countries->countries_id}}" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->billing_countries_id == $countries->countries_id) selected @endif @endif >{{$countries->countries_name}}</option>
                                            @endforeach
                                          @endif
                                      </select>
                                      <span class="help-block error-content" hidden>@lang('website.Please select your country')</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                   <!-- <label for="firstName">Suburb</label> -->
                                    <input type="text" class="form-control" id="billing_suburb" name="billing_suburb" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_suburb}}@endif" placeholder="Suburb" />
                                    <span class="help-block error-content" hidden>Please select your suburb</span> 
                                  </div>
                                  <div class="form-group col-md-6">
                                    <!-- <label for="firstName">@lang('website.State')</label> -->
                                    <select class="form-control same_address_select" id="billing_zone_id" name="billing_zone_id" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) disabled @endif @else disabled @endif  >
                                          <option value="" >@lang('website.Select State')</option>
                                          @if(count((array)$result['zones'])>0)
                                            @foreach($result['zones'] as $key=>$zones)
                                                <option value="{{$zones->zone_id}}" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == $zones->zone_id) selected @endif @endif >{{$zones->zone_name}}</option>
                                            @endforeach                        
                                          @endif
                                            <option value="Other" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->billing_zone_id == 'Other') selected @endif @endif>@lang('website.Other')</option>
                                      </select>
                                      <span class="help-block error-content" hidden>@lang('website.Please select your state')</span> 
                                  </div>
                                  {{-- <div class="form-group col-md-6">
                                   <label for="lastName">@lang('website.City')</label>
                                    <input type="text" class="form-control same_address" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_city" name="billing_city" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_city}}@endif" placeholder="@lang('website.City')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your city')</span>
                                  </div> --}}
                                  <div class="form-group col-md-6">
                                    <!-- <label for="lastName">@lang('website.Zip/Postal Code')</label> -->
                                    <input type="text" class="form-control same_address"  @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_zip" name="billing_zip" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_zip}}@endif" placeholder="@lang('website.Zip/Postal Code')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your Zip/Postal Code')</span> 
                                  </div>  
                                    
                                  <div class="form-group col-md-6">
                                    <!-- <label for="lastName">@lang('website.Phone Number')</label> -->
                                    <input type="text" class="form-control same_address" @if(count((array)session('billing_address'))>0) @if(session('billing_address')->same_billing_address==1) readonly @endif @else readonly @endif  id="billing_phone" name="billing_phone" value="@if(count((array)session('billing_address'))>0){{session('billing_address')->billing_phone}}@endif" placeholder="@lang('website.Phone Number')">
                                    <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span> 
                                  </div>    
                                </div>
                              </div>
                                <div class="button d-none"><button type="submit" class="btn btn-dark"> @lang('website.Continue')</button></div>
                            </form>
                         <!-- </div> -->
                  
                    <!-- <div class="tab-pane fade @if(session('step') == 3) show active @endif" id="pills-order" role="tabpanel" aria-labelledby="order-tab">  -->                    
                        <?php 
                            $price = 0;
                        ?>

                        <?php           
                            if(!empty(session('shipping_detail')) and count((array)session('shipping_detail'))>0){
                                
                                if(!empty(session('coupon_shipping_free')) and session('coupon_shipping_free')=='yes'){
                                    $shipping_price = 0;
                                }else{
                                    $shipping_price = session('shipping_detail')->shipping_price;
                                }
                                $shipping_name = session('shipping_detail')->mehtod_name;
                            }else{
                                $shipping_price = 0;
                                $shipping_name = '';
                            }               
                            $tax_rate = number_format((float)session('tax_rate'), 2, '.', '');
                            $coupon_discount = number_format((float)session('coupon_discount'), 2, '.', '');                
                            $total_price = ($price+$tax_rate+$shipping_price)-$coupon_discount; 
                            session(['total_price'=>$total_price]);
                                        
                        ?>
                        <div class="notes-summary-area">
                          <div class="heading">
                                <h2>Additional information</h2>
                                <hr>
                            </div>
                          <div class="row">
                              <div class="col-12 order-notes">
                                    {{-- <p class="title">Order Notes (optional)</p> --}}
                                    <div class="form-group">
                                        <p for="order_comments"></p>
                                        <textarea name="comments" id="order_comments" class="form-control" placeholder="Order Notes (optional)">@if(!empty(session('order_comments'))){{session('order_comments')}}@endif</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- The braintree Modal -->
                        <div class="modal fade" id="braintreeModel">
                          <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="checkout" method="post" action="{{ URL::to('/place_order')}}">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('website.BrainTree Payment')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                          <div id="payment-form"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-dark">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                                    </div>
                                </form>
                            </div>
                           </div>
                        </div>
                        
                        <!-- The instamojo Modal -->
                        <div class="modal fade" id="instamojoModel">
                          <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="instamojo_form" method="post" action="">
                                  <input type="hidden" name="amount" value="{{number_format((float)$total_price+0, 2, '.', '')}}">
                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">@lang('website.Instamojo Payment')</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                   <div class="modal-body">
                                          <div class="form-group row">
                                            <label for="firstName" class="col-sm-4 col-form-label">@lang('website.Full Name')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="fullname" class="form-control" placeholder="@lang('website.Full Name')" id="firstName">
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your full name')</span>
                                            </div>
                                         </div>
                                          <div class="form-group row">
                                            <label for="firstName" class="col-sm-4 col-form-label">@lang('website.Email')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="email_id" class="form-control " placeholder="@lang('website.Email')" id="email_id">
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your email address')</span>
                                            </div>
                                         </div>
                                          <div class="form-group row">
                                            <label for="firstName" class="col-sm-4 col-form-label">@lang('website.Phone Number')</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="phone_number" class="form-control" placeholder="@lang('website.Phone Number')" id="insta_phone_number">
                                                <span class="help-block error-content" hidden>@lang('website.Please enter your valid phone number')</span>
                                            </div>
                                         </div>
                                         <div class="alert alert-danger alert-dismissible" id="insta_mojo_error" role="alert" style="display: none">
                                            <span class="sr-only">@lang('website.Error'):</span>
                                            <span id="instamojo-error-text"></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="pay_instamojo" class="btn btn-dark">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                                    </div>
                                </form>
                            </div>
                           </div>
                        </div>
                        
                        <!-- The stripe Modal -->
                        <div class="modal fade" id="stripeModel">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                
                                <main>
                                <div class="container-lg">
                                    <div class="cell example example2">
                                        <form>
                                          <div class="row">
                                            <div class="field">
                                              <div id="example2-card-number" class="input empty"></div>
                                              <label for="example2-card-number" data-tid="elements_examples.form.card_number_label">@lang('website.Card number')</label>
                                              <div class="baseline"></div>
                                            </div>
                                          </div>
                                          <div class="row">
                                            <div class="field half-width">
                                              <div id="example2-card-expiry" class="input empty"></div>
                                              <label for="example2-card-expiry" data-tid="elements_examples.form.card_expiry_label">@lang('website.Expiration')</label>
                                              <div class="baseline"></div>
                                            </div>
                                            <div class="field half-width">
                                              <div id="example2-card-cvc" class="input empty"></div>
                                              <label for="example2-card-cvc" data-tid="elements_examples.form.card_cvc_label">@lang('website.CVC')</label>
                                              <div class="baseline"></div>
                                            </div>
                                          </div>
                                        <button type="submit" class="btn btn-dark" data-tid="elements_examples.form.pay_button">@lang('website.Pay') {{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')}}</button>
                                        
                                          <div class="error" role="alert"><svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17">
                                              <path class="base" fill="#000" d="M8.5,17 C3.80557963,17 0,13.1944204 0,8.5 C0,3.80557963 3.80557963,0 8.5,0 C13.1944204,0 17,3.80557963 17,8.5 C17,13.1944204 13.1944204,17 8.5,17 Z"></path>
                                              <path class="glyph" fill="#FFF" d="M8.5,7.29791847 L6.12604076,4.92395924 C5.79409512,4.59201359 5.25590488,4.59201359 4.92395924,4.92395924 C4.59201359,5.25590488 4.59201359,5.79409512 4.92395924,6.12604076 L7.29791847,8.5 L4.92395924,10.8739592 C4.59201359,11.2059049 4.59201359,11.7440951 4.92395924,12.0760408 C5.25590488,12.4079864 5.79409512,12.4079864 6.12604076,12.0760408 L8.5,9.70208153 L10.8739592,12.0760408 C11.2059049,12.4079864 11.7440951,12.4079864 12.0760408,12.0760408 C12.4079864,11.7440951 12.4079864,11.2059049 12.0760408,10.8739592 L9.70208153,8.5 L12.0760408,6.12604076 C12.4079864,5.79409512 12.4079864,5.25590488 12.0760408,4.92395924 C11.7440951,4.59201359 11.2059049,4.59201359 10.8739592,4.92395924 L8.5,7.29791847 L8.5,7.29791847 Z"></path>
                                            </svg>
                                            <span class="message"></span></div>
                                        </form>
                                                    <div class="success">
                                                      <div class="icon">
                                                        <svg width="84px" height="84px" viewBox="0 0 84 84" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                                          <circle class="border" cx="42" cy="42" r="40" stroke-linecap="round" stroke-width="4" stroke="#000" fill="none"></circle>
                                                          <path class="checkmark" stroke-linecap="round" stroke-linejoin="round" d="M23.375 42.5488281 36.8840688 56.0578969 64.891932 28.0500338" stroke-width="4" stroke="#000" fill="none"></path>
                                                        </svg>
                                                      </div>
                                                      <h3 class="title" data-tid="elements_examples.success.title">@lang('website.Payment successful')</h3>
                                                      <p class="message"><span data-tid="elements_examples.success.message">@lang('website.Thanks You Your payment has been processed successfully')</p>
                                                    </div>
                                
                                                </div>
                                            </div>
                                        </main>
                                    </div>
                              </div>
                          </div>
                      </div>
            <!-- </div>  --> <!-- End #pills-order -->
            </div> <!--CHECKOUT LEFT in CLOSE-->
            </div> <!--CHECKOUT LEFT CLOSE-->
                
            <div class="col-12 col-lg-3 col-3 checkout-right ">    
            <div class="checkout-right-in ">    
                <h3><strong>Your</strong> orders</h3>
                <div class="order-review">
                    <form method='POST' id="update_cart_form" action='{{ URL::to('/place_order')}}' >
					<input id="order_no" type="hidden" name="order_no" >
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th align="left">Product</th>
                                        {{-- <th align="left">Total</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
								<?php 
								/* echo "<pre>";
								print_r($result['cart']);
								 die; */
								 $price=0;
								?>
                                @foreach( $result['cart'] as $products)
                                <?php 
                                    $price+= $products->final_price * $products->customers_basket_quantity;
                                    //echo "<pre>"; print_r($products); echo "</pre>".__FILE__.":".__LINE__;
                                ?>
                                    <tr>
                                        <td align="left" class="item">  
                                            <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                                            <div class="cart-product-detail">
                                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="title">
                                                    
                                                </a>
                                                {{$products->products_name}} {{$products->model}} x {{$products->customers_basket_quantity}}
                                                @if(count((array)$products->attributes) >0)
                                                    <ul>
                                                        @foreach($products->attributes as $attributes)
                                                            <li>{{$attributes->attribute_name}}<span>{{$attributes->attribute_value}}</span></li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                            <div class="form-group Qty d-none">                        
                                                <div class="input-group dhqty">      
                                                    <span class="input-group-btn first qtyminus">
                                                        <button class="btn btn-defualt" type="button">-</button>                        
                                                    </span>
                                                    <input type="text" readonly="" name="quantity" value="{{$products->customers_basket_quantity}}" min="1" max="100000" class="form-control qty">
                                                    <span class="input-group-btn last qtyplus">
                                                        <button class="btn btn-defualt" type="button">+</button>                        
                                                    </span>   
                                                    &nbsp;   &nbsp;
                                                    <span class="btn btn-sm btn-secondary dhupdatecart" data-product_id="{{$products->products_id}}" data-cart_id="{{$products->customers_basket_id}}"><i class="fa fa-refresh" aria-hidden="true"></i></span>   
                                                    <a href="{{ URL::to('/editcart?id='.$products->customers_basket_id)}}" class="">@lang('website.Edit')</a>            
                                                </div>
                                            </div>
                                        </td>
                                    
                                         <td align="right" class="subtotal">
                                            <!-- <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}" class"btn btn-sm btn-secondary"><i class="fa fa-trash" aria-hidden="true"></i></a> -->
											<?php echo $web_setting[19]->value.number_format($products->final_price * $products->customers_basket_quantity,2);?>
                                        </td> 
                                    </tr>           
                                @endforeach
								<tr>	
								<td align="right" style="border-top:0px;"><strong>SUB TOTAL</strong></td>
								<td align="right" style="border-top:0px;"><strong>$<?php  echo number_format($price,2);?></strong></td>		
								</tr>	
								<?php 
								$taxRateIs=$result['tax_rate_setting'];
								$GST=$price*($taxRateIs/100);
								$TOTAL=$price+$GST;
								?>
								<tr>												
								<td align="right" style="border-top:0px;"><strong>GST</strong></td>
								<td align="right" style="border-top:0px;"><strong>$<?php  echo number_format($GST,2);?></strong></td>											
								</tr>
								
								<tr>	
								<td align="right" style="border-top:0px;"><strong>TOTAL</strong></td>
								<td align="right" style="border-top:0px;"><strong>$<?php  echo number_format($TOTAL,2);?></strong></td>
								</tr>
								
                                </tbody>  
                            </table>
                        </div>
                    </form>
                </div>
                </div>
                <div class="order-summary-outer d-none">
                    <div class="order-summary">
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td class="label"><span>@lang('website.SubTotal')</span></td>
                                        <td align="right" id="subtotal">{{$web_setting[19]->value}}{{$price+0}}</td>
                                    </tr>
                                    <tr>
                                        <td class="label"><span>@lang('website.Tax')</span></td>
                                        <td align="right">{{$web_setting[19]->value}}{{$tax_rate}}</td>
                                    </tr>
                                    <tr>
                                        <td class="label">
                                          <span>Shipping</br><small>{{$shipping_name}}</small>  @if(!empty($web_setting[82]->value))</br><small>@lang('website.Avail free shpping on') {{$web_setting[19]->value}}{{$web_setting[82]->value}}.</small>@endif</span></td>
                                        <td align="right">
                                            {{-- {{$web_setting[19]->value}}{{$shipping_price}} --}}
                                            <div class="shipping-methods ">
                                                <form name="shipping_mehtods" method="post" id="shipping_mehtods_form" enctype="multipart/form-data" action="{{ URL::to('/checkout_payment_method')}}">
                                                @if(count((array)$result['shipping_methods'])>0)
                                                    <input type="hidden" name="mehtod_name" id="mehtod_name">
                                                    <input type="hidden" name="shipping_price" id="shipping_price">
                                                    
                                                @foreach($result['shipping_methods'] as $shipping_methods)
                                                        {{-- <div class="heading">
                                                            <h2>{{$shipping_methods['name']}}</h2>
                                                        </div> --}}
                                                        <div class="form-check">
                                                            
                                                            <div class="container">
                                                                @if($shipping_methods['success']==1)
                                                                <div class="list row container3">                              
                                                                    @foreach($shipping_methods['services'] as $services)
                                                                     <?php
                                                                         if($services['shipping_method']=='upsShipping')
                                                                            $method_name=$shipping_methods['name'].'('.$services['name'].')';
                                                                         else{
                                                                            $method_name=$services['name'];
                                                                            }
                                                                        ?>
                                                                        <div class="col-10" for="{{$method_name}}">{{$services['name']}} {{$web_setting[19]->value}}{{$services['rate']}}</div>
                                                                        <div class="col-2">
                                                                            <input class="shipping_data" id="{{$method_name}}" type="radio" name="shipping_method" value="{{$services['shipping_method']}}" shipping_price="{{$services['rate']}}"  method_name="{{$method_name}}" @if(!empty(session('shipping_detail')) and count((array)session('shipping_detail')) > 0) 
                                                                        @if(session('shipping_detail')->mehtod_name == $method_name) checked @endif
                                                                        @elseif($shipping_methods['is_default']==1) checked @endif
                                                                        ><span class="checkmark3"></span>
                                                                        </div>
                                                                    @endforeach
                                                                </duv>
                                                                @else
                                                                    <ul class="list">
                                                                        <li>@lang('website.Your location does not support this') {{$shipping_methods['name']}}.</li>
                                                                    </ul>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                <div class="alert alert-danger alert-dismissible error_shipping" role="alert" style="display:none;">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    @lang('website.Please select your shipping method')
                                                </div>
                                                <div class="button d-none">
                                                    <button type="submit" class="btn btn-dark">@lang('website.Continue')</button>
                                                </div>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="label"><span>Discount</span></td>
                                        <td align="right" id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</td>
                                    </tr>
                                    <tr>
                                        <td class="last label"><span>@lang('website.Total')</span></td>
                                        <td class="last" align="right" id="total_price">{{$web_setting[19]->value}}{{number_format((float)$total_price+0, 2, '.', '')+0}}</td>
                                    </tr>
                              </tbody>
                            </table>
                        </div>
                    </div> 
                    @if(Auth::guard('customer')->check())
                        <div class="coupons">
                        	<!-- applied copuns -->
                            @if(count((array)session('coupon')) > 0 and !empty(session('coupon')))
                            	<div class="form-group"> 
                                    <label>@lang('website.Coupon Applied')</label>         
                                    @foreach(session('coupon') as $coupons_show)  
                                            
                                        <div class="alert alert-success">
                                            <a href="{{ URL::to('/removeCoupon/'.$coupons_show->coupans_id)}}" class="close"><span aria-hidden="true">&times;</span></a>
                                            {{$coupons_show->code}}
                                        </div>
                                        
                                    @endforeach
                                </div>         
                            @endif  
                            <form id="apply_coupon" class="form-validate">
                                <div class="form-group">
                                   <!--  <label >@lang('website.Coupon Code')</label> -->
                                    <input type="text" name="coupon_code" class="form-control" id="coupon_code" placeholder = "code">
                                    
                                    <div id="coupon_error" class="help-block" style="display: none"></div>
                                	<div id="coupon_require_error" class="help-block" style="display: none">@lang('website.Please enter a valid coupon code')</div>
									                   <button type="submit" class="btn btn-sm btn-dark applycode">@lang('website.ApplyCoupon')</button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>  
                <div class="payment-area d-none">
                    <h3>Payment options</h3>
                    <div class="payment-methods">                        
                        <div class="alert alert-danger error_payment" style="display:none" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            @lang('website.Please select your payment method')
                        </div>  
                            
                        <form name="shipping_mehtods" method="post" id="payment_mehtods_form" enctype="multipart/form-data" action="{{ URL::to('/order_detail')}}">
                            <div class="container">
                                <?php $i = 0; ?>
                                @foreach($result['payment_methods'] as $payment_methods)
                                    @if($payment_methods['active']==1)
                                        <?php 
                                        $checked = '';
                                        if($i == 0){
                                            $checked = 'checked';
                                        }
                                        $i++;
                                        ?>
                                        <input id="payment_currency" type="hidden" onClick="paymentMethods();" name="payment_currency" value="{{$payment_methods['payment_currency']}}">
                                        @if($payment_methods['payment_method']=='braintree')
                                            
                                            <input id="{{$payment_methods['payment_method']}}_public_key" type="hidden" name="public_key" value="{{$payment_methods['public_key']}}">
                                            <input id="{{$payment_methods['payment_method']}}_environment" type="hidden" name="{{$payment_methods['payment_method']}}_environment" value="{{$payment_methods['environment']}}">
                                            <div class="row container2">
                                                <div class="col-2">
                                                    <input {{$checked}} type="radio" onClick="paymentMethods();" name="payment_method" class="payment_method" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif><span class="checkmark"></span>
                                                </div>
                                                <div class="col-10">
                                                    <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label>
                                                </div>
                                            </div>
                
                                        @else
                                            <input id="{{$payment_methods['payment_method']}}_public_key" type="hidden" name="public_key" value="{{$payment_methods['public_key']}}">
                                            <input id="{{$payment_methods['payment_method']}}_environment" type="hidden" name="{{$payment_methods['payment_method']}}_environment" value="{{$payment_methods['environment']}}">
                                            
                                            <div class="row container2">
                                                <div class="col-2">
                                                    <input {{$checked}} onClick="paymentMethods();" type="radio" name="payment_method" class="payment_method" value="{{$payment_methods['payment_method']}}" @if(!empty(session('payment_method'))) @if(session('payment_method')==$payment_methods['payment_method']) checked @endif @endif><span class="checkmark"></span>
                                                </div>
                                                <div class="col-10">
                                                    <label for="{{$payment_methods['payment_method']}}">{{$payment_methods['name']}}</label>
                                                </div>
                                            </div>
                                        @endif
                                    @endif
                                @endforeach
                            </div>                             
                        </form>
                    </div>
                </div>
            </div>  <!--CHECKOUT RIGHT CLOSE-->
        </div>
        <div class="row">
            <div class="col-12 col-lg-9 col-9 checkout-left">
              <div class="container">
                <div class="row">
                  <div class="checkout-left-in col-6">
                      <a href="/shop-online" class="btn btn-sm btn-secondary">Continue Shopping</a>
                  </div>
                  <div class="checkout-right-in col-6">
                      <div class="order_btn_____1">
                          <div id="paypal_button" class="payment_btns" style="display: none"></div>
                          
                          <button id="braintree_button" style="display: none" class="btn btn-sm btn-secondary payment_btns" data-toggle="modal" data-target="#braintreeModel">Order Now</button>
                          
                          <button id="stripe_button" class="btn btn-sm btn-secondary payment_btns" style="display: none" data-toggle="modal" data-target="#stripeModel">Order Now</button>
                          
                          <button id="cash_on_delivery_button" class="btn btn-sm btn-secondary payment_btns theOrderButton" style="">Order Now</button>
						  
                          <button id="instamojo_button" class="btn btn-sm btn-secondary payment_btns" style="display: none" data-toggle="modal" data-target="#instamojoModel">Order Now</button>
                          
                          <a href="http://connect.rdautopaints.com.au/checkout/hyperpay" id="hyperpay_button" class="btn btn-sm btn-secondary payment_btns" style="display: none">Order Now</a>
                      </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-12 col-lg-3 col-3 checkout-right ">
            {{-- <div class="checkout-right-in ">
                <div class="order_btn">
                    <div id="paypal_button" class="payment_btns" style="display: none"></div>
                    
                    <button id="braintree_button" style="display: none" class="btn btn-dark payment_btns btn-lg btn-secondary" data-toggle="modal" data-target="#braintreeModel">Order Now</button>
                    
                    <button id="stripe_button" class="btn btn-dark payment_btns btn-lg btn-secondary" style="display: none" data-toggle="modal" data-target="#stripeModel">Order Now</button>
                    
                    <button id="cash_on_delivery_button" class="btn btn-secondary btn-lg payment_btns" style="">Order Now</button>
                    <button id="instamojo_button" class="btn btn-dark payment_btns btn-lg btn-secondary" style="display: none" data-toggle="modal" data-target="#instamojoModel">Order Now</button>
                    
                    <a href="http://connect.rdautopaints.com.au/checkout/hyperpay" id="hyperpay_button" class="btn btn-dark payment_btns btn-lg btn-secondary" style="display: none">Order Now</a>
                </div>
            </div> --}}
            </div>
        </div>
    </div>
    <div class="related_product d-none">
        <h2>Products <strong>you may like</strong></h2>
        <div class="row">
            <?php 
            $recentProducts = $result['commonContent']['recentProducts']['product_data'];
            $recentProductsRandom = array_rand($recentProducts, 4);
            for($i=0; $i < count((array)$recentProductsRandom); $i++){
                $recentProduct = $recentProducts[ $recentProductsRandom[$i] ];
            ?>
            <div class="col-12  product-rl">
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <h3 class="product_name">{{$recentProduct->products_name}}</h3>
                        <span class="tag text-center d-md-none">
                            @foreach($recentProduct->categories as $key=>$category)
                                {{$category->categories_name}}
                                @if(++$key === count((array)$recentProduct->categories)) @else, @endif
                            @endforeach
                        </span>
                    </div>
                    <div class="col-md-2 d-md-mobile-none">
                        @foreach($recentProduct->categories as $key=>$category)
                            {{$category->categories_name}}
                            @if(++$key === count((array)$recentProduct->categories)) @else, @endif
                        @endforeach
                    </div>
                    <div class="col-md-2">
                        ${{$recentProduct->products_price}}
                    </div>
                    <div class="col-md-2">
                        <div class="form-group Qty">                        
                            <div class="input-group">      
                                <span class="input-group-btn first qtyminus">
                                    <button class="btn btn-defualt" type="button">-</button>                        
                                </span>

                                <input type="text" readonly name="quantity" value="1" min="1" max="100000" class="form-control qty">

                                <span class="input-group-btn last qtyplus">
                                    <button class="btn btn-defualt" type="button">+</button>                        
                                </span>                     
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 product_addtocart">
                        <button type="button" class="btn btn-secondary btn-round cart" products_id="{{$recentProduct->products_id}}">@lang('website.Add to Cart')</button>
                    </div>
                </div>
            </div>
            <?php
            }
            ?>
        </div>
    </div>
</div>

</section>
<script>
//purchase order number
jQuery(document).on("keyup","#purchase_order_no",function(){ 
var theOrderNo=jQuery(this).val();
 console.log('theOrderNo is:'+theOrderNo);
jQuery("#order_no").val(theOrderNo);  
});
</script>
@endsection   


