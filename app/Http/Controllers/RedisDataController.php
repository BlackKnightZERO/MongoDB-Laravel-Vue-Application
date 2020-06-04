<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RedisDataController extends Controller
{
    public function update(Request $request){

    	
    	$vd = $this->validate($request,[
    		'finalData'=>'required',
    	]);
    	if (Cache::has('finalData')) {
    		//Log::info(Cache::get('finalData'));
    		$c = Cache::get('finalData');
    		return $c;
		}
		//Log::info('json not');
    	Cache::forget('finalData');
    	Cache::forever('finalData', $request->finalData);
    	$c = Cache::get('finalData');
    	return $c;
    }
}
