<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller {
    public function store(StoreOrderRequest $request, OrderService $service): JsonResponse {
        $data = $request->validated();
        $order = $service->create($data);
        return response()->json([
            'data' => $order
        ], 201);
    }
}
