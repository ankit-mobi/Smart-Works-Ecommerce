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

        if (!($checkUser)) {
            return redirect()->route('forgot_password.form')->withErrors('Phone not registered');
        }

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

                return view('web.auth.forgot_otp', compact('title', 'logo', 'user_phone'))->with('otp', $otpval);
            } else {
                return redirect()->route('forgot_password.form')->withErrors('Something went wrong');
            }
        } else {
            return redirect()->route('forgot_password.form')->withErrors('Something went wrong');
        }
    }

    public function verifyForgotOtp(Request $request)
    {
        $title = "Home";
        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();

        $request->validate([
            'user_phone' => 'required|numeric', 
            'otp' => 'required',
        ]);
        $user_phone = $request->user_phone;
        $otp = $request->otp;

        // check for otp verify
        $getUser = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();

        if ($getUser) {
            $getotp = $getUser->otp_value;

            if ($otp == $getotp) {
                return view('web.auth.changepassword', compact('title', 'logo'))->with('user_phone', $user_phone);
            } else {
                return redirect()->route('forgot_password.form')->withErrors('Wrong OTP');
            }
        } else {
            return redirect()->route('forgot_password.form')->withErrors('Wrong OTP');
        }
    }


    public function resetPassword(Request $request)
    {

          $this->validate(
            $request,
            [
                'user_phone' => 'required',
                'user_password' => 'required'
            ],
            [
                'user_phone.required' => 'Enter Mobile...',
                'user_password.required' => 'Enter password...',
            ]
        );

        $user_phone = $request->user_phone;
        $password = $request->user_password;

        $getUser = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();

        if ($getUser) {
            $updateOtp = DB::table('users')
                ->where('user_phone', $user_phone)
                ->update(['user_password' => $password]);

            if ($updateOtp) {
                $checkUser1 = DB::table('users')
                    ->where('user_phone', $user_phone)
                    ->first();

                return redirect()->route('userLogin')->with('success', "password changed");
            } else {
                return redirect()->route('userLogin')->with('errors', "Something wrong");
            }
        } else {
           return redirect()->route('userLogin')->with('errors', "Something wrong");
        }
    }

    
}
