<header id="header-area" class="header-area bg-primary header_three"><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<div class="header-mini">
    	<div class="container">
            <div class="row align-items-center">
                <div class="col-12" style="display: none !important">

                	<nav id="navbar_0" class="navbar navbar-expand-md navbar-dark navbar-0 p-0">
                        <!-- <div class="navbar-brand">
                            <select name="change_language" id="change_language" class="change-language">
                            @foreach($languages as $languages_data)
                                <option value="{{$languages_data->code}}" data-class="{{$languages_data->code}}" data-style="background-image: url({{asset('').$languages_data->image}});" @if(session('locale')==$languages_data->code) selected @endif>{{$languages_data->name}}</option>
                            @endforeach
                            </select>
                        </div> -->

                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbar_collapse_0" aria-controls="navbar_collapse_0" aria-expanded="false" aria-label="Toggle navigation">
                            {{-- <span class="navbar-toggler-icon"></span> --}}
                            <i class="fas fa-bars navbar-toggler-icon"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbar_collapse_0">
                            <ul class="navbar-nav">

                                @if (Auth::guard('customer')->check())
                                    <li class="nav-item">
                                        <div class="nav-link">
                                            <span class="p-pic"><img src="{{asset('').auth()->guard('customer')->user()->customers_picture}}" alt="image"></span>@lang('website.Welcome')&nbsp;{{ auth()->guard('customer')->user()->customers_firstname }}&nbsp;{{ auth()->guard('customer')->user()->customers_lastname }}!
                                        </div>
                                    </li>
                                   <li class="nav-item"> <a href="{{ URL::to('/profile')}}" class="nav-link -before">@lang('website.Profile')</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/wishlist')}}" class="nav-link -before">Favourites</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/orders')}}" class="nav-link -before">Orders History</a> </li>

                                    <li class="nav-item"> <a href="{{ URL::to('/shipping-address')}}" class="nav-link -before">@lang('website.Shipping Address')</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/logout')}}" class="nav-link -before">@lang('website.Logout')</a> </li>
                                @else
                                    <li class="nav-item"><div class="nav-link">@lang('website.Welcome Guest!')</div></li>
                                    <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Login</a> </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
            	</div>
                <div class="col-12 mobile_top_cart">
                    <ul class="top-right-list">
                        <li class="wishlist-header" style="display: none !important;">
                            <a href="{{ URL::to('/wishlist')}}">
                                <span class="fa-stack fa-lg" id="wishlist-count">
                                    <i class="fa fa-heart-o fa-stack-2x"></i>
                                    <span class="number">{{$result['commonContent']['totalWishList']}}</span>
                                </span>
                            </a>
                        </li>
                        <li class="user-header dropdown @if (Auth::guard('customer')->check()) logged @endif">
                            <a href="javascript:void(0)" class="user_profile">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </span>
                            </a>
                            @if (Auth::guard('customer')->check())
                            <span class="logged_customer_name">{{ auth()->guard('customer')->user()->customers_firstname }}</span>
                            @endif
                            <ul class="navbar-nav">
                                @if (Auth::guard('customer')->check())
                                    <li class="nav-item"> <a href="{{ URL::to('/profile')}}" class="nav-link -before">@lang('website.Profile')</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/wishlist')}}" class="nav-link -before">Favourites</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/orders')}}" class="nav-link -before">Orders History</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/shipping-address')}}" class="nav-link -before">@lang('website.Shipping Address')</a> </li>
                                    <li class="nav-item"> <a href="{{ URL::to('/logout')}}" class="nav-link -before">@lang('website.Logout')</a> </li>
                                @else
                                    <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Login</a> </li>
                                @endif
                            </ul>
                        </li>
                        <li class="cart-header dropdown head-cart-content"></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="header-maxi">
    	<div class="container">
        	<div class="row align-items-center">
            	<div class="col-12 col-sm-12 col-md-12 col-lg-3 spaceright-0">
                    <div class="row">
                        <div class="col-7">
                            <nav id="navbar_2" class="mobile_menu navbar navbar-expand-lg navbar-dark navbar-2 p-0 d-block d-lg-none">
                                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar_collapse_2" aria-controls="navbar_collapse_2" aria-expanded="false" aria-label="Toggle navigation"> <i class="fas fa-bars"></i> </button>

                                <div class="collapse navbar-collapse" id="navbar_collapse_2">

                                <ul class="navbar-nav">
                                    <li class="nav-item first"><a href="{{ URL::to('/')}}" class="nav-link">@lang('website.homePages')</a></li>
                                    <li class="nav-item"> <a class="nav-link" href="{{ URL::to('/shop-online')}}">Products</a> </li>
                                    <li class="nav-item"><a href="{{ URL::to('/page?name=about-us')}}" class="nav-link">About Us</a> </li>
									<li class="nav-item"> <a class="nav-link" href="{{ URL::to('/contact-us')}}">@lang('website.Contact Us')</a> </li>
									@if (Auth::guard('customer')->check())
									<li class="nav-item user-header dropdown">
										<a href="javascript:void(0)" class="user_profile nav-link dropdown-toggle" data-toggle="dropdown">
											<span class="fa-stack fa-lg">
												<i class="fa fa-user" aria-hidden="true"></i>
											</span>	My Account</a>
										
										{{-- <span class="logged_customer_name">{{ auth()->guard('customer')->user()->customers_firstname }}</span> --}}
										
										<ul class="dropdown-menu">
											<li class="nav-item"> <a href="{{ URL::to('/profile')}}" class="nav-link -before">@lang('website.Profile')</a> </li>
											<li class="nav-item"> <a href="{{ URL::to('/wishlist')}}" class="nav-link -before">Favourites</a> </li>
                                            <li class="nav-item"> <a href="{{ URL::to('/orders')}}" class="nav-link -before">Orders History</a> </li>
                                            <li class="nav-item"> <a href="{{ URL::to('/report')}}" class="nav-link -before">Report</a> </li>
											<li class="nav-item"> <a href="{{ URL::to('/shipping-address')}}" class="nav-link -before">@lang('website.Shipping Address')</a> </li>
											<li class="nav-item"> <a href="{{ URL::to('/logout')}}" class="nav-link -before">@lang('website.Logout')</a> </li>
											
										</ul>
									</li>
									@else
										<li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i> Login </a> </li>
									@endif
                                </ul>
                                </div>
                            </nav>
                            <a href="{{ URL::to('/')}}" class="logo">
                                @if($result['commonContent']['setting'][78]->value=='name')
                                    <?=stripslashes($result['commonContent']['setting'][79]->value)?>
                                @endif

                                @if($result['commonContent']['setting'][78]->value=='logo')
                                    <img src="{{asset('').$result['commonContent']['setting'][15]->value}}" alt="<?=stripslashes($result['commonContent']['setting'][79]->value)?>">
                                @endif

                                <img class="mobile d-lg-none" src="{{asset('/public/images/logo_mobile_50.png')}}">
                            </a>
                        </div>
                        <div class="col-5 spaceleft-0 d-lg-none mobile_cart_icon">
                            <ul class="top-right-list">
                                <li class="wishlist-header">
                                    <a href="{{ URL::to('/wishlist')}}">
                                        <span class="fa-stack fa-lg" id="wishlist-count">
                                            <i class="fa fa-heart-o fa-stack-2x"></i>
                                            <span class="number">{{$result['commonContent']['totalWishList']}}</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="cart-header dropdown head-cart-content"></li>
                                <li class="user-header dropdown @if (Auth::guard('customer')->check()) logged @endif">
                                    {{-- <a href="javascript:void(0)" class="user_profile dropdown-toggle" id="userDropdownMenuButton" data-toggle="dropdown">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </span>
                                    </a> --}}
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="fa-stack fa-lg1">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                    @if (Auth::guard('customer')->check())
                                    <span class="logged_customer_name">Hi {{ auth()->guard('customer')->user()->customers_firstname }}</span>
                                    @endif
                                    <div class="dropdown-menu" aria-labelledby="userDropdownMenuButton">
                                        <ul class="navbar-nav">
                                            @if (Auth::guard('customer')->check())
                                                <li class="nav-item"> <a href="{{ URL::to('/profile')}}" class="nav-link -before">@lang('website.Profile')</a> </li>
                                                <li class="nav-item"> <a href="{{ URL::to('/wishlist')}}" class="nav-link -before">Favourites</a> </li>
                                                <li class="nav-item"> <a href="{{ URL::to('/orders')}}" class="nav-link -before">Orders History</a> </li>
                                                <li class="nav-item"> <a href="{{ URL::to('/report')}}" class="nav-link -before">Report</a> </li>
                                                <li class="nav-item"> <a href="{{ URL::to('/shipping-address')}}" class="nav-link -before">@lang('website.Shipping Address')</a> </li>
                                                <li class="nav-item"> <a href="{{ URL::to('/logout')}}" class="nav-link -before">@lang('website.Logout')</a> </li>
                                            @else
                                                <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Login</a> </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div> 

                <div class="col-12 col-sm-7 col-md-8 col-lg-9 desktop_menu">
                    <nav id="navbar_1" class="navbar navbar-expand-lg navbar-dark navbar-1 p-0 d-none d-lg-block">

                        <div class="collapse navbar-collapse" id="navbar_collapse_1">
                            <ul class="navbar-nav">
                                <li class="nav-item first dropdown d-none">
                                   <button class="btn btn-secondary dropdown-toggle" type="button" id="allDepartments" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-bars" aria-hidden="true"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="allDepartments" id="dhcategories">
                                       <!-- <li class=""> <a href="#" class="nav-link -before">Category 1</a> </li>
                                        <li class=""> <a href="#" class="nav-link -before">Category 2</a> </li>
                                        <li class=""> <a href="#" class="nav-link -before">Category 3</a> </li>
                                        <li class=""> <a href="#" class="nav-link -before">Category 4</a> </li>
                                        <li class=""> <a href="#" class="nav-link -before">Category 5</a> </li> -->
										 @foreach($result['commonContent']['categories'] as $categories_data)
                                        <?php 
                                        if($categories_data->slug != 'miscellaneous'){
										$miscellaneous_total_products = $categories_data->total_products;
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
                                </li>
    							<li class="nav-item first"><a href="{{ URL::to('/')}}" class="nav-link">@lang('website.homePages')</a></li>
                                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('/shop-online')}}">Products</a> </li>
                                {{-- <li class="nav-item dropdown open">
                                    <a href="" class="nav-link dropdown-toggle">@lang('website.infoPages')</a>

                                    <ul class="dropdown-menu">
                                        @if(count($result['commonContent']['pages']))
                                        @foreach($result['commonContent']['pages'] as $page)
                                            <li> <a href="{{ URL::to('/page?name='.$page->slug)}}" class="dropdown-item">{{$page->name}}</a> </li>
                                        @endforeach
                                        @endif
                                    </ul>
                                </li> --}}

                                {{-- <li class="nav-item"><a href="{{ URL::to('/page?name=how-to-order')}}" class="nav-link">How to order</a> </li> --}}
                                <li class="nav-item"><a href="{{ URL::to('/page?name=about-us')}}" class="nav-link">About Us</a> </li>

                                <li class="nav-item"> <a class="nav-link" href="{{ URL::to('/contact-us')}}">@lang('website.Contact Us')</a> </li>

                                @if (Auth::guard('customer')->check())
                                <li class="nav-item user-header dropdown" style="padding-top:0px;">
                                    <a href="javascript:void(0)" class="user_profile nav-link dropdown-toggle" data-toggle="dropdown">
                                        <span class="fa-stack fa-lg">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </span>
                                        My Account
                                    </a>
                                    
                                    {{-- <span class="logged_customer_name">{{ auth()->guard('customer')->user()->customers_firstname }}</span> --}}
                                    
                                    <ul class="dropdown-menu">
                                        <li class="nav-item"> <a href="{{ URL::to('/profile')}}" class="nav-link -before">@lang('website.Profile')</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/wishlist')}}" class="nav-link -before">Favourites</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/orders')}}" class="nav-link -before">Orders History</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/report')}}" class="nav-link -before">Report</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/shipping-address')}}" class="nav-link -before">@lang('website.Shipping Address')</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/logout')}}" class="nav-link -before">@lang('website.Logout')</a> </li>
                                        
                                    </ul>
                                </li>
                                @else
                                    <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i> Login</a> </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
				</div>
            </div>
        </div>
    </div>
    <div class="header-navi">
    	<div class="container">
        	<div class="row align-items-center">
            	<div class="col-12 col-sm-12 col-md-8 desktop_top_search">
                    <form class="form-inline" action="{{ URL::to('/shop')}}" method="get">
                        <div class="search-categories">
                            <select id="category_id" name="category">
							<?php
							$result['commonContent']['categories']=array_reverse($result['commonContent']['categories']);
							?> 
                                <option value="all">@lang('website.All Categories')</option>
                                @foreach($result['commonContent']['categories'] as $categories_data)
                                    <option value="{{$categories_data->slug}}" @if($categories_data->slug==app('request')->input('category')) selected @endif>{{$categories_data->name}}</option>
                                    {{-- @if(count($categories_data->sub_categories)>0)
                                        @foreach($categories_data->sub_categories as $sub_categories_data)
                                        <option value="{{$sub_categories_data->sub_slug}}" @if($sub_categories_data->sub_slug==app('request')->input('category')) selected @endif>--{{$sub_categories_data->sub_name}}</option>
                                        @endforeach
                                    @endif --}}
                                @endforeach 
                            </select>
                            <input type="search"  name="search" placeholder="@lang('website.Search entire store here')..." value="{{ app('request')->input('search') }}" aria-label="Search">
                            <select id="manufacturer_id" name="manufacturer" class="d-none hidden" hidden>
                                <option value="all">Brands</option>
                                @foreach($result['commonContent']['manufacturers'] as $manufacturers_data)
                                    <option value="{{$manufacturers_data->manufacturers_slug}}" @if($manufacturers_data->manufacturers_slug==app('request')->input('manufacturer')) selected @endif>{{$manufacturers_data->manufacturers_name}}</option>
                                @endforeach 
                            </select>
                            <button type="submit" class="btn btn-secondary"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-12 col-md-4 nav_cart_icon">
                    <ul class="top-right-list">
                        <li class="wishlist-header">
                            <a href="{{ URL::to('/wishlist')}}">
                                <span class="fa-stack fa-lg" id="wishlist-count">
                                    <i class="fa fa-heart-o fa-stack-2x"></i>
                                    <span class="number">{{$result['commonContent']['totalWishList']}}</span>
                                </span>
                            </a>
                        </li>
                        <li class="cart-header dropdown head-cart-content"></li>
                        <li class="user-header dropdown @if (Auth::guard('customer')->check()) logged @endif">
                            {{-- <a href="javascript:void(0)" class="user_profile dropdown-toggle" id="userDropdownMenuButton" data-toggle="dropdown">
                                <span class="fa-stack fa-lg">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </span>
                            </a> --}}
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="userDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="fa-stack fa-lg1">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                </span>
                            </button>
                            @if (Auth::guard('customer')->check())
                            <span class="logged_customer_name">Hi {{ auth()->guard('customer')->user()->customers_firstname }}</span>
                            @endif
                            <div class="dropdown-menu" aria-labelledby="userDropdownMenuButton">
                                <ul class="navbar-nav">
                                    @if (Auth::guard('customer')->check())
                                        <li class="nav-item"> <a href="{{ URL::to('/profile')}}" class="nav-link -before">@lang('website.Profile')</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/wishlist')}}" class="nav-link -before">Favourites</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/orders')}}" class="nav-link -before">Orders History</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/report')}}" class="nav-link -before">Report</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/shipping-address')}}" class="nav-link -before">@lang('website.Shipping Address')</a> </li>
                                        <li class="nav-item"> <a href="{{ URL::to('/logout')}}" class="nav-link -before">@lang('website.Logout')</a> </li>
                                    @else
                                        <li class="nav-item"> <a href="{{ URL::to('/login')}}" class="nav-link -before"><i class="fa fa-lock" aria-hidden="true"></i>&nbsp;Login</a> </li>
                                    @endif
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</header>
