<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBatchRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'provider_id' => ['required', 'integer', 'exists:providers,id'],
            'products' => ['required', 'array', 'min:1'],
            'products.*' => ['required', 'array:product_id,storage_id,quantity,purchase_price,sale_price'],
            'products.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'products.*.storage_id' => ['required', 'integer', 'exists:storages,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'products.*.purchase_price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999.99'],
            'products.*.sale_price' => ['required', 'numeric', 'decimal:0,2', 'min:0', 'max:9999999.99'],
        ];
    }
}
