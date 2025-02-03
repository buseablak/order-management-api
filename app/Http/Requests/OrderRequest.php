<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            '*.customerId' => 'required|exists:customers,id',
            '*.items' => 'required|array',
            '*.items.*.productId' => 'required|exists:products,id',
            '*.items.*.quantity' => 'required|integer|min:1'
        ];
    }

    public function messages(): array
    {
        return [
            '*.customerId.required' => 'Müşteri ID girilmesi zorunludur',
            '*.customerId.exists' => 'Geçersiz müşteri ID',
            '*.items.required' => 'Siparişin ürün bilgileri zorunludur',
            '*.items.array' => 'Sipariş ürünleri dizi formatında olmalıdır',
            '*.items.*.productId.required' => 'Ürün ID girilmesi zorunludur',
            '*.items.*.productId.exists' => 'Geçersiz ürün ID',
            '*.items.*.quantity.required' => 'Ürün adeti girilmesi zorunludur',
            '*.items.*.quantity.integer' => 'Ürün adeti tam sayı olmalıdır',
            '*.items.*.quantity.min' => 'Ürün adeti en az 1 olmalıdır'
        ];
    }
}
