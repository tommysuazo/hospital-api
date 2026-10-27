<?php

namespace Tests\Feature;

use App\Enums\MedicalResultStatusEnum;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\MedicalResult;
use App\Models\MedicalResultTranslation;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class MedicalResultStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_medical_result_generates_a_uuid_uses_it_for_routes_and_soft_deletes(): void
    {
        $medicalResult = MedicalResult::factory()->create(['uuid' => null]);

        $this->assertTrue(Str::isUuid($medicalResult->uuid));
        $this->assertSame('uuid', $medicalResult->getRouteKeyName());

        $medicalResult->delete();

        $this->assertSoftDeleted($medicalResult);
        $this->assertNotNull(
            MedicalResult::withTrashed()->findOrFail($medicalResult->id)->deleted_at
        );
    }

    public function test_medical_result_has_expected_relations(): void
    {
        $client = Client::factory()->create();
        $appointment = Appointment::factory()->create(['client_id' => $client->id]);
        $creator = User::factory()->create();

        $medicalResult = MedicalResult::factory()
            ->forAppointment($appointment)
            ->createdBy($creator)
            ->create();
        MedicalResult::factory()
            ->forAppointment($appointment)
            ->createdBy($creator)
            ->create();

        $this->assertTrue($medicalResult->client->is($client));
        $this->assertTrue($medicalResult->appointment->is($appointment));
        $this->assertTrue($medicalResult->creator->is($creator));
        $this->assertCount(2, $client->medicalResults);
        $this->assertCount(2, $appointment->medicalResults);
        $this->assertCount(2, $creator->createdMedicalResults);
    }

    public function test_appointment_and_creator_are_optional(): void
    {
        $medicalResult = MedicalResult::factory()->create();

        $this->assertNull($medicalResult->appointment_id);
        $this->assertNull($medicalResult->appointment);
        $this->assertNull($medicalResult->created_by);
        $this->assertNull($medicalResult->creator);
    }

    public function test_medical_result_casts_and_draft_state_are_configured(): void
    {
        $medicalResult = MedicalResult::factory()->create([
            'result_date' => '2026-10-20',
        ]);

        $this->assertInstanceOf(Carbon::class, $medicalResult->result_date);
        $this->assertSame('2026-10-20', $medicalResult->result_date->toDateString());
        $this->assertSame(MedicalResultStatusEnum::Draft->value, $medicalResult->status);
        $this->assertNull($medicalResult->published_at);
    }

    public function test_published_factory_state_sets_status_and_published_at(): void
    {
        $medicalResult = MedicalResult::factory()->published()->create();

        $this->assertSame(MedicalResultStatusEnum::Published->value, $medicalResult->status);
        $this->assertInstanceOf(Carbon::class, $medicalResult->published_at);
    }

    public function test_factory_keeps_appointment_and_medical_result_client_consistent(): void
    {
        $appointment = Appointment::factory()->create();

        $medicalResult = MedicalResult::factory()
            ->forAppointment($appointment)
            ->create();

        $this->assertSame($appointment->client_id, $medicalResult->client_id);
        $this->assertTrue($medicalResult->client->is($appointment->client));
    }

    public function test_medical_result_can_have_multiple_translations(): void
    {
        $medicalResult = MedicalResult::factory()->create();
        MedicalResultTranslation::factory()->for($medicalResult)->create(['locale' => 'es']);
        MedicalResultTranslation::factory()->for($medicalResult)->create(['locale' => 'en']);

        $this->assertCount(2, $medicalResult->translations);
        $this->assertTrue(
            $medicalResult->translations->every(
                fn (MedicalResultTranslation $translation): bool => $translation->medicalResult->is($medicalResult)
            )
        );
    }

    public function test_translatable_fields_exist_only_on_medical_result_translations(): void
    {
        $this->assertFalse(Schema::hasColumn('medical_results', 'title'));
        $this->assertFalse(Schema::hasColumn('medical_results', 'description'));
        $this->assertTrue(Schema::hasColumn('medical_result_translations', 'title'));
        $this->assertTrue(Schema::hasColumn('medical_result_translations', 'description'));
    }

    public function test_medical_result_cannot_have_duplicate_translation_locales(): void
    {
        $medicalResult = MedicalResult::factory()->create();
        MedicalResultTranslation::factory()->for($medicalResult)->create(['locale' => 'es']);

        $this->expectException(QueryException::class);

        MedicalResultTranslation::factory()->for($medicalResult)->create(['locale' => 'es']);
    }

    public function test_a_creator_linked_to_a_medical_result_can_be_soft_deleted(): void
    {
        $creator = User::factory()->create();
        $medicalResult = MedicalResult::factory()->createdBy($creator)->create();

        $creator->delete();

        $this->assertSoftDeleted($creator);
        $this->assertDatabaseHas('medical_results', [
            'id' => $medicalResult->id,
            'created_by' => $creator->id,
        ]);
    }

    public function test_a_client_with_medical_results_can_be_soft_deleted(): void
    {
        $medicalResult = MedicalResult::factory()->create();
        $client = $medicalResult->client;

        $client->delete();

        $this->assertSoftDeleted($client);
        $this->assertDatabaseHas('medical_results', [
            'id' => $medicalResult->id,
            'client_id' => $client->id,
        ]);
    }

    public function test_medical_result_indexes_are_configured(): void
    {
        $indexes = collect(Schema::getIndexes('medical_results'));

        $this->assertTrue($indexes->contains(
            fn (array $index): bool => $index['unique'] && $index['columns'] === ['uuid']
        ));

        foreach ([
            ['appointment_id'],
            ['status'],
            ['result_date'],
            ['published_at'],
            ['client_id', 'status'],
            ['client_id', 'result_date'],
        ] as $columns) {
            $this->assertTrue(
                $indexes->contains(
                    fn (array $index): bool => $index['columns'] === $columns
                ),
                'Missing medical_results index for: '.implode(', ', $columns)
            );
        }
    }
}
