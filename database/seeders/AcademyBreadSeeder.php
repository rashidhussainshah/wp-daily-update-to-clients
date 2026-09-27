<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

/**
 * Registers Voyager BREAD (browse/read/edit/add/delete) screens for the
 * Academy reference-data tables so Administrators get a generic admin form
 * for them immediately, without the usual manual "add BREAD via the UI"
 * step. Idempotent - safe to re-run (skips a table already registered).
 *
 * The day-to-day workflow tables (enrollments, reviews, invoices,
 * certificates) intentionally do NOT get generic BREAD here - they're
 * covered by the purpose-built review-queue / fee-admin / course-certificate
 * screens instead, which enforce the assignment-scoped access rules that a
 * generic BREAD form can't.
 */
class AcademyBreadSeeder extends Seeder
{
    public function run(): void
    {
        $this->registerBread(
            'academy_tracks',
            'App\\Models\\AcademyTrack',
            'Academy Track',
            'Academy Tracks',
            'voyager-book',
            [
                'curriculum' => ['type' => 'rich_text_box', 'display_name' => 'Curriculum (JSON: stages/skills)'],
                'is_open_for_enrollment' => ['type' => 'checkbox'],
                'registration_fee_enabled' => ['type' => 'checkbox'],
            ],
            46
        );

        $this->registerBread(
            'academy_courses',
            'App\\Models\\AcademyCourse',
            'Academy Course',
            'Academy Courses',
            'voyager-list',
            [],
            46
        );
    }

    protected function registerBread(string $table, string $model, string $singular, string $plural, string $icon, array $fieldOverrides, ?int $parentMenuId): void
    {
        if (DB::table('data_types')->where('name', $table)->exists()) {
            $this->command?->info("Skipping {$table}: BREAD already registered.");

            return;
        }

        $dataTypeId = DB::table('data_types')->insertGetId([
            'name' => $table,
            'slug' => Str::slug($plural),
            'display_name_singular' => $singular,
            'display_name_plural' => $plural,
            'icon' => $icon,
            'model_name' => $model,
            'policy_name' => null,
            'controller' => null,
            'description' => null,
            'generate_permissions' => 1,
            'server_side' => 0,
            'details' => json_encode(['order_column' => null, 'order_display_column' => null, 'order_direction' => 'asc', 'default_search_key' => null, 'scope' => null]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $order = 1;
        foreach (Schema::getColumnListing($table) as $col) {
            $override = $fieldOverrides[$col] ?? [];
            $isId = $col === 'id';
            $isTimestamp = in_array($col, ['created_at', 'updated_at', 'deleted_at']);
            $flags = $override['flags'] ?? ($isId ? [0, 0, 0, 0, 0] : ($isTimestamp ? [1, 1, 1, 0, $col !== 'deleted_at' ? 1 : 0] : [1, 1, 1, 1, 1]));

            DB::table('data_rows')->insert([
                'data_type_id' => $dataTypeId,
                'field' => $col,
                'type' => $override['type'] ?? 'text',
                'display_name' => $override['display_name'] ?? Str::title(str_replace('_', ' ', $col)),
                'required' => $override['required'] ?? 0,
                'browse' => $flags[0], 'read' => $flags[1], 'edit' => $flags[2], 'add' => $flags[3], 'delete' => $flags[4],
                'details' => json_encode($override['details'] ?? []),
                'order' => $order++,
            ]);
        }

        Permission::generateFor($table);

        if ($adminRole = Role::where('name', 'admin')->first()) {
            $adminRole->permissions()->syncWithoutDetaching(Permission::where('table_name', $table)->pluck('id'));
        }

        DB::table('menu_items')->insert([
            'menu_id' => 1,
            'title' => $plural,
            'url' => '',
            'target' => '_self',
            'icon_class' => $icon,
            'color' => null,
            'parent_id' => $parentMenuId,
            'order' => 99,
            'created_at' => now(), 'updated_at' => now(),
            'route' => 'voyager.'.Str::slug($plural).'.index',
            'parameters' => null,
        ]);

        $this->command?->info("Registered BREAD for {$table} (data_type #{$dataTypeId}).");
    }
}
