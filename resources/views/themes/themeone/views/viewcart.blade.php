@extends('layout')
@section('content')
<section class="site-content">
    <div class="container">
        <div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Shopping cart')</h3>
                <ol class="breadcrumb">
                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                </ol>
            </div>
        </div>
        <div class="cart-area">
		<h2>Cart</h2>
            <div class="row">
				
             	<?php 
					$price = 0;
				?>
				@if(count($result['cart']) > 0)
                     
                <div class="col-lg-9 col-sm-12 cart-left">
                    <div class="row">
                         @if(session()->has('message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                 {{ session()->get('message') }}
                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>               
                        @endif
                    
                        <form method='POST' id="update_cart_form" action='{{ URL::to('/updateCart')}}' >
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th align="center"></th>
                                            <th align="left">@lang('website.product')</th>
                                            {{-- <th align="center">@lang('website.Price')</th>  --}}
                                            <th align="center">@lang('website.Qty')</th>
                                            {{-- <th align="center">@lang('website.Total')</th> --}}
                                        </tr>
                                    </thead>
                                 
                                    @foreach( $result['cart'] as $products)
                                    <?php 
                                    $price+= $products->final_price * $products->customers_basket_quantity;		
                                    ?>
                                     
                                    <tbody>
                                        <tr>
                                            <td align="center" class="item">
                                                <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}">
                                                    <?php 
                                                    $img = URL::to('').'/resources/assets/images/site_images/remove-icon.png';
                                                    ?>
                                                    <img class="img-fluid" src="{{ $img }}" alt="">
                                                    <span style="display: block;">remove</span>
                                                </a>
                                            </td>
                                            <td align="left" class="item">
                                                <input type="hidden" name="cart[]" value="{{$products->customers_basket_id}}">
                                                <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="cart-thumb">
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
                                                </a>
                                                <div class="cart-product-detail">
                                                    <a href="{{ URL::to('/product-detail/'.$products->products_slug)}}" class="title">
                                                        
                                                    </a>
                                                    {{$products->products_name}} {{$products->model}}
                                                    @if(count($products->attributes) >0)
                                                        <ul>
                                                            @foreach($products->attributes as $attributes)
                                                                <li>{{$attributes->attribute_name}}<span>{{$attributes->attribute_value}}</span></li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </td>
                                        
                                            {{-- <td align="center" class="price"><span>{{$web_setting[19]->value}}{{$products->final_price+0}}</span></td>  --}}
                                            <td align="center" class="Qty">
                                                <div class="input-group">
                                                  <span class="input-group-btn qtyminuscart">
                                                    	<i class="fa fa-minus" aria-hidden="true"></i>
                                                  </span>
                                                  <input customer_basket_id="<?php echo $products->customers_basket_id;?>" price_of_one="<?php echo $products->final_price; ?>" name="quantity[]" type="text" value="{{$products->customers_basket_quantity}}" class="form-control qty updateQty" min="1" max="10000000">                                                  
                                                  <span class="input-group-btn qtypluscart">
                                                  		<i class="fa fa-plus" aria-hidden="true"></i>
                                                  </span>
                                                </div>
                                            </td>
                                        
                                            {{-- <td align="center" class="subtotal">
                                                <span class="cart_price_{{$products->customers_basket_id}}">{{$web_setting[19]->value}}{{$products->final_price * $products->customers_basket_quantity}}</span>
                                            </td>  --}}
                                        
                                            {{-- <td align="center" class="subtotal">
                                                <a href="{{ URL::to('/editcart?id='.$products->customers_basket_id)}}" class="btn btn-sm btn-secondary">@lang('website.Edit')</a>
                                                <a href="{{ URL::to('/deleteCart?id='.$products->customers_basket_id)}}" class="btn btn-sm btn-secondary">@lang('website.Remove Item')</a>
                                            </td>  --}}
                                        </tr> 
                                    </tbody>            
                                    @endforeach
                                </table>
                            </div>
                        </form>
                    </div>
					
					
					
                    <div class="row">
                        <div class="col-9 col-lg-9 col-9 col-md-9 col-sm-6 checkout-left-btn" style="margin-top: 10px;">                            <a href="{{ URL::to('/shop-online')}}" class="btn btn-sm btn-secondary">Continue Shopping</a>
                        </div>
                        <div class="col-3 col-lg-3 col-3 col-md-3 col-sm-6 checkout-right-btn" style="text-align: right; margin-top: 10px;">
                            <a href="{{ URL::to('/checkout')}}" class="btn btn-sm btn-secondary checkout_link" >@lang('website.proceedToCheckout')</a>
                        </div>
                    </div>
                </div>
				<!-- right side-->
					<div class="col-12 col-lg-3 col-3 checkout-right ">    
            <div class="checkout-right-in ">    
                <h3><strong>Order</strong> total</h3>
                <div class="order-review">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                </thead>
                                <tbody>
								<tr>	
								<td align="middle" style="border-top:0px;"><strong>SUB TOTAL</strong></td>
								<td align="right" style="border-top:0px;"><strong>$<span class="theSubTotal"><?php  echo number_format($price,2);?></span></strong></td>		
								</tr>	
								<?php 
								$taxRateIs=$result['tax_rate_setting'];
								$GST=$price*($taxRateIs/100);
								$TOTAL=$price+$GST;
								?>
								<tr>												
								<td align="middle" style="border-top:0px;"><strong>GST</strong></td>
								<td align="right" style="border-top:0px;"><strong>$<span the_tax_rate="<?php echo $taxRateIs; ?>" class="theGST"><?php  echo number_format($GST,2);?></span></strong></td>											
								</tr>
								
								<tr>	
								<td align="middle" style="border-top:0px;"><strong>TOTAL</strong></td>
								<td align="right" style="border-top:0px;"><strong>$<span class="theTotal"><?php  echo number_format($TOTAL,2);?></span></strong></td>
								</tr>
								
                                </tbody>  
                            </table>
                        </div>
                </div>
                </div>
					<!-- end right side -->
                    
                <div class="row button cart-none">
                    <div class="col-12 col-sm-6">                
                        <div class="row">
                        	<a href="{{ URL::to('/shop')}}" class="btn btn-dark">@lang('website.Back To Shopping')</a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                    	<div class="row justify-content-end">
                        	<button class="btn btn-dark" id="update_cart">@lang('website.Update Cart')</button>
                        </div>
                    </div>
                </div>
            
                <div class="col-12 col-lg-3 cart-right d-none">
                	<div class="order-summary-outer">
                    	<div class="order-summary">
                            <div class="table-responsive">
                                <table class="table">
                                	<thead>
                                    	<tr>
                                        	<th align="left" colspan="2">@lang('website.Order Summary') </th>
                                        </tr>
                                    </thead>
                                  	<tbody>
                                        <tr>
                                            <td align="left"><span>@lang('website.SubTotal')</span></td>
                                            <td align="right" id="subtotal">{{$web_setting[19]->value}}{{$price+0}}</td>
                                        </tr>
                                        

                                        <tr>
                                            <td align="left"><span>@lang('website.Discount(Coupon)')</span></td>
                                            <td align="right" id="discount">{{$web_setting[19]->value}}{{number_format((float)session('coupon_discount'), 2, '.', '')+0}}</td>
                                        </tr>
                                        <tr>
                                            <td class="last" align="left"><span>@lang('website.Total')</span></td>
                                            <td class="last" align="right" id="total_price">{{$web_setting[19]->value}}{{$price+0-number_format((float)session('coupon_discount'), 2, '.', '')}}</td>
                                        </tr>
                                	</tbody>
                                </table>
                            </div>
                        </div>                      
                        <div class="coupons">
                        	<!-- applied copuns -->
                            @if(count(session('coupon')) > 0 and !empty(session('coupon')))
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
                    </div>
                </div>
                
                        
                {{-- <div class="col-9 col-lg-9 col-sm-9 buttons process-checkout text-right">
                    <a href="{{ URL::to('/checkout')}}" class="btn btn-sm btn-secondary" >@lang('website.proceedToCheckout')</a>
                </div> --}}
                @else
                
                <div class="col-xs-12 col-sm-12 page-empty">
                	<span class="fa fa-cart-arrow-down"></span>
                	<div class="page-empty-content">
                    	{{-- <span>@lang('website.cartEmptyText')</span> --}}
                        <span>You have no items in your shopping cart.<br>Click <a href="{{ URL::to('/shop-online')}}">here</a> to continue shopping.</span>
                    </div>
                </div>
               @endif

			<div class="related_product d-none">
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
                            <div class="product_price col-md-2 col-sm-2">
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
		</div>


 </section>
 
		
@endsection 	


