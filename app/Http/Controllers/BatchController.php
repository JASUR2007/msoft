<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBatchRequest;
use App\Services\PurchaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BatchController extends Controller{
    public function __construct(private PurchaseService $purchaseService) {}

    public function store(StoreBatchRequest $request): JsonResponse {
        $data = $request->validated();
        $batch = $this->purchaseService->purchase($data);
        return response()->json([
            'message' => 'Product bought',
            'data' => $batch
        ],201);
    }
}
