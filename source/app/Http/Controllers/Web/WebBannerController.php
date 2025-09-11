<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class WebBannerController extends Controller
{
     public function mainbannerlist()
    {  
        $main_banners = DB::table('banner')
                      ->get();
                      
         return $main_banners;
    }
    
    public function secbannerlist()
    {  
        $Sec_banners = DB::table('secondary_banner')
                    ->get();
        
         return $Sec_banners;
    }
}
