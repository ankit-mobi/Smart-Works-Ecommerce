<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;

use function Laravel\Prompts\select;

class AllProductController extends Controller
{
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

    
    public function latest_category_three()   //top category it fetchs 6 category
    {
        $latest_category = DB::table('categories')
            ->orderBy('cat_id', 'desc')
            ->take(6)
            ->get();

        return $latest_category;
    }

    public function deal_products()
    {
        $deal_products  = DB::table('deal_product')
            ->join('product_varient', 'deal_product.varient_id', '=', 'product_varient.varient_id')
            ->join('product', 'product_varient.product_id', '=', 'product.product_id')
            ->join('categories', 'product.cat_id', '=', 'categories.cat_id')
            ->select(
                'deal_product.*',
                'product_varient.varient_image',
                'product_varient.base_mrp',
                'product_varient.base_price',
                'product.product_name',
                'product.product_id',
                'categories.title'
            )
            ->orderBy('deal_product.deal_id', 'desc')
            ->get();

        return $deal_products;
    }

    //Just Arrived Products
    public function latest_products()
    {
        $latest_products = DB::table('product')
            ->join('product_varient', 'product_varient.product_id', '=', 'product.product_id')
            ->select(
                'product.*',
                'product_varient.base_mrp',
                'product_varient.base_price',
                'product.product_name',
            )
            ->orderBy('product.product_id', 'desc')
            ->take(12)
            ->get();

        return $latest_products;
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
        $product_id = $request->id;
        $product = DB::table(table: 'product as p')
            ->join('product_varient as pv', 'pv.product_id', '=', 'p.product_id')
            ->where('p.product_id', $product_id)
            ->select(
                'p.*',
                'pv.varient_id',
                'pv.base_mrp',
                'pv.base_price',
                'pv.description'
            )
            ->first(); // single product info

        // related product to selected product
        $related_prods = DB::table(table: 'product as p')
            ->join('product_varient as pv', 'pv.product_id', '=', 'p.product_id')
            ->where('p.cat_id', $product->cat_id)
            ->select(
                'p.*',
                'pv.base_mrp',
                'pv.base_price',
                'pv.description'
            )
            ->get();

        return view('web.product.product_preview', compact("title","logo","category", "category_sub", "category_child", "product", 'cust', 'cust_phone', 'related_prods'));
        // return view('web.product.product_preview', compact("product",'related_prods'));

    }


     public function products_siding()
    {
        $products_All = DB::table('product as p')
            ->join('product_varient as pv', 'pv.product_id', '=', 'p.product_id')
            ->select(
                'p.*',
                'pv.varient_id',
                'pv.base_mrp',
                'pv.base_price',
                'pv.varient_image',
            )         
            ->take(12)
            ->get();
        return $products_All;
    }


    public function cate_siding()
    {     
        $cate_All = DB::table('categories')
            ->get();

        return $cate_All;
    }
}
