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
                       {{--<span class="products_manufactures d-md-block">Unit: {{$products->unit_of_measure}}</span> --}}
                    </div>
                    
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
    <input id="filter_total_record" type="hidden" value="{{$result['products']['total_record']}}"> 
    
    @if(count($result['products']['product_data'])> 0 and $result['limit'] > $result['products']['total_record'])
		<style>
			#load_products{
				display: none;
			}
			#loaded_content{
				display: block !important;
			}
			#loaded_content_empty{
				display: none !important;
			}
        </style>
    @endif
    @elseif(count($result['products']['product_data'])==0 or $result['products']['success']==0)
		<style>
            #load_products{
                display: none;
            }
            #loaded_content{
                display: none !important;
            }
            #loaded_content_empty{
                display: block !important;
            }
        </style>
    @endif
