<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ModuleController extends Controller
{
    /** @var array<string, array{title: string, description: string}> */
    private const MODULES = [
        'appointments' => ['title' => 'Citas', 'description' => 'Gestión de citas, estados y agenda del centro médico.'],
        'clients' => ['title' => 'Pacientes', 'description' => 'Expedientes, datos de contacto y actividad de pacientes.'],
        'doctors' => ['title' => 'Médicos', 'description' => 'Directorio profesional, especialidades y disponibilidad.'],
        'medical-results' => ['title' => 'Resultados médicos', 'description' => 'Creación, revisión y publicación de resultados.'],
        'schedules' => ['title' => 'Horarios', 'description' => 'Horarios médicos, bloqueos, ausencias y vacaciones.'],
        'medical-areas' => ['title' => 'Áreas médicas', 'description' => 'Catálogo de áreas y especialidades médicas.'],
        'medical-services' => ['title' => 'Servicios', 'description' => 'Servicios disponibles y su relación con las áreas médicas.'],
        'facilities' => ['title' => 'Instalaciones', 'description' => 'Sucursales, departamentos, pisos y consultorios.'],
        'insurance' => ['title' => 'Seguros', 'description' => 'Aseguradoras, planes y coberturas aceptadas.'],
        'documents' => ['title' => 'Documentos', 'description' => 'Acceso organizado a documentos clínicos relacionados.'],
        'users' => ['title' => 'Personal', 'description' => 'Usuarios internos y estado de sus cuentas.'],
        'roles' => ['title' => 'Roles y permisos', 'description' => 'Asignación de roles y capacidades del personal.'],
        'audit' => ['title' => 'Auditoría', 'description' => 'Registro de acciones relevantes dentro del sistema.'],
        'settings' => ['title' => 'Configuración', 'description' => 'Preferencias generales y parámetros del centro médico.'],
    ];

    public function show(Request $request): Response
    {
        $moduleKey = (string) $request->route('module');
        abort_unless(isset(self::MODULES[$moduleKey]), 404);

        return Inertia::render('Staff/Module', [
            'module' => [
                'key' => $moduleKey,
                ...self::MODULES[$moduleKey],
            ],
        ]);
    }
}
