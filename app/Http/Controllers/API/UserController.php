<?php
namespace App\Http\Controllers\API;

use App\User;
use Socialite;
//use Mail;
//validator is builtin class in laravel
use Validator;
use Services;
use File; 

use Illuminate\Contracts\Auth\Authenticatable;
use Hash;
use DB;


//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;


//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
//for Carbon a value 
use Carbon;
use Illuminate\Support\Facades\Redirect;
use Session;
use Lang;

//email
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Web\AlertController;
use App\Notifications\PasswordReset;
use App\Http\Controllers\API\ShippingController;
use App\Http\Controllers\API\WishlistController;

class UserController extends Controller 
{
    public $successStatus = 200;
    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function saleLogin(Request $request){
        //check authentication of email and password
        $adminInfo = array("email" => $request->email, "password" => $request->password);
        if(auth()->guard('admin')->attempt($adminInfo)) {
            $admin = auth()->guard('admin')->user();
            $adminType = auth()->guard('admin')->user()->adminType;
            $administrators = DB::table('administrators')->where('myid', $admin->myid)->first(); 
            $adminTypes = DB::table('admin_types')->select('admin_type_name')->where('isActive', 1)->where('admin_type_id', $adminType)->first();
            $administrators->adminTypeName = $adminTypes->admin_type_name;
            return response()->json(['error'=> 'Login success'], 200); 
        }else{ 
            return response()->json(['error'=>Lang::get("website.Email or password is incorrect")], 401); 
        } 
    }

    public function login(Request $request){ 
        $uInfo = array("email" => $request->email, "password" => $request->password);

        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $old_session = Session::getId();        
            $result = array();

            $customer = Auth::user(); 
            $success['token'] =  $customer->createToken('MyApp')->accessToken; 
            $success['user'] =  $customer; 
            $success['user']->adminType = '-1';
            $success['user']->adminTypeName = 'Customer';

            //$customer = auth()->guard('customer')->user();
                
            //set session               
            session(['customers_id' => $customer->customers_id]);
            
            //cart              
            $cart = DB::table('customers_basket')->where([
                ['session_id', '=', $old_session],
            ])->get();
            
            if(count($cart)>0){                 
                foreach($cart as $cart_data){                       
                    $exist = DB::table('customers_basket')->where([
                        ['customers_id', '=', $customer->customers_id],
                        ['products_id', '=', $cart_data->products_id],
                        ['is_order', '=', '0'],
                    ])->delete();
                }                                   
            }
            
            DB::table('customers_basket')->where('session_id','=', $old_session)->update([
                'customers_id'  =>  $customer->customers_id
                ]);

            DB::table('customers_basket_attributes')->where('session_id','=', $old_session)->update([
                'customers_id'  =>  $customer->customers_id
                ]);
            
            
            //insert device id
            if(!empty(session('device_id'))){                   
                $deviceID = session('device_id');
                if($deviceID == '' or $deviceID == null or $deviceID === null){
                    $deviceID = '';
                }
                DB::table('devices')->where('device_id', $deviceID)->update(['customers_id'  =>  $customer->customers_id]);      
            }
                    
            //$result['customers'] = DB::table('customers')->where('customers_id', $customer->customers_id)->get();

            // Insert Device ID
            $deviceID = request('device_id');
            $deviceType = request('device_type');
            if($deviceID == '' or $deviceID == null or $deviceID === null){
                $deviceID = '';
            }if($deviceType == '' or $deviceType == null or $deviceType === null){
                $deviceType = '';
            }
            $device_data = array(
                'device_id'     => $deviceID,
                'customers_id'  => $customer->customers_id,
                'device_type'   => $deviceType,
                'register_date' => time(),
                'update_date'   => time(),
                'status'        => '1',
                'ram'           =>  '',
                'processor'     => '',
                'device_os'     => request('device_type') == 1 ? 'ios' : 'android',
                'location'      => '',
                'device_model'  => '',
                'is_notify'     => '1',
                'fcm'           => '1',
                'onesignal'     => '1',
                'manufacturer'  => '',
            );

            $device_id = DB::table('devices')
                ->where('customers_id','=', $customer->customers_id)
                ->where('device_type', request('device_type'))
                ->get();
        
            if(count($device_id)>0){            
                //$dataexist = DB::table('devices')->where('device_id','=', $request->device_id)->where('customers_id','==', '0')->get();
                DB::table('devices')
                    ->where('customers_id', $customer->customers_id)
                    ->where('device_type', request('device_type'))
                    ->update($device_data);         
            }
            else{
                $device_id = DB::table('devices')->insertGetId($device_data);   
            }   
            $success['result'] =  $result;

            // get Shipping Address
            $shippingRequest = new \Illuminate\Http\Request();
            $shippingRequest->setMethod('POST');
            $shippingRequest->request->add(['address_id' => '']);
            $shipping = new ShippingController();
            $shippingAddress = $shipping->getShippingAddress($shippingRequest, $customer->customers_id);
            $success['shippingaddress'] = json_decode($shippingAddress->getContent());

            // get Wishlist
            $wishlist = DB::table('liked_products')
                ->select('products.products_id', 'products.products_slug', 'products.products_image', 'products.products_model', 'products.products_price', 'products.products_status', 'products_description.products_name')
                ->join('products_description', 'products_description.products_id', '=', 'liked_products.liked_products_id')
                ->join('products', 'products.products_id', '=', 'liked_products.liked_products_id')
                ->where([
                'liked_customers_id' => $customer->customers_id
                ])->get();
            $success['wishlist'] = $wishlist;

            //echo "<pre>"; print_r($result); echo "</pre>".__FILE__.":".__LINE__;
            return response()->json(['success' => $success], $this->successStatus); 
        }elseif(auth()->guard('admin')->attempt($uInfo)) {
            $admin = auth()->guard('admin')->user();
            $success['token'] =  $admin->createToken('MyApp')->accessToken; 

            $adminType = auth()->guard('admin')->user()->adminType;
            $administrators = DB::table('administrators')->where('myid', $admin->myid)->first(); 
            $adminTypes = DB::table('admin_types')->select('admin_type_name')->where('isActive', 1)->where('admin_type_id', $adminType)->first();
            $administrators->adminTypeName = $adminTypes->admin_type_name;

            // set same customers
            $administrators->customers_id = $admin->myid;
            $administrators->customers_gender = '';
            $administrators->customers_firstname = $admin->first_name;
            $administrators->customers_lastname = $admin->last_name;
            $administrators->customers_dob = '';
            $administrators->customers_default_address_id = '';
            $administrators->customers_telephone = '';
            $administrators->customers_fax = '';
            $administrators->customers_newsletter = '';
            $administrators->isActive = '';
            $administrators->fb_id = '';
            $administrators->google_id = '';
            $administrators->customers_picture = '';
            $administrators->created_at = '';
            $administrators->updated_at = '';
            $administrators->is_seen = '';
            $administrators->company = '';
            $administrators->billing_firstname = '';
            $administrators->billing_lastname = '';
            $administrators->billing_street_address = '';
            $administrators->billing_suburb = '';
            $administrators->billing_postcode = '';
            $administrators->billing_city = '';
            $administrators->billing_state = '';
            $administrators->shipping_firstname = '';
            $administrators->shipping_lastname = '';
            $administrators->shipping_street_address = '';
            $administrators->shipping_suburb = '';
            $administrators->shipping_postcode = '';
            $administrators->shipping_city = '';
            $administrators->shipping_state = '';
            $administrators->shipping_company = '';
            $administrators->billing_phone = '';
            $administrators->billing_email = '';
            $administrators->first_time = '';

            //echo "<pre>"; print_r($administrators); echo "</pre>".__FILE__.":".__LINE__; 

            $success['user'] =  $administrators;
            return response()->json(['success' => $success], $this->successStatus); 
        }else{ 
            return response()->json(['error'=>Lang::get("website.Email or password is incorrect")], 401); 
        } 
    }

    // customer list
    public function customers(Request $request){
        $results = DB::table('customers')->get();
        return response()->json(['results' => $results], $this->successStatus); 
    }

    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request) { 
        $old_session = Session::getId();
        
        $firstName = $request->firstName;
        $lastName = $request->lastName;
        $gender = $request->gender;
        $email = $request->email;
        $password = $request->password;
        $re_password = $request->re_password;
        //$token = $request->token;
        $date = date('y-md h:i:s');
        
        $extensions = array('gif','jpg','jpeg','png');
        if($request->hasFile('picture') and in_array($request->picture->extension(), $extensions)){
            $image = $request->picture;
            $fileName = time().'.'.$image->getClientOriginalName();
            $image->move('resources/assets/images/user_profile/', $fileName);
            $profile_photo = 'resources/assets/images/user_profile/'.$fileName; 
        }   else{
            $profile_photo = 'resources/assets/images/user_profile/default_user.png';
        }   
        
//      //validation start
        $validator = Validator::make(
            array(
                'firstName' => $request->firstName,
                'lastName' => $request->lastName,
                //'customers_gender' => $request->gender,
                'email' => $request->email,
                'password' => $request->password,
                're_password' => $request->re_password,
                
            ),array(
                'firstName' => 'required ',
                'lastName'  => 'required',
                //'customers_gender'  => 'required',
                'email'     => 'required | email',
                'password'  => 'required',
                're_password' => 'required | same:password',
            )
        );
        if($validator->fails()){
            //return redirect('signup')->withErrors($validator)->withInput();
            return response()->json(['error'=>$validator->errors()], 401);  
        }else{
            
            //echo "Value is completed";
            $data = array(
                'customers_firstname' => $request->firstName,
                'customers_lastname'  => $request->lastName,
                'company'             => $request->company,
                'customers_telephone' => $request->telephone,
                //'customers_gender'  => $request->gender,
                'email'               => $request->email,
                'password'            => Hash::make($password),
                'customers_picture'   =>  $profile_photo,
                'created_at'          => $date,
                'updated_at'          => $date,
            );
            
            
            //eheck email already exit
            $user_email = DB::table('customers')->select('email')->where('email', $email)->get();   
            if(count($user_email)>0){
                //return redirect('/signup')->withInput($request->input())->with('error', Lang::get("website.Email already exist"));
                return response()->json(['error'=>Lang::get("website.Email already exist")], 401);  
            }else{
                if(DB::table('customers')->insert($data)){                  
                    
                    //check authentication of email and password
                    $customerInfo = array("email" => $request->email, "password" => $request->password);
                                        
                    if(Auth::attempt($customerInfo)) {
                        $customer = Auth::user();
                        
                        //set session
                        session(['customers_id' => $customer->customers_id]);

                        //cart 
                        $cart = DB::table('customers_basket')->where([
                            ['session_id', '=', $old_session],
                        ])->get();

                        if(count($cart)>0){
                            foreach($cart as $cart_data){
                                $exist = DB::table('customers_basket')->where([
                                    ['customers_id', '=', $customer->customers_id],
                                    ['products_id', '=', $cart_data->products_id],
                                    ['is_order', '=', '0'],
                                ])->delete();
                            }
                        }

                        DB::table('customers_basket')->where('session_id','=', $old_session)->update([
                            'customers_id'  =>  $customer->customers_id
                            ]);

                        DB::table('customers_basket_attributes')->where('session_id','=', $old_session)->update([
                            'customers_id'  =>  $customer->customers_id
                            ]);

                        //insert device id
                        if(!empty(session('device_id'))){                   
                            DB::table('devices')->where('device_id', session('device_id'))->update(['customers_id'  =>  $customer->customers_id]);      
                        }
                        
                        $customers = DB::table('customers')->where('customers_id', $customer->customers_id)->get();
                        $result['customers'] = $customers;
                        //email and notification            
                        $myVar = new AlertController();
                        $alertSetting = $myVar->createUserAlert($customers);
                        
                        //return redirect()->intended('/')->with('result', $result);

                        $success['token'] =  $customer->createToken('MyApp')->accessToken; 
                        $success['result'] =  $result;
                        return response()->json(['success'=>$success], $this->successStatus); 

                    }else{
                        //return redirect('login')->with('loginError', Lang::get("website.Email or password is incorrect"));
                        return response()->json(['error'=> Lang::get("website.Email or password is incorrect")], 401); 
                    }

                    
                }else{
                    //return redirect('/signup')->with('error', Lang::get("website.something is wrong"));
                    return response()->json(['error'=> Lang::get("website.something is wrong")], 401); 
                }
            }       
            
        }

        /******************/
        /*$validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        $input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user = User::create($input); 
        $success['token'] =  $user->createToken('MyApp')->accessToken; 
        $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this->successStatus); */
    }


    public function changePassword(Request $request) { 
        $customers_id = auth()->guard('api')->id();
        $customer = auth()->guard('api')->user();

        $old_session = Session::getId();  
        $new_password                               =   $request->new_password;
        $old_password                               =   $request->old_password;
        //$customers_email_address                  =   $request->customers_email_address;
        $updated_at                                 =   date('y-m-d h:i:s');    
        $customers_info_date_account_last_modified  =   date('y-m-d h:i:s');    
        
        if (Hash::check($old_password, $customer->password)) { 
        
            $customer_data = array(
                'password'          =>  bcrypt($new_password),
                'updated_at'        =>  date('y-m-d h:i:s'),
            );
            
            $userData = DB::table('customers')->where('customers_id', $customers_id)->update($customer_data);

            DB::table('customers_info')->where('customers_info_id', $customers_id)->update(['customers_info_date_account_last_modified'   =>   $customers_info_date_account_last_modified]);

            $message = Lang::get("website.Password has been updated successfully");
            $success = 1;
        } else {
            $message = 'Password does not match';
            $success = 0;
        }

        return response()->json(['success' => $success, 'message' => $message], $this->successStatus); 
    } 

    
    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    } 

    /** 
     * Update User api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function updateUser(Request $request) { 
        $customers_id                               =   auth()->guard('api')->user()->customers_id; 
        $customers_firstname                        =   $request->firstname;
        $customers_lastname                         =   $request->lastname;           
        //$customers_email_address                  =   $request->customers_email_address;  
        $customers_fax                              =   $request->fax;    
        $customers_telephone                        =   $request->telephone;  
        $customers_company                          =   $request->company;  
        $customers_gender                           =   $request->gender; 
        $customers_dob                              =   $request->dob;
        $customers_info_date_account_last_modified  =   date('y-m-d h:i:s');
        
        $customer_data = array(
            'customers_firstname'            =>  $customers_firstname,
            'customers_lastname'             =>  $customers_lastname,
            'customers_fax'                  =>  $customers_fax,
            'customers_telephone'            =>  $customers_telephone,
            'company'                        =>  $customers_company,
            'customers_gender'               =>  $customers_gender,
            'customers_dob'                  =>  $customers_dob
        );
                    
        //update into customer
        DB::table('customers')->where('customers_id', $customers_id)->update($customer_data);
                
        DB::table('customers_info')->where('customers_info_id', $customers_id)->update(['customers_info_date_account_last_modified'   => $customers_info_date_account_last_modified]);  

        return response()->json(['success' => Lang::get("website.Prfile has been updated successfully")], $this->successStatus); 
    } 
	
	/* =============================New implementations March 02,2020=======================================*/
	

    /** 
     * Forgot Password
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function forgotPassWord(Request $request) { 
        $password = substr(md5(uniqid(mt_rand(), true)) , 0, 8);
        
        $email            =   $request->email;
        $postData = array();
                
        //check email exist
        $existUser = DB::table('customers')->where('email', $email)->get();           
        if(count($existUser)>0){
            DB::table('customers')->where('email', $email)->update([
                    'password'  =>  Hash::make($password)
                    ]);
            
            $existUser[0]->password = $password;
			$data = array('username'=>$email,'password'=>$password);
			//print_r($data);

            Mail::send('emails.password',$data, function($message) use ($data) {
            $message->to($data['username'], 'Receiver Name')

                    ->subject('User New Password');
             });

            //echo $password;
            
            $myVar = new AlertController();
            $alertSetting = $myVar->forgotPasswordAlert($existUser);
           
            return response()->json(['success' => Lang::get("website.Password has been sent to your email address")], $this->successStatus); 
        }else{  
            return response()->json(['error' => Lang::get("website.Email address does not exist")], 401); 
        }

        
    } 
	
	/** 
     * Server Time
     * 
     * @return \Illuminate\Http\Response 
     */ 
	public function serverTime(Request $request){
	
	if($request->customer_id)
	{
	$customer_addresses = DB::table('address_book')
			->leftJoin('zones', 'zones.zone_id', '=', 'address_book.entry_zone_id')
			->leftJoin('countries', 'countries.countries_id', '=', 'address_book.entry_country_id')
			->where('customers_id', '=', $request->customer_id)->get();
	foreach($customer_addresses as $customer_addresses){
	$zone_name = $customer_addresses->zone_name;
	if($zone_name == 'New South Wales'){
	$timezone = 'Australia/Sydney';
	}
	elseif($zone_name == 'Victoria'){
	$timezone = 'Australia/Sydney';
	}
	elseif($zone_name == 'Queensland'){
	$timezone = 'Australia/Brisbane';
	}
	elseif($zone_name == 'Tasmania'){
	$timezone = 'Australia/Hobart';
	}
	elseif($zone_name == 'South Australia'){
	$timezone = 'Australia/Adelaide';
	}
	elseif($zone_name == 'Western Australia'){
	$timezone = 'Australia/Perth';
	}
	elseif($zone_name == 'Northern Territory'){
	$timezone = 'Australia/Darwin';
	}
	elseif($zone_name == 'Australian Capital Territory'){
	$timezone = 'Australia/Canberra';
	}
	else {
	
	}
    date_default_timezone_set($timezone);
    $timeZone= date_default_timezone_get();
	$currentTime = date( 'd-m-Y h:i:s A', time () );
    //echo $currentTime;
	}
	}
	else{
		
	 date_default_timezone_set("Australia/Sydney");
	 $timeZone= date_default_timezone_get();
	 
	}
	 return response()->json(['success' => 1, 'timezone' => $timeZone,'time'=>$currentTime], $this->successStatus);

	
	
	}
	
	
}