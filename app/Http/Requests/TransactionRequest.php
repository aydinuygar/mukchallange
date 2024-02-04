<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'subscription_id' => 'required|exists:subscriptions,id',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'subscription_id.required' => 'Abonelik ID alanı zorunludur.',
            'subscription_id.exists' => 'Belirtilen abonelik bulunamadı.',
            'price.required' => 'Fiyat alanı zorunludur.',
            'price.numeric' => 'Fiyat sayısal bir değer olmalıdır.',
            'price.min' => 'Fiyat sıfırdan büyük olmalıdır.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
