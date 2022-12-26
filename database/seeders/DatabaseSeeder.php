<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\ContactInfoType;
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
                'name' => 'Admin',
                'guard_name' => 'web'
            ]);

            Role::create([
                'name' => 'Specialist',
                // 'guard_name' => 'api'
            ]);

            Role::create([
                'name' => 'User',
                // 'guard_name' => 'api'
            ]);
        }

        if (User::count() == 0) {
            $admin = User::create([
                'name' => 'Admin',
                'phone_number' => '1500400300',
                'email' => 'admin@consulting.com',
                'password' => Hash::make('AdminP@$$w0rd!'),
            ]);

            $admin->assignRole('Admin');

            $user = User::create([
                'name' => 'Guest',
                'phone_number' => '1300400500',
                'email' => 'guest@consulting.com',
                'password' => Hash::make('Password'),
            ]);

            $user_role = Role::findByName('User');
            
            $user->assignRole($user_role);
        }

        if(Category::count() == 0)
        {
            Category::create([
                'name' => 'Medical',
            ]);

            Category::create([
                'name' => 'Programming',
            ]);
        }

        if(ContactInfoType::count() == 0)
        {
            ContactInfoType::create([
                'name' => 'Whatsapp',
                'url' => 'https://api.whatsapp.com/send/?&text&app_absent=0&phone=',
                'description' => 'Whatsapp account'
            ]);

            ContactInfoType::create([
                'name' => 'Facebook',
                'url' => 'https://www.facebook.com/',
                'description' => 'Facebook username'
            ]);
        }
    }
}
