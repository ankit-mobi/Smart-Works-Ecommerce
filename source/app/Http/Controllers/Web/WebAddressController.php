<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Session;
use DB;

class WebAddressController extends Controller
{

    public function show_address()
    {
        $cust_phone = Session::get('bamaCust');
        $user_id = DB::table('users')
            ->select(
                'users.user_id'
            )
            ->where('user_phone', $cust_phone)
            ->first();


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
            $message = array('status' => '1', 'message' => 'Address list', 'data' => $address);
            return $message;
        } else {
            $message = array('status' => '0', 'message' => 'Address not found! Add Address', 'data' => []);
            return $message;
        }
    }
}
