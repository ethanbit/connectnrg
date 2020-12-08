@extends('layout')
@section('content')

<section class="site-content">
  <div class="container">
    <div class="cff-home-text">        
      <div class="row">
        {{-- <div class="col-12 col-sm-12">
		 
		    <h2><strong>Popular</strong> departments</h2>
           <div class="group-banners mb-3 homebanner">
                <div class="row row-eq-height">
                    @if(count($result['commonContent']['homeBanners'])>0)
                        @foreach($result['commonContent']['homeBanners'] as $homeBanners)
                            @if($homeBanners->type == 8)
                            <div class="col-12 col-sm-4">
                                <div class="banner-image">
                                    <a title="Banner Image" href="{{ $homeBanners->banners_url}}">
                                        <img class="img-fluid" src="{{asset('').$homeBanners->banners_image}}" alt="Banner Image">
                                        <span>{{ $homeBanners->banners_title}}</span>
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div> --}}
        <div class="col-12 col-sm-12">
            <div class="homepage_specialproduct">
                <h2>Specials</h2>
                <div class="productslidernav">
                    <span class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                </div>
            </div>
            <div class="container homepage_specialproduct_slider">
                <div class="row row-eq-height owl-carousel owl-theme">
                @foreach($result['featured']['product_data'] as $key=>$featured)
                    <div class="product col-md-12 col-sm-12 item">
                        <div class="d-md-block">
                            <div class="row">
                                <div class="col-12 thumb">
								<?php if($featured->products_image !=""){?>
                                    <img class="img-fluid" src="{{asset('').$featured->products_image}}" alt="{{$featured->products_name}}">
									<?php } else  {
									$img = URL::to('/').'/resources/assets/images/site_images/1586580478.logo_mobile.png';
									?>
									<img class="img-fluid" src="{{$img}}" alt="{{$featured->products_name}}">
									<?php }?>
                                </div>
                                <div class="col-12">
                                    <span class="tag d-md-block categories_name">
                                        @foreach($featured->categories as $key=>$category)
                                            {{$category->categories_name}}
                                            @if(++$key === count($featured->categories)) 
                                            @else | 
                                            @endif
                                        @endforeach
                                    </span>
                                    <h3 class="product_name">{{$featured->products_name}}</h3>
                                    {{-- <span class="tag d-md-block">Brand: {{$featured->manufacturers_name}}</span> --}}
                                </div>
                                <div class="col-12 product_price">
                                    ${{$featured->products_price}}
                                </div> 
                                <div class="col-md-3 product_qty">
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
                                <div class="col-md-3 product_addtocart">
                                    <button type="button" class="btn btn-secondary btn-round cart" products_id="{{$featured->products_id}}">@lang('website.Add to Cart')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>
            <!-- 1st tab -->
        </div>
        <div class="col-12">
            <div class="home_banner_desktop">
                <p>With a focus on quality, consistency and value, we aim to build a long-lasting, mutually beneficial relationship with our clients.</p>
            </div>
        </div>
        <div class="col-12 categories_wrapper">
            <div class="homepage_categories">
                <h2>categories</h2>
                <div class="categoriesslidernav">
                    <span class="prev"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
                    <span class="next"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
                </div>
            </div>
			<?php
			 
			?>
            <div class="container homepage_categories_slider">
                <div class="row row-eq-height owl-carousel owl-theme">
				
                @foreach($result['categories_only'] as $key=>$category)
                    <div class="product col-md-12 col-sm-12 item">
                    <?php if(\Auth::guard('customer')->check()) { ?>
                    <a href="{{ URL::to('/shop')}}?category={{$category->slug}}" class="nav-link">
                    <?php } ?>
                        <div class="img">
                            <img width="390px" height="260px" alt="" src="{{ URL::to($category->image) }}" />
                        </div>
                        <div class="categori_name">
                            {{ $category->name }}
						
                        </div>
                    <?php if(\Auth::guard('customer')->check()) { ?>
                     </a>
                     <?php } ?>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
      </div>
    </div>

   

  </div>
</section>
<section class="banner">
	<div class="container">
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
</section>
<section class="brand-stock">
	<div class="container">
		<h2><strong>Brands</strong> we stock</h2>
	</div>
</section>
<section class="logoslides">
<div class="container">
    <div class="slides">
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/bega.png' }}">
            <img src="{{ asset('').'public/images/logos/bega.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/bulla.png' }}">
            <img src="{{ asset('').'public/images/logos/bulla.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/dairy-farmers.png' }}">
            <img src="{{ asset('').'public/images/logos/dairy-farmers.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/edgell.png' }}">
            <img src="{{ asset('').'public/images/logos/edgell.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/fonterra.png' }}">
            <img src="{{ asset('').'public/images/logos/fonterra.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/heinz.png' }}">
            <img src="{{ asset('').'public/images/logos/heinz.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/manildra.png' }}">
            <img src="{{ asset('').'public/images/logos/manildra.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/mccain.png' }}">
            <img src="{{ asset('').'public/images/logos/mccain.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/mckenzies.png' }}">
            <img src="{{ asset('').'public/images/logos/mckenzies.png' }}">
        </div>
        <div class="slide">
            <img src="{{ asset('').'public/images/logos/pacific.png' }}">
            <img src="{{ asset('').'public/images/logos/pacific.png' }}">
        </div>
    </div>
</div>
</section>
@endsection
