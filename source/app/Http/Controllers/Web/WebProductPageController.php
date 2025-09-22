<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB; 
use Session;

class WebProductPageController extends Controller
{
    public function our_products(Request $req, WebOrderController $webOrderController, WebCategoryController $webCategoryController){
       
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

         $type = $req->type;

        switch($type){
            case 1: //top selling
             $allproducts = $webOrderController->top_selling();
             break;
            
             case 2: //deal of the day
              $allproducts = $webCategoryController->dealproduct();
              break;
            
            case 3: //Just Arrived Products
                $allproducts = $webOrderController->just_arrived_prod();
                break;

             default:
             abort(400);

        }

        return view('web.product.our_product',compact("title", "logo", "cust", "cust_phone", "category", "category_sub", "category_child","allproducts"));

    }
}
