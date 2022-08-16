<?php

namespace App\Services;

class Censurator
{
    const censure = ["Drogue", "alcool", "iptv"];

    public function purify($string){
        foreach (self::censure as $censure){
            $string = str_ireplace($censure,"*",$string);
        }
        return $string;
    }
}