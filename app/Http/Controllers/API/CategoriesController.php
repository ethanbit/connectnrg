<?php 
namespace App\Http\Controllers\API;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
use Session;

use App\Http\Controllers\Web\DataController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoriesController extends Controller {
	public $successStatus = 200;

	public function getCategories(){
		$DataController = new DataController();
		$result = $DataController->categories();
		$result=array_reverse($result); 
		$tax_setting = DB::table('settings')->where('id',93)->get();
		return response()->json(['tax_amount_percent'=>@$tax_setting[0]->value,'success' => $result], $this->successStatus);
	}

	public function getSubCategories(Request $request){
		$langID = Session::get('language_id');
		if(empty($langID)){
			$langID = 1;
		}

		$categories_id = $request->catid; 

		$sub_categories = DB::table('categories')
			->LeftJoin('categories_description', 'categories_description.categories_id', '=', 'categories.categories_id')
			->select('categories.categories_id as sub_id',
				 'categories.categories_image as sub_image',
				 'categories.categories_icon as sub_icon',
				 'categories.sort_order as sub_order',				 
			 	'categories.categories_slug as sub_slug',
				 'categories.parent_id',
				 'categories_description.categories_name as sub_name'
				 )
			->where('categories_description.language_id','=', $langID)
			->where('parent_id',$categories_id)
			->get();
		
		$data = array();
		$index2 = 0; 
		foreach($sub_categories as $sub_categories_data){
			$sub_categories_id = $sub_categories_data->sub_id;
							
			$individual_products = DB::table('products_to_categories')
				->LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
				->select('products_to_categories.categories_id', DB::raw('COUNT(DISTINCT products.products_id) as total_products'))
				->where('products_to_categories.categories_id','=', $sub_categories_id)
				->get();
		
			$sub_categories_data->total_products = $individual_products[0]->total_products;
			$data[$index2++] = $sub_categories_data;				
		
		}

		return response()->json(['success' => $data], $this->successStatus);
	}

	public function getProducts(Request $request){  
		$DataController = new DataController();
		$data = array();
		$data['page_number']   = $request->page_number;
		$data['limit']         = $request->limit;
		$data['categories_id'] = $request->categories_id;
		// Sort have some value: atoz, ztoa, hightolow, lowtohigh, topseller, mostliked, special, flashsale
		$data['type']          = $request->sort; 

		$data['min_price']     = '';
		$data['max_price']     = '';
		$data['search']        = $request->search;
		$data['filters']       = array();
		
		$result = $DataController->products($data);
		return response()->json(['success' => $result], $this->successStatus);
		
	}

	public function getProducts2(Request $request){
		$langID = 1;
		$data = array();
		$data['page_number']   = $request->page_number;
		$data['limit']         = $request->limit;
		$data['categories_id'] = $request->categories_id;
		// Sort have some value: atoz, ztoa, hightolow, lowtohigh, topseller, mostliked, special, flashsale
		$data['type']          = $request->sort; 

		$data['search']        = $request->search;

		if(empty($data['page_number']) or $data['page_number'] == 0 ){
			$skip								=   $data['page_number'].'0';
		}else{
			$skip								=   $data['limit']*$data['page_number'];
		}		

		$take									=   $data['limit'];
		$currentDate 							=   time();	
		$type									=	$data['type'];
		
		if($type=="atoz"){
			$sortby								=	"products_name";
			$order								=	"ASC";
		}elseif($type=="ztoa"){
			$sortby								=	"products_name";
			$order								=	"DESC";
		}elseif($type=="hightolow"){
			$sortby								=	"products_price";
			$order								=	"DESC";
		}elseif($type=="lowtohigh"){
			$sortby								=	"products_price";
			$order								=	"ASC";
		}elseif($type=="topseller"){
			$sortby								=	"products_ordered";
			$order								=	"DESC";
		}elseif($type=="mostliked"){
			$sortby								=	"products_liked";
			$order								=	"DESC";
			
		}elseif($type == "special"){ 
			$sortby = "specials.products_id";
			$order = "desc";
		}elseif($type == "flashsale"){ //flashsale products
			$sortby = "flash_sale.flash_start_date";
			$order = "asc";
		}else{
			$sortby = "products.products_id";
			$order = "desc";
		}	
			
		$categories = DB::table('products')
			//->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
			//->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
			->leftJoin('products_description','products_description.products_id','=','products.products_id')
			->where('products_status','=',1);
			
		if(!empty($data['categories_id'])){
			$categories->LeftJoin('products_to_categories', 'products.products_id', '=', 'products_to_categories.products_id')
					->leftJoin('categories','categories.categories_id','=','products_to_categories.categories_id')
					->LeftJoin('categories_description','categories_description.categories_id','=','products_to_categories.categories_id');
		}
		
		if(!empty($data['search'])){
			$categories->select('products.products_id', 'products.products_status', 'products.products_model', 'products.products_image', 'products.products_price', 'products.products_slug', 'products_description.products_name');
				;
		}

		//parameter special
		elseif($type == "special"){
			$categories->LeftJoin('specials', 'specials.products_id', '=', 'products.products_id')
				->select('products.products_id', 'products.products_status', 'products.products_model', 'products.products_image', 'products.products_price', 'products.products_slug', 'products_description.products_name', 'specials.specials_new_products_price as discount_price', 'specials.specials_new_products_price as discount_price');
		}
		elseif($type == "flashsale"){
			//flash sale				
			$categories->LeftJoin('flash_sale', 'flash_sale.products_id', '=', 'products.products_id')
			->select(DB::raw(time().' as server_time'),'products.products_id', 'products.products_status', 'products.products_model', 'products.products_image', 'products.products_price', 'products.products_slug', 'products_description.products_name','flash_sale.flash_start_date', 'flash_sale.flash_expires_date', 'flash_sale.flash_sale_products_price as flash_price');
			
		}
		else{
			$categories->LeftJoin('specials', function ($join) use ($currentDate) {  
				$join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1')->where('expires_date', '>', $currentDate);
			})->select('products.products_id', 'products.products_status', 'products.products_model', 'products.products_image', 'products.products_price', 'products.products_slug', 'products_description.products_name');
		}
		
		if($type == "special"){ //deals special products
			$categories->where('specials.status','=', '1')->where('expires_date','>',  $currentDate);
		}
		
		if($type == "flashsale"){ //flashsale
			$categories->where('flash_sale.flash_status','=', '1')->where('flash_expires_date','>',  $currentDate);
		}else{
			$categories->whereNotIn('products.products_id',function($query) use ($currentDate) {
						$query->select('flash_sale.products_id')->from('flash_sale')->where('flash_sale.flash_status','=', '1');
					});
		}
		
		//get single products
		if(!empty($data['products_id']) && $data['products_id']!=""){
			$categories->where('products.products_id','=', $data['products_id']);
		}
					
		if(!empty($data['search'])){
			
			$searchValue = $data['search'];
			// if(!empty($data['manufacturer_id'])){
			// 	$categories->where('products.manufacturers_id','=', $data['manufacturer_id']);
			// }		
			
			$categories->Where('products_name', 'LIKE', '%'.$searchValue.'%')
						->orWhere('products_model', 'LIKE', '%'.$searchValue.'%');	
				
			// if(!empty($data['manufacturer_id'])){
			// 	$categories->where('products.manufacturers_id','=', $data['manufacturer_id']);
			// }
			
			// if(!empty($data['manufacturer_id'])){
			// 	$categories->where('products.manufacturers_id','=', $data['manufacturer_id']);
			// }
							
		}
		
		$categories->where('products_description.language_id','=',$langID);
		
		//get single category products		
		
		if(!empty($data['categories_id'])){
			$categories->where('categories_description.language_id','=',$langID)
			->where('products_to_categories.categories_id','=', $data['categories_id']);;
		}	

		// if(!empty($data['manufacturer_id'])){
		// 	$categories->where('manufacturers.manufacturers_id','=', $data['manufacturer_id']);
		// 	$categories->where('manufacturers_info.languages_id','=', $langID);
		// }
		
		$categories->orderBy($sortby, $order)->groupBy('products.products_id');
			
		//count
		//echo $categories->toSql()."------";

		
		//Cache::rememberForever('categories', function() use ($langID) {
		$search_slug = '';
		if(!empty($data['search'])){
			$search_slug = Str::slug($data['search'], '-');
		}
		
		$cache_key = 'products_'.$data['categories_id'].'_'.$search_slug.'_'.$skip.'_'.$take.'_'.$type;
		// cache remember 1 day

		//echo "Duc:".count($categories->get());

		$total_record = Cache::remember('total_record_'.$cache_key, 60*60*24 , function () use ($categories){
			$result = $categories->get();
			return $result;
		});
		//$total_record = $categories->get();

		$products = Cache::remember($cache_key, 60*60*24 , function () use ($categories, $skip, $take){
			$result = $categories->skip($skip)->take($take)->get();
			return $result;
		});
		//$products = $categories->skip($skip)->take($take)->get();
		
		// $total_record = $categories->get();
		// $products  = $categories->skip($skip)->take($take)->get();
				
		//check if record exist
		if(count($products)>0){
			
			$responseData = array('success'=>'1', 'total_record'=>count($total_record), 'product_data'=> $products,  'message'=> 'Returned all products');
				
		}else{
			$responseData = array('success'=>'0', 'total_record'=>count($total_record), 'product_data'=> array(),  'message'=> 'Empty record');
		}	
		
		return response()->json($responseData, $this->successStatus);
		
	}

	public function getSubcategoryProducts(Request $request){
     	$result 	= 	array();
			$sub_categories_id = $request->category_id;
			
				$individual_products = DB::table('products_to_categories')
					->LeftJoin('products', 'products.products_id', '=', 'products_to_categories.products_id')
					->select('products.*')
					->where('products_to_categories.categories_id','=', $sub_categories_id)
					->get();
			$index2 = 0; 
			foreach($individual_products as $individual_products){
			 //print_r($individual_products);
			 $data[$index2++] = $individual_products;		
			}
	return response()->json(['success' => $data], $this->successStatus);	

			
			 
				//$sub_categories_data->total_products = $individual_products[0]->total_products;
						
			
		
			
			
		}
		
		}
  // print_r($result);		
	
