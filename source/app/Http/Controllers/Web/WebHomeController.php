<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Web\AllProductController;
use App\http\Controllers\Web\WebBannerController;
use App\Http\Controllers\Web\WebUserController;
use DB;
use Session;

class WebHomeController extends Controller
{
    public function web(Request $request, AllProductController $allProductController, WebBannerController $bannerController, WebUserController $webUserController)
    {
        $title = "Home";
        $logo = DB::table('tbl_web_setting')
                ->where('set_id', '1')
                ->first();	
        
        $cust_phone = Session::get('bamaCust');
        $cust = DB::table('users')
            ->where('user_phone', $cust_phone)
            ->first();

        $latest_category = $allProductController->latest_category_three();
        $deal_products = $allProductController->deal_products();
        $latest_products = $allProductController->latest_products();
        $banners_list = [
            'main_banner' => $bannerController->mainbannerlist(),
            'secondary_banner' =>$bannerController->secbannerlist(),
        ];
        $products_siding = $allProductController->products_siding();
        $cate_siding = $allProductController->cate_siding();

                        

        return view('web.home.main', compact('title','logo', 'cust', 'cust_phone', 'latest_category', 'deal_products', 'latest_products', 'banners_list','products_siding','cate_siding'));
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
