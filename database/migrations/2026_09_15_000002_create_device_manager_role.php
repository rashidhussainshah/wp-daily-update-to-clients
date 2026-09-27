<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Dedicated "Device Manager" role for the Company Devices module - lets
 * someone other than a full Administrator/HR account be assigned devices to
 * manage. Scoped only to company_devices / company_device_assignments
 * (browse/read/edit/add/delete); no access to anything else in the admin.
 *
 * Depends on the permission rows created by
 * 2026_09_09_000001_create_company_devices_module.php, so this must run
 * after it (guaranteed by the later timestamp).
 *
 * Same effect is also available re-runnably via
 * Database\Seeders\DeviceManagerRoleSeeder for staging/local/fresh installs:
 *   php artisan db:seed --class=DeviceManagerRoleSeeder
 */
return new class extends Migration
{
    public const ROLE_NAME = 'Device Manager';

    private array $tables = ['company_devices', 'company_device_assignments'];

    public function up(): void
    {
        $roleId = DB::table('roles')->where('name', self::ROLE_NAME)->value('id');
        if (!$roleId) {
            $roleId = DB::table('roles')->insertGetId([
                'name'         => self::ROLE_NAME,
                'display_name' => self::ROLE_NAME,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        }

        foreach ($this->tables as $tableName) {
            foreach (['browse', 'read', 'edit', 'add', 'delete'] as $action) {
                $key = "{$action}_{$tableName}";

                $permissionId = DB::table('permissions')->where('key', $key)->where('table_name', $tableName)->value('id');
                if (!$permissionId) {
                    // Company Devices permissions haven't been created yet on this database.
                    continue;
                }

                DB::table('permission_role')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id'       => $roleId,
                ]);
            }
        }
    }

    public function down(): void
    {
        $roleId = DB::table('roles')->where('name', self::ROLE_NAME)->value('id');
        if (!$roleId) {
            return;
        }

        DB::table('permission_role')->where('role_id', $roleId)->delete();

        // Only drop the role itself if nobody has been assigned it, so we
        // never leave a user pointed at a deleted role_id.
        if (DB::table('users')->where('role_id', $roleId)->doesntExist()) {
            DB::table('roles')->where('id', $roleId)->delete();
        }
    }
};
