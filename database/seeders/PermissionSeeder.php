<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Dynamic permissions
        $entities = ['category', 'brand', 'product', 'role', 'user',];
        $actions = ['create', 'view', 'edit', 'delete'];

        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                Permission::firstOrCreate([
                    'name' => $entity . '_' . $action, // category_create
                    'guard_name' => 'web',
                ]);
            }
        }

        // Static permissions
        $staticPermissions = [
            'dashboard_view',
            'system-settings_edit',
            'system-settings_update',
            // 'status_update'
        ];

        foreach ($staticPermissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }
    }
}
