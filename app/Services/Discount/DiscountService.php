<?php

namespace App\Services\Discount;

use App\Services\Discount\Buy5Get1FreeService;
use App\Services\Discount\TenPercentDiscountService;
use App\Services\Discount\TwentyPercentDiscountService;
use Illuminate\Support\Facades\Log;

class DiscountService
{

    protected $tenPercentDiscountService;
    protected $buy5Get1FreeService;
    protected $twentyPercentDiscountService;

    function __construct(TenPercentDiscountService $tenPercentDiscountService,Buy5Get1FreeService $buy5Get1FreeService,TwentyPercentDiscountService $twentyPercentDiscountService)
    {
        $this->tenPercentDiscountService = $tenPercentDiscountService;
        $this->buy5Get1FreeService = $buy5Get1FreeService;
        $this->twentyPercentDiscountService = $twentyPercentDiscountService;
    }

    public function calculateDiscountByOrder($id)
    {
        try {
            $tenPercentDiscount = $this->tenPercentDiscountService->applyDiscount($id);
            $buy5Get1Free = $this->buy5Get1FreeService->applyDiscount($id);
            $twentyPercentDiscount = $this->twentyPercentDiscountService->applyDiscount($id);

            $allDiscounts = array_merge($tenPercentDiscount, $buy5Get1Free, $twentyPercentDiscount);
            
            if (empty($allDiscounts)) {
                return [
                    'orderId' => $id,
                    'discount' => ['Bu sipariş indirim koşullarını karşılamamaktadır.'],
                    'totalDiscount' => 0,
                    'discountedTotal' => 0
                ];
            }

            $totalDiscount = array_sum(array_column($allDiscounts, 'discountAmount'));
            $discountedTotal = $tenPercentDiscount[0]['subtotal'] - $totalDiscount;
            
            return [
                'orderId' => $id,
                'discount' => $allDiscounts,
                'totalDiscount' => $totalDiscount,
                'discountedTotal' => round($discountedTotal,2) 
            ];

        } catch (\Exception $e) {
            Log::error('İndirim hesaplama hatası', ['order_id' => $id,'error' => $e->getMessage()]);
            throw $e;
        }
    }

}