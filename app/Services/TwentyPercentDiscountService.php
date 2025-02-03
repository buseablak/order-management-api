<?php

namespace App\Services;

use App\DiscountInterface;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class TwentyPercentDiscountService implements DiscountInterface
{
    public function applyDiscount($orderId)
    {
        try {
            $result = [];
            $orderItems = OrderItem::with('product')
                ->where('order_id', $orderId)
                ->whereHas('product', function ($query) {
                    $query->where('category_id', 1);
                })
                ->get()
                ->sortBy('unit_price');

            if ($orderItems->count() >= 2) {
                $cheapestItem = $orderItems->first();
                $discountAmount = $cheapestItem->unit_price * 0.20;

                $result[] = [
                    'discountReason' => 'CHEAPEST_20_PERCENT_OFF',
                    'discountAmount' => $discountAmount,
                    'subtotal' => floatval($cheapestItem->total)
                ];
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('%20 indirim hesaplama hatası', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
