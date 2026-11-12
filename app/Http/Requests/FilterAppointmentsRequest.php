<?php

namespace App\Http\Requests;

use App\Enums\AppointmentStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterAppointmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'doctor' => $this->filled('doctor') ? trim((string) $this->input('doctor')) : null,
            'status' => $this->filled('status') ? trim((string) $this->input('status')) : null,
            'date' => $this->filled('date') ? trim((string) $this->input('date')) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'date' => ['nullable', 'date_format:Y-m-d'],
            'doctor' => ['nullable', 'uuid'],
            'status' => ['nullable', Rule::in(array_column(AppointmentStatusEnum::cases(), 'value'))],
        ];
    }
}
