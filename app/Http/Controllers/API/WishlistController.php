<?php 
namespace App\Http\Controllers\API;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
use Session;

use App\Http\Controllers\Web\DataController;
use App\Http\Controllers\Web\CustomersController;

class WishlistController extends Controller {
	public $successStatus = 200;

	/**
	limit
	*/
	public function getWishlist(Request $request){
		$result = array();	
		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 15;
		}	
		
		$myVar = new DataController();
		$data = array('page_number'=>0, 'type'=>'wishlist', 'limit'=>$limit, 'categories_id'=>'', 'search'=>'', 'min_price'=>'', 'max_price'=>'' );			
		$products = $myVar->products($data);
		$result['products'] = $products;

		if($limit > $result['products']['total_record']){		
			$result['limit'] = $result['products']['total_record'];
		}else{
			$result['limit'] = $limit;
		}
		
		//echo '<pre>'.print_r($result['products'], true).'</pre>';
		return response()->json(['success' => 1, 'limit' => $limit, 'datas' => $products], $this->successStatus);
	}

	/**
	products_id
	*/
	public function addToWishList(Request $request){
		$customer = new CustomersController();
		$wishlist = $customer->likeMyProduct($request);

		return $wishlist;
	}

	/**	remove from wishlist	Params: liked_products_id, liked_customers_id	*/	
	public function removeFromWishlist(Request $request){		 		
		$result = array();			
		$liked_products_id= $request->liked_products_id;		
		$liked_customers_id= $request->liked_customers_id;				
		$deleted= DB::table('liked_products')->where([					
			'liked_products_id'  => $liked_products_id,					
			'liked_customers_id' => $liked_customers_id				
		])->delete();	

		if($deleted){			 
			return response()->json(['success' => 1,  'message' => 'Product removed from the favorite list'], $this->successStatus);		 
		}else{			  
			return response()->json(['success' => 0,  'message' => 'Product not removed from the favorite list'], $this->successStatus);		  
		}	
	}	
}