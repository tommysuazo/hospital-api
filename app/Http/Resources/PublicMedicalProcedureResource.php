<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicMedicalProcedureResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'translations' => $this->translations
                ->mapWithKeys(fn ($translation) => [
                    $translation->locale => [
                        'name' => $translation->name,
                        'short_description' => $translation->short_description,
                        'description' => $translation->description,
                    ],
                ])
                ->all(),
        ];
    }
}
