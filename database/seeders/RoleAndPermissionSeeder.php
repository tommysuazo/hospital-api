<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    private const GUARD = 'web';

    private const PERMISSIONS = [
        'dashboard.view',

        'clients.viewAny',
        'clients.view',
        'clients.create',
        'clients.update',
        'clients.deactivate',

        'appointments.viewAny',
        'appointments.view',
        'appointments.create',
        'appointments.update',
        'appointments.reschedule',
        'appointments.confirm',
        'appointments.cancel',
        'appointments.complete',
        'appointments.markNoShow',

        'doctors.viewAny',
        'doctors.view',
        'doctors.create',
        'doctors.update',
        'doctors.deactivate',

        'doctor-schedules.view',
        'doctor-schedules.manage',

        'doctor-unavailabilities.view',
        'doctor-unavailabilities.manage',

        'medical-areas.viewAny',
        'medical-areas.view',
        'medical-areas.create',
        'medical-areas.update',
        'medical-areas.delete',

        'medical-services.viewAny',
        'medical-services.view',
        'medical-services.create',
        'medical-services.update',
        'medical-services.delete',

        'medical-results.viewAny',
        'medical-results.view',
        'medical-results.create',
        'medical-results.update',
        'medical-results.publish',
        'medical-results.delete',

        'users.viewAny',
        'users.view',
        'users.create',
        'users.update',
        'users.deactivate',

        'roles.viewAny',
        'roles.create',
        'roles.update',
        'roles.delete',
        'roles.assign',

        'permissions.viewAny',
        'permissions.assign',

        'settings.view',
        'settings.update',
    ];

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (self::PERMISSIONS as $permission) {
            Permission::findOrCreate($permission, self::GUARD);
        }

        foreach ($this->rolePermissions() as $roleName => $permissions) {
            Role::findOrCreate($roleName, self::GUARD)
                ->syncPermissions($permissions);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * @return array<string, list<string>>
     */
    private function rolePermissions(): array
    {
        return [
            'super-admin' => self::PERMISSIONS,
            'administrator' => array_values(array_diff(self::PERMISSIONS, [
                'roles.delete',
                'permissions.assign',
            ])),
            'receptionist' => [
                'dashboard.view',
                'clients.viewAny',
                'clients.view',
                'clients.create',
                'clients.update',
                'appointments.viewAny',
                'appointments.view',
                'appointments.create',
                'appointments.update',
                'appointments.reschedule',
                'appointments.confirm',
                'appointments.cancel',
                'doctors.viewAny',
                'doctors.view',
                'doctor-schedules.view',
                'doctor-unavailabilities.view',
                'medical-areas.viewAny',
                'medical-areas.view',
                'medical-services.viewAny',
                'medical-services.view',
            ],
            'doctor' => [
                'dashboard.view',
                'clients.viewAny',
                'clients.view',
                'appointments.viewAny',
                'appointments.view',
                'appointments.update',
                'appointments.reschedule',
                'appointments.cancel',
                'appointments.complete',
                'appointments.markNoShow',
                'doctors.viewAny',
                'doctors.view',
                'doctor-schedules.view',
                'doctor-unavailabilities.view',
                'medical-areas.viewAny',
                'medical-areas.view',
                'medical-services.viewAny',
                'medical-services.view',
                'medical-results.viewAny',
                'medical-results.view',
                'medical-results.create',
                'medical-results.update',
                'medical-results.publish',
            ],
            'laboratory' => [
                'dashboard.view',
                'clients.viewAny',
                'clients.view',
                'appointments.viewAny',
                'appointments.view',
                'doctors.viewAny',
                'doctors.view',
                'medical-areas.viewAny',
                'medical-areas.view',
                'medical-services.viewAny',
                'medical-services.view',
                'medical-results.viewAny',
                'medical-results.view',
                'medical-results.create',
                'medical-results.update',
                'medical-results.publish',
            ],
            'records-manager' => [
                'dashboard.view',
                'clients.viewAny',
                'clients.view',
                'clients.create',
                'clients.update',
                'appointments.viewAny',
                'appointments.view',
                'doctors.viewAny',
                'doctors.view',
                'medical-areas.viewAny',
                'medical-areas.view',
                'medical-services.viewAny',
                'medical-services.view',
                'medical-results.viewAny',
                'medical-results.view',
                'medical-results.create',
                'medical-results.update',
            ],
            'viewer' => [
                'dashboard.view',
                'clients.viewAny',
                'clients.view',
                'appointments.viewAny',
                'appointments.view',
                'doctors.viewAny',
                'doctors.view',
                'doctor-schedules.view',
                'doctor-unavailabilities.view',
                'medical-areas.viewAny',
                'medical-areas.view',
                'medical-services.viewAny',
                'medical-services.view',
                'medical-results.viewAny',
                'medical-results.view',
                'users.viewAny',
                'users.view',
                'roles.viewAny',
                'permissions.viewAny',
                'settings.view',
            ],
        ];
    }
}
