@extends('layout')
@section('content')

<section class="site-content">
	<div class="container">
		<div class="shop-area">
			{{-- <div>
				<h1>Product Categories</h1>
				<p>Our <b>City Fine Foods Services</b> product list have been evaluated to meet the requirements of applicable specifications and have been set out in categories.</p>
				<p>Each category is then split into more specific listings, to assist you in finding what you need. Please click on one of the icons, and begin your journey to a successfull menu. </p>
				<p>For guaranted freshness and quality of seafood meat and poultry cuts please allow 48 hours placing your order.</p>
			</div> --}}
			<form method="get" enctype="multipart/form-data" id="load_products_form">            
                <div class="row">  
                    <div class="col-12 col-lg-12">
                        <div class="col-12">
                        	<div class="row">
                            <ul class="navbar-nav flex-column dhcategories">
							
							<?php 
								$result['commonContent']['categories']=array_reverse($result['commonContent']['categories']);
								?>
						    @foreach($result['commonContent']['categories'] as $categories_data)
							<?php 
							//echo '<pre>'; print_r($categories_data); echo '</pre>'.__FILE__.':'.__LINE__;
							if($categories_data->slug != 'miscellaneous'){
							?>
						      <li class="nav-item col-lg-2 col-md-3 col-sm-4">
						        	<div>
						        		<a href="{{ URL::to('/shop')}}?category={{$categories_data->slug}}" class="nav-link">
								          	<img class="img-fuild" style="max-width: 100%" src="{{asset('').$categories_data->image}}">
								          	<h3>{{$categories_data->name}}</h3>
							          	</a>
						        	</div>
						        
						        <!-- @if(count($categories_data->sub_categories)>0)
						        	<ul class="dropdown-menu multi-level">
						        	@foreach($categories_data->sub_categories as $sub_categories_data)            
						            	<li class="dropdown-submenu">
							              	<a class="dropdown-item" tabindex="-1" href="{{ URL::to('/shop')}}?category={{$sub_categories_data->sub_slug}}">
							                	<img class="img-fuild" src="{{asset('').$sub_categories_data->sub_icon}}">
							                	{{$sub_categories_data->sub_name}}
							              	</a>              
						            	</li>            
					            	@endforeach 
						          	</ul>
					          		@endif -->
								</li>
							<?php 
							}else{
								$miscellaneous_total_products = $categories_data->total_products;
							} 
							?>
							@endforeach
								<li class="nav-item col-lg-2 col-md-3 col-sm-4 d-none">
									<div>
										<a href="{{ URL::to('/shop')}}?category=miscellaneous" class="nav-link">
											<img class="img-fuild d-none" src="{{asset('').$categories_data->icon}}">
											<h3>miscellaneous</h3>
										</a>
									</div>
								</li>
						    </ul>                       
                            </div>
                        </div>
                        
                    </div>
                    
                                        
				</div>
			</form>
		</div>
	</div>
</section>
@endsection 