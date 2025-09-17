<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DB;

class WebCategoryController extends Controller
{

    //Deal products 
     public function dealproduct()
    {
        $d = Carbon::Now();
        // $lat = $request->lat;
        // $lng = $request->lng;
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
        if (true) {        //$nearbystore->del_range >= $nearbystore->distance
            $deal_p = DB::table('deal_product')
                ->join('store_products', 'deal_product.varient_id', '=', 'store_products.varient_id')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                ->select('store_products.store_id', 'store_products.stock', 'deal_product.deal_price as price', 'product_varient.varient_image', 'product_varient.quantity', 'product_varient.unit', 'store_products.mrp', 'product_varient.description', 'product.product_name', 'product.product_image', 'product_varient.varient_id', 'product.product_id', 'deal_product.valid_to', 'deal_product.valid_from')
                ->groupBy('store_products.store_id', 'store_products.stock', 'deal_product.deal_price', 'product_varient.varient_image', 'product_varient.quantity', 'product_varient.unit', 'store_products.mrp', 'product_varient.description', 'product.product_name', 'product.product_image', 'product_varient.varient_id', 'product.product_id', 'deal_product.valid_to', 'deal_product.valid_from')
                // ->where('store_products.store_id',$nearbystore->store_id)
                ->whereDate('deal_product.valid_from', '<=', $d->toDateString())
                ->WhereDate('deal_product.valid_to', '>', $d->toDateString())
                ->where('store_products.price', '!=', NULL)
                ->where('product.hide', 0)
                ->get();


            if (count($deal_p) > 0) {
                $result = array();
                $i = 0;
                $j = 0;
                foreach ($deal_p as $deal_ps) {
                    array_push($result, $deal_ps);

                    $val_to =  $deal_ps->valid_to;
                    $diff_in_minutes = $d->diffInMinutes($val_to);
                    $totalDuration =  $d->diff($val_to)->format('%H:%I:%S');
                    $result[$i]->timediff = $diff_in_minutes;
                    $i++;
                    $result[$j]->hoursmin = $totalDuration;
                    $j++;
                }

                // $message = array('status' => '1', 'message' => 'Products found', 'data' => $deal_p);
                return $deal_p;
                
            } else {
                $message = array('status' => '0', 'message' => 'Products not found', 'data' => []);
                return $message;
            }
         }
        // else {
        //     $message = array('status' => '2', 'message' => 'No Products Found Nearby', 'data' => []);
        //     return $message;
        // }
    }
 
    // Top ten category
      public function top_ten_cate()
    {
        //       $lat = $request->lat;
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
        if (true) {                  //$nearbystore->del_range >= $nearbystore->distance
            $toptencate = DB::table('store_products')
                ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                ->Leftjoin('store_orders', 'product_varient.varient_id', '=', 'store_orders.varient_id')
                ->Leftjoin('orders', 'store_orders.order_cart_id', '=', 'orders.cart_id')
                ->join('categories', 'product.cat_id', '=', 'categories.cat_id')
                ->select('categories.title', 'categories.image', 'categories.description', 'categories.cat_id', DB::raw('count(store_orders.varient_id) as count'))
                ->groupBy('categories.title', 'categories.image', 'categories.description', 'categories.cat_id')
                //    ->where('store_products.store_id', $nearbystore->store_id)
                ->where('store_products.price', '!=', NULL)
                ->where('product.hide', 0)
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();
            if (count($toptencate) > 0) {
                // $message = array('status' => '1', 'message' => 'Top Six Categories', 'data' => $toptencate);
                // return $message;
                return $toptencate;
            } else {
                $message = array('status' => '0', 'message' => 'Nothing in Top Six', 'data' => []);
                return $message;
            }
        }
        //  else {
        //     $message = array('status' => '2', 'message' => 'No Products Found Nearby', 'data' => []);
        //     return $message;
        // }
    }

}
