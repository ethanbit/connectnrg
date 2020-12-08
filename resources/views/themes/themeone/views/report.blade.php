@extends('layout')
@section('content')
<section class="site-content">
    <div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.My Orders')</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                     <li class="breadcrumb-item active">@lang('website.My Orders')</li>
                </ol>
            </div>
        </div>
        
        <div class="my-order-area">
        	
        	
        	<div class="row">
            	{{-- <div class="col-12 col-lg-3">
								@include('common.sidebar_account')
							</div> --}}
            	<div class="col-12 col-lg-12 new-customers p-md-0">
                	
                	<div class="col-12">
                        <div class="heading">
                            <h2>Report</h2>
                            <hr>
                        </div>
                        
                        <div class="report">
        
                        @if(session()->has('message'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                 {{ session()->get('message') }}
                            </div>
                            
                        @endif
                        
                        <div class="table-responsive">
													<?php //echo "<!-- Ducnb <pre>"; print_r($result['data']); echo "</pre>".__FILE__.": ".__LINE__."--> "; ?>
													<table class="table">
														<thead>
															<tr>
																<th>Order date</th>
																<th>Delivery Address</th>
																<th>Suburb</th>
																<th>State</th>
																<th>Product Code</th>
																<th>Product description</th>
																<th>Category</th>
																<th>Certification</th>
																<th>Cost</th>
																<th>Quantity</th>
																<th>User email</th>
															</tr>
														</thead>
														<tbody>
															<?php 
															foreach($result['data'] as $data)	{
																$orders_id = $data->orders_id;
																$date_purchased = date('d/m/Y', strtotime($data->date_purchased));
																$delivery_suburb = $data->delivery_suburb;
																$delivery_state = $data->delivery_state;
																$delivery_street_address = $data->delivery_street_address;
																$email = $data->email;
																foreach($data->products as $product){
															?>
															<tr>
																<td><?php echo $date_purchased ?></td>
																<td><?php echo $delivery_street_address ?></td>
																<td><?php echo $delivery_suburb ?></td>
																<td><?php echo $delivery_state ?></td>
																<td><?php echo $product->products_model ?></td>
																<td><?php echo $product->description ?></td>
																<td><?php echo $product->categories_name ?></td>
																<td><?php echo $product->certification ?></td>
																<td><?php echo $product->final_price ?></td>
																<td><?php echo $product->products_quantity ?></td>
																<td><?php echo $email ?></td>
															</tr>
																<?php } ?>
															<?php } ?>
														</tbody>
													</table>
                        </div>
                    </div>
                </div>	
            </div>    
        </div>
	</div>
</section>	
@endsection 	


