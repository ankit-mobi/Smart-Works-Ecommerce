<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
     protected $fillable = [
    'user_id','token','status','currency',
    'subtotal','discount_total','tax_total','shipping_total','grand_total'
  ];

  

}
