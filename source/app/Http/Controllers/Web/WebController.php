<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WebController extends Controller
{

    public function delivery_info()
    {
        $del_fee = DB::table('freedeliveryCart')
                   ->first();

        if (request()->expectsJson()) {
        if($del_fee){
            $message = array('status'=>'1','data'=>$del_fee);
            return response()->json($message);
        }
        else{
            $message = array('status'=>'0','message'=>'data not found', 'data'=>[]);
            return response()->json($message);
        }
    }

        return $del_fee;
    }
}
