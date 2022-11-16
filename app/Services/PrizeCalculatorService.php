<?php

namespace App\Services;

/**
 * Class PrizeCalculatorService.
 */
class PrizeCalculatorService
{

    public function getPrize($quantity){
        if ($quantity < 200) {
            return $quantity * 1;
        }elseif ($quantity <= 500) {
            return $quantity * 0.95;
        }elseif ($quantity <= 1000) {
            return $quantity * 0.85;
        }elseif ($quantity <= 2000) {
            return $quantity * 0.75;
        }else{
            return $quantity * 0.65;
        }
    }
}
