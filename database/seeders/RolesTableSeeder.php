<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'cashier']);
    }
}
