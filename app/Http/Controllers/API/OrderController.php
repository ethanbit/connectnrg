<?php 
namespace App\Http\Controllers\API;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
use Lang;
use Auth;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\OrdersController;
use App\Http\Controllers\Admin\AdminAlertController;
use App\Customer;

class OrderController extends Controller {
	public $successStatus = 200;

	public function getOrderList(Request $request){   
	
		$result = array();		
		
		if(Auth::guard('api')->check()){
			$customers_id = auth()->guard('api')->id();

		}elseif(Auth::guard('apiadmin')->check()){ // sale staff login and place order for customers
			$customer     = Customer::find($request->customers_id);
			 $customers_id = @$customer->customers_id;	
			
			//echo '<pre>'.$customer->customers_id.':'.$customer->email; print_r($customer); echo '</pre>'.__FILE__.':'.__LINE__;exit;
		}
		else{
			$customers_id=$request->customers_id;
		}
		
		if(isset($customers_id))
		{

		//orders		
		$orders = DB::table('orders')->orderBy('date_purchased','DESC')->where('customers_id','=', $customers_id)->get();	
		//print_r($orders);
		$index = 0;
		$total_price = array();
		
		$shippedOrder = array();
		foreach($orders as $orders_data){
			$orders_products = DB::table('orders_products')
				->select('final_price', DB::raw('SUM(final_price) as total_price'))
				->where('orders_id', '=' ,$orders_data->orders_id)
				->get();
				
			$orders[$index]->total_price = $orders_products[0]->total_price;		
			
			$orders_status_history = DB::table('orders_status_history')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
				->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
				->where('orders_id', '=', @$orders_data->orders_id)
				->orderby('orders_status_history.orders_status_history_id', 'DESC')->limit(1)->first();
				
			if($orders_status_history){
				$orders[$index]->orders_status_id = $orders_status_history->orders_status_id;
				$orders[$index]->orders_status = $orders_status_history->orders_status_name;
				
				$orders_items = DB::table('orders_products')
					->where('orders_id', '=', $orders_data->orders_id)->count();
				$orders[$index]->orders_items = $orders_items;
				$orders[$index]->order_no = $orders_data->order_no?$orders_data->order_no:'';

				if($orders_status_history->orders_status_id == 5){
					$shippedOrder[] = $orders[$index];
				}
				$index++;
			}
		
		}
				
		$result['orders'] = $orders;
		
		
		//echo '<pre>'.print_r($result['products'], true).'</pre>';
		return response()->json(['success' => 1, 'total' =>count($shippedOrder), 'data' => $shippedOrder], $this->successStatus);
		}
		else{
		return response()->json(['success' => 2, 'message'=>'No such customer found or deleted.']);	
		}
	}
	
	public function getOrderListAll(Request $request){
	
		$result = array();		
		
		if(Auth::guard('api')->check()){
			$customers_id = auth()->guard('api')->id();

		}elseif(Auth::guard('apiadmin')->check()){ // sale staff login and place order for customers
			$customer     = Customer::find($request->customers_id);
			$customers_id = $customer->customers_id;	
				
			
			//echo '<pre>'.$customer->customers_id.':'.$customer->email; print_r($customer); echo '</pre>'.__FILE__.':'.__LINE__;exit;
		}
		else{
			$customers_id=$request->customers_id;
		}
		

		//orders		
		$orders = DB::table('orders')->orderBy('date_purchased','DESC')->where('customers_id','=', $customers_id)->get();	
		
		$index = 0;
		$total_price = array();
		
		$shippedOrder = array();
		foreach($orders as $orders_data){
		$FullName = explode(" ", $orders_data->customers_name);
			$custinitials = '';
			foreach ($FullName as $inits) {
				 $custinitials .= $inits[0];
			}
			$orders_products = DB::table('orders_products')
				->select('final_price', DB::raw('SUM(final_price) as total_price'))
				->where('orders_id', '=' ,$orders_data->orders_id)
				->get();
				
			$orders[$index]->total_price = $orders_products[0]->total_price;		
			
			$orders_status_history = DB::table('orders_status_history')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
				->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
				->where('orders_id', '=', $orders_data->orders_id)
				->orderby('orders_status_history.orders_status_history_id', 'DESC')->limit(1)->first();
			//echo '<pre>'; print_r($orders_status_history); echo '</pre>'.__FILE__.':'.__LINE__;
			
			if(!empty($orders_status_history)){
				$orders[$index]->orders_status_id = $orders_status_history->orders_status_id;
				$orders[$index]->orders_status = $orders_status_history->orders_status_name;
			}else{
				$orders[$index]->orders_status_id = '';
				$orders[$index]->orders_status = '';
			}
			
			$orders[$index]->order_number = "#".$custinitials.$orders_data->customers_id.$orders_data->orders_id;
			
			$orders_items = DB::table('orders_products')
				->where('orders_id', '=', $orders_data->orders_id)->count();
			$orders[$index]->orders_items = $orders_items;
			$orders[$index]->order_no = $orders_data->order_no?$orders_data->order_no:'';

			$index++;
			/* if($orders_status_history->orders_status_id == 5){ 
				$shippedOrder[] = $orders;
			} */
		
		}
				
		$result['orders'] = $orders;
	
		//echo '<pre>'.print_r($result['products'], true).'</pre>';
		return response()->json(['success' => 1, 'total' =>count($orders), 'data' => $orders], $this->successStatus);
	}
	
	public function getOrderList__bk_abhinav(Request $request){
	
		$result = array();		
		$ordersData=array();		
		
		if(Auth::guard('api')->check()){
			$customers_id = auth()->guard('api')->id();

		}elseif(Auth::guard('apiadmin')->check()){ // sale staff login and place order for customers
			$customer     = Customer::find($request->customers_id);
			 $customers_id = @$customer->customers_id;	
		}
		else{
			$customers_id=$request->customers_id;
		}
		
		if(isset($customers_id))
		{

		//get the orders which are shipped only i.e status is 5
		 /* $ordersData = DB::table('orders')
		->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
		->where('orders_status_history.orders_status_id', '=',5)
		->where('orders.customers_id','=', $customers_id)
		->orderBy('date_purchased','DESC')	
		->get();  */


		 $ordersDataIs = DB::table('orders')
		->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
		->where('orders.customers_id','=', $customers_id)
		->groupBY('orders_status_history.orders_status_id')		 	
		->get(); 	
			
			$index = 0;
		   foreach($ordersDataIs as $key=>$val)
		   {
		   if($val->orders_id !=""  && $val->orders_status_id !="" && $val->orders_status_id ==5)
		   {
			   $ordersData[$index]=$val;
		   }
		   }
		  $total_price = array();
		  foreach($ordersData as $key=>$orders_data){
			 
				$orders_products = DB::table('orders_products')
				->select('final_price', DB::raw('SUM(final_price) as total_price'))
				->where('orders_id', '=' ,$orders_data->orders_id)
				->get();
				
			$ordersData[$index]->total_price = $orders_products[0]->total_price;		
			
			$orders_status_history = DB::table('orders_status_history')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
				->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
				->where('orders_id', '=', @$orders_data->orders_id)
				->orderby('orders_status_history.orders_status_history_id', 'DESC')->limit(1)->first(); 
			if($orders_status_history){
			$ordersData[$index]->orders_status_id = $orders_status_history->orders_status_id;
			$ordersData[$index]->orders_status = $orders_status_history->orders_status_name;
			
			
			$orders_items = DB::table('orders_products')->where('orders_id', '=', $orders_data->orders_id)->count();
			$ordersData[$index]->orders_items = $orders_items;

			$index++;
			}
		
		}
		return response()->json(['success' => 1, 'total' =>count($ordersData), 'data' => $ordersData], $this->successStatus);
		}
		else{
		return response()->json(['success' => 2, 'message'=>'No such customer found or deleted.']);	
		}
	}

	public function orderDetail(Request $request){
		$result = array();	
		
		if(Auth::guard('api')->check()){
			$customers_id = auth()->guard('api')->id();

		}elseif(Auth::guard('apiadmin')->check()){ // sale staff login and place order for customers
			$customer     = Customer::find($request->customers_id);
			$customers_id = $customer->customers_id;	
			
			//echo '<pre>'.$customer->customers_id.':'.$customer->email; print_r($customer); echo '</pre>'.__FILE__.':'.__LINE__;exit;
		}

		$orderID =  $request->id;
		//orders		
		$orders = DB::table('orders')->orderBy('date_purchased','DESC')->where('orders_id','=', $orderID)->where('customers_id',$customers_id)->get();	
		if(count($orders)>0){
			$index = 0;		
			foreach($orders as $orders_data){
					
				$orders_status_history = DB::table('orders_status_history')
					->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
					->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
					->where('orders_id', '=', $orders_data->orders_id)->orderby('orders_status_history.orders_status_history_id', 'DESC')->limit(1)->get();
				
				$products_array = array();
				$index2 = 0;
				$order_products = DB::table('orders_products')
					->join('products','products.products_id','=','orders_products.products_id')
					->select('products.products_image', 'products.products_model as model', 'products.unit_of_measure as unit_of_measure','orders_products.*')
					->where('orders_id',$orders_data->orders_id)->get();
				
				foreach($order_products as $products){
				    array_push($products_array,$products);
					$attributes = DB::table('orders_products_attributes')->where([['orders_id',$products->orders_id],['orders_products_id',$products->orders_products_id]])->get();
					if(count($attributes)==0){
						$attributes = $attributes;
					}
					
					$products_array[$index2]->products_model = $products->model?$products->model:'';
					$products_array[$index2]->unit_of_measure = $products->unit_of_measure?$products->unit_of_measure:'';
					$products_array[$index2]->attributes = $attributes;
					$index2++;
					
				}
				
				$orders_status_history = DB::table('orders_status_history')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
				->orderBy('orders_status_history.date_added', 'desc')
				->where('orders_id', '=', $orders_data->orders_id)->get();
				
				$orders[$index]->statusess = $orders_status_history;
				$orders[$index]->products = $products_array;
				$orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
				$orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;
				$index++;
			
			}
				
			$result['orders'] = $orders;
			
			return response()->json(['success' => 1, 'data' => $orders], $this->successStatus);
		}else{
			return response()->json(['success' => 0, 'data' => 'Your order not found!'], 401);
		}
	}

	public function checkout(Request $request){
		// $customer = Customer::find($request->customers_id);
		// echo '<pre>'.$customer->customers_id.':'.$customer->email; print_r($customer); echo '</pre>'.__FILE__.':'.__LINE__;exit;

		// add to cart
		$cart = new CartController(); 
		$data = $request->all();
		if(isset($data['order_no']) && !empty($data['order_no']))
		{
		$order_no=$data['order_no']; // for admin use
		}
		$products = (array)$data['products'];
		for($i=0; $i < count($products); $i++){
			$myRequest = new Request();
			$myRequest->setMethod('POST');

			$product = $products[$i];
			foreach($product as $key => $value){
				$myRequest->request->add($product);
			}

			$cart->addToCart($myRequest);
		}
		
		foreach($request->all() as $key=>$value){
			$shipping_data[$key] = $value;

			//billing address 
			if($key=='firstname'){
				$billing_data['billing_firstname'] = $value;
			}else if($key=='lastname'){
			 	$billing_data['billing_lastname'] = $value;
			}else if($key=='company'){
				$billing_data['billing_company'] = $value;
			}else if($key=='street'){
				$billing_data['billing_street'] = $value;
			}else if($key=='countries_id'){
				$billing_data['billing_countries_id'] = $value;
			}else if($key=='suburb'){
				$billing_data['billing_suburb'] = $value;
			}else if($key=='zone_id'){
				$billing_data['billing_zone_id'] = $value;
			}else if($key=='city'){
				$billing_data['billing_city'] = $value;
			}else if($key=='postcode'){
				$billing_data['billing_zip'] = $value;
			}else if($key=='delivery_date'){
				$billing_data['billing_delivery_date'] = $value;
			}else if($key=='delivery_phone'){
				$billing_data['billing_phone'] = $value;
			}else if($key=='delivery_note'){
				$billing_data['billing_delivery_note'] = $value;
			}		  
		}
		
		// set Billing Address same Shipping Address
		$billing_address = (object) $billing_data;
		$billing_address->same_billing_address = 1;
		session(['billing_address' => $billing_address]);
				
		// set Shipping Address
		$address = (object) $shipping_data;
		session(['shipping_address' => $address]);

		// payment method
		session(['payment_method' => 'cash_on_delivery']);

		// Shipping Method
		$shipping_detail = array();
		$shipping_detail['shipping_method'] = 'flateRate'; 	
		$shipping_detail['mehtod_name']     = 'Flat Rate'; 	
		$shipping_detail['shipping_price']  = '0'; 	
		session(['shipping_detail' => (object) $shipping_detail]);

		session(['order_comments' => $request->comments]);

		//echo "<pre>"; print_r($request); echo "</pre>".__FILE__.":".__LINE__;exit;
		// place order
		$order = new OrdersController();
		$orderID = $order->place_order($request);
		if($orderID){
			return response()->json(['success' => 1,'data' => $orderID, 'msg'=> Lang::get("website.Payment has been processed successfully")], 200);
		}else{
			return response()->json(['success' => 0, 'msg' => Lang::get("website.Error while placing order")], 401);
		}
	}

	public function updateOrderStatus(Request $request){  
		$customers_id = $request->customer_id;
		$orderID = $request->order_id;
		$comments = $request->comment;

		DB::table('orders_status_history')->insert([
			'orders_id'         => $orderID,
			'orders_status_id'  => 5,  
			'date_added'        => date('Y-m-d H:i:s'),
			'customer_notified' => 1,
			'comments'          => $comments
		]);	

		$data['orders_id']    = $orderID;
		$data['status']       = 'Shipped';
		$data['customers_id'] = $customers_id;
		
		$adminAlert = new AdminAlertController();
		$adminAlert->orderStatusChange($data);
	}

	public function saveOrderImage(Request $request){
	    $customers_id = $request->customers_id;
	 	$destinationPath = 'uploads';
	
		$profileImage = '';
		$file = $request->file('profileImage');
		if($file){
			$fileUplaoded= $file->move($destinationPath, $file->getClientOriginalName());
			$profileImage = $destinationPath.'/'.$file->getClientOriginalName();
	    	if($fileUplaoded) //save only if image uploaded
	    	{
				DB::table('orders_images')->insert([
					'order_id' => $request->order_id,
					'image' => $profileImage	    
				]);

				DB::table('orders_status_history')->insert([
					'orders_id'         => $request->order_id,
					'orders_status_id'  => 2,
					'date_added'        => date('Y/m/d H:i:s'),
					'customer_notified' => 1
				]);
				
				// send order status notification
				$data['orders_id']    = $request->order_id;
				$data['status']       = 'Completed';
				$data['customers_id'] = $customers_id;
				
				$adminAlert = new AdminAlertController();
				$adminAlert->orderStatusChange($data);
	    	}
			return response()->json(['success' => 1, 'msg' => 'Upload success.', 'order_id' => $request->order_id, 'file' => (Array)$file], 200);
		}
		else
		{
			return response()->json(['success' => 2, 'msg' => 'Please upload image.']);
		}

	}



	public function getOrderImage(Request $request){
		// table: orders_images
	 	$order_id = $request->order_id;


		$data = DB::table('orders_images')->select('image')->where('order_id', '=', $order_id)->get()->toArray();

		return response()->json(['success' => 1, 'data' => $data, 'msg'=> ''], 200);
	}

	public function testmethod(Request $request){
		$destinationPath = 'uploads';
	
		$profileImage = '';
		$file = $request->file('profileImage');
		if($file){
			$fileUplaoded= $file->move($destinationPath, $file->getClientOriginalName());
			$profileImage = $destinationPath.'/'.$file->getClientOriginalName();
	    	if($fileUplaoded) //save only if image uploaded
	    	{
	    	DB::table('orders_images')->insert([
			'order_id' => $request->order_id,
			'image' => $profileImage
	    
		    ]);
	    	}
			return response()->json(['success' => 1, 'msg' => 'Upload success.', 'order_id' => $request->order_id, 'file' => (Array)$file, 'rawdata' => $_FILES], 200);
		}
		else
		{
			return response()->json(['success' => 2, 'msg' => 'Please upload image.', 'file' => (Array)$request->profileImage, 'rawdata' => $_FILES]);
		}
		
		// echo "Started";
// 
// 		foreach (getallheaders() as $name => $value) {
// 			echo "<br>";
// 			echo "$name: $value\n";
// 		}
// 
// 		echo "<br>";
// 		var_dump($_POST, $_FILES);
// 		echo "Completed";

		return;
	}
	
	public function uploadOrderImage(Request $request){
	    $destinationPath = 'uploads';
	
	
		$profileImage = '';
		$file = $request->file('profileImage');
		if($file){
		    echo "yes";
			$file->move($destinationPath, $file->getClientOriginalName());
			$profileImage = $destinationPath.'/'.$file->getClientOriginalName();
		}
		else
		{
		     echo "no";
		}
		 die;

		DB::table('orders_images')->insert([
			'order_id' => $request->order_id,
			'image' => $profileImage
		]);

		return response()->json(['success' => 1, 'msg' => 'Upload success.', 'order_id' => $request->order_id, 'file' => (Array)$file], 200);
	}
	
	public function OrderCsv(Request $request){
		
		
	$orders = DB::table('orders')			
				->orderBy('date_purchased','DESC')
				->where('customers_id','!=','')
				->where(function($query){
					if(isset($_GET['action']) and $_GET['action'] == 'trash'){
						$query->where('action','=', $_GET['action']);	
					}else{
						$query->where('action','!=', 'trash');
					}

					if(isset($_GET['order_search']) and trim($_GET['order_search']) != ''){
						if(is_numeric(trim($_GET['order_search']))){
							$query->where('orders_id','=', intval($_GET['order_search']));
						}else{
							return redirect()->back()->with('error', 'You must enter order ID');
						}						
					}

					// filter buy status
					if(isset($_GET['status'])){
						$status = $_GET['status'];
						if($status == 'pending' or $status == 'completed' or $status == 'cancelled' or $status == 'returned'){

							$orders = DB::table('orders')->get();
							$penddingArr = $completedArr = $cancelledArr = $returnedArr = array();
							foreach($orders as $orders_data){
								$orders_status_history = DB::table('orders_status_history')
									->where('orders_id', '=', $orders_data->orders_id)
									->orderby('date_added', 'DESC')
									->first();
								if($orders_status_history->orders_status_id == 1){
									$penddingArr[] = $orders_status_history->orders_id;
								}elseif($orders_status_history->orders_status_id == 2){
									$completedArr[] = $orders_status_history->orders_id;
								}elseif($orders_status_history->orders_status_id == 3){
									$cancelledArr[] = $orders_status_history->orders_id;
								}elseif($orders_status_history->orders_status_id == 4){
									$returnedArr[] = $orders_status_history->orders_id;
								}
							}

							if($status == 'pending'){
								$ordersInArr = $penddingArr;
							}elseif($status == 'cancelled'){
								$ordersInArr = $cancelledArr;
							}elseif($status == 'completed'){
								$ordersInArr = $completedArr;
							}elseif($status == 'returned'){
								$ordersInArr = $returnedArr;
							}
							$query->whereIn('orders_id', $ordersInArr);

							
						}
					}
				})
				->paginate(300);	
			
			$index = 0;
			$total_price = array();
			
			foreach($orders as $orders_data){
			$FullName = explode(" ", $orders_data->customers_name);
			$custinitials = '';
			foreach ($FullName as $inits) {
				 $custinitials .= $inits[0];
			}
			
				$orders_products = DB::table('orders_products')
					->select('final_price', DB::raw('SUM(final_price) as total_price'))
					->where('orders_id', '=' ,$orders_data->orders_id)
					->get();
					
				$orders[$index]->total_price = $orders_products[0]->total_price;		
				
				$orders_status_history = DB::table('orders_status_history')
					->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
					->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
					->where('orders_id', '=', $orders_data->orders_id)->orderby('orders_status_history.date_added', 'DESC')->limit(1)->get()->toArray();
					
				//print_r($orders_status_history);				
				$orders[$index]->orders_status_id = '';
				$orders[$index]->orders_status = '';
				$orders[$index]->order_number = "#".$custinitials.$orders_data->customers_id.$orders_data->orders_id;
				if(!empty($orders_status_history)){
					$orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
					$orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;
				}
				$index++;
			}
    $columns = array('ID','Order Number', 'Company', 'Deliver Date', 'Delivery Address', 'Status', 'Date Purchased');

	 $location = 'publicuploads';
	$filepath = $location;	
	
	$file = fopen($filepath.'/'.'orders.csv',"w");
	fputcsv($file, $columns);
   foreach($orders as $order){
   //print_r($order);
   //die;
   $address = $order->customers_name.' '.$order->customers_company.$order->customers_street_address.' '.$order->customers_suburb.' '.$order->customers_postcode;
   $date_purchased = date('d/m/Y', strtotime($order->date_purchased));
   
    fputcsv($file, array($order->orders_id,$order->order_number, $order->customers_company, $order->delivery_date,$address,$order->orders_status,$order->date_purchased));
}

	fclose($file);
	
	}
	
	
	// CustomersCsv
	public function CustomersCsv(Request $request){
	
	$customers = DB::table('customers')
			->LeftJoin('address_book','address_book.address_book_id','=', 'customers.customers_default_address_id')
			->LeftJoin('countries','countries.countries_id','=', 'address_book.entry_country_id')
			->LeftJoin('zones','zones.zone_id','=', 'address_book.entry_zone_id')
			->LeftJoin('customers_info','customers_info.customers_info_id','=', 'customers.customers_id')
			->select('customers.*', 'address_book.entry_gender as entry_gender', 'address_book.entry_company as entry_company', 'address_book.entry_firstname as entry_firstname', 'address_book.entry_lastname as entry_lastname', 'address_book.entry_street_address as entry_street_address', 'address_book.entry_suburb as entry_suburb', 'address_book.entry_postcode as entry_postcode', 'address_book.entry_city as entry_city', 'address_book.entry_state as entry_state', 'countries.*', 'zones.*')
			->orderBy('customers.customers_id','ASC')
			->paginate(15);
			
	$filename = "customers_csv.csv";		
	$fp = fopen('php://output', 'w');		
    $columns = array('Sr.no','ID','Name', 'Email', 'Telephone', 'Delivery Address');

	 $location = 'publicuploads';
	$filepath = $location;	
	
	$file = fopen($filepath.'/'.$filename,"w");
	//fputcsv($file, $columns);
	header('Content-type: application/csv');
	header('Content-Disposition: attachment; filename='.$filename);
	fputcsv($fp, $columns);
	

	$i=1;
   foreach($customers as $key=> $customer){ 
    
   
    $custName=@$customer->customers_firstname." ".@$customer->customers_lastname;
   $fullAddress = implode(",", array_filter([@$customer->entry_firstname." ".@$customer->entry_lastname,@$customer->entry_company,@$customer->entry_street_address,@$customer->entry_suburb,@$customer->entry_postcode,@$customer->countries_iso_code_3,@$customer->zone_code])) ; 
   // fputcsv($file, array($i,$customer->customers_id,$custName, $customer->email, $customer->customers_telephone, $fullAddress));
	fputcsv($fp, array($i,@$customer->customers_id,$custName, @$customer->email, @$customer->customers_telephone, @$fullAddress)); 
	
	$i++;
}


	fclose($file);
	
	}
	// CustomersCsv
	public function ProductsCsv(Request $request){
	
	 $language_id     =   1;	
	 $product = DB::table('products')
			->leftJoin('products_description','products_description.products_id','=','products.products_id')
			->leftJoin('products_to_categories','products_to_categories.products_id','=','products.products_id')
			->leftJoin('manufacturers','manufacturers.manufacturers_id','=','products.manufacturers_id')
			->leftJoin('manufacturers_info','manufacturers.manufacturers_id','=','manufacturers_info.manufacturers_id')
			->LeftJoin('specials', function ($join) {
				$join->on('specials.products_id', '=', 'products.products_id')->where('status', '=', '1');
			 })
			->select('products.*','products_description.*','manufacturers.*','manufacturers_info.manufacturers_url', 'specials.specials_id', 'specials.products_id as special_products_id', 'specials.specials_new_products_price as specials_products_price', 'specials.specials_date_added as specials_date_added', 'specials.specials_last_modified as specials_last_modified', 'specials.expires_date','products_to_categories.categories_id')
			->where('products_description.language_id','=', $language_id);
			
			$product->orderBy('products.products_id', 'DESC');
			$product->groupBy('products.products_model');
			//$product->paginate(10);
			
		 $products = $product->get();
		 
		
		 $filename = "NRG-Products-Export.csv";		
		 $fp = fopen('php://output', 'w');		
		 //$columns = array('Sr.no','Name','Quantity','Model', 'Price', 'Type','Category ID.','Description');

		 $columns = array('ItemCode','ItemName','ItemDescription','ManufacturerName','CategoryLevel1', 'CategoryLevel2', 'UnitOfMeasure','UnitOfMeasure','Spare','NRG Pricelist');
		 $location = 'publicuploads';
		 $filepath = $location;	
	
		 $file = fopen($filepath.'/'.$filename,"w");
		 //fputcsv($file, $columns);
		 header('Content-type: application/csv');
		 header('Content-Disposition: attachment; filename='.$filename);
		 fputcsv($fp, $columns);
		
		
		$i=1;
	     foreach($products as $key=> $product){ 
		 
		 //manufacturers_name
		 $products_model=$product->products_model?$product->products_model:' ';
		 $products_name=$product->products_name?$product->products_name:' ';
		 $products_description=$product->products_description?$product->products_description:' ';
		 $manufacturers_name=$product->manufacturers_name?$product->manufacturers_name:' ';
		 $unit_of_measure=$product->unit_of_measure?$product->unit_of_measure:' ';
		 $products_price=$product->products_price?$product->products_price:'0.00';
		 //get the category name
		 $categories = DB::table('categories_description')
						->select('categories_description.categories_name as categories_name')
						->where('categories_description.language_id', $language_id)
						->where('categories_description.categories_id', $product->categories_id);
			$cat_data= $categories->get()[0];
			if($cat_data){
				$catName = $cat_data->categories_name;
			} else {
				$catName = "miscellaneous";
			} 
		fputcsv($fp, array(@$products_model,$products_name, @$products_description, @$manufacturers_name,@$catName, @$catName, @$unit_of_measure,0,0,"$".@$products_price));   
		
		$i++;
		}
		fclose($file); 
		}
		
		public function test_checkout(Request $request){
		
		// $customer = Customer::find($request->customers_id);
		// echo '<pre>'.$customer->customers_id.':'.$customer->email; print_r($customer); echo '</pre>'.__FILE__.':'.__LINE__;exit;

		// add to cart
		$cart = new CartController();
		$data = $request->all();
		$products = (array)$data['products'];
		for($i=0; $i < count($products); $i++){
			$myRequest = new Request();
			$myRequest->setMethod('POST');

			$product = $products[$i];
			foreach($product as $key => $value){
				$myRequest->request->add($product);
			}

			$cart->addToCart($myRequest);
		}
		
		foreach($request->all() as $key=>$value){
			$shipping_data[$key] = $value;

			//billing address 
			if($key=='firstname'){
				$billing_data['billing_firstname'] = $value;
			}else if($key=='lastname'){
			 	$billing_data['billing_lastname'] = $value;
			}else if($key=='company'){
				$billing_data['billing_company'] = $value;
			}else if($key=='street'){
				$billing_data['billing_street'] = $value;
			}else if($key=='countries_id'){
				$billing_data['billing_countries_id'] = $value;
			}else if($key=='suburb'){
				$billing_data['billing_suburb'] = $value;
			}else if($key=='zone_id'){
				$billing_data['billing_zone_id'] = $value;
			}else if($key=='city'){
				$billing_data['billing_city'] = $value;
			}else if($key=='postcode'){
				$billing_data['billing_zip'] = $value;
			}else if($key=='delivery_date'){
				$billing_data['billing_delivery_date'] = $value;
			}else if($key=='delivery_phone'){
				$billing_data['billing_phone'] = $value;
			}else if($key=='delivery_note'){
				$billing_data['billing_delivery_note'] = $value;
			}		  
		}
		
		// set Billing Address same Shipping Address
		$billing_address = (object) $billing_data;
		$billing_address->same_billing_address = 1;
		session(['billing_address' => $billing_address]);
				
		// set Shipping Address
		$address = (object) $shipping_data;
		session(['shipping_address' => $address]);

		// payment method
		session(['payment_method' => 'cash_on_delivery']);

		// Shipping Method
		$shipping_detail = array();
		$shipping_detail['shipping_method'] = 'flateRate'; 	
		$shipping_detail['mehtod_name']     = 'Flat Rate'; 	
		$shipping_detail['shipping_price']  = '0'; 	
		session(['shipping_detail' => (object) $shipping_detail]);

		session(['order_comments' => $request->comments]);

		//echo "<pre>"; print_r($request); echo "</pre>".__FILE__.":".__LINE__;exit;
		// place order
		$order = new OrdersController();
		$orderID = $order->place_order($request);
		if($orderID){
			return response()->json(['success' => 1, 'data' => $orderID, 'msg'=> Lang::get("website.Payment has been processed successfully")], 200);
		}else{
			return response()->json(['success' => 0, 'msg' => Lang::get("website.Error while placing order")], 401);
		}
	}
	

	
}