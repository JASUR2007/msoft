<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBatchRequest;
use App\Services\InventoryService;
use App\Services\PurchaseService;
use Illuminate\Http\JsonResponse;

class BatchController extends Controller {
    public function __construct(private PurchaseService $purchaseService) {}
    public function profits(InventoryService $service): JsonResponse {
        return response()->json($service->profits());
    }
    public function store(StoreBatchRequest $request): JsonResponse {
        $data = $request->validated();
        $batch = $this->purchaseService->purchase($data);

        return response()->json([
            'message' => 'Product bought',
            'data' => $batch,
        ], 201);
    }
}
