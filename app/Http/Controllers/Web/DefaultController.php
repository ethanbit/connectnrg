<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
*/
namespace App\Http\Controllers\Web;
//use Mail;
//validator is builtin class in laravel
use Validator;

use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Lang;
//for Carbon a value 
use Carbon;

//email
use Illuminate\Support\Facades\Mail;
use Session;

class DefaultController extends DataController
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
  
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	
	//setStyle
	public function setStyle(Request $request){		
		session(['homeStyle' => $request->style]);		
		return redirect('/');
	}
	
	//
	public function settheme(Request $request){		
		session(['theme' => $request->theme]);		
		return redirect('/');
	}
	
	
	//index 
	public function index(Request $request){
		
		$title = array('pageTitle' => Lang::get("website.Home"));
		$result = array();			
		$result['commonContent'] = $this->commonContent();

		if(!empty($request->limit)){
			$limit = $request->limit;
		}else{
			$limit = 12;
		}
		
		//min_price
		if(!empty($request->min_price)){
			$min_price = $request->min_price;
		}else{
			$min_price = '';
		}
		
		//max_price
		if(!empty($request->max_price)){
			$max_price = $request->max_price;
		}else{
			$max_price = '';
		}	
		
		//products
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>'', 'limit'=>10, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$special_products = $myVar->products($data);
		$result['products'] = $special_products;
		
		//special products
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>'special', 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$special_products = $myVar->products($data);
		$result['special'] = $special_products;
		
		//top seller
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>'topseller', 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$top_seller = $myVar->products($data);
		$result['top_seller'] = $top_seller;
		
		//most liked
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>'mostliked', 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$most_liked = $myVar->products($data);
		$result['most_liked'] = $most_liked;
		
		//is feature
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>'is_feature', 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$featured = $myVar->products($data);
		$result['featured'] = $featured;
		
		//is feature
		$myVar = new DataController();
		$data = array('page_number'=>'0', 'type'=>'flashsale', 'limit'=>$limit, 'min_price'=>$min_price, 'max_price'=>$max_price );
		$flash_sale = $myVar->products($data);
		$result['flash_sale'] = $flash_sale;
		//dd($result['flash_sale']);
		
		//news
		$myVar = new NewsController();
		$data = array('page_number'=>'0', 'type'=>'', 'limit'=>3, 'is_feature'=>1);
		$news = $myVar->getAllNews($data);
		$result['news'] = $news;
		
		//current time
		$currentDate = Carbon\Carbon::now();
		$currentDate = $currentDate->toDateTimeString();
		
		$slides = DB::table('sliders_images')
		   ->select('sliders_id as id', 'sliders_title as title', 'sliders_url as url', 'sliders_image as image', 'type', 'sliders_title as title')
		   ->where('status', '=', '1')
		   ->where('languages_id', '=', session('language_id'))
		   ->where('expires_date', '>', $currentDate)
		   ->get();
		
		$result['slides'] = $slides;
		
		//cart array
		$cart = '';
		$myVar = new CartController();
		$result['cartArray'] = $myVar->cartIdArray($cart);
		
		//liked products
		$result['liked_products'] = $this->likedProducts();
		
		$orders =  DB::select(DB::raw('SELECT orders_id FROM orders WHERE YEARWEEK(CURDATE()) BETWEEN YEARWEEK(date_purchased) AND YEARWEEK(date_purchased)'));
		
		if(count($orders)>0){
			$allOrders = $orders;
		}else{
			$allOrders =  DB::table('orders')->get();
		}
		
		$temp_i = array();
		foreach($allOrders as $orders_data){
			$mostOrdered = DB::table('orders_products')
							->select('orders_products.products_id')
							->where('orders_id', $orders_data->orders_id)
							->get();
			
			foreach($mostOrdered as $mostOrderedData){
				$temp_i[] = $mostOrderedData->products_id;		
			}		
		}	
		$detail = array();
		$temp_i = array_unique($temp_i);				
		foreach($temp_i as $temp_data){			
			$myVar = new DataController();
			$data = array('page_number'=>'0', 'type'=>'', 'products_id'=>$temp_data, 'limit'=>7, 'min_price'=>'', 'max_price'=>'');
			$single_product = $myVar->products($data);
			if(!empty($single_product['product_data'][0])){
				$detail[] = $single_product['product_data'][0];
			}
		}
		
		$result['weeklySoldProducts'] = array('success'=>'1', 'product_data'=>$detail,  'message'=>"Returned all products.", 'total_record'=>count($detail));

		$categories = DB::table('categories as c')
						->join('categories_description as cd', 'cd.categories_id', '=', 'c.categories_id')
						->select('categories_image as image', 'categories_name as name')
						->where('language_id', 1)
						->get();
		$result['categories'] = $categories;
		
		$categoriesOnly = DB::table('categories as c')
						->join('categories_description as cd', 'cd.categories_id', '=', 'c.categories_id')
						->select('categories_image as categories_id','categories_image as image', 'categories_name as name','categories_slug as slug')
						->where('language_id', 1)
						->where('parent_id', 0)
						->orderBy('categories_id', 'desc')
						->get();
		$result['categories_only'] = $categoriesOnly;
		
		
		return view("index", $title)->with('result', $result); 
		
	}
	
	//myContactUs
	public function ContactUs(Request $request){
		$title = array('pageTitle' => Lang::get("website.Contact Us"));
		$result = array();			
		$result['commonContent'] = $this->commonContent();
		
		return view("contact-us", $title)->with('result', $result); 
	}
	
	//processContactUs
	public function processContactUs(Request $request){
		 $messagesValidate = [
			'g-recaptcha-response.required' => 'The Captcha field is required.',
		];
		$validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'phone' => 'required',
            'email' => 'required|email',
            'message' => 'required',
            'g-recaptcha-response' => 'required|captcha',
		], $messagesValidate);

		if ($validator->fails()) {
            return redirect('/contact-us')
                        ->withErrors($validator)
                        ->withInput();
        } 

		$name     =  $request->firstname;
		$lastname =  $request->lastname;
		$phone    =  $request->phone;
		$email    =  $request->email;
		$company  =  $request->company;
		//$subject  =  $request->subject;
		$message  =  $request->message;
		
		$result['commonContent'] = $this->commonContent();
		
		$data = array('name'=>$name, 'lastname'=>$lastname, 'phone'=>$phone, 'company'=>$company, 'email'=>$email, 'message'=>$message, 'adminEmail'=>$result['commonContent']['setting'][3]->value, 'customer' => '');
		$customerData = array('name'=>$name, 'lastname'=>$lastname, 'phone'=>$phone, 'company'=>$company, 'email'=>$email, 'message'=>$message, 'adminEmail'=>$result['commonContent']['setting'][3]->value, 'customer' => 'Thank you for your enquiry, someone will be in touch soon');
		
		Mail::send('/mail/contactUs', ['data' => $data], function($m) use ($data){
			$m->to($data['adminEmail'])
			//->Cc('carol@sparkinteract.com.au')
			//->Cc('programers@sparkinteract.com.au')
			->subject(Lang::get("website.contact us title"))->getSwiftMessage()
			->getHeaders()
			->addTextHeader('x-mailgun-native-send', 'true');	
		});
		Mail::send('/mail/contactUs', ['data' => $customerData], function($m) use ($data){
			$m->to($_POST['email'])
			->subject('NRG Indigenous - Thank you for your enquiry')->getSwiftMessage()
			->getHeaders()
			->addTextHeader('x-mailgun-native-send', 'true');	
		});
		
		//return redirect()->back()->with('success', Lang::get("website.contact us message"));
		return redirect()->back()->with('success', 'Thank you for contacting us. We will be in touch with you shortly.');
	}
	
	//page
	public function page(Request $request){
		
		$pages = DB::table('pages')
					->leftJoin('pages_description','pages_description.page_id','=','pages.page_id')
					->where([['pages.status','1'],['type',2],['pages_description.language_id',session('language_id')],['pages.slug',$request->name]])->get();
		
		if(count($pages)>0){
			$title = array('pageTitle' => $pages[0]->name);
			$result['commonContent'] = $this->commonContent();
			$result['pages'] = $pages;			
			return view("page", $title)->with('result', $result);
		
		}else{
			return redirect()->intended('/') ;
		}
	}
	
	public function importCSV(){
		$csvFile = base_path('importdb.csv');
		$row = 1;
		if (($handle = fopen($csvFile, "r")) !== FALSE) {
		    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
		    	if($row == 1){
		    		$row++;
		    		continue;
		    	}

		        //echo "<pre>"; print_r($data); echo "</pre>".__FILE__.":".__LINE__."<br>";
		        

				$product_brand         = $data[0];
				$product_brand_slug    = str_slug($data[0], "-");
				$product_sku           = $data[1];
				$product_name          = $data[2];
				$product_name_slug     = str_slug($data[2], "-");
				$product_price         = $data[3];
				$product_category      = $data[4];
				$product_category_slug = str_slug($data[4], "-");

				//check manufacturer exist
				$manufacturers = DB::table('manufacturers')
								->where('manufacturers_slug', $product_brand_slug);
				$bandCheck = $manufacturers->exists();
				
				// Brand
				$manufacturer_id = 0;
				if($bandCheck){
					$brand = $manufacturers->select('manufacturers_id')->first();
					$manufacturer_id = $brand->manufacturers_id;
					
				}else{
					$manufacturer_id = DB::table('manufacturers')->insertGetId(
					    [ 
					    	'manufacturers_name' => $product_brand,
					    	'manufacturers_slug' => $product_brand_slug
					    ]
					);
					DB::table('manufacturers_info')->insert(
					    [ 
					    	'manufacturers_id' => $manufacturer_id,
					    	'languages_id' => 1
					    ]
					);
				}
				
				// Categories
				$cat_id = 0;
				//check Cat exist
				$category = DB::table('categories')
								->where('categories_slug', $product_category_slug);
				$catCheck = $category->exists();
				if($catCheck){
					$cat = $category->select('categories_id')->first();
					$cat_id = $cat->categories_id;
				}else{
					$cat_id = DB::table('categories')->insertGetId(
					    [ 
							'categories_icon' => '',
							'parent_id'       => '0',
							'categories_slug' => $product_category_slug
					    ]
					);
					DB::table('categories_description')->insert(
					    [ 
							'categories_id'   => $cat_id,
							'language_id'    => 1,
							'categories_name' => $product_category
					    ]
					);
				}

				// Product
				// check SKU exist
				$product = DB::table('products')
								->where('products_model', $product_sku);
				$productCheck = $product->exists();
				if($productCheck){
					// update product by SKU
				}else{
					$product_id = DB::table('products')->insertGetId(
					    [ 
							'products_slug'     => $product_name_slug,
							'products_status'   => 1,
							'manufacturers_id'  => $manufacturer_id,
							'products_price'    => $product_price,
							'products_model'    => $product_sku,
							'products_quantity' => 9999,
					    ]
					);
					DB::table('products_description')->insert(
					    [ 
							'products_id'   => $product_id,
							'language_id'    => 1,
							'products_name' => $product_name
					    ]
					);
					DB::table('products_to_categories')->insert(
					    [ 
							'products_id'   => $product_id,
							'categories_id' => $cat_id
					    ]
					);
				}
		    }
		    fclose($handle);
		}

		echo "<br>ngon";
		return;
	}
}