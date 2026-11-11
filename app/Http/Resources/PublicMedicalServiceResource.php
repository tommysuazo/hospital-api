<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublicMedicalServiceResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'url' => route(
                $request->routeIs('client.services.*') ? 'client.services.show' : 'public.services.show',
                $this->slug
            ),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category->id,
                'slug' => $this->category->slug,
                'names' => $this->category->translations
                    ->pluck('name', 'locale')
                    ->all(),
            ]),
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
