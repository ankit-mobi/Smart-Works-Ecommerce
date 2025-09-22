<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\AllProductController;
use App\http\Controllers\Web\WebBannerController;
use App\Http\Controllers\Web\WebUserController;
use App\Http\Controllers\Web\WebOrderController;
use App\Http\Controllers\Web\WebCategoryController;
use DB;
use Session;

class WebHomeController extends Controller
{
    public function web(Request $request, AllProductController $allProductController, WebBannerController $bannerController, WebUserController $webUserController, WebOrderController $webOrderController, WebCategoryController $webCategoryController)
    {
        $title = "Home";
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	
        
        $cust_phone = Session::get('bamaCust');
        $cust = DB::table('users')
            ->where('user_phone', $cust_phone)
            ->first();

        $banners_list = [
            'main_banner' => $bannerController->mainbannerlist(),
            'secondary_banner' =>$bannerController->secbannerlist(),
        ];    

        

        $top_selling = $webOrderController->top_selling();
        $deal_products = $webCategoryController->dealproduct();
        $new_prods = $webOrderController->just_arrived_prod();
        $top_ten_cate = $webCategoryController->top_ten_cate();
        
        

                        

        return view('web.home.main', compact('title','logo', 'cust', 'cust_phone', 'banners_list', 'top_selling', 'deal_products', 'new_prods', 'top_ten_cate')); //'latest_category', 'latest_products' , 'products_siding', 'cate_siding'
    }


    public function aboutus(Request $request)
    {
        $title = "About Us";
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	

        $cust_phone = Session::get('bamaCust');
        $cust = DB::table('users')
            ->where('user_phone', $cust_phone)
            ->first();
        $about = DB::table('aboutuspage')
            ->first();

        return view('web.about', compact('title','logo', 'about', 'cust', 'cust_phone'));
    }
    public function terms(Request $request)
    {
        $title = "About Us";
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	
        $cust_phone = Session::get('bamaCust');
        $cust = DB::table('users')
            ->where('user_phone', $cust_phone)
            ->first();

        $about = DB::table('termspage')
            ->first();

        return view('web.terms', compact('title','logo', 'about', 'cust', 'cust_phone'));
    }
}
