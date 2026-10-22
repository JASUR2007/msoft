<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReturnRequest;
use App\Models\Batch;
use App\Services\RefundService;
use Illuminate\Http\JsonResponse;

class PurchaseRefundController extends Controller {
    public function __construct(protected RefundService $refundService) {}
    public function store(StoreReturnRequest $request, Batch $batch): JsonResponse {
        $data = $request->validated();
        $refund = $this->refundService->refund($data, $batch);

        return response()->json([
            'message' => true,
            'data' => $refund,

        ]);
    }
}
