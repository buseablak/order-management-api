<?php

namespace App\Http\Controllers\Discount;

use App\Http\Controllers\Controller;
use App\Services\Discount\DiscountService;
use Illuminate\Support\Facades\Log;

class DiscountController extends Controller
{
   
    protected $discountService;

    function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }
    

 public function discount($id){

    try {
        $discounts = $this->discountService->calculateDiscountByOrder($id);
        return response()->json($discounts, 200);

    } catch (\Exception $e) {
        
        Log::error('İndirim hatası', ['order_id' => $id,'error' => $e->getMessage()]);

        return response()->json(['message' => 'İndirim hesaplanırken bir hata oluştu','error' => $e->getMessage()], 500);
    }

 }

}
