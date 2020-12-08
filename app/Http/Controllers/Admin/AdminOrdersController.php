<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/

*/
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;


use Validator;

//to send an email use Mail class in laravel
use Mail;
use App\User;

use App;
use Lang;

use DB;
//for password encryption or hash protected

use Hash;
use App\Administrator;

//for authenitcate login data
use Auth;
//for redirect
use Illuminate\Support\Facades\Redirect;


//for requesting a value 
use Illuminate\Http\Request;

class AdminOrdersController extends Controller
{
	//add listingOrders
	public function orders(){
		if(session('orders_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
				
			$title = array('pageTitle' => Lang::get("labels.ListingOrders"));
			//$language_id            				=   $request->language_id;
			$language_id            				=   '1';			
			
			$message = array();
			$errorMessage = array();
			
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
				->paginate(10);	
			
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
				$orders[$index]->order_number = $custinitials.$orders_data->customers_id.$orders_data->orders_id;
				if(!empty($orders_status_history)){
					$orders[$index]->orders_status_id = $orders_status_history[0]->orders_status_id;
					$orders[$index]->orders_status = $orders_status_history[0]->orders_status_name;
				}
				$index++;
			}
			//exit;
			
			$ordersData['message'] = $message;
			$ordersData['errorMessage'] = $errorMessage;
			$ordersData['orders'] = $orders;
			$ordersData['total_order'] = $this->getTotal();

			//echo "<!-- Ducnb <pre>"; print_r($ordersData); echo "</pre>".__FILE__.":".__LINE__."-->";
			
			//get function from other controller
			$myVar = new AdminSiteSettingController();
			$ordersData['currency'] = $myVar->getSetting();
			
			return view("admin.orders",$title)->with('listingOrders', $ordersData);
		}
	}

	public function getTotal(){
		$orders = DB::table('orders')
			->orderBy('date_purchased','DESC')
			->where('customers_id','!=','')
			->where('action','!=','0')
			->get();	
		
		$index = 0;
		$pending = $cancelled = $trash = $return = $completed = 0;
		foreach($orders as $orders_data){
			$orders_products = DB::table('orders_products')
				->select('final_price', DB::raw('SUM(final_price) as total_price'))
				->where('orders_id', '=' ,$orders_data->orders_id)
				->get();
					
			
			$orders_status_history = DB::table('orders_status_history')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=', 'orders_status_history.orders_status_id')
				->select('orders_status.orders_status_name', 'orders_status.orders_status_id')
				->where('orders_id', '=', $orders_data->orders_id)->orderby('orders_status_history.date_added', 'DESC')->limit(1)->get()->toArray();				
			
			if(!empty($orders_status_history)){
				if($orders_status_history[0]->orders_status_id == 1){
					$pending++;
				}
				if($orders_status_history[0]->orders_status_id == 2){
					$completed++;
				}
				if($orders_status_history[0]->orders_status_id == 3){
					$cancelled++;
				}
				if($orders_status_history[0]->orders_status_id == 4){
					$return++;
				}				
			}
		}

		$trashOrders = DB::table('orders')
			->orderBy('date_purchased','DESC')
			->where('customers_id','!=','')
			->where('action','=','0')
			->get();	
		
		$ordersData['total_orders']    = count($orders);
		$ordersData['total_pending']   = $pending;
		$ordersData['total_completed'] = $completed;
		$ordersData['total_cancelled'] = $cancelled;
		$ordersData['total_return']    = $return;
		$ordersData['total_trash']     = count($trashOrders);

		return $ordersData;
	}
	
	
	//view order detail
	public function vieworder(Request $request){
		if(session('orders_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.ViewOrder"));
		$language_id             =   '1';	
		$orders_id        	 	 =   $request->id;			
		
		$message = array();
		$errorMessage = array();
		
		DB::table('orders')->where('orders_id', '=', $orders_id)
			->where('customers_id','!=','')->update(['is_seen' => 1 ]);
		
		$order = DB::table('orders')
				->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
				->LeftJoin('orders_images', 'orders.orders_id', '=' ,'orders_images.order_id')
				->select('orders.*', 'orders_status_history.*', 'orders_status.*', 'orders_images.image as orders_image')
				->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();
			
		foreach($order as $data){
			$orders_id	 = $data->orders_id;
			
			$orders_products = DB::table('orders_products')
				->join('products', 'products.products_id','=', 'orders_products.products_id')
				->select('orders_products.*', 'products.products_image as image,products.products_model as sku')
				->where('orders_products.orders_id', '=', $orders_id)->get();
				$i = 0;
				$total_price  = 0;
				$total_tax	  = 0;
				$product = array();
				$subtotal = 0;
				foreach($orders_products as $orders_products_data){
					$product_attribute = DB::table('orders_products_attributes')
						->where([
							['orders_products_id', '=', $orders_products_data->orders_products_id],
							['orders_id', '=', $orders_products_data->orders_id],
						])
						->get();
						//get the sku of each product
				$product_sku = DB::table('products')
				->select('products.products_model as sku')
			    ->where('products.products_id', '=', $orders_products_data->products_id)->get();
				
						$orders_products_data->sku = $product_sku[0]->sku;
					$orders_products_data->attribute = $product_attribute;
					
					$product[$i] = $orders_products_data;
					$total_price = $total_price+$orders_products[$i]->final_price;
					
					$subtotal += $orders_products[$i]->final_price;
					
					$i++;
				}
			$data->data = $product;
			$orders_data[] = $data;
		}
		
		$orders_status_history = DB::table('orders_status_history')
			->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
			->orderBy('orders_status_history.date_added', 'desc')
			->where('orders_id', '=', $orders_id)->get();
				
		$orders_status = DB::table('orders_status')->get();
				
		$ordersData['message'] 					=	$message;
		$ordersData['errorMessage']				=	$errorMessage;
		$ordersData['orders_data']		 	 	=	$orders_data; 
		$ordersData['total_price']  			=	$total_price;
		$ordersData['orders_status']			=	$orders_status;
		$ordersData['orders_status_history']    =	$orders_status_history;
		$ordersData['subtotal']    				=	$subtotal;
				
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$ordersData['currency'] = $myVar->getSetting();
		
		return view("admin.vieworder", $title)->with('data', $ordersData);
		}
	}

	public function bulkUpdateOrders(Request $request){
		if(isset($request->bulk_action)){
			$action = $request->bulk_action;
			$ids = $request->orders;
			if(!empty($ids)){
				foreach($ids as $id){
					if($action == 'trash'){
						DB::table('orders')
						->where('orders_id', '=', $id)
						->update(['action' => 0]);
					}
					if($action == 'restore'){
						DB::table('orders')
						->where('orders_id', '=', $id)
						->update(['action' => 1]);
					}
					if($action == 'delete'){
						DB::table('orders')
						->where('orders_id', '=', $id)
						->delete();
					}
					if($action == 'pending' or $action == 'cancelled' or $action == 'completed' or $action == 'returned'){
						//orders status history
						if($action == 'pending'){
							$orders_status = 1;
						}elseif($action == 'cancelled'){
							$orders_status = 3;
						}elseif($action == 'completed'){
							$orders_status = 2;
						}elseif($action == 'returned'){
							$orders_status = 4;
						}

						$orders_history_id = DB::table('orders_status_history')->insertGetId(
							[	 'orders_id'  => $id,
								 'orders_status_id' => $orders_status,
								 'date_added'  => date('Y-m-d h:i:s'),
								 'customer_notified' =>'1',
								 'comments'  =>  ''
							]);

						if($action == 'completed'){				
							$orders_products = DB::table('orders_products')->where('orders_id', '=', $id)->get();
							foreach($orders_products as $products_data){
								DB::table('products')
								->where('products_id', $products_data->products_id)
								->update([
									'products_quantity' => DB::raw('products_quantity - "'.$products_data->products_quantity.'"'),
									'products_ordered'  => DB::raw('products_ordered + 1')
								]);
							}
						}


						$orders = DB::table('orders')
							->where('orders_id', '=', $id)
							->where('customers_id','!=','')->get();
						
						$data = array();
						$data['customers_id'] = $orders[0]->customers_id;
						$data['orders_id'] = $id;
						$data['status'] = $action;
						
						//notify user		
						$myVar = new AdminAlertController();
						$myVar->orderStatusChange($data);
					}
				}
				if($action == 'trash'){
					$msg = 'Your order moved to trash.';
				}elseif($action == 'restore'){
					$msg = 'Your order restored.';
				}elseif($action == 'delete'){
					$msg = 'Your order deleted.';
				}elseif($action == 'processing' or $action == 'cancelled' or $action == 'completed' or $action == 'returned'){
					$msg = 'Your order chang to "'.$action.'"';
				}
				return redirect()->back()->with('success', $msg);
			}else{
				return redirect()->back()->with('error', 'You must select order');
			}
		}
	}
	
	
	//update order
	public function updateOrder(Request $request){
		if(session('orders_confirm')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{			
			$orders_status     =	 $request->orders_status;
			$comments          =	 $request->comments;
			$orders_id         = 	 $request->orders_id;
			$old_orders_status = 	 $request->old_orders_status;
			$date_added        =    date('Y-m-d h:i:s');

			//get function from other controller
			$myVar = new AdminSiteSettingController();
			$setting = $myVar->getSetting();

			$status = DB::table('orders_status')->where('orders_status_id', '=', $orders_status)->get();


			if($old_orders_status==$orders_status){
			 	return redirect()->back()->with('error', Lang::get("labels.StatusChangeError"));
			}else{

			//orders status history
			$orders_history_id = DB::table('orders_status_history')->insertGetId(
			[	 'orders_id'  => $orders_id,
				 'orders_status_id' => $orders_status,
				 'date_added'  => $date_added,
				 'customer_notified' =>'1',
				 'comments'  =>  $comments
			]);
		
			if($orders_status=='2'){
				
				$orders_products = DB::table('orders_products')->where('orders_id', '=', $orders_id)->get();
				
				foreach($orders_products as $products_data){
					DB::table('products')->where('products_id', $products_data->products_id)->update([
						'products_quantity' => DB::raw('products_quantity - "'.$products_data->products_quantity.'"'),
						'products_ordered'  => DB::raw('products_ordered + 1')
						]);
				}
			}
		
		$orders = DB::table('orders')->where('orders_id', '=', $orders_id)
			->where('customers_id','!=','')->get();
		
		$data = array();
		$data['customers_id'] = $orders[0]->customers_id;
		$data['orders_id'] = $orders_id;
		$data['status'] = $status[0]->orders_status_name;
		
		//notify user		
		$myVar = new AdminAlertController();
		$myVar->orderStatusChange($data);
						
		return redirect()->back()->with('message', Lang::get("labels.OrderStatusChangedMessage"));
		 }
		
		}
		
	}
	
	//deleteorders
	public function deleteOrder(Request $request){
		DB::table('orders')->where('orders_id', $request->orders_id)->delete();
		DB::table('orders_products')->where('orders_id', $request->orders_id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.OrderDeletedMessage")]);
	}
	
	//view order detail
	public function invoiceprint(Request $request){
					
		$title = array('pageTitle' => Lang::get("labels.ViewOrder"));
		$language_id             =   '1';	
		$orders_id        	 	 =   $request->id;			
		
		$message = array();
		$errorMessage = array();
		
		DB::table('orders')->where('orders_id', '=', $orders_id)
			->where('customers_id','!=','')->update(['is_seen' => 1 ]);
		
		$order = DB::table('orders')
				->LeftJoin('orders_status_history', 'orders_status_history.orders_id', '=', 'orders.orders_id')
				->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
				->where('orders.orders_id', '=', $orders_id)->orderby('orders_status_history.date_added', 'DESC')->get();
			
		
		foreach($order as $data){
			$orders_id	 = $data->orders_id;
			
			$orders_products = DB::table('orders_products')
				->join('products', 'products.products_id','=', 'orders_products.products_id')
				->select('orders_products.*', 'products.products_image as image')
				->where('orders_products.orders_id', '=', $orders_id)->get();
				$i = 0;
				$total_price  = 0;
				$total_tax	  = 0;
				$product = array();
				$subtotal = 0;
				foreach($orders_products as $orders_products_data){
					
					//categories
					$categories = DB::table('products_to_categories')
									->leftjoin('categories','categories.categories_id','products_to_categories.categories_id')
									->leftjoin('categories_description','categories_description.categories_id','products_to_categories.categories_id')
									->select('categories.categories_id','categories_description.categories_name','categories.categories_image','categories.categories_icon', 'categories.parent_id')
									->where('products_id','=', $orders_products_data->orders_products_id)
									->where('categories_description.language_id','=',$language_id)->get();		
					
					$orders_products_data->categories =  $categories;
				
					$product_attribute = DB::table('orders_products_attributes')
						->where([
							['orders_products_id', '=', $orders_products_data->orders_products_id],
							['orders_id', '=', $orders_products_data->orders_id],
						])
						->get();
					//get the sku of each product
					$product_sku = DB::table('products')
					->select('products.products_model as sku')
					->where('products.products_id', '=', $orders_products_data->products_id)->get();
				
						$orders_products_data->sku = $product_sku[0]->sku;	
					$orders_products_data->attribute = $product_attribute;
					$product[$i] = $orders_products_data;
					$total_price = $total_price+$orders_products[$i]->final_price;
					
					$subtotal += $orders_products[$i]->final_price;
					
					$i++;
				}
			$data->data = $product;
			$orders_data[] = $data;
		}
		
		$orders_status_history = DB::table('orders_status_history')
			->LeftJoin('orders_status', 'orders_status.orders_status_id', '=' ,'orders_status_history.orders_status_id')
			->orderBy('orders_status_history.date_added', 'desc')
			->where('orders_id', '=', $orders_id)->get();
				
		$orders_status = DB::table('orders_status')->get();
				
		$ordersData['message'] 					=	$message;
		$ordersData['errorMessage']				=	$errorMessage;
		$ordersData['orders_data']		 	 	=	$orders_data;
		$ordersData['total_price']  			=	$total_price;
		$ordersData['orders_status']			=	$orders_status;
		$ordersData['orders_status_history']    =	$orders_status_history;
		$ordersData['subtotal']    				=	$subtotal;
		
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$ordersData['currency'] = $myVar->getSetting();
		
		return view("admin.invoiceprint", $title)->with('data', $ordersData);
		
	}
	
}
