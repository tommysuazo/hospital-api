<?php

namespace App\Repositories;

use App\Models\MedicalProcedure;
use Illuminate\Database\Eloquent\Collection;

class MedicalProcedureRepository
{
    /**
     * @return Collection<int, MedicalProcedure>
     */
    public function getPublicList(): Collection
    {
        return MedicalProcedure::query()
            ->select(['id', 'slug'])
            ->with('translations')
            ->where('is_active', true)
            ->orderBy('slug')
            ->get();
    }
}
