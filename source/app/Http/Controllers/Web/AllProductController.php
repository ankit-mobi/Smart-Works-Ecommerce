<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon\Carbon;
use Psy\Command\WhereamiCommand;

use function Laravel\Prompts\select;
use function PHPSTORM_META\elementType;

class AllProductController extends Controller
{


    // our product
    public function products(Request $request)
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
        $prod_variant =  DB::table('product_varient')
            ->get();



        $today = Carbon::now()->toDateString();
        //      $lat = $request->lat;
        //    $lng = $request->lng;
        // $cityname = $request->city;
        // $city = ucfirst($cityname);
        //    $nearbystore = DB::table('store')
        //                 ->select('del_range','store_id',DB::raw("6371 * acos(cos(radians(".$lat . ")) 
        //                 * cos(radians(store.lat)) 
        //                 * cos(radians(store.lng) - radians(" . $lng . ")) 
        //                 + sin(radians(" .$lat. ")) 
        //                 * sin(radians(store.lat))) AS distance"))
        //               ->where('store.del_range','>=','distance')
        //               ->orderBy('distance')
        //               ->first();



        if (true) {                 //$nearbystore->del_range >= $nearbystore->distance

            //for product details
            $products = DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
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
                    'product.product_id',
                    'product.product_name',
                    'product.product_image',
                    'product.cat_id',
                    'product_varient.description',
                    'store_products.mrp',
                    'product_varient.varient_image',
                    'product_varient.unit',
                    'product_varient.quantity',
                     DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price') //  Deal price if active, else normal price
                )
                // ->where('store_products.store_id', $nearbystore->store_id)
                ->where('store_products.price', '!=', NULL)
                ->where('product.hide', 0)
                ->orderByRaw('RAND()')
                ->get();


            return view('web.product.cat_product', compact("title", "logo", "category", "category_sub", "category_child", "products", "prod_variant", 'cust', 'cust_phone'));
        }
    }

    // side categories
    public function cate(Request $request)
    {

        $title = "Home";
        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();
        $cust_phone = Session::get('bamaCust');
        $cust = DB::table('users')
            ->where('user_phone', $cust_phone)
            ->first();

        $cat_id = $request->cat_id;

        $category = DB::table('categories')
            ->get();
        $category_sub = DB::table('categories')
            ->where('level', 1)
            ->get();
        $category_child = DB::table('categories')
            ->where('level', 2)
            ->get();





        //    $lat = $request->lat;
        //    $lng = $request->lng;
        // $cityname = $request->city;
        // $city = ucfirst($cityname);
        //    $nearbystore = DB::table('store')
        //                 ->select('del_range','store_id',DB::raw("6371 * acos(cos(radians(".$lat . ")) 
        //                 * cos(radians(store.lat)) 
        //                 * cos(radians(store.lng) - radians(" . $lng . ")) 
        //                 + sin(radians(" .$lat. ")) 
        //                 * sin(radians(store.lat))) AS distance"))
        //               ->where('store.del_range','>=','distance')
        //               ->orderBy('distance')
        //               ->first();
        if (true) {              //$nearbystore->del_range >= $nearbystore->distance
           $products = DB::table('store_products')
    ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
    ->join('product', 'product_varient.product_id', '=', 'product.product_id')
    ->leftJoin('deal_product', function ($join) {
        $d = now(); // current timestamp
        $join->on('product_varient.varient_id', '=', 'deal_product.varient_id')
             ->where('deal_product.valid_from', '<=', $d)
             ->where('deal_product.valid_to', '>', $d);
    })
    ->where('product.cat_id', $cat_id)
    // ->where('store_products.store_id', $nearbystore->store_id)
    ->whereNotNull('store_products.price')
    ->where('product.hide', 0)
    ->select(
        'store_products.store_id',
        'store_products.stock',
        'product_varient.varient_id',
        'product.product_id',
        'product.product_name',
        'product.product_image',
        'product_varient.description',
        'store_products.mrp',
        'product_varient.varient_image',
        'product_varient.unit',
        'product_varient.quantity',
        DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price') // ✅ Deal or fallback
    )
    ->get();

if (count($products) > 0) {
    $result = [];
    $i = 0;

    foreach ($products as $prods) {
        array_push($result, $prods);

        $app = json_decode($prods->product_id);
        $apps = [$app];

        $app = DB::table('store_products')
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
                DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price') // ✅ Deal or fallback
            )
            // ->where('store_products.store_id', $nearbystore->store_id)
            ->whereIn('product_varient.product_id', $apps)
            ->whereNotNull('store_products.price')
            ->get();

        $result[$i]->varients = $app;
        $i++;
    }


                // $message = array('status' => '1', 'message' => 'Products found', 'data' => $prod);
                // return $message;
                 return view('web.demo', compact("products",  "title", "logo", "category", "category_sub", "category_child", 'cust', 'cust_phone')); //"prod_variant", 'web.product.cat_product'  "title", "logo", "category", "category_sub", "category_child", 'cust', 'cust_phone'
             } 
             //else {
            //     $message = array('status' => '0', 'message' => 'Products not found', 'data' => []);
            //     return $message;
            // }
        }
        //  else {
        //     $message = array('status' => '2', 'message' => 'No Products Found Nearby', 'data' => []);
        //     return $message;
        // }




        // $products =  DB::table('product')
        //     ->where('cat_id', $cat_id)
        //     ->get();
        // $prod_variant =  DB::table('product_varient')
        //     ->get();
      
        // return view('web.product.cat_product', compact("title", "logo", "category", "category_sub", "category_child", "products",'cust', 'cust_phone')); //"prod_variant",
    } 
     

    // product preview 
    public function product_details(Request $request)
    {

        $title = "Home";
        $logo = DB::table('tbl_web_setting')
            ->where('set_id', '1')
            ->first();

        //remove these later after merging both page
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


        // product which is selected
        $prod_id  = $request->id;
        $store_id = $request->store_id;
        $today = Carbon::now()->toDateString();
        //      $lat = $request->lat;
        //    $lng = $request->lng;
        // $cityname = $request->city;
        // $city = ucfirst($cityname);
        //    $nearbystore = DB::table('store')
        //                 ->select('del_range','store_id',DB::raw("6371 * acos(cos(radians(".$lat . ")) 
        //                 * cos(radians(store.lat)) 
        //                 * cos(radians(store.lng) - radians(" . $lng . ")) 
        //                 + sin(radians(" .$lat. ")) 
        //                 * sin(radians(store.lat))) AS distance"))
        //               ->where('store.del_range','>=','distance')
        //               ->orderBy('distance')
        //               ->first();


        if (true) {                 //$nearbystore->del_range >= $nearbystore->distance

            //for product details
            if ($prod_id > 0) {
                $prev_product = DB::table('store_products')
                    ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                    ->join('product', 'product_varient.product_id', '=', 'product.product_id')
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
                        'product.product_id',
                        'product.product_name',
                        'product.product_image',
                        'product.cat_id',
                        'product_varient.description',
                        'store_products.mrp',
                        'product_varient.varient_image',
                        'product_varient.unit',
                        'product_varient.quantity',
                        DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price') //  Deal price if active, else normal price

                    )
                    ->where('product.product_id', $prod_id)
                    // ->where('store_products.store_id', $nearbystore->store_id)
                    ->where('store_products.price', '!=', NULL)
                    ->where('product.hide', 0)
                    ->first();

                //varients of its 
                $varient = DB::table('store_products')
                    ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                    ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                    ->leftJoin('deal_product', function ($join) {
                            $d = now();
                            $join->on('product_varient.varient_id', '=', 'deal_product.varient_id')
                                ->where('deal_product.valid_from', '<=', $d)
                                ->where('deal_product.valid_to', '>', $d);
                        })                    ->select(
                        'store_products.store_id',
                        'store_products.stock',
                        'product_varient.varient_id',
                        'product_varient.description',
                        'product.product_id',
                        'product.product_name',
                        'store_products.mrp',
                        'product_varient.varient_image',
                        'product_varient.unit',
                        'product_varient.quantity',
                        DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price') //  Deal price if active, else normal price

                    )
                    ->where('product.product_id', $prod_id)
                    ->where('store_products.price', '!=', NULL)
                    // ->where('store_products.store_id',$nearbystore->store_id)
                    ->where('store_products.store_id', '!=', $store_id) //exclude same store
                    ->get();
 

                // related product to selected product
                if (!empty($prev_product->cat_id)) { //
                    $related_prods = DB::table('store_products as sp')
                        ->join('product_varient as pv', 'sp.varient_id', '=', 'pv.varient_id')
                        ->join('product as p', 'pv.product_id', '=', 'p.product_id')
                       ->leftJoin('deal_product', function ($join) {
                            $d = now();
                            $join->on('pv.varient_id', '=', 'deal_product.varient_id')
                                ->where('deal_product.valid_from', '<=', $d)
                                ->where('deal_product.valid_to', '>', $d);
                        })  
                        ->select(
                            'sp.store_id',
                            'sp.stock',
                            'p.product_id',
                            'p.product_name',
                            'p.product_image',
                            'p.cat_id',
                            'pv.description',
                            'sp.mrp',
                            'pv.varient_image',
                            'pv.unit',
                            'pv.quantity',
                            DB::raw('COALESCE(deal_product.deal_price, sp.price) as price') //  Deal price if active, else normal price

                        )
                        ->where('p.cat_id', $prev_product->cat_id)
                        ->where('p.product_id', '!=', $prev_product->product_id) // exclude current product
                        ->where('sp.price', '!=', null)
                        ->where('p.hide', 0) // only visible products     
                        ->get();
                } else {
                    $related_prods = null;
                }

                // return $related_prods;
                return view('web.product.product_preview', compact("title", "logo", "category", "category_sub", "category_child", "prev_product", 'cust', 'cust_phone', 'related_prods', 'varient'));
             } // else {
            //     //    $message = array('status' => '2', 'message' => 'No Products Found Nearby', 'data' => []);
            //     // return $message;
            //     $prev_product = null;
            //     return view('web.product.product_preview', compact("title", "logo", "category", "category_sub", "category_child", "prev_product", 'cust', 'cust_phone'))->withErrors('No Products Found Nearby');
            // }
        }
        // else {
        //       return view('web.product.product_preview', compact("title", "logo", "category", "category_sub", "category_child", "prev_product", 'cust', 'cust_phone'))->withErrors('No Products Found Nearby');
        // }


    }
}