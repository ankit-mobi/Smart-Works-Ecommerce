<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{
public function add(Request $request, $id, $store_id)
{

        $cart = session()->get('cart', []);

        $product = DB::table('store_products')
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
                    ->where('product.product_id', $id)
                    // ->where('store_products.store_id', $nearbystore->store_id)
                    ->where('store_products.store_id',$store_id)
                    ->where('store_products.price', '!=', NULL)
                    ->where('product.hide', 0)
                    ->first();

                    if (!$product) {
                      return back()->withErrors('Product not found or unavailable');
                     }


                    //  Make unique key with product + store
                     $cartKey = $id . '-' . $store_id;


        // If product already in cart, just increase quantity
    if (isset($cart[$cartKey])) {
        $cart[$cartKey]['quantity']++;
    } else {
        $cart[$cartKey] = [
            'product_id'    => $product->product_id,
            'store_id'      => $product->store_id,
            'name'          => $product->product_name,
            'image'         => $product->varient_image ?? $product->product_image,
            'price'         => $product->price,
            'mrp'           => $product->mrp,
            'unit'          => $product->unit,
            'varient_id'    => $product->varient_id,
            'description'   => $product->description,
            'quantity'      => 1,
        ];
    }

    session()->put('cart', $cart);

    return back()->with('success', 'Product added to cart!');
}


  public function update(Request $request, $product_id, $store_id)
{
 

    $cart = session()->get('cart', []);

       $cartKey = $product_id . '-' . $store_id;

    if (!isset($cart[$cartKey])) {
        return back()->with('error', 'Product not found in cart');
    }

    if ($request->action === 'increase') {
        $cart[$cartKey]['quantity']++;
    } elseif ($request->action === 'decrease') {
        $cart[$cartKey]['quantity']--;

        // remove if quantity drops to 0
        if ($cart[$cartKey]['quantity'] < 1) {
            unset($cart[$cartKey]);
        }
    }

    session()->put('cart', $cart);
    return back();
}

public function remove($product_id, $store_id)
{
    $cart = session()->get('cart', []);

    $cartKey = $product_id . '-' . $store_id; // composite key

    if (isset($cart[$cartKey])) {
        unset($cart[$cartKey]);
        session()->put('cart', $cart);
    }

    return back()->with('success','Product removed from cart!');
}

}