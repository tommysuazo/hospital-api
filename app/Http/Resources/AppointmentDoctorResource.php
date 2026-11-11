<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentDoctorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => trim("{$this->user->first_name} {$this->user->last_name}"),
            'photo' => $this->picture,
            'room' => $this->consulting_room,
            'medicalAreas' => PublicMedicalAreaResource::collection(
                $this->medicalAreas
            )->resolve($request),
            'schedules' => $this->schedules
                ->map(fn ($schedule): array => [
                    'dayOfWeek' => $schedule->day_of_week,
                    'startTime' => substr($schedule->start_time, 0, 5),
                    'endTime' => substr($schedule->end_time, 0, 5),
                ])
                ->values()
                ->all(),
            'unavailabilities' => $this->unavailabilities
                ->map(fn ($period): array => [
                    'startsOn' => $period->starts_on->toDateString(),
                    'endsOn' => $period->ends_on->toDateString(),
                ])
                ->values()
                ->all(),
        ];
    }
}
