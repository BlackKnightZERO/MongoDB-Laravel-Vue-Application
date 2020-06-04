<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JsonData;

class MongoDataController extends Controller
{
	public function get(){
		$finalData = JsonData::all();
		if($finalData->isNotEmpty()){
			return $finalData;
		} return response()->json([
			'msg' => 'Empty Collection',
		],404);
	}

	public function add(Request $request){
		$this->validate($request,[
			'finalData'=>'required',
		]);
		return JsonData::create([
    		'finalData'=>$request->finalData
    	]);
	}



    public function update(Request $request){

    	$vd = $this->validate($request,[
			'_id'=>'required',
			'finalData'=>'required',
    	]);

    	return JsonData::where('_id',$request->_id)->update([
    		'finalData'=>$request->finalData
    	]);

    	
  //   	$vd = $this->validate($request,[
  //   		'finalData'=>'required',
  //   	]);
  //   	if (Cache::has('finalData')) {
  //   		$c = Cache::get('finalData');
  //   		return $c;
		// }
  //   	Cache::forget('finalData');
  //   	Cache::forever('finalData', $request->finalData);
  //   	$c = Cache::get('finalData');
  //   	return $c;
    }
}
//Log::info('json not');