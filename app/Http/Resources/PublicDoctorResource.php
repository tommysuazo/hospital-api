<?php

namespace App\Http\Resources;

use App\Enums\DayOfWeekEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicDoctorResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dayNumbers = [
            DayOfWeekEnum::Sunday->value => 0,
            DayOfWeekEnum::Monday->value => 1,
            DayOfWeekEnum::Tuesday->value => 2,
            DayOfWeekEnum::Wednesday->value => 3,
            DayOfWeekEnum::Thursday->value => 4,
            DayOfWeekEnum::Friday->value => 5,
            DayOfWeekEnum::Saturday->value => 6,
        ];

        return [
            'id' => $this->id,
            'uuid' => $this->uuid,
            'url' => route('public.doctors.show', $this->uuid),
            'name' => trim("{$this->user->first_name} {$this->user->last_name}"),
            'photo' => $this->picture,
            'room' => $this->consulting_room,
            'ext' => $this->phone_extension,
            'descriptions' => $this->translations
                ->pluck('professional_summary', 'locale')
                ->all(),
            'specialties' => PublicMedicalSpecialtyResource::collection(
                $this->medicalSpecialties
            )->resolve($request),
            'workDays' => $this->schedules
                ->pluck('day_of_week')
                ->map(fn (string $day): int => $dayNumbers[$day])
                ->unique()
                ->sort()
                ->values()
                ->all(),
        ];
    }
}
