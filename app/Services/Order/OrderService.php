<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class OrderService
{
    public function createOrders(array $orders)
    {
        $results = [];
        foreach ($orders as $order) {
            $result = $this->createOrder($order);

            if ($result['success']) {
                $results['success'][] = $result['data'];
            } else {
                $results['error'][] = $result['error'];
            }
        }

        return $results;
    }

    private function createOrder($order)
    {
        DB::beginTransaction();

        try {

            $productIds = array_column($order['items'], 'productId');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($order['items'] as $item) {
                $product = $products->get($item['productId']);

                if ($item['quantity'] > $product->stock) {
                    throw new Exception("{$product->product_name} ürünü için yetersiz stok.Mevcut Stok: {$product->stock}");
                }
            }

            $newOrder = Order::create([
                'customer_id' => $order['customerId'],
                'total_price' => 0
            ]);

            foreach ($order['items'] as $item) {
                $product = $products->get($item['productId']);
                $price = $product->product_price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $newOrder->id,
                    'product_id' => $item['productId'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->product_price,
                    'total' => $price
                ]);

                //https://laravel.com/docs/11.x/queries#increment-and-decrement
                $product->decrement('stock', $item['quantity']);
                $newOrder->increment('total_price', $price);
            }

            DB::commit();

            return [
                'success' => true,
                'data' => [
                    'order_id' => $newOrder->id,
                    'customer_id' => $newOrder->customer_id,
                    'total_price' => $newOrder->total_price,
                    'items' => $newOrder->items
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Sipariş hatası: ' . $e->getMessage(), ['order_data' => $order]);
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    public function getOrderItems($id)
    {
        return Order::with('items')->findOrFail($id);
    }

    public function getAllOrder()
    {
        return Order::all();
    }

    public function deleteOrderByOrderId($id)
    {
        return Order::findOrFail($id)->delete();
    }
}
