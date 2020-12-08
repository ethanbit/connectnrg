@extends('layout')
@section('content')
<section class="site-content">
    <div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.View Order')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                    <li class="breadcrumb-item"><a href="{{ URL::to('/orders')}}">@lang('website.My Orders')</a></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0)">@lang('website.View Order')</a></li>
                </ol>
            </div>
        </div>
        <div class="orders-detail-area">
            <div class="row">
            	<div class="col-12 col-lg-3 spaceright-0">
                	@include('common.sidebar_account')
                </div>
                <div class="col-12 col-lg-9">
                    <div class="col-12 spaceright-0">
                        <div class="heading">
                            <h2>@lang('website.Order information')</h2>
                            <hr>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6 col-lg-6 card-box">
                                <div class="card">
                                    <div class="card-header">
                                        @lang('website.orderID')&nbsp;{{$result['orders'][0]->orders_id}}

                                        <?php 
                                        //echo "<pre>"; print_r($result['orders']); echo "</pre>".__FILE__.":".__LINE__;
                                        $orderID = '';
                                        foreach( $result['orders'][0]->products as $products){
                                            if($orderID == ''){
                                                $orderID = $products->products_id;
                                            }else{
                                                $orderID .= ','.$products->products_id;
                                            }
                                            
                                        }
                                        ?>
                                        <button type="button" class="btn btn-secondary btn-round reorder" products_id="{{ $orderID }}" style="float: right;">Reorder</button>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-text">
                                            {{-- <p>
                                                <strong>@lang('website.orderStatus')</strong>
                                                @if($result['orders'][0]->orders_status_id == '1')
                                                    <span class="badge badge-primary">{{$result['orders'][0]->orders_status}}</span>
                                                
                                                @elseif($result['orders'][0]->orders_status_id == '2')
                                                    <span class="badge badge-success">{{$result['orders'][0]->orders_status}}</span>
                                                @elseif($result['orders'][0]->orders_status_id == '3')
                                                    <span class="badge badge-danger">{{$result['orders'][0]->orders_status}}</span>
                                                @else
                                                	<span class="badge badge-warning">{{$result['orders'][0]->orders_status}}</span>   
                                                @endif
                                            </p> --}}
                                            <p><strong>Ordered Date</strong>{{ date('d/m/Y', strtotime($result['orders'][0]->date_purchased))}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 card-box">
                                <div class="card">
                                  <div class="card-header">                
                                    @lang('website.Shipping Detail')
                                  </div>
                                  <div class="card-body">
                                    <div class="card-text">
                                        <p><strong>{{$result['orders'][0]->delivery_name}}</strong></p>
                                        <p>{{$result['orders'][0]->delivery_street_address}} {{$result['orders'][0]->delivery_city}}, {{$result['orders'][0]->delivery_state}},
                                        {{$result['orders'][0]->delivery_postcode}}</p>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6 col-lg-6 card-box d-none">
                                <div class="card">
                                  <div class="card-header">                
                                    @lang('website.Billing Detail')
                                  </div>
                                  <div class="card-body">
                                    <div class="card-text">
                                        <p>
                                            <strong>{{$result['orders'][0]->billing_name}}</strong></p>
                                            <p>{{$result['orders'][0]->billing_street_address}}, {{$result['orders'][0]->billing_city}}, {{$result['orders'][0]->billing_state}},
                                            {{$result['orders'][0]->billing_postcode}}
                                        </p>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-12 col-lg-12 card-box">
                                <div class="card">
                                  <div class="card-header">
                                    Comment
                                  </div>
                                  <div class="card-body">
                                    <div class="card-text">
                                    {{-- <p><strong>Delivery Date: </strong>{{$result['orders'][0]->delivery_date}}</p> --}}
                                    <?php 
                                    //echo '<pre>'; print_r($result['orders']); echo '</pre>'.__FILE__.':'.__LINE__;
                                    ?>
                                    <p><strong>Delivery Notes: </strong>{{$result['orders'][0]->delivery_note}}</p>
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-start">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table" style="margin-bottom:0;">
                                        <thead>
                                            <tr>
                                                <th align="left">@lang('website.items')</th>
                                                <th align="right">@lang('website.Qty')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $price = 0;
                                            ?>
                                            @if(count($result['orders']) > 0)
                                                @foreach( $result['orders'][0]->products as $products)
                                                <?php 
                                                    $price+= $products->final_price;					
                                                ?>
                                                <tr>
                                                    <td align="left" class="item">
                                                       <!-- <div class="cart-thumb">
                                                            <?php 
                                                            $imgArr = explode('.', $products->image);
                                                            $imgEXT = strtolower(end($imgArr));
                                                            if(in_array($imgEXT, array('jpg', 'png', 'gif'))){
                                                                $img = asset('').$products->image;
                                                            }else{
                                                                $img = URL::to('').'/resources/assets/images/site_images/1556496390.logo.png';
                                                            }
                                                            ?>
                                                            <img class="img-fluid" src="{{$img}}" alt="{{$products->products_name}}" alt="">
                                                        </div> -->
                                                        <div class="cart-product-detail">
                                                            <div class="title">{{$products->products_name}} {{$products->model}}</div>
                                                            @if(count($products->attributes) >0)
                                                                <ul>
                                                                    @foreach($products->attributes as $attributes)
                                                                        <li>{{$attributes->products_options}}<span>{{$attributes->products_options_values}}</span></li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td align="right" class="Qty"><span>{{$products->products_quantity}}</span></td>
                                                </tr>    
                                                @endforeach												<tr>												<td align="middle" style="border-top:0px;"><strong>SUB TOTAL</strong></td>												<td align="right" style="border-top:0px;"><strong>$<?php  echo number_format($result['orders'][0]->order_price,2);?></strong></td>												</tr>												<tr>												<td align="middle" style="border-top:0px;"><strong>GST</strong></td>												<td align="right" style="border-top:0px;"><strong>$<?php  echo number_format($result['orders'][0]->total_tax,2);?></strong></td>												</tr>												<tr>												<td align="middle" style="border-top:0px;"><strong>TOTAL</strong></td>												<td align="right" style="border-top:0px;"><strong>$<?php  echo number_format($result['orders'][0]->order_price+$result['orders'][0]->total_tax,2);?></strong></td>												</tr>
                                            @endif				
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-xs-12 col-sm-12">
                                @if(count($result['orders'][0]->statusess)>0)
                                    <div class="card">
                                        <div class="card-header">
                                        	@lang('website.Comments')
                                        </div>
                                        <div class="card-body">
                                        @foreach($result['orders'][0]->statusess as $key=>$statusess)
                                            @if(!empty($statusess->comments))
                                                @if(++$key==1)
                                                	<h6>@lang('website.Order Comments'): {{ date('d/m/Y', strtotime($statusess->date_added))}}</h6>
                                                   
                                                @else
                                                	<h6>@lang('website.Admin Comments'): {{ date('d/m/Y', strtotime($statusess->date_added))}}</h6>
                                                @endif
                                                <p class="card-text">{{$statusess->comments}}</p>  

                                            @endif
                                        @endforeach
                                        </div>
                                    </div>
                                @endif 
                            </div>
                        </div> -->
					</div>
				</div>
			</div>
		</div>
	</div>
 </section>		
@endsection 	


