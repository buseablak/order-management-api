<?php

namespace App\Services\Discount;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class DiscountService
{

  protected array $discountApplyServices;

    function __construct(array $discountApplyServices)
    {
        $this->discountApplyServices = $discountApplyServices;
    }

    public function calculateDiscountByOrder($id)
    {
        try {
        $allDiscounts = [];
            foreach ($this->discountApplyServices as $discountApplyService) {
                $discount = $discountApplyService->applyDiscount($id);
                $allDiscounts = array_merge($allDiscounts, $discount);
            }
            
            if (empty($allDiscounts)) {
                return [
                    'orderId' => $id,
                    'discount' => ['Bu sipariş indirim koşullarını karşılamamaktadır.'],
                    'totalDiscount' => 0,
                    'discountedTotal' => 0
                ];
            }
            $order = Order::findOrFail($id);

            $totalDiscount = array_sum(array_column($allDiscounts, 'discountAmount'));
            $discountedTotal = $order->total_price - $totalDiscount;
            
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