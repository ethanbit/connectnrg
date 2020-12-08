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
use App;
use Lang;
use File;
use DB;

//for password encryption or hash protected
use Hash;
use App\Administrator;

//for authenitcate login data
use Auth;

//for requesting a value 
use Illuminate\Http\Request;


class AdminNotificationController extends Controller
{
	//listingDevices
	public function devices(Request $request){
		if(session('notifications_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
		$title = array('pageTitle' => Lang::get("labels.ListingDevices"));		
			
		$result = array();
		$message = array();
		$errorMessage = array();
				
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$setting = $myVar->getSetting();
		
		if(!empty($request->id)){
			if($request->active=='no'){
				$status = '0';
			}elseif($request->active=='yes'){
				$status = '1';
			}
			
			DB::table('devices')->where('id', '=', $request->id)->update([
				'status'		 =>	  $status
				]);	
		}
		
		if(isset($request->filter) and !empty($request->filter)){
			$devices = DB::table('devices')
				->LeftJoin('customers', 'customers.customers_id','=', 'devices.customers_id')
				->where('device_type','=', $request->filter)
				->where('devices.is_notify','=', '1')
				->where('devices.'.$setting[54]->value,'=', '1')				
				->orderBy('id','DESC')
				->paginate(100);
		}else{
			$queryDevice = DB::table('devices')
				->LeftJoin('customers', 'customers.customers_id','=', 'devices.customers_id')
				->orderBy('id','DESC')
				//->where('devices.is_notify','=', '1')
				->where('devices.'.$setting[54]->value,'=', '1')	
				//->paginate(100)
				;
			//echo $queryDevice->toSql();
			$devices = $queryDevice->paginate(100);
		}
				
		$result['message'] = $message;
		$result['devices'] = $devices;
		//echo "<pre>"; print_r($result); echo "</pre>".__FILE__.":".__LINE__;
		return view("admin.devices",$title)->with('result', $result);
		}
	}
	
	//viewDevices
	public function viewdevices(Request $request){	
		if(session('notifications_view')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{	
		$title = array('pageTitle' => Lang::get("labels.ViewDevice"));
		$result = array();		
		$result['message'] = array();
		
		$devices = DB::table('devices')
			->LeftJoin('customers', 'customers.customers_id','=', 'devices.customers_id')
			->where('devices.id', $request->id)
			->get();	
		
		$result['devices'] = $devices;	
		return view("admin.viewdevices",$title)->with('result', $result);
		}
	}
	
		
	//notifyUser
	public function notifyUser(Request $request){
		if(session('notifications_send')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$device_type 	= 	$request->device_type;
		$device_id 		= 	$request->device_id;
		$message 		= 	$request->message;
		$title 			= 	$request->title;
		$pageResponse   =   0;
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$extensions = $myVar->imageType();	
		$setting = $myVar->getSetting();		
		
		$dir = 'assets/images/notification_images/';		
		if(!File::exists(resource_path($dir))) {
			$dir = File::makeDirectory(resource_path($dir), 0775, true);// path does not exist
		}
		
		if($request->hasFile('image') and in_array($request->image->extension(), $extensions)){
			$image = $request->image;
			$fileName = time().'.'.$image->getClientOriginalName();			
			$image->move('resources/assets/images/notification_images/', $fileName);
			$uploadImage = 'resources/assets/images/notification_images/'.$fileName; 			
			$websiteURL =  "http://" . $_SERVER['SERVER_NAME'] .'/demos/website/'. $uploadImage;			
		}else{
			$websiteURL = '';
		}	
		
		$sendData = array
				  (
					'body' 	=> $message,
					'title'	=> $title ,
							'icon'	=> 'myicon',/*Default Icon*/
							'sound' => 'mySound',/*Default sound*/
							'image' => $websiteURL,
				  );
				  
		if($setting[54]->value=='fcm'){
			$functionName = 'fcmNotification';	
		}elseif($setting[54]->value=='onesignal'){
			$functionName = 'onesignalNotification';
		}
		
		$response = $this->$functionName($device_id, $sendData, $pageResponse);				
		return $response;
		}
	}
	
	
	//notifications
	public function notifications(Request $request){		
		if(session('notifications_send')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
		$title = array('pageTitle' => Lang::get("labels.SendNotifications"));
		$result = array();		
		$result['message'] = array();
		$groups = DB::table('groups')->get()->toArray();
		$result['groups'] = $groups;
		return view("admin.notifications", $title)->with('result', $result);
		}
	}

	
	//sendNotification
	public function sendNotifications(Request $request){
		if(session('notifications_send')==0){
			print Lang::get("labels.You do not have to access this route");
		}else{
			
			$device_type    = 	$request->device_type;
			$group          = 	$request->group;
			$devices_status = 	$request->devices_status;
			$message        = 	$request->message;
			$title          = 	$request->title;
			$pageResponse   =	1;
			
			//get function from other controller
			$myVar = new AdminSiteSettingController();
			$extensions = $myVar->imageType();	
			$setting = $myVar->getSetting();
			
			$dir = 'assets/images/notification_images/';	
				
			if(!File::exists(resource_path($dir))) {
				$dir = File::makeDirectory(resource_path($dir), 0775, true);// path does not exist
			}
			
			
			if($request->hasFile('image') and in_array($request->image->extension(), $extensions)){
				$image = $request->image;
				$fileName = time().'.'.$image->getClientOriginalName();			
				$image->move('resources/assets/images/notification_images/', $fileName);
				$uploadImage = 'resources/assets/images/notification_images/'.$fileName; 			
				$websiteURL =  "http://" . $_SERVER['SERVER_NAME'] .'/demos/website/'. $uploadImage;			
			}else{
				$websiteURL = '';
			}	
			
			$sendData = array
					  (
					'body' 	=> $message,
					'title'	=> $title ,
							'icon'	=> 'myicon',/*Default Icon*/
							'sound' => 'mySound',/*Default sound*/
							'image' => $websiteURL,
					  );	
			
			//get function from other controller
			$myVar = new AdminSiteSettingController();
			$setting = $myVar->getSetting();

			$usersGroup = DB::table('customers_group')->where('group_id', '=', $group)->get()->toArray();
			//echo "<pre>"; print_r($usersGroup); echo "</pre>".__FILE__.":".__LINE__;exit;

			for($i=0; $i < count($usersGroup); $i++){
				$customer = $usersGroup[$i];
				$customer_id = $customer->customer_id;
				$QueryDevices = DB::table('devices')
							->where('status','=', $devices_status)
							->where('devices.'.$setting[54]->value,'=', '1')	
							->where('devices.is_notify','=', '1')
							->where('devices.customers_id','=', $customer_id)
							->where('devices.device_id','!=', '')
							//->get()
							;
				//echo $QueryDevices->toSql();
				$devices = $QueryDevices->get();
				//echo "<pre>".count($devices).'-'.$devices_status.'-'.$customer_id.'-'; print_r($devices); echo "</pre>".__FILE__.":".__LINE__;

				if($setting[54]->value=='fcm'){
					$functionName = 'fcmNotification';	
				}elseif($setting[54]->value=='onesignal'){
					$functionName = 'onesignalNotification';
				}

				if(count($devices)>0){
					foreach($devices as $devices_data){	
						$response[] = $this->$functionName($devices_data->device_id, $sendData, $pageResponse);
					}
				}else{
					$response[] = '2';
				}
			}

			//exit;
					
			/*if($device_type =='all'){ 	///* to all users notification
				
				$devices = DB::table('devices')
							->where('status','=', $devices_status)
							->where('devices.'.$setting[54]->value,'=', '1')	
							->where('devices.is_notify','=', '1')
							->get();
				if($setting[54]->value=='fcm'){
					$functionName = 'fcmNotification';	
				}elseif($setting[54]->value=='onesignal'){
					$functionName = 'onesignalNotification';
				}
				if(count($devices)>0){
					foreach($devices as $devices_data){	
						$response[] = $this->$functionName($devices_data->device_id, $sendData, $pageResponse);
					}
				}else{
					$response[] = '2';
				}
						
				
			}else if($device_type =='1'){    ///* apple notification			
				
				$devices = DB::table('devices')
								->select('devices.device_id')
								->where('status','=', $devices_status)
								->where('devices.is_notify','=', '1')
								->where('devices.'.$setting[54]->value,'=', '1')	
								->where('device_type','=', $device_type)
								->get();
				if($setting[54]->value=='fcm'){
					$functionName = 'fcmNotification';	
				}elseif($setting[54]->value=='onesignal'){
					$functionName = 'onesignalNotification';
				}
				if(count($devices)>0){
					foreach($devices as $devices_data){				
						$response[] = $this->$functionName($devices_data->device_id, $sendData, $pageResponse);
					}
				}else{
					$response[] = '2';
				}
				
			}else if($device_type =='2'){ 	///* android notification
			
				$devices = DB::table('devices')
								->select('devices.device_id')
								->where('status','=', $devices_status)
								->where('devices.is_notify','=', '1')
								->where('devices.'.$setting[54]->value,'=', '1')	
								->where('device_type','=', $device_type)
								->get();
				
				if($setting[54]->value=='fcm'){
					$functionName = 'fcmNotification';	
				}elseif($setting[54]->value=='onesignal'){
					$functionName = 'onesignalNotification';
				}
				if(count($devices)>0){	
					foreach($devices as $devices_data){
						$response[] = $this->$functionName($devices_data->device_id, $sendData, $pageResponse);
					}
				}else{
					$response[] = '2';
				}			
							
			}else if($device_type =='3'){ 	///* android notification 
			
				$devices = DB::table('devices')
								->select('devices.device_id')
								->where('status','=', $devices_status)
								->where('devices.is_notify','=', '1')
								->where('devices.'.$setting[54]->value,'=', '1')	
								->where('device_type','=', $device_type)
								->get();
				
				if($setting[54]->value=='fcm'){
					$functionName = 'fcmNotification';	
				}elseif($setting[54]->value=='onesignal'){
					$functionName = 'onesignalNotification';
				}
				if(count($devices)>0){	
					foreach($devices as $devices_data){
						$response[] = $this->$functionName($devices_data->device_id, $sendData, $pageResponse);
					}
				}else{
					$response[] = '2';
				}			
							
			}*/
			
			if(in_array('1', $response)){
				$message = 'sent';
			}elseif(in_array('2', $response)){
				$message = 'empty';	
			}else{
				$message = 'error';	
			}
			
			if ($message == 'sent'){	
				return redirect()->back()->withErrors([Lang::get("labels.notificationSendMessage")]);
			}elseif ($message == 'error'){	
				return redirect()->back()->withErrors([Lang::get("labels.notificationSendMessageError")]);
			}elseif ($message == 'empty'){	
				return redirect()->back()->withErrors([Lang::get("labels.notificationSendMessageErrorEmpty")]);
			}
		}
	}
	
	//customerNotification
	public function customerNotification(Request $request){
		
		$devices = DB::table('devices')
					->leftJoin('customers','customers.customers_id','=','devices.customers_id')
					->select('devices.*','customers.customers_firstname','customers.customers_lastname')
					->where('devices.customers_id','=', $request->customers_id)
					->where('devices.is_notify','=', '1')
					->orderBy('register_date','DESC')->take(1)->get();
		
		return view("admin/customerNotificationForm")->with('devices', $devices);
	}
	
	
	//deleteTaxRate
	public function deletedevice(Request $request){
		DB::table('devices')->where('device_id', $request->id)->delete();
		return redirect()->back()->withErrors([Lang::get("labels.DeviceDeletedMessage")]);
	}
	
	public function fcmNotification($device_id, $sendData, $pageResponse){
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$setting = $myVar->getSetting();
		
		#API access key from Google API's Console
		if (!defined('API_ACCESS_KEY')){
			define('API_ACCESS_KEY', $setting[12]->value);
		}
				
		$fields = array
				(
					'to'   => $device_id,
					'data' => $sendData
				);

		$url = 'https://fcm.googleapis.com/fcm/send';
		$arrayToSend  = array('to' => $device_id, 'notification' => $sendData,'priority'=>'high');
		$json         = json_encode($arrayToSend);

		$headers   = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Authorization: key='. API_ACCESS_KEY;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
		//Send the request
		$response = curl_exec($ch);
		//Close request
		curl_close($ch);
		if ($response === FALSE) {
			return '3';
		}else{
			return '1';
		}

	}
	
	public function onesignalNotification($device_id, $sendData, $pageResponse){
		
		//get function from other controller
		$myVar = new AdminSiteSettingController();
		$setting = $myVar->getSetting();
		
		$content = array(
		   "en" => $sendData['body']
		   );
		
		$headings = array(
		   "en" => $sendData['title']
		   );
		
		$big_picture = array(
		   "id1" => $sendData['image']
		   );
		$fields = array(
		   'app_id' => $setting[55]->value,
		   'include_player_ids' => array($device_id),		   
		   'contents' => $content,
		   'headings'=>$headings,
		   'chrome_web_image'=>$sendData['image'],
		   'big_picture'=>$sendData['image'],
		   'ios_attachments'=>$sendData['image'],
		   'firefox_icon'=>$sendData['image'],
		);
	
		$fields = json_encode($fields);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
				   'Authorization: Basic ZTJhZTcwNzItODQ4Ni00Y2FiLWFjZjEtMGY4ODZhZGZlMGZl'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
		$result = curl_exec($ch);
		$data = json_decode($result);
		curl_close($ch);
		
		if(!empty($data->recipients) and $data->recipients >= 1){
			$response = '1';
		}else{
			$response = '0';	
		}	
		
		if($pageResponse==1){
			return $response;
		}else{
			print $response;
		}		
	}
	
}
