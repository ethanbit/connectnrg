@extends('layout')
@section('content')
<style>
.category_name ul.navbar-nav.dhcategories li.nav-item > div, .category_name ul.navbar-nav.dhcategories li.nav-item > div a {
   height: 100%;
   display: flex;
   align-items: center;
   text-align: center !important;
   flex-flow: wrap;
}
.category_name ul.navbar-nav.dhcategories li.nav-item > div a h3 {
   width: 100%;
   padding-left: 10px !important;
   padding-right: 10px !important;
}
</style>
<section class="site-content">
	<div class="container">
		<div class="shop-area">
			<form method="get" enctype="multipart/form-data" id="load_products_form">
               @if(!empty(app('request')->input('search')))
                <input type="hidden"  name="search" value="{{ app('request')->input('search') }}">
               @endif
               @if(!empty(app('request')->input('category')))
                <input type="hidden"  name="category" value="@if(app('request')->input('category')!='all'){{ app('request')->input('category') }} @endif">
               @endif
               @if(!empty(app('request')->input('manufacturer')))
                <input type="hidden"  name="manufacturer" value="@if(app('request')->input('manufacturer')!='all'){{ app('request')->input('manufacturer') }} @endif">
               @endif
                <input type="hidden"  name="load_products" value="1">                
                <div class="row">                
                    <div class="col-12 col-lg-3 spaceright-0_1 sidebar_shop">
                        <div class="sidebar_categries">
                            <h3>product categories</h3>
                            <ul class="flex-column">
                                <?php //echo "<!-- Ducnb <pre>"; print_r($result['commonContent']['categories']); echo "</pre>".__FILE__.":".__LINE__."-->"; ?>
								<?php 
								$result['commonContent']['categories']=array_reverse($result['commonContent']['categories']);
								?>
								@foreach($result['commonContent']['categories'] as $categories_data)
                                <?php 
								$miscellaneous_total_products=0;
                                if($categories_data->slug != 'miscellaneous'){
                                ?>
                                <li class="nav-item dropdown">
                                    <a href="{{ URL::to('/shop')}}?category={{$categories_data->slug}}" class="nav-link dropdown-toggle">
                                        {{$categories_data->name}}
                                        <span class="cat_totalproduct">{{ $categories_data->total_products }}</span>
                                    </a>
                                </li>
                                <?php 
                                }else{
                                    $miscellaneous_total_products = $categories_data->total_products;
                                } 
                                ?>
                            @endforeach
                                <li class="nav-item dropdown d-none">
                                    <a href="{{ URL::to('/shop')}}?category=miscellaneous" class="nav-link dropdown-toggle">
                                        miscellaneous
                                        <span class="cat_totalproduct">{{ $miscellaneous_total_products }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="sidebar_banner">
                            @if(count($result['commonContent']['homeBanners'])>0)
                                @foreach($result['commonContent']['homeBanners'] as $homeBanners)
                                    @if($homeBanners->type == 2)
                                        <div class="banner-image">
                                            <a title="Banner Image" href="{{ $homeBanners->banners_url}}"><img class="img-fluid" src="{{asset('').$homeBanners->banners_image}}" alt="Banner Image"></a>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    
       
                    <div class="col-12 col-lg-9">
                        <div class="col-12">
                        	<div class="row">                            
                                <div class="breadcum-area">
                                    <div class="breadcum-inner">
                                        <ol class="breadcrumb">                    
                                            <li class="breadcrumb-item"><a href="{{ URL::to('/')}}">@lang('website.Home')</a></li>
                                            
                                            @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                                                <li class="breadcrumb-item"><a href="{{ URL::to('/shop-online')}}">@lang('website.Shop')</a></li>
                                                <li class="breadcrumb-item"><a href="{{ URL::to('/shop?category='.$result['category_slug'])}}">{{$result['category_name']}}</a></li>
                                                <li class="breadcrumb-item active">{{$result['sub_category_name']}}</li>
                                            @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                                                <li class="breadcrumb-item"><a href="{{ URL::to('/shop')}}">@lang('website.Shop')</a></li>
                                                <li class="breadcrumb-item active">{{$result['category_name']}}</li>
                                            @else                    
                                                <li class="breadcrumb-item active">@lang('website.Shop')</li>
                                            @endif
                                        </ol>
                                    </div>
                                </div>

                            @if(!empty(app('request')->input('search')))
                                <div class="search-result">
                                    <h4>@lang('website.Search result for') '{{app('request')->input('search')}}' @if($result['products']['total_record']>0) {{$result['products']['total_record']}} @else 0 @endif @lang('website.item found') <h4>
                                </div>
                            @endif
                                
                                <div class="container top_shop_banner">
                                    <div class="row">
                                    <?php 
                                    //echo "<!-- Ducnb <pre>"; print_r($result['commonContent']); echo "</pre>".__FILE__.":".__LINE__."-->"; 
                                    ?>
                                    @if(count($result['commonContent']['slides'])>0)
                                        @foreach($result['commonContent']['slides'] as $slides)
                                            @if($slides->type == 'category')
                                                <div class="shop_banner col-12">
                                                    <a title="" href="{{ URL::to('/shop?category='.$slides->url) }}">
                                                        <img class="img-fluid" src="{{asset('').$slides->image}}" alt="">
                                                    </a>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    </div>
                                </div>

                             @if($result['products']['total_record']>0)
                                <div class="container">
                                    <div class="row mb-3 align-items-center">
                                        
                                        <div class="col-12 col-lg-4 col-sm-12 spaceright-0 spaceleft-0 offset-md-2 offset-sm-0 order-2" style="padding:0;margin:12px 0 0;">
                                            <div class="form-inline">
                                               <!--  <div class="form-group col-12 col-md-4">
                                                    <label class="col-12 col-lg-5 col-form-label">@lang('website.Display')</label>
                                                    <div class="col-12 col-lg-7 btn-group">
                                                        <a href="javascript:void(0);" id="grid" class="btn btn-default active"> <i class="fa fa-th-large" aria-hidden="true"></i> </a>
                                                        <a href="javascript:void(0);" id="list" class="btn btn-default"> <i class="fa fa-list" aria-hidden="true"></i> </a>
                                                    </div>
                                                </div>  -->
                                                <!-- <div class="form-group col-12 col-lg-4 col-sm-12 center shop_sort h-auto">
                                                    <label class="col-12 col-lg-3 col-form-label spaceright-0">@lang('website.Sort')</label> 
                                                    <div class="col-lg-9">
                                                        <button type="submit" class="btn price <?php if(app('request')->input('type') == 'hightolow') echo 'hightolow active'; elseif(app('request')->input('type') == 'lowtohigh') echo 'lowtohigh active'; ?>" name="type" value="<?php if(app('request')->input('type') == 'hightolow') echo 'lowtohigh'; else echo 'hightolow'; ?>">
                                                            Price 
                                                            @if(app('request')->input('type') == 'hightolow') 
                                                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i> 
                                                            @elseif(app('request')->input('type') == 'lowtohigh') 
                                                            <i class="fa fa-long-arrow-left" aria-hidden="true"></i> 
                                                            @else 
                                                            <i class="fa fa-exchange" aria-hidden="true"></i>
                                                            @endif
                                                            
                                                        </button>
                                                        <button type="submit" class="btn <?php if(app('request')->input('type') == 'atoz') echo 'atoz active'; elseif(app('request')->input('type') == 'ztoa') echo 'ztoa active'; ?>" name="type" value="<?php if(app('request')->input('type') == 'atoz') echo 'ztoa'; else echo 'atoz'; ?>">
                                                            Name 
                                                            @if(app('request')->input('type') == 'atoz') 
                                                            <i class="fa fa-long-arrow-left" aria-hidden="true"></i> 
                                                            @elseif(app('request')->input('type') == 'ztoa') 
                                                            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
                                                            @else 
                                                            <i class="fa fa-exchange" aria-hidden="true"></i> 
                                                            @endif
                                                            
                                                        </button>
                                                        <button type="submit" class="btn date @if(app('request')->input('type') == 'desc') active @endif" name="type" value="desc">
                                                            Date 
                                                            <i class="fa fa-exchange" aria-hidden="true"></i>
                                                        </button>

                                                        {{-- <select class="col-12 col-lg-12 form-control sortby" name="type">
                                                            <option value="desc" @if(app('request')->input('type')=='desc') selected @endif>@lang('website.Newest')</option>
                                                            <option value="atoz" @if(app('request')->input('type')=='atoz') selected @endif>@lang('website.A - Z')</option>
                                                            <option value="ztoa" @if(app('request')->input('type')=='ztoa') selected @endif>@lang('website.Z - A')</option>
                                                            <option value="hightolow" @if(app('request')->input('type')=='hightolow') selected @endif>@lang('website.Price: High To Low')</option>
                                                            <option value="lowtohigh" @if(app('request')->input('type')=='lowtohigh') selected @endif>@lang('website.Price: Low To High')</option>
                                                            <option value="topseller" @if(app('request')->input('type')=='topseller') selected @endif>@lang('website.Top Seller')</option> 
                                                            <option value="special" @if(app('request')->input('type')=='special') selected @endif>@lang('website.Special Products')</option>
                                                            <option value="mostliked" @if(app('request')->input('type')=='mostliked') selected @endif>@lang('website.Most Liked')</option>
                                                        </select> --}}
                                                    </div>
                                                </div> -->
                                                 <div class="col-12 col-lg-12 col-sm-12 text-right">
                                                    <span>
                                                        @if($result['products']['total_record']>0) 
                                                        {{$result['products']['total_record']}} 
                                                        @else 0 @endif 
                                                        @lang('website.item found') 
                                                    <span>
                                                    {{-- <label class="col-12 col-lg-4 col-form-label">@lang('website.Limit')</label>
                                                    <select class="col-12 col-lg-3 form-control sortby" name="limit">
                                                        <option value="15" @if(app('request')->input('limit')=='15') selected @endif>15</option>
                                                        <option value="30" @if(app('request')->input('limit')=='30') selected @endif>30</option>
                                                        <option value="60" @if(app('request')->input('limit')=='60') selected @endif>60</option>
                                                    </select>
                                                    <label class="col-12 col-lg-5 col-form-label">@lang('website.per page')</label> --}}
                                                </div> 
                                            </div>
                                        </div>
										<div class="col-12 col-lg-8 category_name order-1">
                                            <h1 style="">
                                            @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                                                {{$result['sub_category_name']}}
                                            @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                                                {{$result['category_name']}}
                                            @else                    
                                                <strong>All</strong> Shop
                                            @endif
                                            </h1>
										</div>
										
                                    </div>
									<?php if($_GET['category']!="all"){?>
									<div class="row">
									 <div class="col-12 col-lg-12 category_name">

											<?php
							

											if(count($result['sub_category_data']) >0){?>
											 <ul class="navbar-nav flex-column dhcategories p-2">
						   <?php  foreach($result['sub_category_data'] as $key=> $sub_categories_data){ ?>
							<?php if($result['sub_category_data_count'][$key] >0){?>
						      <li class="nav-item col-lg-2 col-md-3 col-sm-4">
						        	<div>
						        		<a href="{{ URL::to('/shop')}}?category=<?php echo $sub_categories_data->categories_slug;?>" class="nav-link">
								          	<h3><?php echo $sub_categories_data->categories_name;?></h3>
							          	</a>
						        	</div>
								</li>
							
							<?php } }?>
								
						    </ul>  
							<?php }?>
                                        </div>
									</div>
									<?php  }?>
                                </div>
                               <!-- <div class="container">                                
                                    <div class="subcategories col-12 col-lg-12">
                                        <div class="row row-eq-height">
                                            <?php 
                                           $result['commonContent']['categories']=array_reverse($result['commonContent']['categories']);
                                            $currentCategory = $_GET['category'];
                                            foreach($result['commonContent']['categories'] as $k => $category){
                                                if($category->slug == $currentCategory and !empty($category->sub_categories)){
                                                    foreach($category->sub_categories as $ks => $subcat){
                                                    ?>
                                                    <div class="col-lg-2 col-md-3 col-sm-4">
                                                        <div>
                                                            <a href="{{ URL::to('/shop?category='.$subcat->sub_slug) }}" class="nav-link">
                                                                <img class="img-fuild" src="{{ $subcat->sub_image }}">
                                                                <h3>{{ $subcat->sub_name }}</h3>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div> 
                                </div> -->
                                <!-- products-3x for gird -->
                                <!--products-list for list-->
                                <div class="products products-list" id="listing-products">
                                    @if($result['products']['success']==1)
                                    @foreach($result['products']['product_data'] as $key=>$products)
                                    
                                    <div class="product">
                                        <article>
                                            <div class="container">
                                                <div class="row">
                                                    <div class="thumb col-md-2 col-sm-4">
														<?php							
														$img = asset('').$products->products_image;
														$imgArr = explode('.', $products->products_image);
														$imgExt = strtolower(end($imgArr));
														$extArr = array('png','jpg', 'gif');
														if(!in_array($imgExt, $extArr)){
															$img = URL::to('/').'/resources/assets/images/site_images/1586580478.logo_mobile.png';
														}
														?>
														<img class="img-fluid" src="{{ $img }}" alt="{{$products->products_name}}">
													</div>
                                                    <div class="product_name col-md-3 col-sm-4" data-slug="{{$products->products_slug}}">
                                                        <h3 class="product_title">
                                                        {{$products->products_name}}
                                                       </h3>  
                                                       <span style="margin-top:5px;" class="products_model d-md-block">SKU: {{$products->products_model}}</span>
                                                       <?php 
                                                       if(!empty($products->certification)){
                                                       ?>
                                                       <span style="margin-top:5px;" class="products_model d-md-block">Certification: {{$products->certification}}</span>
                                                       <?php } ?>
                                                       {{--<span class="products_manufactures d-md-block">Unit: {{$products->unit_of_measure}}</span> --}}
                                                    </div>
                                                    {{-- <div class="product_cat col-md-2 col-sm-4">
                                                        
                                                            @if(!empty($result['category_name']) and !empty($result['sub_category_name']))
                                                                {{$result['sub_category_name']}}
                                                            @elseif(!empty($result['category_name']) and empty($result['sub_category_name']))
                                                                {{$result['category_name']}}
                                                            @else                    
                                                                All Categories
                                                            @endif
                                                            
                                                    </div> --}}
													{{-- <div class="product_cat col-md-2 col-sm-4">
													{{ $result['products_price'] }}
														
															@if(!empty($result['manufacturer_name']) and !empty($result['manufacturer_name']))
																{{$result['manufacturer_name']}}
															@else                    
																All Manufacturers

															@endif
															
													</div> --}}
													<div class="product_cat col-md-1 col-sm-2">
														${{ $products->products_price }}
													</div> 
                                                    <div class="product_wishlist col-md-1 col-sm-2">
                                                        <div class="like-box">
                                                            <span products_id="{{$products->products_id}}" class="fa @if($products->isLiked==1) fa-star @else fa-star-o @endif is_liked">
                                                                <span class="badge badge-secondary">{{$products->products_liked}}</span> 
                                                            </span>                                          
                                                        </div>
                                                    </div> 
                                                    <div class="product_qty col-md-2 col-sm-6">
                                                        <div class="form-group Qty">                        
                                                            <div class="input-group">      
                                                                <span class="input-group-btn first qtyminus">
                                                                    <button class="btn btn-defualt" type="button">-</button>                        
                                                                </span>

                                                                <input type="text"  name="quantity" value="1" min="1" max="100000" class="form-control qty">

                                                                <span class="input-group-btn last qtyplus">
                                                                    <button class="btn btn-defualt" type="button">+</button>                        
                                                                </span>                     
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="product_addtocart col-md-3 col-sm-6">
                                                        <button type="button" class="btn btn-secondary btn-round cart" products_id="{{$products->products_id}}">@lang('website.Add to Cart')</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </article>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                        		
                                <div class="toolbar mt-3">
                                    <div class="form-inline">
                                        <div class="form-group  justify-content-start col-6">
                                        	<input id="record_limit" type="hidden" value="{{$result['limit']}}"> 
                                        	<input id="total_record" type="hidden" value="{{$result['products']['total_record']}}"> 
                                            <label for="staticEmail" class="col-form-label"> @lang('website.Showing')<span class="showing_record">{{$result['limit']}} </span> &nbsp; @lang('website.of')  &nbsp;<span class="showing_total_record">{{$result['products']['total_record']}}</span> &nbsp;@lang('website.results')</label>                                            
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
                                            <button class="btn btn-dark" type="button" id="load_products" 
                                            @if(count($result['products']['product_data']) < $record ) 
                                                style="display:none"
                                            @endif 
                                            >@lang('website.Load More')</button>        
                                        </div>
                                    </div>
                                </div>  
                                @elseif(empty(app('request')->input('search')))
                                    <p>@lang('website.Record not found')</p>
                                @endif                             
                            </div>
                        </div>
                        
                    </div>
                    
                                        
				</div>
			</form>
		</div>
	</div>
</section>
@endsection 