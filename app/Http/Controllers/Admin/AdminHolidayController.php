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
use Mail;
//for authenitcate login data
use Auth;
//for requesting a value 
use Illuminate\Http\Request;

class AdminHolidayController extends Controller {
	
	//listingDevices
	public function holiday(Request $request){
		$title = array('pageTitle' => 'Holiday');		
		$result = array();
		$message = array();

		if(!empty($request->id)){
			if($request->active == 'no'){
				$status = '0';
			}elseif($request->active == 'yes'){
				$status = '1';
			}
			
			DB::table('holiday')->where('id', '=', $request->id)->update([
				'status' => $status
			]);	
		}

		if(isset($request->filter) and !empty($request->filter)){
			$holiday = DB::table('holiday')
				->where('date','=', $request->filter)				
				->orderBy('id','DESC')
				->paginate(100);
		}else{
			$queryDevice = DB::table('holiday')
				->orderBy('id','DESC')
				//->paginate(100)
				;
			//echo $queryDevice->toSql();
			$holiday = $queryDevice->paginate(100);
		}

		$result['message'] = $message;
		$result['holiday'] = $holiday;
		//echo "<pre>"; print_r($result); echo "</pre>".__FILE__.":".__LINE__; exit;
		return view("admin.holiday",$title)->with('result', $result);
	}

	public function deleteholiday(Request $request){
		$id = $request->id;
		DB::table('holiday')->where('id', $id)->delete();
		return redirect()->back()->withErrors(['Deleted holiday (#'.$id.').']);
	}

	public function updateholiday(Request $request){
		$id = $request->id;
		$name = $request->name;
		$date = $request->date;
		$status = $request->status;
		DB::table('holiday')->where('id', $id)->update(['name'=>$name, 'date' => $date, 'status' => $status]);
		return redirect()->back()->withErrors(['Updated holiday. (#'.$id.')']);
	}

	public function addnewholiday(Request $request){
		$id = $request->id;
		$name = $request->name;
		$date = $request->date;
		$status = $request->status;
		DB::table('holiday')->insert(['name'=>$name, 'date' => $date, 'status' => $status]);
		return redirect()->back()->withErrors(['Updated holiday. (#'.$id.')']);
	}
}
