<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'   => ['required', 'string', 'max:100'],
            'last_name'    => ['required', 'string', 'max:100'],
            'email'        => ['required', 'email:rfc', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'service_type' => ['required', 'in:تغطية صحفية,مقالات تحليلية,تسويق محتوى'],
            'message'      => ['required', 'string', 'min:10', 'max:3000'],
            'website'      => ['nullable', 'max:0'], // honeypot : doit rester vide
        ];
    }
}
