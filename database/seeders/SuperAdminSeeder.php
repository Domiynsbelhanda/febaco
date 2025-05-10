<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@febaco.cd'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // Change le mot de passe ici si besoin
            ]
        );

        // S'assure que le rôle existe avant de l'assigner
        $role = Role::firstOrCreate(['name' => 'Administrateur']);

        $user->assignRole($role);
    }
}
