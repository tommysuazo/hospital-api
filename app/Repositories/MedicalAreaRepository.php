<?php

namespace App\Repositories;

use App\Models\MedicalArea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class MedicalAreaRepository
{
    /**
     * @return Builder<MedicalArea>
     */
    protected function query(): Builder
    {
        $query = MedicalArea::query();

        $query->orderBy('id');

        return $query;
    }

    /**
     * @return Collection<int, MedicalArea>
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

    /**
     * @return Collection<int, MedicalArea>
     */
    public function getPublicTranslatedList(): Collection
    {
        return $this->query()
            ->select(['id', 'slug'])
            ->with('translations')
            ->where('is_active', true)
            ->get();
    }
}
