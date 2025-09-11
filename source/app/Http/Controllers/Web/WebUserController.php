<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use DB;

class WebUserController extends Controller
{
    
    public function profile()
    {
        $title = "Home";
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	

        $user_phone = Session::get('bamaCust');
        $user = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();
        $addresses = DB::table('address')
        ->where('address.user_id','user.user_id')
        ->get();

        $orders = DB::table('orders')
        ->where('orders.user_id','user.user_id')
        ->get();
        
       return view('web.home.profile',compact('title','logo','user','addresses','orders'));
      
    }
}
