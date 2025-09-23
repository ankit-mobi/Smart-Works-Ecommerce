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

    public function profile_edit(Request $request)
    {
        $user_id = $request->user_id;
        $user_name = $request->user_name;
        $user_email = $request->user_email;
        $user_phone = $request->user_phone;
        $user_image = $request->user_image;
        $uu = DB::table('users')
            ->where('user_id', $user_id)
            ->first();
        $user_password = $uu->user_password;
        // $date=date('d-m-Y');

        if ($request->user_image) {
            $user_image = $request->user_image;
            $user_image = str_replace('data:image/png;base64,', '', $user_image);
            $fileName = str_replace(" ", "-", $user_image);
            $fileName = date('dmyHis') . 'user_image' . '.' . 'png';
            $fileName = str_replace(" ", "-", $fileName);
            \File::put(public_path() . '/images/user/' . $fileName, base64_decode($user_image));
            $user_image = 'images/user/' . $fileName;
        } else {
            $user_image = 'N/A';
        }

        $checkUser = DB::table('users')
            ->where('user_phone', $user_phone)
            ->where('user_id', '!=', $user_id)
            ->first();
        if ($checkUser && $checkUser->is_verified == 1) {
            $message = array('status' => '0', 'message' => 'This Phone number is attached with another account');
            return $message;
        } else {

            $insertUser = DB::table('users')
                ->where('user_id', $user_id)
                ->update([
                    'user_name' => $user_name,
                    'user_email' => $user_email,
                    'user_phone' => $user_phone,
                    'user_image' => $user_image,
                    'user_password' => $user_password,
                ]);

            $Userdetails = DB::table('users')
                ->where('user_id', $user_id)
                ->first();


            if ($insertUser) {

                $message = array('status' => '1', 'message' => 'Profile Updated', 'data' => $Userdetails);
                return $message;
            } else {
                $message = array('status' => '0', 'message' => 'Something Went wrong');
                return $message;
            }
        }
    }

    public function user_block_check(Request $request)
    {
        $user_id = $request->user_id;
        $user =  DB::table('users')
            ->select('block')
            ->where('user_id', $user_id)
            ->first();

        if ($user) {
            if ($user->block == 1) {
                $message = array('status' => '1', 'message' => 'User is Blocked');
                return $message;
            } else {
                $message = array('status' => '2', 'message' => 'User is Active');
                return $message;
            }
        } else {
            $message = array('status' => '0', 'message' => 'User not found');
            return $message;
        }
    }
}
