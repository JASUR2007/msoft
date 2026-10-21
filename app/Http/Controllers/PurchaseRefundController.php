<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReturnRequest;
use App\Services\RefundService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class PurchaseRefundController extends Controller
{
    public function __construct(protected RefundService $refundService) {}

    public function refund(StoreReturnRequest $request): JsonResponse {
        $refund = $request->validated();

        return response()->json([
            'message' => true,

        ]);
    }
}
