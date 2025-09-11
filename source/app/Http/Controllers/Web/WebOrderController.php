<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use DB;
use Session;

use Illuminate\Http\Request;


class WebOrderController extends Controller
{
    public function checkout($id)
    {
        $title = "Home";
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	

        $user_phone = Session::get('bamaCust');
        $user = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();

        // product which is selected
        $product = DB::table(table: 'product as p')
            ->join('product_varient as pv', 'pv.product_id', '=', 'p.product_id')
            ->where('p.product_id', $id) // Use the $id parameter directly
            ->select(
                'p.*',
                'pv.varient_id',
                'pv.base_mrp',
                'pv.base_price',
                'pv.description'
            )
            ->first(); // single product info

        return view('web.product.purchase', compact('title','logo','product', 'user'));
    }

    public function cartcheckout()
    {

        $title = "Home";
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	

        $user_phone = Session::get('bamaCust');
        $user = DB::table('users')
            ->where('user_phone', $user_phone)
            ->first();
            
                    $items = DB::table('cart_item as ct')
                        ->join('product_varient as pv', 'pv.varient_id','=', 'ct.varient_id')
                        ->join('product', 'pv.product_id', '=', 'product.product_id')
                        ->select('ct.*',
                         'pv.varient_image',
                         'product.product_name',
                         'product.product_id',
                         'pv.description')                       
                        ->get();
                  
        return view('web.product.cart-check-out', compact('title','logo','items', 'user'));
    }
}
