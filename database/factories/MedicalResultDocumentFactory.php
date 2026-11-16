<?php

namespace Database\Factories;

use App\Models\MedicalResult;
use App\Models\MedicalResultDocument;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MedicalResultDocument>
 */
class MedicalResultDocumentFactory extends Factory
{
    public function definition(): array
    {
        $uuid = (string) Str::uuid();
        $mimeType = fake()->randomElement([
            'application/pdf',
            'image/jpeg',
            'image/png',
        ]);
        $extension = match ($mimeType) {
            'application/pdf' => 'pdf',
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
        };

        return [
            'uuid' => $uuid,
            'medical_result_id' => MedicalResult::factory(),
            'name' => fake()->words(3, true),
            'file_path' => "medical-results/fake/{$uuid}.{$extension}",
            'mime_type' => $mimeType,
            'file_size' => fake()->numberBetween(10_000, 10_000_000),
        ];
    }
}
