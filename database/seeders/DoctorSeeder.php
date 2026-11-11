<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\MedicalArea;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $count = max(0, (int) config('seeding.doctor_count', 10));

        if ($count === 0) {
            return;
        }

        if (! MedicalArea::query()->where('is_active', true)->exists()) {
            $this->call(MedicalAreaSeeder::class);
        }

        Doctor::factory()
            ->count($count)
            ->withDirectoryRelations()
            ->create();
    }
}
