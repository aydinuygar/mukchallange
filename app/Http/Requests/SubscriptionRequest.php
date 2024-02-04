<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class SubscriptionRequest extends FormRequest
{
     public function authorize()
    {
        return true; // Herkesin abonelik oluşturmasına izin veriyoruz, bu yüzden true dönüyoruz.
    }

    public function rules()
    {
        return [
            'start_date' => 'required|date_format:d.m.Y|after_or_equal:today',
        ];
    }

    public function messages()
    {
        return [
            'start_date.required' => 'Başlangıç tarihi alanı zorunludur.',
            'start_date.date_format' => 'Başlangıç tarihi geçerli bir tarih formatında olmalıdır (GG.AA.YYYY).',
            'start_date.after_or_equal' => 'Başlangıç tarihi bugünün tarihine eşit veya daha ileri bir tarih olmalıdır.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
