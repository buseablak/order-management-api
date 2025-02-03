<?php

namespace App\Services\Discount;

use App\DiscountInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class TenPercentDiscountService implements DiscountInterface
{

    public function applyDiscount($orderId)
    {
        try {
            $result = [];
            $order = Order::findOrFail($orderId);

            if ($order->total_price >= 1000) {
                $result[] = [
                    'discountReason' => '10_PERCENT_OVER_1000',
                    'discountAmount' => round(($order->total_price * 0.10),2),
                    'subtotal' => floatval($order->total_price)
                ];
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('%10 indirim hesaplama hatası', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
