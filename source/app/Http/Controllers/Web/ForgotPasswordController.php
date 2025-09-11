<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;
use Carbon\Carbon;
use App\Traits\SendSms;

class ForgotPasswordController extends Controller
{

    use SendSms;

    // to show forgot password form
    public function showforgotForm(Request $request)
    {
        $title = "Home";
        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();

        return view('web.auth.forgot', compact('title', 'logo'));
    }


    public function forgotPassword(Request $request)
    {
        $title = "Home";
        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();

          $this->validate(
            $request,
            [
                'user_phone' => 'required|numeric',
            ],
            [
                'user_phone.required' => 'Enter Mobile...',
            ]
        );

        $user_phone = $request->user_phone;

        $checkUser = DB::table('users')
            ->where('user_phone', $user_phone)
            ->where('is_verified', 1)
            ->first();

        if ($checkUser) {
            $chars = "0123456789";
            $otpval = "";
            for ($i = 0; $i < 4; $i++) {
                $otpval .= $chars[mt_rand(0, strlen($chars) - 1)];
            }

            $otpmsg = $this->otpmsg($otpval, $user_phone);



            $updateOtp = DB::table('users')
                ->where('user_phone', $user_phone)
                ->update(['otp_value' => $otpval]);

            if ($updateOtp) {
                $checkUser1 = DB::table('users')
                    ->where('user_phone', $user_phone)
                    ->first();

                return view('web.auth.forgot_otp', compact('title','logo','user_phone'))->with('otp', $otpval);
            } else {
                return redirect()->route('forgot_password.form')->withErrors('Something went wrong');
            }
        } else {
            return redirect()->route('forgot_password.form')->withErrors('Something went wrong');
        }
    }

      public function verifyOtp(Request $request)
    {
        $phone = $request->user_phone;
        $otp = $request->otp;

        // check for otp verify
        $getUser = DB::table('users')
            ->where('user_phone', $phone)
            ->first();

        if ($getUser) {
            $getotp = $getUser->otp_value;

            if ($otp == $getotp) {
                $message = array('status' => 1, 'message' => "Otp Matched Successfully");
                return $message;
            } else {
                $message = array('status' => 0, 'message' => "Wrong OTP");
                return $message;
            }
        } else {
            $message = array('status' => 0, 'message' => "User not registered");
            return $message;
        }
    }
}
