<?php

namespace App\Http\Resources;

use App\Enums\AppointmentStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientAppointmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $translation = $this->medicalArea->translations
            ->firstWhere('locale', 'es') ?? $this->medicalArea->translations->first();

        return [
            'uuid' => $this->uuid,
            'date' => $this->appointment_date->toDateString(),
            'turnNumber' => $this->turn_number,
            'appointmentFor' => $this->appointment_for,
            'reason' => $this->reason,
            'status' => $this->status,
            'canEdit' => $this->status === AppointmentStatusEnum::Confirmed->value,
            'doctor' => [
                'uuid' => $this->doctor->uuid,
                'name' => trim("{$this->doctor->user->first_name} {$this->doctor->user->last_name}"),
                'photo' => $this->doctor->picture,
                'room' => $this->doctor->consulting_room,
            ],
            'medicalArea' => [
                'slug' => $this->medicalArea->slug,
                'name' => $translation?->name ?? $this->medicalArea->slug,
            ],
        ];
    }
}
