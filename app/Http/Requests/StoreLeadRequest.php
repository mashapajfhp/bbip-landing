<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:30'],
            'challenge' => ['required', 'string', 'max:2000'],
            'consent' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your full name.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'whatsapp.required' => 'Please provide your WhatsApp number.',
            'challenge.required' => 'Please tell us what you need help with.',
            'consent.required' => 'You must agree to be contacted.',
            'consent.accepted' => 'You must agree to be contacted.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => trim($this->input('name')),
            'email' => trim($this->input('email')),
            'whatsapp' => trim($this->input('whatsapp')),
            'challenge' => trim($this->input('challenge')),
        ]);
    }
}
