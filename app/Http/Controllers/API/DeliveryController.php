<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use DB;
class DeliveryController extends Controller 
{
public $successStatus = 200;
public function deliveryBlockList(Request $request){
$dates = DB::table('delivery_dates')->select('delivery_dates')->get();
return response()->json(['success' => 1, 'data' => $dates], $this->successStatus);
}

}