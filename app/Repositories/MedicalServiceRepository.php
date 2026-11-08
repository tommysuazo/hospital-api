<?php

namespace App\Repositories;

use App\Models\MedicalService;
use Illuminate\Database\Eloquent\Collection;

class MedicalServiceRepository
{
    /**
     * @return Collection<int, MedicalService>
     */
    public function getPublicList(): Collection
    {
        return MedicalService::query()
            ->select(['id', 'slug', 'sort_order'])
            ->with('translations')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function findPublicBySlug(string $slug): MedicalService
    {
        return MedicalService::query()
            ->select([
                'id',
                'medical_service_category_id',
                'slug',
                'sort_order',
            ])
            ->with([
                'translations',
                'category' => fn ($query) => $query
                    ->select(['id', 'slug'])
                    ->with('translations'),
            ])
            ->where('is_active', true)
            ->whereHas(
                'category',
                fn ($query) => $query->where('is_active', true)
            )
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
