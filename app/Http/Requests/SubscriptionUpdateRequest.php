<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubscriptionUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'update_date' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'update_date.required' => 'Güncelleme tarihi alanı zorunludur.',
            'update_date.date' => 'Güncelleme tarihi geçerli bir tarih formatında olmalıdır.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
