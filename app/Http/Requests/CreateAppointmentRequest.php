<?php

namespace App\Http\Requests;

use App\Enums\AppointmentForEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'doctor_uuid' => trim((string) $this->input('doctor_uuid')),
            'medical_area_slug' => trim((string) $this->input('medical_area_slug')),
            'reason' => $this->filled('reason')
                ? trim((string) $this->input('reason'))
                : null,
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'doctor_uuid' => ['required', 'uuid'],
            'medical_area_slug' => ['required', 'string', 'max:255'],
            'appointment_for' => [
                'required',
                Rule::in(array_column(AppointmentForEnum::cases(), 'value')),
            ],
            'appointment_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'doctor_uuid.required' => 'Selecciona un doctor.',
            'doctor_uuid.uuid' => 'El doctor seleccionado no es válido.',
            'medical_area_slug.required' => 'Selecciona un área médica.',
            'appointment_for.required' => 'Indica para quién es la cita.',
            'appointment_for.in' => 'La opción seleccionada para la cita no es válida.',
            'appointment_date.required' => 'Selecciona la fecha de la cita.',
            'appointment_date.date_format' => 'La fecha seleccionada no es válida.',
            'appointment_date.after_or_equal' => 'La fecha de la cita no puede estar en el pasado.',
            'reason.max' => 'El motivo no puede superar los 1000 caracteres.',
        ];
    }
}
