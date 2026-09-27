<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * "Device Manager" role, scoped to the Company Devices module (catalogue +
 * assignment history) so someone other than a full Administrator/HR account
 * can be assigned devices to manage. Same effect as the
 * create_device_manager_role migration; kept here too so it can be re-run
 * directly on any environment:
 *
 *   php artisan db:seed --class=DeviceManagerRoleSeeder
 */
class DeviceManagerRoleSeeder extends Seeder
{
    public const ROLE_NAME = 'Device Manager';

    private array $tables = ['company_devices', 'company_device_assignments'];

    public function run(): void
    {
        $role = Role::firstOrNew(['name' => self::ROLE_NAME]);
        if (!$role->exists) {
            $role->fill(['display_name' => self::ROLE_NAME])->save();
        }

        $keys = [];
        foreach ($this->tables as $tableName) {
            foreach (['browse', 'read', 'edit', 'add', 'delete'] as $action) {
                $keys[] = "{$action}_{$tableName}";
            }
        }

        $permissionIds = Permission::whereIn('key', $keys)->pluck('id');

        if ($permissionIds->isEmpty()) {
            $this->command?->warn('Company Devices permissions not found - run its migration first, then re-seed this role.');

            return;
        }

        $role->permissions()->syncWithoutDetaching($permissionIds);

        $this->command?->info('Device Manager role ready - assign it to a user from /admin/users.');
    }
}
