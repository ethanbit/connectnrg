<?php 
namespace App\Http\Controllers\API;

//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;

class PublicHolidayController extends Controller {
	public $successStatus = 200;
	//holiday
	public function publicholiday(Request $request){
		$holiday = DB::table('holiday')->orderBy('date','ASC')->get();
		return response()->json(['success' => 1, 'data' => $holiday], $this->successStatus);
	}
}