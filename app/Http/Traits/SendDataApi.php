<?php

namespace App\Http\Traits;


trait SendDataApi{
   public static function bind($data,$status=200){
        return response()->json($data,$status);
   }
}
?>