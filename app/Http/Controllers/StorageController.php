<?php

namespace App\Http\Controllers;

use App\Services\InventoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StorageController extends Controller {
    public function index(Request $request, InventoryService $service): JsonResponse {
        $data = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
            'storage_id' => ['sometimes', 'integer', 'exists:storages,id'],
        ]);

        $condition = isset($data['storage_id']) ? (int) $data['storage_id'] : null;
        $storage = $service->stock($data['date'], $condition);

        return response()->json($storage);
    }
}
