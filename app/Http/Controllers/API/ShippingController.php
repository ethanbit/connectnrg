<?php 
namespace App\Http\Controllers\API;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Auth;
use DB;

class ShippingController extends Controller {
	public $successStatus = 200;

	public function getShippingAddress(Request $request, $customers_id = ''){
		$address_id = $request->address_id;

		if($customers_id == ''){
			if(Auth::guard('api')->check()){
				$customers_id = auth()->guard('api')->user()->customers_id;
			}elseif(Auth::guard('apiadmin')->check()){
				$customers_id = $request->customers_id;
			}
		}

		$addresses = DB::table('address_book')
					->leftJoin('countries', 'countries.countries_id', '=' ,'address_book.entry_country_id')
					->leftJoin('zones', 'zones.zone_id', '=' ,'address_book.entry_zone_id')
					->leftJoin('customers', 'customers.customers_default_address_id', '=' , 'address_book.address_book_id')
					->select(
							'address_book.address_book_id as address_id',
							'address_book.entry_gender as gender',
							'address_book.entry_company as company',
							'address_book.entry_firstname as firstname',
							'address_book.entry_lastname as lastname',
							'address_book.entry_street_address as street',
							'address_book.entry_suburb as suburb',
							'address_book.entry_postcode as postcode',
							'address_book.entry_city as city',
							'address_book.entry_state as state',
							'address_book.entry_phone as phone',							
							'countries.countries_id as countries_id',
							'countries.countries_name as country_name',							
							'zones.zone_id as zone_id',
							'zones.zone_code as zone_code',
							'zones.zone_name as zone_name',
							'customers.customers_default_address_id as default_address'
							)
					->where('address_book.customers_id', $customers_id);
		
		if(!empty($address_id)){
			$addresses->where('address_book_id', '=', $address_id);
		}
		$result = $addresses->get();
		return response()->json(['success' => 1, 'data' => $result], $this->successStatus);
	}

	//update shipping address 
	public function updateShippingAddress(Request $request){
		
		$customers_id            				=   auth()->guard('api')->user()->customers_id;
		$address_book_id            			=   $request->address_id;	
		$entry_firstname            		    =   $request->firstname;
		$entry_lastname             		    =   $request->lastname;
		$entry_street_address       		    =   $request->street_address;
		$entry_suburb             				=   $request->suburb;
		$entry_postcode             			=   $request->postcode;
		$entry_city             				=   $request->city;
		//$entry_state             				=   $request->entry_state;
		$entry_country_id             			=   $request->country_id;
		$entry_zone_id             				=   $request->zone_id;	
		//$entry_gender							=   $request->entry_gender;
		//
		$entry_company							=   $request->company;
		$entry_phone							=   $request->phone;

		$customers_default_address_id			=   $request->customers_default_address_id;
							
		if(!empty($customers_id)){
		
			$address_book_data = array(
				'entry_firstname'               =>   $entry_firstname,
				'entry_lastname'                =>   $entry_lastname,
				'entry_street_address'          =>   $entry_street_address,
				'entry_suburb'             		=>   $entry_suburb,
				'entry_postcode'            	=>   $entry_postcode,
				'entry_city'             		=>   $entry_city,
				//'entry_state'            		=>   $entry_state,
				'entry_country_id'            	=>   $entry_country_id,
				'entry_zone_id'             	=>   $entry_zone_id,
				'customers_id'             		=>   $customers_id,
				//'entry_gender'					=>   $entry_gender,
				'entry_company'					=>   $entry_company,
				'entry_phone'					=>   $entry_phone
			);	
			
			//add address into address book
			DB::table('address_book')->where('address_book_id', $address_book_id)->update($address_book_data);
			
			//default address id
			if($customers_default_address_id == '1'){
				DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $address_book_id]);
			}
			return response()->json(['success' => 1, 'data' => 'Your address has been updated successfully!'], $this->successStatus);
		}else{
			return response()->json(['success' => 0, 'data' => 'Have an error when updat your shipping address!'], 401);
		}
					
	}

	//delete shipping address 
	public function deleteShippingAddress(Request $request){
		
		$customers_id    = auth()->guard('api')->user()->customers_id;
		$address_book_id = $request->address_id;	
							
		if(!empty($customers_id)){
		
			//delete address into address book
			DB::table('address_book')->where('address_book_id', $address_book_id)->delete();
			
			$defaultAddress = DB::table('customers')->where([['customers_id', $customers_id],
										 ['customers_default_address_id', $address_book_id],])->get();

			if(count($defaultAddress)>0){
				//default address id
				$getFirstAdd = DB::table('address_book')->where([['customers_id', $customers_id]])->first();
				$customers_default_address_id = $getFirstAdd->address_book_id;
				DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $customers_default_address_id]);
			}
			
			//$address_book_data = DB::table('address_book')->get();
		}

		return response()->json(['success' => 1, 'data' => 'Your address has been deleted successfully!'], $this->successStatus);
					
	}

	public function addShippingAddress(Request $request){
		$customers_id            				=   auth()->guard('api')->user()->customers_id;
		$entry_firstname            		    =   $request->firstname;
		$entry_lastname             		    =   $request->lastname;
		$entry_street_address       		    =   $request->street_address;
		$entry_suburb             				=   $request->suburb;
		$entry_postcode             			=   $request->postcode;
		$entry_city             				=   $request->city;
		//$entry_state             				=   $request->state;
		$entry_country_id             			=   $request->country_id;
		$entry_zone_id             				=   $request->zone_id;
		//$entry_gender							=   $request->gender;
		//
		$entry_company							=   $request->company;
		$entry_phone							=   $request->phone;

		$customers_default_address_id			=   $request->customers_default_address_id;
							
		if(!empty($customers_id)){		
			$address_book_data = array(
				'entry_firstname'               =>   $entry_firstname,
				'entry_lastname'                =>   $entry_lastname,
				'entry_street_address'          =>   $entry_street_address,
				'entry_suburb'             		=>   $entry_suburb,
				'entry_postcode'            	=>   $entry_postcode,
				'entry_city'             		=>   $entry_city,
				'entry_country_id'            	=>   $entry_country_id,
				'entry_zone_id'             	=>   $entry_zone_id,
				'customers_id'             		=>   $customers_id,
				//'entry_state'            		=>   $entry_state,
				//'entry_gender'					=>   $entry_gender,
				'entry_company'					=>   $entry_company,
				'entry_phone'					=>   $entry_phone
			);	
			
			//add address into address book
			$address_book_id = DB::table('address_book')->insertGetId($address_book_data);
			
			//default address id
			if($customers_default_address_id == '1'){
				DB::table('customers')->where('customers_id', $customers_id)->update(['customers_default_address_id' => $address_book_id]);
			}
		}
		
		//echo '<pre>'.print_r($result['products'], true).'</pre>';
		return response()->json(['success' => 1, 'data' => 'Your address has been added successfully!'], $this->successStatus);
	}
}