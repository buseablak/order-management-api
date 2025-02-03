<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $orderService;

    function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        $orders = $this->orderService->getAllOrder();

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        try {
            $orders = $request->validated();
            $results = $this->orderService->createOrders($orders);
            
            $successCount = count($results['success'] ?? []);
            $errorCount = count($results['error'] ?? []);
    
            if ($successCount > 0 && $errorCount > 0) {
                return response()->json([
                    'message' => "{$successCount} sipariş başarıyla oluşturuldu, {$errorCount} sipariş oluşturulamadı",
                    'successful_orders' => $results['success'],
                    'failed_orders' => $results['error']
                ], 207); 
            }else if ($errorCount > 0) {
                return response()->json([
                    'message' => "Siparişler oluşturulamadı.",
                    'errors' => $results['error']
                ], 400); 
            }else{
                return response()->json([
                    'message' => "Siparişler başarıyla oluşturuldu",
                    'data' => $results['success']
                ], 201); 
            }
        
        } catch (\Exception $e) {
            Log::error('Sipariş hatası: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Beklenmeyen bir hata oluştu',
                'error' => $e->getMessage()
            ], 500); 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $orderItems = $this->orderService->getOrderItems($id);
            return response()->json($orderItems);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Sipariş Bulunamadı',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->orderService->deleteOrderByOrderId($id);

            return response()->json(['message' => 'Silme işlemi başarılı']);
        } catch (\Exception $e) {

            return response()->json(['message' => 'Silme işlemi başarısız', 'error' => $e->getMessage()]);
        }
    }
}
