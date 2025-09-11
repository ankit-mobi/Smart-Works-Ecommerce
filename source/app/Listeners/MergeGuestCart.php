<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class MergeGuestCart
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
       $guestCart = Session::get('cart');

    if ($guestCart) {
        $userId = Auth::id();
        $userCart = DB::table('cart')->where('user_id', $userId)->where('status', 'active')->first();

        if (!$userCart) {
            $userCartId = DB::table('cart')->insertGetId(['user_id' => $userId]);
        } else {
            $userCartId = $userCart->id;
        }

        foreach ($guestCart as $item) {
            $existingItem = DB::table('cart_item')->where('cart_id', $userCartId)->where('varient_id', $item['varient_id'])->first();

            if ($existingItem) {
                DB::table('cart_item')->where('id', $existingItem->id)->increment('qty', $item['qty']);
            } else {
                DB::table('cart_item')->insert([
                    'cart_id' => $userCartId,
                    'varient_id' => $item['varient_id'],
                    'qty' => $item['qty'],
                    'price' => $item['price'],
                    'line_total' => $item['line_total'],
                ]);
            }
        }
        // Clear the session cart after merging
        Session::forget('cart');
    }
    }
}
