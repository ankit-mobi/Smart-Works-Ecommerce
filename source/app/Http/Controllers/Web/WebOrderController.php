<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Carbon;
use Session;
use Illuminate\Http\Request;


class WebOrderController extends Controller
{

      public function top_selling(){
       $current = Carbon::now();
    //    $lat = $request->lat;
    //    $lng = $request->lng;
    //     $cityname = $request->city;
    //    $city = ucfirst($cityname);
    //    $nearbystore = DB::table('store')
    //                 ->select('del_range','store_id',DB::raw("6371 * acos(cos(radians(".$lat . ")) 
    //                 * cos(radians(store.lat)) 
    //                 * cos(radians(store.lng) - radians(" . $lng . ")) 
    //                 + sin(radians(" .$lat. ")) 
    //                 * sin(radians(store.lat))) AS distance"))
    //               ->where('store.del_range','>=','distance')
    //               ->orderBy('distance')
    //               ->first();
if(true) {     //$nearbystore->del_range >= $nearbystore->distance
      $topselling = DB::table('store_products')
                 ->join ('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                  ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
                  ->Leftjoin ('store_orders', 'store_products.varient_id', '=', 'store_orders.varient_id') 
                  ->Leftjoin ('orders', 'store_orders.order_cart_id', '=', 'orders.cart_id')
                  ->Leftjoin ('deal_product', 'product_varient.varient_id', '=', 'deal_product.varient_id')
                  ->select('store_products.store_id','store_products.stock','product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'store_products.price', 'store_products.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity',DB::raw('count(store_orders.varient_id) as count'))
                  ->groupBy('store_products.store_id','store_products.stock','product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'store_products.price', 'store_products.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity')
                //   ->where('store_products.store_id', $nearbystore->store_id)
                  ->where('deal_product.deal_price', NULL)
                  ->where('store_products.price','!=',NULL)
                  ->where('product.hide',0)
                  ->orderBy('count','desc')
                  // ->limit(10)
                  ->get();
                  
         if(count($topselling)>0){
        	// $message = array('status'=>'1', 'message'=>'top selling products', 'data'=>$topselling);
        	// return $message;
            return $topselling;
        }
        else{
        	// $message = array('status'=>'0', 'message'=>'nothing in top', 'data'=>[]);
        	// return $message;
            return $topselling='';
        }      
      }
    //    else{
    //        $message = array('status'=>'2', 'message'=>'No Products Found Nearby', 'data'=>[]);
    //         return $message; 
    //    }
     
  }    

     public function whatsnew(){
        $current = Carbon::now(); 
      //    $lat = $request->lat;
      //  $lng = $request->lng;
      //   $cityname = $request->city;
      //  $city = ucfirst($cityname);
      //  $nearbystore = DB::table('store')
      //               ->select('del_range','store_id',DB::raw("6371 * acos(cos(radians(".$lat . ")) 
      //               * cos(radians(store.lat)) 
      //               * cos(radians(store.lng) - radians(" . $lng . ")) 
      //               + sin(radians(" .$lat. ")) 
      //               * sin(radians(store.lat))) AS distance"))
      //             ->where('store.del_range','>=','distance')
      //             ->orderBy('distance')
      //             ->first();
       if(true) {      //$nearbystore->del_range >= $nearbystore->distance         
      $new = DB::table('store_products')
                 ->join ('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                  ->join ('product', 'product_varient.product_id', '=', 'product.product_id')
                  ->Leftjoin ('deal_product', 'product_varient.varient_id', '=', 'deal_product.varient_id')
                  ->select('store_products.store_id','store_products.stock','product_varient.varient_id','product.product_id','product.product_name', 'product.product_image', 'product_varient.description', 'store_products.price', 'store_products.mrp', 'product_varient.varient_image','product_varient.unit','product_varient.quantity')
                  ->limit(10)
                  //  ->where('store_products.store_id', $nearbystore->store_id)
                  ->where('deal_product.deal_price', NULL)
                ->where('store_products.price','!=',NULL)
                ->where('product.hide',0)
                  ->orderByRaw('RAND()')
                  ->get();
                  
         if(count($new)>0){
        	// $message = array('status'=>'1', 'message'=>'New in App', 'data'=>$new);
        	return $new;
        }
        else{
        	return $new=null;
        }      
    }
      //  else{
      //      $message = array('status'=>'2', 'message'=>'No Products Found Nearby', 'data'=>[]);
      //       return $message; 
      //  }
  } 



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
