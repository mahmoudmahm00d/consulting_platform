<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (Role::count() == 0) {
            Role::create([
                'name' => 'Admin'
            ]);

            Role::create([
                'name' => 'Specialist'
            ]);

            Role::create([
                'name' => 'User'
            ]);
        }

        if (User::count() == 0) {
            $admin = User::create([
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'phone_number' => '1500400300',
                'email' => 'admin@consulting.com',
                'password' => Hash::make('AdminP@$$w0rd!'),
            ]);

            $admin->assignRole('Admin');

            $user = User::create([
                'first_name' => 'Guest',
                'phone_number' => '1300400500',
                'email' => 'guest@consulting.com',
                'password' => Hash::make('AdminP@$$w0rd!'),
            ]);

            $user->assignRole('User');

        }
    }
}
