<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Carbon;
use Session;
use Illuminate\Http\Request;
use App\http\Controllers\Web\WebAddressController;
use App\Http\Controllers\Web\WebController;
use App\Http\Controllers\Web\WebCartvalueController;

class WebOrderController extends Controller
{

  public function top_selling()
  {
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
    if (true) {     //$nearbystore->del_range >= $nearbystore->distance
      $topselling = DB::table('store_products')
        ->join('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
        ->join('product', 'product_varient.product_id', '=', 'product.product_id')
        ->leftJoin('store_orders', 'store_products.varient_id', '=', 'store_orders.varient_id')
        ->leftJoin('orders', 'store_orders.order_cart_id', '=', 'orders.cart_id')
        ->leftJoin('deal_product', function ($join) {
          // Use a join condition to check for active deals
          $d = now(); // Use Carbon's now() for the current time
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
          'product_varient.description',
          'store_products.mrp',
          'product_varient.varient_image',
          'product_varient.unit',
          'product_varient.quantity',
          DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price'), // Main change
          DB::raw('count(store_orders.varient_id) as count')
        )
        ->groupBy(
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
          'store_products.price', // Add store_products.price to group by
          'deal_product.deal_price' // Add deal_product.deal_price to group by
        )
        // You no longer need to check for NULL deal_price in the where clause
        ->where('store_products.price', '!=', NULL)
        ->where('deal_product.deal_price', NULL)
        ->where('product.hide', 0)
        ->orderBy('count', 'desc')
        ->get();

      if (count($topselling) > 0) {
        // $message = array('status'=>'1', 'message'=>'top selling products', 'data'=>$topselling);
        // return $message;
        return $topselling;
      } else {
        // $message = array('status'=>'0', 'message'=>'nothing in top', 'data'=>[]);
        // return $message;
        return $topselling = '';
      }
    }
    //    else{
    //        $message = array('status'=>'2', 'message'=>'No Products Found Nearby', 'data'=>[]);
    //         return $message; 
    //    }

  }

  public function just_arrived_prod()
  {
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
    if (true) {      //$nearbystore->del_range >= $nearbystore->distance         
      $new = DB::table('store_products')
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
          'product_varient.description',
          'store_products.mrp',
          'product_varient.varient_image',
          'product_varient.unit',
          'product_varient.quantity',
          DB::raw('COALESCE(deal_product.deal_price, store_products.price) as price')
        )
        ->where('store_products.price', '!=', NULL)
        ->where('product.hide', 0)
        //  ->where('store_products.store_id', $nearbystore->store_id)
        // ->where('deal_product.deal_price', NULL)
        ->orderByRaw('RAND()')
        // ->limit(10)
        ->get();

      if (count($new) > 0) {
        return $new;
      } else {
        return $new = null;
      }
    }
    //  else{
    //      $message = array('status'=>'2', 'message'=>'No Products Found Nearby', 'data'=>[]);
    //       return $message; 
    //  }
  }


  // cart-check-out
  public function order_summary(WebAddressController $address, WebController $webController, WebCartvalueController $web_cartvalue) 
  {

    $title = "Home";
    $logo = DB::table('tbl_web_setting')
      ->where('set_id', '1')
      ->first();

    $user_phone = Session::get('bamaCust');
    $user = DB::table('users')
      ->where('user_phone', $user_phone)
      ->first();

   if (!$user) {
    return redirect()->route('userLogin')->with('error', 'User not registered');
   }
  


    $cart = session()->get('cart', []);
    if (empty($cart)) {
        return redirect()->route('products')->with('error', 'Your cart is empty!');
    }

    // Calculate totals
    $totalAmount = 0;
    $totalItems = 0;

    foreach ($cart as $item) {
        $totalAmount += $item['price'] * $item['quantity'];
        $totalItems += $item['quantity'];
    }

    $addresses = $address->show_address();
    $delivery_info = $webController->delivery_info();
    $minmax = $web_cartvalue->minmax();
    // return $delivery_info;

    return view('web.orders.order_summary_page', compact('title', 'logo', 'user_phone', 'user' ,'cart', 'totalAmount', 'totalItems', 'addresses', 'delivery_info','minmax')); 
}



// public function order(){

//     $title = "Home";
//     $logo = DB::table('tbl_web_setting')
//       ->where('set_id', '1')
//       ->first();

//     $user_phone = Session::get('bamaCust');
//     $user = DB::table('users')
//       ->where('user_phone', $user_phone)
//       ->first();

      
//    if (!$user) {
//     return redirect()->route('userLogin')->with('error', 'User not registered');
//    }

//   return view('web.orders.demo-order', compact('title', 'logo', 'user_phone', 'user'));
// }




   public function order(Request $request)
    {   
        $current = Carbon::now();
        $data= $request->order_array;
        $data_array = json_decode($data);
        
        // dd($request->all());
        // dd($request->);

        $user_id= $request->user_id;
        $delivery_date = $request-> delivery_date;
        $time_slot= $request->time_slot;
        $store_id = $request->store_id;
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                $val = "";
                for ($i = 0; $i < 4; $i++){
                    $val .= $chars[mt_rand(0, strlen($chars)-1)];
                }
                
        $chars2 = "0123456789";
                $val2 = "";
                for ($i = 0; $i < 2; $i++){
                    $val2 .= $chars2[mt_rand(0, strlen($chars2)-1)];
                }        
        $cr  = substr(md5(microtime()),rand(0,26),2);
        $cart_id = $val.$val2.$cr;
        $ar= DB::table('address')
            ->select('society','city','lat','lng','address_id')
            ->where('user_id', $user_id)
            ->where('select_status', 1)
            ->first();
       if(!$ar){
           	$message = array('status'=>'0', 'message'=>'Select any Address');
        	return $message;
       }
        $created_at = Carbon::now();
        $user_id= $request->user_id;
        $price2=0;
        $price5=0;
        $ph = DB::table('users')
                  ->select('user_phone','wallet')
                  ->where('user_id',$user_id)
                  ->first();
        $user_phone = $ph->user_phone;
      
       
    foreach ($data_array as $h){
      // dd($h);
        $varient_id = $h->varient_id;
         $p =  DB::table('store_products')
            ->join ('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
            ->join('product','product_varient.product_id','=','product.product_id')
           ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
           ->where('product_varient.varient_id',$varient_id)
           ->where('store_products.store_id',$store_id)
           ->first();
         if($p->deal_price != NULL &&  $p->valid_from < $current && $p->valid_to > $current){
          $price= $p->deal_price;    
        }else{
      $price = $p->price;
        } 
        
        $mrpprice = $p->mrp;
        $order_qty = $h->qty;
        $price2+= $price*$order_qty;
        $price5+=$mrpprice*$order_qty;
        $unit[] = $p->unit;
        $qty[]= $p->quantity;
        $p_name[] = $p->product_name."(".$p->quantity.$p->unit.")*".$order_qty;
        $prod_name = implode(',',$p_name);
        
    }    
    
    foreach ($data_array as $h)
    { 
        $varient_id = $h->varient_id;
        $p =  DB::table('store_products')
            ->join ('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
             ->join('product','product_varient.product_id','=','product.product_id')
           ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
           ->where('product_varient.varient_id',$varient_id)
           ->where('store_products.store_id',$store_id)
           ->first();
        if($p->deal_price != NULL &&  $p->valid_from < $current && $p->valid_to > $current){
          $price= $p->deal_price;    
        }else{
      $price = $p->price;
        } 
        $mrp = $p->mrp;
        $order_qty = $h->qty;
        $price1= $price*$order_qty;
        $total_mrp = $mrp*$order_qty;
        $order_qty = $h->qty;
        $p = DB::table('store_products')
            ->join ('product_varient', 'store_products.varient_id', '=', 'product_varient.varient_id')
             ->join('product','product_varient.product_id','=','product.product_id')
           ->Leftjoin('deal_product','product_varient.varient_id','=','deal_product.varient_id')
           ->where('product_varient.varient_id',$varient_id)
           ->where('store_products.store_id',$store_id)
           ->first();
       
        $n =$p->product_name;
     

        $insert = DB::table('store_orders')
                ->insertGetId([
                        'varient_id'=>$varient_id,
                        'qty'=>$order_qty,
                        'product_name'=>$n,
                        'varient_image'=>$p->varient_image,
                        'quantity'=>$p->quantity,
                        'unit'=>$p->unit,
                        'total_mrp'=>$total_mrp,
                        'order_cart_id'=>$cart_id,
                        'order_date'=>$created_at,
                        'price'=>$price1]);
      
 }
 
 $delcharge=DB::table('freedeliverycart')
           ->where('id', 1)
           ->first();
           
if ($delcharge->min_cart_value<=$price2){
    $charge=0;
}  
else{
    $charge =$delcharge->del_charge;
}
 
  if($insert){
        $oo = DB::table('orders')
            ->insertGetId(['cart_id'=>$cart_id,
            'total_price'=>$price2 + $charge,
            'price_without_delivery'=>$price2,
            'total_products_mrp'=>$price5,
            'delivery_charge'=>$charge,
            'user_id'=>$user_id,
            'store_id'=>$store_id,
            'rem_price'=>$price2 + $charge,
            'order_date'=> $created_at,
            'delivery_date'=> $delivery_date,
            'time_slot'=>$time_slot,
            'address_id'=>$ar->address_id]); 
                    
           $ordersuccessed = DB::table('orders')
                           ->where('order_id',$oo)
                           ->first();
        	$message = array('status'=>'1', 'message'=>'Proceed to payment', 'data'=>$ordersuccessed );
        	return $message;
        }
        else{
        	$message = array('status'=>'0', 'message'=>'insertion failed', 'data'=>[]);
        	return $message;
        }
       
 }


}
