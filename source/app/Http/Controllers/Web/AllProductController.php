<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Carbon\Carbon;
use function Laravel\Prompts\select;
use function PHPSTORM_META\elementType;

class AllProductController extends Controller
{

    //search product
      public function search_web(Request $request){

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

        $keyword = $request->keyword;
        
         // this show all data of all categories
        $products = DB::table('product')
            ->get();
    //      $lat = $request->lat;
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
    if(true) 
         { //$nearbystore->del_range >= $nearbystore->distance
        $prod = DB::table('store_products')
                 ->join ('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
			     ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
			     ->select('product.product_name','product.product_id')
                 ->groupBy('product.product_name','product.product_id')
                //  ->where('store_products.store_id', $nearbystore->store_id)
                ->where('product.product_name', 'like', '%'.$keyword.'%')
                ->get();

        if ($prod->count() > 0) {
        foreach ($prod as $i => $prods) {
            $variants = DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->leftJoin('deal_product', 'product_varient.varient_id', '=', 'deal_product.varient_id')
                ->select(
                    'store_products.store_id',
                    'store_products.stock',
                    'product_varient.varient_id',
                    'product_varient.description',
                    'store_products.price',
                    'store_products.mrp',
                    'product_varient.varient_image',
                    'product_varient.unit',
                    'product_varient.quantity',
                    'deal_product.deal_price',
                    'deal_product.valid_from',
                    'deal_product.valid_to'
                )
                ->where('product_varient.product_id', $prods->product_id)
                ->get();

            $prod[$i]->varients = $variants;
        }

        
         return view('web.product.cat_product', compact("title","logo", "category", "category_sub", "category_child", "prod_variant", 'cust', 'cust_phone','prod'));
        }
        else{
              return view('web.product.cat_product', compact("title","logo", "category", "category_sub", "category_child", "products", "prod_variant", 'cust', 'cust_phone'))->withErrors('No Products Found');
        }
      }
    //    else{
    //        $message = array('status'=>'2', 'message'=>'No Products Found Nearby', 'data'=>[]);
    //         return $message; 
    //    }
    }

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


        // this show all data of all categories
        $products = DB::table('product')
            ->get();

        return view('web.product.cat_product', compact("title","logo", "category", "category_sub", "category_child", "products", "prod_variant", 'cust', 'cust_phone'));
    }

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

        $products =  DB::table('product')
            ->where('cat_id', $cat_id)
            ->get();

        $prod_variant =  DB::table('product_varient')
            ->get();
        $category = DB::table('categories')
            ->get();
        $category_sub = DB::table('categories')
            ->where('level', 1)
            ->get();
        $category_child = DB::table('categories')
            ->where('level', 2)
            ->get();
        return view('web.product.cat_product', compact("title","logo","category", "category_sub", "category_child", "products", "prod_variant", 'cust', 'cust_phone'));
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
            $prev_product = DB::table('store_products')
                    ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                    ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                    ->leftJoin('deal_product', function($join) use ($today) {
                        $join->on('product_varient.varient_id', '=', 'deal_product.varient_id')
                             ->whereDate('deal_product.valid_from', '<=', $today)
                             ->whereDate('deal_product.valid_to', '>', $today);
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
                     DB::raw('
                     CASE 
                     WHEN deal_product.deal_price IS NOT NULL 
                     THEN deal_product.deal_price 
                     ELSE store_products.price 
                     END as price
                     '),
                    'store_products.mrp',
                    'product_varient.varient_image',
                    'product_varient.unit',
                    'product_varient.quantity',
                   )
                  ->where('product.product_id',$prod_id )
                  // ->where('store_products.store_id', $nearbystore->store_id)
                  ->where('store_products.price','!=',NULL)
                  ->where('product.hide',0)
                  ->first();

             //varients of its 
             $varient = DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->Leftjoin('deal_product', 'product_varient.varient_id', '=', 'deal_product.varient_id')
                ->select(
                    'store_products.store_id',
                             'store_products.stock',
                             'product_varient.varient_id',
                             'product_varient.description',
                             'store_products.price',
                             'store_products.mrp',
                             'product_varient.varient_image', 
                             'product_varient.unit', 
                             'product_varient.quantity', 
                             'deal_product.deal_price',
                             'deal_product.valid_from',
                             'deal_product.valid_to')
                ->where('product_id', $prod_id)
                ->where('store_products.price', '!=', NULL)
                // ->where('store_products.store_id',$nearbystore->store_id)
                ->get();

              // related product to selected product
              if (!empty($prev_product->cat_id)) {
                  $related_prods = DB::table('product as p')
                  ->join('product_varient as pv', 'pv.product_id', '=', 'p.product_id')
                  ->select(
                      'p.product_id',
                        'p.product_name',
                        'p.product_image',
                        'p.cat_id',
                        'pv.varient_id',
                        'pv.base_mrp',
                        'pv.base_price',
                        'pv.description'
                         )
                  ->where('p.cat_id', $prev_product->cat_id)
                  ->where('p.product_id', '!=', $prev_product->product_id) // exclude current product
                  ->where('p.hide', 0) // only visible products     
                  ->get();
                    }
                    else{
                        $related_prods = null;
                    }
                   
            // return $related_prods;
                         return view('web.product.product_preview', compact("title","logo","category", "category_sub", "category_child", "prev_product", 'cust', 'cust_phone', 'related_prods'));
            
            // else{
            //    $message = array('status' => '2', 'message' => 'No Products Found Nearby', 'data' => []);
            // return $message;
            // }
           

            }
            // else {
        //     $message = array('status' => '2', 'message' => 'No Products Found Nearby', 'data' => []);
        //     return $message;
        // }


    }
}


   

