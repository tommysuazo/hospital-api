<?php

namespace App\Repositories;

use App\Models\MedicalServiceCategory;
use Illuminate\Database\Eloquent\Collection;

class MedicalServiceCategoryRepository
{
    /**
     * @return Collection<int, MedicalServiceCategory>
     */
    public function getPublicList(): Collection
    {
        return MedicalServiceCategory::query()
            ->select(['id', 'slug', 'sort_order'])
            ->with([
                'translations',
                'medicalServices' => fn ($query) => $query
                    ->select([
                        'id',
                        'medical_service_category_id',
                        'slug',
                        'sort_order',
                    ])
                    ->with('translations')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('id'),
            ])
            ->where('is_active', true)
            ->whereHas(
                'medicalServices',
                fn ($query) => $query->where('is_active', true)
            )
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }
}
