<?php

namespace App\Http\Controllers\Client;

use App\Enums\AppointmentForEnum;
use App\Exceptions\KnownException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\FilterAppointmentsRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentDoctorResource;
use App\Http\Resources\ClientAppointmentResource;
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

    public function index(FilterAppointmentsRequest $request): Response
    {
        Gate::authorize('viewAny', Appointment::class);

        /** @var Client $client */
        $client = $request->user('client');
        $appointments = $this->appointmentService->getClientHistory($client, $request->validated());
        $doctors = $this->appointmentService->getClientHistoryDoctors($client);

        return Inertia::render('Client/Appointments/Index', [
            'appointments' => [
                'data' => ClientAppointmentResource::collection($appointments->getCollection())->resolve($request),
                'currentPage' => $appointments->currentPage(),
                'lastPage' => $appointments->lastPage(),
                'total' => $appointments->total(),
            ],
            'doctors' => $doctors->map(fn ($doctor): array => [
                'uuid' => $doctor->uuid,
                'name' => trim("{$doctor->user->first_name} {$doctor->user->last_name}"),
            ])->values(),
            'filters' => $request->safe()->only(['date', 'doctor', 'status']),
            'statusOptions' => [
                ['value' => 'confirmed', 'label' => 'Pendiente'],
                ['value' => 'completed', 'label' => 'Completada'],
                ['value' => 'cancelled', 'label' => 'Cancelada'],
                ['value' => 'no_show', 'label' => 'No asistió'],
            ],
            'status' => $request->session()->get('status'),
        ]);
    }

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

    public function edit(Request $request, Appointment $appointment): Response
    {
        Gate::authorize('update', $appointment);

        return Inertia::render('Client/Appointments/Edit', [
            'appointment' => ClientAppointmentResource::make(
                $appointment->load(['doctor.user', 'medicalArea.translations'])
            )->resolve($request),
            'doctors' => AppointmentDoctorResource::collection(
                $this->appointmentService->getCreationDoctors()
            )->resolve($request),
            'minimumDate' => now()->toDateString(),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        Gate::authorize('update', $appointment);

        /** @var Client $client */
        $client = $request->user('client');

        try {
            $this->appointmentService->update($client, $appointment, $request->validated());

            return to_route('client.appointments.index')
                ->with('status', 'Tu cita pendiente fue actualizada correctamente.');
        } catch (KnownException $exception) {
            return back()->withErrors(['appointment' => $exception->getMessage()])->withInput();
        } catch (Throwable $exception) {
            report($exception);

            return back()->withErrors([
                'appointment' => 'No pudimos actualizar la cita en este momento. Inténtalo nuevamente.',
            ])->withInput();
        }
    }
}
