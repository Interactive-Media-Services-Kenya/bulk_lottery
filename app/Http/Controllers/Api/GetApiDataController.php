<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PrizeCalculatorService;
use Illuminate\Http\Request;

class GetApiDataController extends Controller
{
    protected $prizeCalculatorService;
    public function __construct(PrizeCalculatorService $prizeCalculatorService)
    {
        $this->prizeCalculatorService = $prizeCalculatorService;
    }

    public function getPrize($quantity){
        $amount = $this->prizeCalculatorService->getPrize($quantity);
        return $amount;
    }
}
