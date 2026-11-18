<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoleAndPermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_the_permission_catalog_and_expected_roles(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $this->assertDatabaseCount('permissions', 54);
        $this->assertDatabaseCount('roles', 7);

        $this->assertSame([
            'administrator',
            'doctor',
            'laboratory',
            'receptionist',
            'records-manager',
            'super-admin',
            'viewer',
        ], Role::query()->orderBy('name')->pluck('name')->all());

        $this->assertDatabaseMissing('permissions', [
            'name' => 'medical-result-documents.manage',
        ]);

        $superAdmin = Role::findByName('super-admin');
        $administrator = Role::findByName('administrator');

        $this->assertCount(54, $superAdmin->permissions);
        $this->assertTrue($administrator->hasPermissionTo('roles.assign'));
        $this->assertFalse($administrator->hasPermissionTo('roles.delete'));
        $this->assertFalse($administrator->hasPermissionTo('permissions.assign'));
    }

    public function test_role_assignments_follow_the_expected_access_boundaries(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $receptionist = Role::findByName('receptionist');
        $doctor = Role::findByName('doctor');
        $laboratory = Role::findByName('laboratory');
        $recordsManager = Role::findByName('records-manager');
        $viewer = Role::findByName('viewer');

        $this->assertTrue($receptionist->hasPermissionTo('appointments.create'));
        $this->assertFalse($receptionist->hasPermissionTo('medical-results.view'));

        $this->assertTrue($doctor->hasPermissionTo('medical-results.publish'));
        $this->assertFalse($doctor->hasPermissionTo('users.update'));

        $this->assertTrue($laboratory->hasPermissionTo('medical-results.publish'));
        $this->assertFalse($laboratory->hasPermissionTo('appointments.update'));

        $this->assertTrue($recordsManager->hasPermissionTo('medical-results.update'));
        $this->assertFalse($recordsManager->hasPermissionTo('medical-results.publish'));

        $this->assertTrue($viewer->hasPermissionTo('medical-results.view'));
        $this->assertFalse($viewer->hasPermissionTo('medical-results.update'));
    }

    public function test_super_admin_bypasses_registered_and_future_permissions(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->assertTrue(Gate::forUser($user)->allows('future.permission'));
    }

    public function test_it_can_run_more_than_once_without_duplicates(): void
    {
        $this->seed(RoleAndPermissionSeeder::class);
        $this->seed(RoleAndPermissionSeeder::class);

        $this->assertDatabaseCount('permissions', 54);
        $this->assertDatabaseCount('roles', 7);
        $this->assertSame(54, Permission::query()->distinct()->count('name'));
    }

    public function test_it_syncs_roles_when_model_events_are_disabled(): void
    {
        Model::withoutEvents(function (): void {
            $this->seed(RoleAndPermissionSeeder::class);
        });

        $role = Role::findByName('super-admin');
        $permission = Permission::findByName('dashboard.view');

        $this->assertTrue($role->hasPermissionTo($permission));
        $this->assertDatabaseHas('role_has_permissions', [
            'role_id' => $role->getKey(),
            'permission_id' => $permission->getKey(),
        ]);
    }
}
