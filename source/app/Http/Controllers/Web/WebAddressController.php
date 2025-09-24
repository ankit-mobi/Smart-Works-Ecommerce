<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use DB;
use Carbon\Carbon;

class WebAddressController extends Controller
{


    public function add_address(Request $request)
    {
        // Get the user's phone number from the session
        $user_phone = Session::get('bamaCust');

        // Find the user in the database using the phone number
        $user = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();

        // Check if a user was found
        if ($user) {
            $user_id = $user->user_id;
        } else {
            return redirect()->route('userLogin')->withErrors('User not registered');
        }
        


        $unselect = DB::table('address')
            ->where('user_id', $user_id)
            ->get();

        if (count($unselect) > 0) {
            $unselect = DB::table('address')
                ->where('user_id', $user_id)
                ->update(['select_status' => 0]);
        }


        $insertaddress = DB::table('address')->insert([
            'user_id'       => $user_id,
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'city' => $request->city,
            'society'       => $request->society,
            'house_no'      => $request->house_no,
            'landmark'      => $request->landmark,
            'state'         => $request->state,
            'pincode'       => $request->pincode,
            'select_status' => 1,
            'lat'           => $request->lat,
            'lng'           => $request->lng,
            'added_at'      => Carbon::now()
        ]);


        if ($insertaddress) {
            return redirect()->route('profile')->with('success','address added');
        } else {
            return redirect()->route('profile')->with('erroe','something went wrong');
        }
    }


    public function show_address()
    {
       
        $user_phone = Session::get('bamaCust');
        $user = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();

        // Check if a user was found
        // if ($user) {
            $user_id = $user->user_id;
        // } else {
        //     return redirect()->route('userLogin')->withErrors('User not registered');
        // }

        


        // $user_id = $request->user_id;
        //     $store_id = $request->store_id;

        //    $store = DB::table('store')
        //        ->where('store_id', $store_id)
        //        ->first();


        $address = DB::table('address')
            ->where('user_id', $user_id)
            ->where('select_status', '!=', 2)
            //     ->select('address.*',DB::raw("6371 * acos(cos(radians(".$store->lat . ")) 
            //                * cos(radians(address.lat)) 
            //                * cos(radians(address.lng) - radians(" . $store->lng . ")) 
            //                + sin(radians(" .$store->lat. ")) 
            //                * sin(radians(address.lat))) AS distance"))
            //                ->having('distance','<=',$store->del_range)
            ->get();


        if (count($address) > 0) {
            foreach ($address as $addresses) {
                $address_id[] = $addresses->address_id;
            }
            $check = DB::table('address')
                ->WhereIn('address_id', $address_id)
                ->where('select_status', 1)
                ->get();
            if (count($check) == 0) {
                $selected =   DB::table('address')
                    ->where('user_id', $user_id)
                    ->where('select_status', 1)
                    ->update(['select_status' => 0]);
            }
            // $message = array('status' => '1', 'message' => 'Address list', 'data' => $address);
            // return $message;
            return $address;
        } else {
            // $message = array('status' => '0', 'message' => 'Address not found! Add Address', 'data' => []);
            // return $message;
            return $address;
        }
    }



}
