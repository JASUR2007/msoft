<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSalesRefundRequest;
use App\Models\Order;
use App\Services\RefundService;
use Illuminate\Http\JsonResponse;

class SalesRefundController extends Controller {
    public function __construct(private RefundService $refundService) {}

    public function store(StoreSalesRefundRequest $request, Order $order): JsonResponse {
        $validate = $request->validated();
        $sales = $this->refundService->refundSale($validate, $order);

        return response()->json([
            'data' => $sales,
        ], 201);
    }
}
