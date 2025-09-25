<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WebCartvalueController extends Controller
{
    public function minmax(){
       $minmax = DB::table('minimum_maximum_order_value')
                  ->first();

        
        if(request()->expectsJson()){
        if($minmax){
            $message = array('status'=>'1','data'=>$minmax);
            return response()->json($message);
        }
        else{
            $message = array('status'=>'0','data'=>[]);
            return response()->json($message);
        }
        }
        
        return $minmax;
    }
}
