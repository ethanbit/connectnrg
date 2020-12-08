@extends('layout')
@section('content')
<section class="site-content">
	<div class="container">
  		<div class="breadcum-area">
            <div class="breadcum-inner">
                <h3>@lang('website.Wishlist')</h3>
                <ol class="breadcrumb">                    
                    <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
            		<li class="breadcrumb-item active">@lang('website.Wishlist')</li>
                </ol>
            </div>
        </div>
    	<div class="shop-area">
        	<form method="get" enctype="multipart/form-data" id="load_wishlist_form" style="width:100%;">
            <input type="hidden"  name="search" value="{{ app('request')->input('search') }}">
            <input type="hidden"  name="category_id" value="{{ app('request')->input('category_id') }}">
            <input type="hidden"  name="load_wishlist" value="1">
            <input type="hidden"  name="type" value="wishlist">
        	<div class="row">
            	<div class="col-12 col-lg-3">
                    @include('common.sidebar_account')
                </div>
            	<div class="col-12 col-lg-9 new-customers">
                	
                	<div class="col-12">
                        <div class="heading">
                            <h2>@lang('website.Wishlist')</h2>
                            <hr>
                        </div>
                        <div class="row">
                        	@if($result['products']['success']==1)
                            <div class="toolbar mb-3 loaded_content" hidden>
                                <div class="form-inline">
                                    <div class="form-group col-12 col-md-4">
                                        <label class="col-12 col-lg-5 col-form-label">@lang('website.Display')</label>
                                        <div class="col-12 col-lg-7 btn-group">
                                            <a href="#" id="grid_wishlist" class="btn btn-default"> <i class="fa fa-th-large" aria-hidden="true"></i></a>
                                            <a href="#" id="list_wishlist" class="btn btn-default active"><i class="fa fa-list" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-group col-12 col-md-4"></div>
                                    <div class="form-group col-12 col-md-4">
                                        <label class="col-12 col-lg-4 col-form-label">@lang('website.Limit')</label>
                                        <select class="col-12 col-lg-3 form-control sortbywishlist" name="limit">
                                            <option value="15" @if(app('request')->input('limit')=='15') selected @endif">15</option>
                                            <option value="30" @if(app('request')->input('limit')=='30') selected @endif>30</option>
                                            <option value="45" @if(app('request')->input('limit')=='45') selected @endif>45</option>
                                        </select>
                                        <label class="col-12 col-lg-5 col-form-label">@lang('website.per page')</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="products products-list loaded_content" id="listing-wishlist">
                                @foreach($result['products']['product_data'] as $key=>$products)
                                    <div class="product">
                                        <article>
                                            <div class="container">
                                                <div class="row">
                                                    <div class="thumb col-md-2 col-sm-3">
                                                        <?php                           
                                                        $img = asset('').$products->products_image;
                                                        $imgArr = explode('.', $products->products_image);
                                                        $imgExt = strtolower(end($imgArr));
                                                        $extArr = array('png','jpg', 'gif');
                                                        if(!in_array($imgExt, $extArr)){
                                                            $img = URL::to('/').'/resources/assets/images/site_images/1556496390.logo.png';
                                                        }
                                                        ?>
                                                        <img class="img-fluid" src="{{ $img }}" alt="{{$products->products_name}}">
                                                    </div>
                                                    <div class="product_name col-md-5 col-sm-8" data-slug="{{$products->products_slug}}"><h3 class="product_title">
                                                        {{$products->products_name}}
                                                       </h3>  <span class="products_model">SKU: {{$products->products_model}}</span>
                                                    </div>
                                                    <div class="product_wishlist col-md-1 col-sm-1">
                                                        <div class="like-box">
                                                            <span products_id="{{$products->products_id}}" class="fa @if($products->isLiked==1) fa-star @else fa-star-o @endif is_liked">
                                                                <!-- <span class="badge badge-secondary">{{$products->products_liked}}</span> -->
                                                            </span>                                          
                                                        </div>
                                                    </div>
                                                    <div class="product_qty col-md-2 col-sm-6">
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
                                                    <div class="product_addtocart col-md-2 col-sm-6">
                                                        <button type="button" class="btn btn-secondary btn-round cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                            <div class="toolbar mt-3 loaded_content">
                            	<div class="form-inline">
                                    <div class="form-group  justify-content-start col-6">
                                    	
                                        <input id="record_limit" type="hidden" value="{{$result['limit']}}"> 
                                        <input id="total_record" type="hidden" value="{{$result['products']['total_record']}}">
                                       <label for="staticEmail" class="col-form-label">@lang('website.Showing')<span class="showing_record">{{$result['limit']}} </span> &nbsp; @lang('website.of')  &nbsp;<span class="showing_total_record">{{$result['products']['total_record']}}</span> &nbsp;@lang('website.results')</label>
                                        
                                    </div>
                                    <div class="form-group justify-content-end col-6">
                                        <input type="hidden" value="1" name="page_number" id="page_number">
                                        <?php
                                            if(!empty(app('request')->input('limit'))){
                                                $record = app('request')->input('limit');
                                            }else{
                                                $record = '15';
                                            }
                                        ?>
                                        <button class="btn btn-dark " type="button" id="load_wishlist" @if(count($result['products']['product_data']) < $record ) style="display:none" @endif >@lang('website.Load More')</button>

                                    </div>
                                </div>
                            </div>
                           
                           @endif
                           <div id="loaded_content_empty" @if($result['products']['success']==1) style="display: none;" @endif>
                           		You don’t have any products in your Favourites List
                           </div>
                        </div>
                    </div>  
                </div>
        	</div>
            </form>
		</div>
    </div>
</section>
@endsection 