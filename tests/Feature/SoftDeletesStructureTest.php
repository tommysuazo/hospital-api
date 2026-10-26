<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Doctor;
use App\Models\DoctorUnavailability;
use App\Models\MedicalProcedure;
use App\Models\MedicalResult;
use App\Models\MedicalSpecialty;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SoftDeletesStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_requested_models_and_tables_use_soft_deletes(): void
    {
        $modelsByTable = [
            'users' => User::class,
            'clients' => Client::class,
            'doctors' => Doctor::class,
            'doctor_unavailabilities' => DoctorUnavailability::class,
            'medical_specialties' => MedicalSpecialty::class,
            'medical_procedures' => MedicalProcedure::class,
            'appointments' => Appointment::class,
            'medical_results' => MedicalResult::class,
        ];

        foreach ($modelsByTable as $table => $model) {
            $this->assertTrue(
                Schema::hasColumn($table, 'deleted_at'),
                "The {$table} table is missing deleted_at."
            );
            $this->assertContains(
                SoftDeletes::class,
                class_uses_recursive($model),
                "The {$model} model does not use SoftDeletes."
            );
        }
    }
}
