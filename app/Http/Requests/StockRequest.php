<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StockRequest extends FormRequest
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
            'per_page' => ['integer', 'min:1'],
            'brand' => ['string'],
            'model' => ['string'],
            'vin' => ['string'],
            'price_from' => ['integer', 'min:0'],
            'price_to' => ['integer', 'min:0'],
            'year_from' => ['integer', 'min:1900'],
            'year_to' => ['integer', 'min:1900'],
            'mileage_from' => ['integer', 'min:0'],
            'mileage_to' => ['integer', 'min:0'],
        ];
    }
}
