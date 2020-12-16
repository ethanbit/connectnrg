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
        
        <div class="my-order-area report_data">
        	
        	
        	<div class="row">
            	{{-- <div class="col-12 col-lg-3">
								@include('common.sidebar_account')
							</div> --}}
            	<div class="col-12 col-lg-12 new-customers p-md-0">
                	
                	<div class="col-12">
                        <div class="heading">
														<div>
															<h2>Report</h2>
															<a href="exportreport" class="btn btn-secondary export"><i class="fa fa-cloud-download" aria-hidden="true"></i> Export</a>
														</div>
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
																<th>
																	Order date
																	<input type="text" id="date" value="" />
																</th>
																<th width="150px">
																	Delivery Address
																	<input type="text" id="address" value="" />
																</th>
																<th>
																	Suburb
																	<input type="text" id="suburb" value="" />
																</th>
																<th width="70px">
																	State
																	<input type="text" id="state" value="" />
																</th>
																<th width="100px">
																	Product Code
																	<input type="text" id="model" value="" />
																</th>
																<th width="150px">
																	Product description
																	<input type="text" id="description" value="" />
																</th>
																<th>
																	Category
																	<input type="text" id="categories_name" value="" />
																</th>
																<th>
																	Certification
																	<input type="text" id="certification" value="" />
																</th>
																<th width="70px">
																	Cost
																	<input type="text" id="final_price" value="" />
																</th>
																<th width="50px">
																	Qty
																	<input type="text" id="products_quantity" value="" />
																</th>
																<th>
																	User email
																	<input type="text" id="email" value="" />
																</th>
															</tr>
														</thead>
														<tbody>
															<?php 															
															foreach($result['data'] as $data)	{
																$orders_id = $data->orders_id;
																$date_purchased = date('d/m/Y', strtotime($data->date_purchased));
																$delivery_suburb = isset($data->delivery_suburb) ? $data->delivery_suburb : '';
																$delivery_state = isset($data->delivery_state) ? $data->delivery_state : '';
																$delivery_street_address = isset($data->delivery_street_address) ? $data->delivery_street_address : '';
																$email = isset($data->email) ? $data->email : '';
																if(empty($data->products)){
																	continue;
																}
																foreach($data->products as $product){
																	if(empty($product->products_model) and $product->final_price == 0){
																		continue;
																	}
															?>
															<tr>
																<td class='date'>
																	<?php echo $date_purchased ?>
																</td>
																<td class='address' width="150px">
																	<?php echo $delivery_street_address ?>
																</td>
																<td class='suburb'>
																	<?php echo $delivery_suburb ?>
																</td>
																<td class='state' width="70px">
																	<?php echo $delivery_state ?>
																</td>
																<td class='model' width="100px">
																	<?php echo $product->products_model ?>
																</td>
																<td class='description' width="150px" title="<?php echo strip_tags($product->description) ?>">
																	<p>
																		<?php echo strip_tags($product->description) ?>
																	</p>
																</td>
																<td class='categories_name'>
																	<?php echo $product->categories_name ?>
																</td>
																<td class='certification'>
																	<?php echo $product->certification ?>
																</td>
																<td class='final_price' width="70px">
																	<?php echo $product->final_price ?>
																</td>
																<td class='products_quantity' width="50px">
																	<?php echo $product->products_quantity ?>
																</td>
																<td class='email'>
																	<?php echo $email ?>
																</td>
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


