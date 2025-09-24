<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Hash;
use Session;

class UserloginController extends Controller
{
  public function userlogin(Request $request)
  {
    if(Session::has('bamaCust')){
        return redirect()->route('webhome');    
    }
    
    $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();
                
  	return view('web.auth.login', compact('logo'));
  }

   public function logincheck(Request $request)
    {
        
        $user_phone = $request->phone;
        $user_password = $request->password;
        $device_id = $request->device_id;

        
         // 1. Check if user exists
         $checkUserReg = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();

        if (!($checkUserReg) || $checkUserReg->is_verified == 0) {
           return redirect()->route('userLogin')->withErrors('Phone not registered');
        }

            // 2. Verify password
    $checkUser = DB::table('users')
            ->where('user_phone', $user_phone)
            ->where('user_password', $user_password)
            ->first();

        if ($checkUser) {
              // 3. If not verified → send OTP again
            if ($checkUser->is_verified == 0) {
                $chars = "0123456789";
                $otpval = "";
                for ($i = 0; $i < 4; $i++) {
                    $otpval .= $chars[mt_rand(0, strlen($chars) - 1)];
                }

                $otpmsg = $this->otpmsg($otpval, $user_phone);

                $updateOtp = DB::table('users')
                    ->where('user_phone', $user_phone)
                    ->update(['otp_value' => $otpval]);

                $checkUser1 = DB::table('users')
                    ->where('user_phone', $user_phone)
                    ->first();

               return redirect()->route('userVerifyOtp')->with('phone', $user_phone);
            } else {
                $updateDeviceId = DB::table('users')
                    ->where('user_phone', $user_phone)
                    ->update(['device_id' => $device_id]);

                $checkUser1 = DB::table('users')
                    ->where('user_phone', $user_phone)
                    ->where('user_password', $user_password)
                    ->first();
                    

               Session::put('bamaCust', $user_phone);
               Session::save();
        return redirect()->route('webhome');
            }
        } else {
           
            return redirect()->route('userLogin')->withErrors('Wrong Password');
        }
    }




  public function logout(Request $request)
  {	
 	Session::forget('bamaCust');
 	return redirect()->route('userLogin')->withSuccess("User logged out.");
  }
}
