<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class CartComposer
{
    public function compose(View $view)
    {
        $items = [];

        if (Session::has('bamaCust')) {
            $phone = Session::get('bamaCust');
            $user = DB::table('users')->where('user_phone', $phone)->first();

            if ($user) {
                $cart = DB::table('cart')
                    ->where('user_id', $user->user_id)
                    ->where('status', 'active')
                    ->first();

                if ($cart) {
                    $items = DB::table('cart_item')
                        ->join('product_varient', 'cart_item.varient_id', '=', 'product_varient.varient_id')
                        ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                        ->select(
                            'cart_item.*',
                            'product_varient.varient_image',
                            'product.product_name',
                            DB::raw('cart_item.qty * cart_item.price as line_total')
                        )
                        ->where('cart_id', $cart->id)
                        ->get();
                }
            }
        } else {
            $items = collect(Session::get('cart', []))
                ->map(fn($item) => (object) $item);
        }

        $view->with('items', $items);
    }
}
