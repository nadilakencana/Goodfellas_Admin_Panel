<?php

namespace App\Services;


class KodePesananService{
     public function kodePesanan($length = 5)
    {
        $str = '';
        $charecters = array_merge(range('A', 'Z'), range('a', 'z'));
        $max = count($charecters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $charecters[$rand];
        }
        return $str;
    }
}