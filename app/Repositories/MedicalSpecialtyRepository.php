<?php

namespace App\Repositories;

use App\Models\MedicalSpecialty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MedicalSpecialtyRepository
{
    /**
     * @return Builder<MedicalSpecialty>
     */
    protected function query(): Builder
    {
        $query = MedicalSpecialty::query();

        $query->orderBy('slug');

        return $query;
    }

    /**
     * @return Collection<int, MedicalSpecialty>
     */
    public function getPublicList(string $locale): Collection
    {
        return $this->query()
            ->select(['id', 'slug'])
            ->with([
                'translations' => fn ($query) => $query->where('locale', $locale),
            ])
            ->where('is_active', true)
            ->get();
    }
}
