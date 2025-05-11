<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Reset peran dan izin yang di-cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat izin
        Permission::create(['name' => 'view mata pelajaran']);
        Permission::create(['name' => 'buat mata pelajaran']);
        Permission::create(['name' => 'ubah mata pelajaran']);
        Permission::create(['name' => 'hapus mata pelajaran']);
        Permission::create(['name' => 'management data siswa']);
        Permission::create(['name' => 'view dashboard guru']);
        Permission::create(['name' => 'view dashboard siswa']);
        Permission::create(['name' => 'input data siswa']);
        Permission::create(['name' => 'input nilai siswa']);
        Permission::create(['name' => 'lihat nilai']);

        // Buat peran dan berikan izin
        $guruRole = Role::create(['name' => 'guru']);
        $guruRole->givePermissionTo([
            'view mata pelajaran',
            'buat mata pelajaran',
            'ubah mata pelajaran',
            'hapus mata pelajaran',
            'view dashboard guru',
            'management data siswa'
        ]);

        $siswaRole = Role::create(['name' => 'siswa']);
        $siswaRole->givePermissionTo([
            'view dashboard siswa',
            'input data siswa',
            'input nilai siswa',
            'lihat nilai'
        ]);
    }
}
