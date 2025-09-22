<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Session;

class WebSearchController extends Controller
{
    //search product
    public function search_web(Request $request)
    {

        $title = "Home";
        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();
        $cust_phone = Session::get('bamaCust');
        $cust = DB::table('users')
            ->where('user_phone', $cust_phone)
            ->first();

        $category = DB::table('categories')
            ->get();
        $category_sub = DB::table('categories')
            ->where('level', 1)
            ->get();
        $category_child = DB::table('categories')
            ->where('level', 2)
            ->get();

        // user input
        $keyword = $request->keyword;

        //    $lat = $request->lat;
        //    $lng = $request->lng;

        //    $nearbystore = DB::table('store')
        //                 ->select('del_range','store_id',DB::raw("6371 * acos(cos(radians(".$lat . ")) 
        //                 * cos(radians(store.lat)) 
        //                 * cos(radians(store.lng) - radians(" . $lng . ")) 
        //                 + sin(radians(" .$lat. ")) 
        //                 * sin(radians(store.lat))) AS distance"))
        //               ->where('store.del_range','>=','distance')
        //             //   ->where('store.city',$city)
        //               ->orderBy('distance')
        //               ->first();
        if (true) { //$nearbystore->del_range >= $nearbystore->distance
            $prod = DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                ->select('product.product_name', 'product.product_id')
                ->groupBy('product.product_name', 'product.product_id')
                // ->where('store_products.store_id', $nearbystore->store_id)
                ->where('product.product_name', 'like', '%' . $keyword . '%')
                ->get();

            if ($prod->count() > 0) {
                foreach ($prod as $i => $prods) {
                    $variants = DB::table('store_products')
                        ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                        ->leftJoin('deal_product', function ($join) {
                            $d = now();
                            $join->on('product_varient.varient_id', '=', 'deal_product.varient_id')
                                ->where('deal_product.valid_from', '<=', $d)
                                ->where('deal_product.valid_to', '>', $d);
                        })
                        ->select(
                            'store_products.store_id',
                            'store_products.stock',
                            'product_varient.varient_id',
                            'product_varient.description',
                            'store_products.mrp',
                            'product_varient.varient_image',
                            'product_varient.unit',
                            'product_varient.quantity',
                            DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price') //  Deal price if active, else normal price
                        )
                        ->where('product_varient.product_id', $prods->product_id)
                        ->get();

                    $prod[$i]->varients = $variants;
                }

                return view('web.product.our_product', compact("title", "logo", "category", "category_sub", "category_child", 'cust', 'cust_phone', 'prod'));
                
            } else {
                return view('web.product.our_product', compact("title", "logo", "category", "category_sub", "category_child", 'cust', 'cust_phone'))->withErrors('No Products Found');
            }
        }
        //    else{
        // return view('web.product.our_product', compact("title", "logo", "category", "category_sub", "category_child",'cust', 'cust_phone'))->withErrors('No Products Found Nearby');
        //    }
    }
}
