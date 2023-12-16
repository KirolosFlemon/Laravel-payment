<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'qty' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'code' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'card_number' => 'required|string|regex:/^[0-9]{16}$/',
            'expiration_date' => 'required|date',
            'cvv' => 'required|string|regex:/^[0-9]{3}$/',
        ];
    }
}
