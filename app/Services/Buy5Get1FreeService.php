<?php

namespace App\Services;

use App\DiscountInterface;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Log;

class Buy5Get1FreeService implements DiscountInterface
{

    public function applyDiscount($orderId)
    {

        try {
            $result = [];

            $orderItems = OrderItem::with('product')
                ->where('order_id', $orderId)
                ->whereHas('product', function ($query) {
                    $query->where('category_id', 2);
                })->get();

            foreach ($orderItems as $item) {
                if ($item->quantity == 6) {
                    $result[] = [
                        'discountReason' => 'BUY_5_GET_1',
                        'discountAmount' => floatval($item->unit_price),
                        'subtotal' => floatval($item->total)
                    ];
                }
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('5 alana 1 bedava hesaplama hatası.', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}
