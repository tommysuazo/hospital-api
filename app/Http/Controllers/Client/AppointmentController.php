<?php

namespace App\Http\Controllers\Client;

use App\Enums\AppointmentForEnum;
use App\Exceptions\KnownException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Resources\AppointmentDoctorResource;
use App\Models\Appointment;
use App\Models\Client;
use App\Services\AppointmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentService $appointmentService,
    ) {}

    public function create(Request $request): Response
    {
        Gate::authorize('create', Appointment::class);

        $doctors = $this->appointmentService->getCreationDoctors();
        $selectedDoctor = (string) $request->query('doctor', '');

        if (! $doctors->contains('uuid', $selectedDoctor)) {
            $selectedDoctor = '';
        }

        return Inertia::render('Client/Appointments/Create', [
            'doctors' => AppointmentDoctorResource::collection($doctors)->resolve($request),
            'appointmentForOptions' => array_map(
                fn (AppointmentForEnum $option): array => [
                    'value' => $option->value,
                    'label' => $option === AppointmentForEnum::Self ? 'Para mí' : 'Para un dependiente',
                ],
                AppointmentForEnum::cases()
            ),
            'minimumDate' => now()->toDateString(),
            'selectedDoctorUuid' => $selectedDoctor,
            'status' => $request->session()->get('status'),
        ]);
    }

    public function store(CreateAppointmentRequest $request): RedirectResponse
    {
        Gate::authorize('create', Appointment::class);

        /** @var Client $client */
        $client = $request->user('client');

        try {
            $appointment = $this->appointmentService->create(
                $client,
                $request->validated()
            );

            return to_route('client.appointments.create')
                ->with(
                    'status',
                    "Tu cita fue confirmada. Tu número de turno es {$appointment->turn_number}."
                );
        } catch (KnownException $exception) {
            return back()
                ->withErrors(['appointment' => $exception->getMessage()])
                ->withInput();
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withErrors([
                    'appointment' => 'No pudimos crear la cita en este momento. Inténtalo nuevamente.',
                ])
                ->withInput();
        }
    }
}
