<?php
/*
Author: Ducnb

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

class AdminGroupsController extends Controller {
	
	//listingDevices
	public function groups(Request $request){
		$title = array('pageTitle' => 'Groups');		
		$result = array();
		$message = array();

		$queryDevice = DB::table('groups')
				->orderBy('id','DESC')
				//->paginate(100)
				;
		//echo $queryDevice->toSql();
		$holiday = $queryDevice->paginate(100);

		$result['message'] = $message;
		$result['holiday'] = $holiday;
		//echo "<pre>"; print_r($result); echo "</pre>".__FILE__.":".__LINE__; exit;
		return view("admin.groups", $title)->with('result', $result);
	}

	public function deletegroup(Request $request){
		$id = $request->id;
		DB::table('groups')->where('id', $id)->delete();
		return redirect()->back()->withErrors(['Deleted Group (#'.$id.').']);
	}

	public function updategroup(Request $request){
		$id = $request->id;
		$name = $request->name;
		DB::table('groups')->where('id', $id)->update(['name' => $name]);
		return redirect()->back()->withErrors(['Updated Group. (#'.$id.')']);
	}

	public function addnewgroup(Request $request){
		$name = $request->name;
		DB::table('groups')->insert(['name' => $name]);
		return redirect()->back()->withErrors(['Added Group']);
	}
}
