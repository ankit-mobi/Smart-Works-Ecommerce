<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // You can remove this if not using Laravel's built-in Auth
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PDO;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $varientId = $request->input('varient_id');
        $qty = $request->input('qty', 1);

        $productVarient = DB::table('product_varient')->where('varient_id', $varientId)->first();
        if (!$productVarient) {
            return back()->with('error', 'Product not found!');
        }

        $dealPrice = DB::table('deal_product')
            ->where('varient_id', $varientId)
            ->where('valid_from', '<=', now())
            ->where('valid_to', '>=', now())
            ->where('status', 1)
            ->value('deal_price');

        $price = $dealPrice ?? $productVarient->base_price;
        $lineTotal = $price * $qty;

        // Use your session-based authentication check
        if (Session::has('bamaCust')) {
            // Logic for a logged-in user with session auth
            $phone = Session::get('bamaCust');
            $user = DB::table('users')->where('user_phone', $phone)->first();
            
            if ($user) {
                $userId = $user->user_id; // Assuming the user ID is in the 'id' column
                $cart = DB::table('cart')->where('user_id', $userId)->where('status', 'active')->first();

                if (!$cart) {
                    $cartId = DB::table('cart')->insertGetId([
                        'user_id' => $userId,
                        'status' => 'active',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                } else {
                    $cartId = $cart->id;
                }

                $cartItem = DB::table('cart_item')->where('cart_id', $cartId)->where('varient_id', $varientId)->first();
                if ($cartItem) {
                    DB::table('cart_item')
                        ->where('id', $cartItem->id)
                        ->update([
                            'qty' => $cartItem->qty + $qty,
                            'line_total' => $cartItem->line_total + $lineTotal,
                            'updated_at' => now(),
                        ]);
                } else {
                    DB::table('cart_item')->insert([
                        'cart_id' => $cartId,
                        'varient_id' => $varientId,
                        'qty' => $qty,
                        'price' => $price,
                        'line_total' => $lineTotal,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } else {
            // Logic for a guest user using the session
            $cart = Session::get('cart', []);
            $product = DB::table('product')->where('product_id', $productVarient->product_id)->first();
            
            if (isset($cart[$varientId])) {
                $cart[$varientId]['qty'] += $qty;
                $cart[$varientId]['line_total'] = $cart[$varientId]['price'] * $cart[$varientId]['qty'];
            } else {
                $cart[$varientId] = [
                    'varient_id' => $varientId,
                    'product_id' => $productVarient->product_id,
                    'product_name' => $product->product_name ?? 'N/A',
                    'varient_image' => $productVarient->varient_image ?? 'N/A',
                    'qty' => $qty,
                    'price' => $price,
                    'line_total' => $lineTotal,
                ];
            }
            Session::put('cart', $cart);
        }

        return back()->with('success', 'Product added to cart!');
    }
    public function index()
    {
        // Use your session-based authentication check
        if (Session::has('bamaCust')) {
            $phone = Session::get('bamaCust');
            $user = DB::table('users')->where('user_phone', $phone)->first();

            if ($user) {
                $cart = DB::table('cart')
                ->where('user_id', $user->user_id)
                ->where('status', 'active')->first();
                if ($cart) {
                    $items = DB::table('cart_item')
                                ->join('product_varient', 'cart_item.varient_id', '=', 'product_varient.varient_id')
                                ->join('product', 'product_varient.product_id', '=', 'product.product_id')
                                ->select('cart_item.*', 'product_varient.varient_image', 'product.product_name','product.product_id')
                                ->where('cart_id', $cart->id)
                                ->get();
                       return $items;
                }
            }
        } else {
            $items = Session::get('cart', []);
            $items = collect($items)->map(function ($item) {
                return (object) $item;
            });
               return $items;
        }
        
       
    }

 
}