<?php

namespace App\Http\Requests\Auth;

use App\Enums\UserDocumentTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => mb_strtolower(trim((string) $this->input('email')))]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'document_type' => ['required', Rule::in(array_column(UserDocumentTypeEnum::cases(), 'value'))],
            'document_number' => ['required', 'string', 'max:50', Rule::unique('clients')->where(fn ($query) => $query->where('document_type', $this->input('document_type')))],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', Rule::in(['female', 'male', 'other'])],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:clients,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['accepted'],
        ];
    }
}
