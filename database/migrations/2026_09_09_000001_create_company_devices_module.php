<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Company Devices module.
 *
 * Additive only - two new tables plus their Voyager BREADs, permissions and
 * sidebar items. Nothing existing is modified.
 *
 *  - company_devices ............ the asset catalogue (laptop / phone / etc.),
 *                                 with purchase invoice + supporting attachments.
 *  - company_device_assignments . full handover history; a model observer keeps
 *                                 company_devices.assigned_to / assigned_at /
 *                                 status in sync with the open row.
 *
 * Management BREADs are visible to Administrator, admin and HR only. Developers
 * get a separate read-only "My Devices" page (route my-devices.index).
 */
return new class extends Migration
{
    /** Roles allowed to manage the module. */
    private array $manageRoleNames = ['Administrator', 'admin', 'HR'];

    public function up(): void
    {
        $this->createTables();

        $manageRoleIds = DB::table('roles')->whereIn('name', $this->manageRoleNames)->pluck('id')->all();

        $this->createDevicesBread();
        $this->createAssignmentsBread();

        $this->grantPermissions('company_devices', $manageRoleIds);
        $this->grantPermissions('company_device_assignments', $manageRoleIds);

        $this->createMenuItems();
        $this->createMyDevicesPermission();

        // Menu items were inserted via the query builder (no model events), so
        // bust Voyager's cached admin menu and permission lookups.
        \Cache::forget('voyager_menu_admin');
    }

    public function down(): void
    {
        DB::table('menu_items')
            ->whereIn('url', ['/admin/company-devices', '/admin/company-device-assignments'])
            ->orWhere('route', 'my-devices.index')
            ->delete();

        foreach (['company_devices', 'company_device_assignments', 'my-devices'] as $tableName) {
            $permissionIds = DB::table('permissions')->where('table_name', $tableName)->pluck('id');
            DB::table('permission_role')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }

        foreach (['company_devices', 'company_device_assignments'] as $breadName) {
            $typeId = DB::table('data_types')->where('name', $breadName)->value('id');
            if ($typeId) {
                DB::table('data_rows')->where('data_type_id', $typeId)->delete();
                DB::table('data_types')->where('id', $typeId)->delete();
            }
        }

        Schema::dropIfExists('company_device_assignments');
        Schema::dropIfExists('company_devices');

        \Cache::forget('voyager_menu_admin');
    }

    /* -------------------------------------------------------------------- */
    /*  Tables                                                             */
    /* -------------------------------------------------------------------- */

    private function createTables(): void
    {
        if (!Schema::hasTable('company_devices')) {
            Schema::create('company_devices', function (Blueprint $table) {
                $table->id();
                $table->string('asset_tag')->nullable()->unique()->comment('Internal inventory number');
                $table->string('name')->comment('e.g. MacBook Pro 16" 2021');
                $table->enum('type', ['laptop', 'desktop', 'phone', 'tablet', 'monitor', 'accessory', 'other'])->default('laptop');
                $table->string('brand')->nullable();
                $table->string('model')->nullable();
                $table->string('serial_number')->nullable();
                $table->text('specs')->nullable()->comment('CPU / RAM / storage etc.');
                $table->string('condition', 20)->nullable()->comment('new / good / fair / poor');
                $table->date('purchase_date')->nullable();
                $table->decimal('purchase_cost', 12, 2)->nullable();
                $table->string('currency', 8)->default('PKR');
                $table->string('vendor')->nullable();
                $table->string('invoice')->nullable()->comment('Voyager media path - purchase invoice');
                $table->text('attachments')->nullable()->comment('Voyager media JSON - photos, warranty, handover docs');
                $table->enum('status', ['available', 'assigned', 'repair', 'retired', 'lost'])->default('available');
                $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
                $table->date('assigned_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('company_device_assignments')) {
            Schema::create('company_device_assignments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('device_id')->constrained('company_devices')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
                $table->date('assigned_on');
                $table->date('returned_on')->nullable();
                $table->string('condition_on_assign', 20)->nullable()->comment('new / good / fair / poor');
                $table->string('condition_on_return', 20)->nullable()->comment('new / good / fair / poor');
                $table->text('handover_notes')->nullable();
                $table->string('document')->nullable()->comment('Voyager media path - signed handover form');
                $table->timestamps();

                $table->index(['device_id', 'returned_on']);
                $table->index('user_id');
            });
        }
    }

    /* -------------------------------------------------------------------- */
    /*  BREAD                                                              */
    /* -------------------------------------------------------------------- */

    private function createDevicesBread(): void
    {
        $typeId = $this->dataType('company_devices', 'Company Device', 'Company Devices', \App\Models\CompanyDevice::class, 'voyager-hardware');

        $this->rows($typeId, [
            ['id', 'hidden', 'Id', ['browse' => 0, 'read' => 0, 'edit' => 0, 'add' => 0]],
            ['asset_tag', 'text', 'Asset Tag'],
            ['name', 'text', 'Name / Label', ['required' => 1]],
            ['type', 'select_dropdown', 'Type', ['required' => 1, 'details' => ['default' => 'laptop', 'options' => \App\Models\CompanyDevice::TYPES]]],
            ['brand', 'text', 'Brand', ['browse' => 1]],
            ['model', 'text', 'Model', ['browse' => 0]],
            ['serial_number', 'text', 'Serial Number', ['browse' => 0]],
            ['specs', 'text_area', 'Specs', ['browse' => 0]],
            ['condition', 'select_dropdown', 'Condition', ['browse' => 0, 'details' => ['default' => '', 'options' => ['' => '-'] + \App\Models\CompanyDevice::CONDITIONS]]],
            ['purchase_date', 'date', 'Purchase Date', ['browse' => 0]],
            ['purchase_cost', 'text', 'Purchase Cost', ['browse' => 0]],
            ['currency', 'select_dropdown', 'Currency', ['browse' => 0, 'details' => ['default' => 'PKR', 'options' => ['PKR' => 'PKR', 'USD' => 'USD', 'GBP' => 'GBP', 'EUR' => 'EUR', 'AED' => 'AED']]]],
            ['vendor', 'text', 'Vendor / Supplier', ['browse' => 0]],
            ['invoice', 'file', 'Purchase Invoice', ['browse' => 0]],
            ['attachments', 'file', 'Attachments', ['browse' => 0, 'details' => ['multiple' => true]]],
            ['status', 'select_dropdown', 'Status', ['required' => 1, 'details' => ['default' => 'available', 'options' => \App\Models\CompanyDevice::STATUSES]]],
            ['assigned_at', 'date', 'Assigned Since', ['edit' => 0, 'add' => 0]],
            ['notes', 'text_area', 'Notes', ['browse' => 0]],
            ['holder', 'relationship', 'Currently With', [
                'edit' => 0, 'add' => 0,
                'details' => [
                    'model'       => \App\Models\User::class,
                    'table'       => 'users',
                    'type'        => 'belongsTo',
                    'column'      => 'assigned_to',
                    'key'         => 'id',
                    'label'       => 'name',
                    'pivot_table' => 'roles',
                    'pivot'       => '0',
                    'taggable'    => '0',
                ],
            ]],
            ['history', 'relationship', 'Assignment History', [
                'browse' => 0, 'edit' => 0, 'add' => 0,
                'details' => [
                    'model'       => \App\Models\CompanyDeviceAssignment::class,
                    'table'       => 'company_device_assignments',
                    'type'        => 'hasMany',
                    'column'      => 'device_id',
                    'key'         => 'id',
                    'label'       => 'assigned_on',
                    'pivot_table' => 'roles',
                    'pivot'       => '0',
                    'taggable'    => '0',
                ],
            ]],
            ['created_at', 'timestamp', 'Created At', ['browse' => 0, 'edit' => 0, 'add' => 0]],
            ['updated_at', 'timestamp', 'Updated At', ['browse' => 0, 'read' => 0, 'edit' => 0, 'add' => 0]],
        ]);
    }

    private function createAssignmentsBread(): void
    {
        $typeId = $this->dataType('company_device_assignments', 'Device Assignment', 'Device Assignments', \App\Models\CompanyDeviceAssignment::class, 'voyager-people');

        $this->rows($typeId, [
            ['id', 'hidden', 'Id', ['browse' => 0, 'read' => 0, 'edit' => 0, 'add' => 0]],
            ['device', 'relationship', 'Device', [
                'required' => 1,
                'details'  => [
                    'model'       => \App\Models\CompanyDevice::class,
                    'table'       => 'company_devices',
                    'type'        => 'belongsTo',
                    'column'      => 'device_id',
                    'key'         => 'id',
                    'label'       => 'name',
                    'pivot_table' => 'roles',
                    'pivot'       => '0',
                    'taggable'    => '0',
                ],
            ]],
            ['holder', 'relationship', 'Assigned To', [
                'required' => 1,
                'details'  => [
                    'model'       => \App\Models\User::class,
                    'table'       => 'users',
                    'type'        => 'belongsTo',
                    'column'      => 'user_id',
                    'key'         => 'id',
                    'label'       => 'name',
                    'pivot_table' => 'roles',
                    'pivot'       => '0',
                    'taggable'    => '0',
                ],
            ]],
            ['assigned_on', 'date', 'Assigned On', ['required' => 1]],
            ['returned_on', 'date', 'Returned On', ['browse' => 1]],
            ['condition_on_assign', 'select_dropdown', 'Condition At Handover', ['browse' => 0, 'details' => ['default' => '', 'options' => ['' => '-'] + \App\Models\CompanyDevice::CONDITIONS]]],
            ['condition_on_return', 'select_dropdown', 'Condition At Return', ['browse' => 0, 'details' => ['default' => '', 'options' => ['' => '-'] + \App\Models\CompanyDevice::CONDITIONS]]],
            ['handover_notes', 'text_area', 'Handover Notes', ['browse' => 0]],
            ['document', 'file', 'Signed Handover Form', ['browse' => 0]],
            ['assigner', 'relationship', 'Recorded By', [
                'add' => 0, 'edit' => 0,
                'details' => [
                    'model'       => \App\Models\User::class,
                    'table'       => 'users',
                    'type'        => 'belongsTo',
                    'column'      => 'assigned_by',
                    'key'         => 'id',
                    'label'       => 'name',
                    'pivot_table' => 'roles',
                    'pivot'       => '0',
                    'taggable'    => '0',
                ],
            ]],
            ['created_at', 'timestamp', 'Created At', ['browse' => 0, 'edit' => 0, 'add' => 0]],
            ['updated_at', 'timestamp', 'Updated At', ['browse' => 0, 'read' => 0, 'edit' => 0, 'add' => 0]],
        ]);
    }

    private function dataType(string $name, string $singular, string $plural, string $model, string $icon): int
    {
        $typeId = DB::table('data_types')->where('name', $name)->value('id');

        if (!$typeId) {
            $typeId = DB::table('data_types')->insertGetId([
                'name'                  => $name,
                'slug'                  => str_replace('_', '-', $name),
                'display_name_singular' => $singular,
                'display_name_plural'   => $plural,
                'icon'                  => $icon,
                'model_name'            => $model,
                'policy_name'           => null,
                'controller'            => '',
                'description'           => '',
                'generate_permissions'  => 1,
                'server_side'           => 1,
                'details'               => null,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }

        return $typeId;
    }

    /**
     * @param  array<int, array{0:string,1:string,2:string,3?:array<string,mixed>}>  $rows
     */
    private function rows(int $typeId, array $rows): void
    {
        DB::table('data_rows')->where('data_type_id', $typeId)->delete();

        foreach (array_values($rows) as $index => $row) {
            $field   = $row[0];
            $type    = $row[1];
            $display = $row[2];
            $opts    = $row[3] ?? [];

            DB::table('data_rows')->insert([
                'data_type_id' => $typeId,
                'field'        => $field,
                'type'         => $type,
                'display_name' => $display,
                'required'     => $opts['required'] ?? 0,
                'browse'       => $opts['browse'] ?? 1,
                'read'         => $opts['read'] ?? 1,
                'edit'         => $opts['edit'] ?? 1,
                'add'          => $opts['add'] ?? 1,
                'delete'       => $opts['delete'] ?? 1,
                'details'      => isset($opts['details']) ? json_encode($opts['details']) : '',
                'order'        => $index + 1,
            ]);
        }
    }

    /* -------------------------------------------------------------------- */
    /*  Permissions & menu                                                 */
    /* -------------------------------------------------------------------- */

    private function grantPermissions(string $tableName, array $roleIds): void
    {
        foreach (['browse', 'read', 'edit', 'add', 'delete'] as $action) {
            $key = "{$action}_{$tableName}";

            $permissionId = DB::table('permissions')->where('key', $key)->where('table_name', $tableName)->value('id');
            if (!$permissionId) {
                $permissionId = DB::table('permissions')->insertGetId([
                    'key'        => $key,
                    'table_name' => $tableName,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($roleIds as $roleId) {
                DB::table('permission_role')->updateOrInsert([
                    'permission_id' => $permissionId,
                    'role_id'       => $roleId,
                ]);
            }
        }
    }

    private function createMenuItems(): void
    {
        $items = [
            ['title' => 'Company Devices',   'url' => '/admin/company-devices',            'icon' => 'voyager-hardware', 'order' => 90],
            ['title' => 'Device Assignments', 'url' => '/admin/company-device-assignments', 'icon' => 'voyager-people',   'order' => 91],
        ];

        foreach ($items as $item) {
            if (DB::table('menu_items')->where('url', $item['url'])->exists()) {
                continue;
            }

            DB::table('menu_items')->insert([
                'menu_id'    => 1,
                'title'      => $item['title'],
                'url'        => $item['url'],
                'target'     => '_self',
                'icon_class' => $item['icon'],
                'color'      => null,
                'parent_id'  => null,
                'order'      => $item['order'],
                'route'      => null,
                'parameters' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('menu_items')->where('route', 'my-devices.index')->exists()) {
            DB::table('menu_items')->insert([
                'menu_id'    => 1,
                'title'      => 'My Devices',
                'url'        => '',
                'target'     => '_self',
                'icon_class' => 'voyager-laptop',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 92,
                'route'      => 'my-devices.index',
                'parameters' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Menu visibility for the developer-facing "My Devices" page. Voyager's
     * MenuItemPolicy derives the permission key from the link slug - for
     * route my-devices.index that is "browse_my-devices" (hyphen). Without a
     * row of that exact key the menu check fails open and shows the item to
     * everyone, so we register it and grant it to Developer plus manage roles.
     */
    private function createMyDevicesPermission(): void
    {
        $permissionId = DB::table('permissions')->where('key', 'browse_my-devices')->value('id');
        if (!$permissionId) {
            $permissionId = DB::table('permissions')->insertGetId([
                'key'        => 'browse_my-devices',
                'table_name' => 'my-devices',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $roleIds = DB::table('roles')
            ->whereIn('name', array_merge($this->manageRoleNames, ['Developer']))
            ->pluck('id');

        foreach ($roleIds as $roleId) {
            DB::table('permission_role')->updateOrInsert([
                'permission_id' => $permissionId,
                'role_id'       => $roleId,
            ]);
        }
    }
};
