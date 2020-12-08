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
                <div class="col-12">
                    <div class="col-12 spaceright-0">
                        <div class="row">
                            <div class="col-12 thankyou_msg">
                               <!-- <p>Your order has been received and is now <b>{{$result['orders'][0]->orders_status}}</b></p>
                                <p>Your order details are show below for your reference.</p> -->
								
                                <center>
                                    <p class="icon_success"><i class="fa fa-check-circle-o" aria-hidden="true"></i></p>
                                    <h1>Your order has been placed.</h1>
                                    <h3>Order Number: {{$result['orders'][0]->orders_id}}</h3>
									<!-- <p>Sed venenatis felis pulvinar metus ultricies, eu lobortis risus suscipit.</p> -->
                                    <a href="{{ URL::to('/orders') }}" class="btn btn-lg btn-secondary">My Account</a>
                                </center>
                            </div>
                            <div class="col-12 order_items d-none">
                                <div class="table-responsive">
                                    <table class="table" style="margin-bottom:0;">
                                        <thead>
                                            <tr>
                                                <th align="left">Products</th>
                                                <th align="right">@lang('website.Qty')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(count($result['orders']) > 0)
                                                @foreach( $result['orders'][0]->products as $products)
                                                <tr>
                                                    <td align="left" class="item">
                                                        <div class="cart-thumb">
                                                            <?php 
                                                            $imgArr = explode('.', $products->image);
                                                            $imgEXT = strtolower(end($imgArr));
                                                            if(in_array($imgEXT, array('jpg', 'png', 'gif'))){
                                                                $img = asset('').$products->image;
                                                            }else{
                                                                $img = URL::to('').'/resources/assets/images/site_images/1556496390.logo.png';
                                                            }
                                                            ?>
                                                            <img class="img-fluid" src="{{ $img }}" alt="{{$products->products_name}}" alt="">
                                                        </div>
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
                                                @endforeach
                                            @endif              
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-12 delivery_date d-none">
                                Delivery Date: <b>{{$result['orders'][0]->delivery_date}}</b>
                            </div>

                            <div class="col-12 customer_detail d-none">
                                <?php //echo "<pre>"; print_r($result['orders']); echo "</pre>".__FILE__.":".__LINE__; ?>
                                <p class="comment">Note: 
                                    @if(!empty($result['orders'][0]->statusess[0]->comments))
                                        {{$result['orders'][0]->statusess[0]->comments}}
                                    @endif
                                </p>
                                <p class="email">{{$result['orders'][0]->email}}</p>
                                <p class="phone">{{$result['orders'][0]->customers_telephone}}</p>
                            </div>

                            <div class="col-12 col-md-12 col-lg-12 card-box d-none">
                                <div class="card">
                                  <div class="card-header">                
                                    @lang('website.Shipping Detail')
                                  </div>
                                  <div class="card-body">
                                    <div class="card-text">
                                        <p><strong>{{$result['orders'][0]->delivery_name}}</strong></p>
										<p>{{$result['orders'][0]->delivery_company}}</p>
                                        <p>{{$result['orders'][0]->delivery_street_address}}, {{$result['orders'][0]->delivery_city}} {{$result['orders'][0]->delivery_suburb}} {{$result['orders'][0]->delivery_state}} {{$result['orders'][0]->delivery_postcode}}</p>
										<p>{{$result['orders'][0]->delivery_phone}}</p>
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
                                        <p> <strong>{{$result['orders'][0]->billing_name}}</strong></p>
                                        <p>{{$result['orders'][0]->billing_street_address}}, {{$result['orders'][0]->billing_city}}, {{$result['orders'][0]->delivery_suburb}} {{$result['orders'][0]->billing_state}} {{$result['orders'][0]->billing_postcode}} </p>
                                        <p>{{$result['orders'][0]->billing_company}}g></p>
                                        <p>{{$result['orders'][0]->billing_phone}}</p>
											
                                    </div>
                                  </div>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div>
        
     <!--   <div class="related_product">
            <h2>Products <strong>you may like</strong></h2>
            <div class="row">
                <?php 
                $recentProducts = $result['commonContent']['recentProducts']['product_data'];
                $recentProductsRandom = array_rand($recentProducts, 4);
                for($i=0; $i < count($recentProductsRandom); $i++){
                    $recentProduct = $recentProducts[ $recentProductsRandom[$i] ];
                ?>
                <div class="col-12  product-rl">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <h3 class="product_name">{{$recentProduct->products_name}}</h3>
                            <span class="tag text-center d-md-none">
                                @foreach($recentProduct->categories as $key=>$category)
                                    {{$category->categories_name}}
                                    @if(++$key === count($recentProduct->categories)) @else, @endif
                                @endforeach
                            </span>
                        </div>
                        <div class="col-md-2 d-md-mobile-none">
                            @foreach($recentProduct->categories as $key=>$category)
                                {{$category->categories_name}}
                                @if(++$key === count($recentProduct->categories)) @else, @endif
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
        </div> -->

        <div class="banner_cart"> 
            @if(count($result['commonContent']['homeBanners'])>0)
                @foreach($result['commonContent']['homeBanners'] as $homeBanners)
                    @if($homeBanners->type == 1)
                    <a title="Banner Image" href="{{ $homeBanners->banners_url}}">
                        <img class="img-fluid" src="{{asset('').$homeBanners->banners_image}}" alt="" style="width:100%;">
                    </a>
                    @endif
                @endforeach
            @endif
        </div>
	</div>
 </section>		
@endsection 	


