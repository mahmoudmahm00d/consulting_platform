<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\ContactInfoType;
use App\Models\User;
use App\Models\Wallet;
use ApplicationRoles;
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
                'name' => ApplicationRoles::$admin,
                'guard_name' => 'web'
            ]);

            Role::create([
                'name' => ApplicationRoles::$specialist,
                // 'guard_name' => 'api'
            ]);

            Role::create([
                'name' => ApplicationRoles::$user,
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

            $user->assignRole(Role::findByName(ApplicationRoles::$user));

            Wallet::create([
                'user_id' => $user->id,
                'amount' => 5000,
            ]);


            $specialist = User::create([
                'name' => 'Specialist',
                'phone_number' => '1200300400',
                'email' => 'specialist@consulting.com',
                'password' => Hash::make('Specialist'),
            ]);

            $specialist->assignRole(Role::findByName(ApplicationRoles::$specialist));

            Wallet::create([
                'user_id' => $specialist->id,
                'amount' => 5000,
            ]);
        }

        if (Category::count() == 0) {
            Category::create([
                'name' => 'Medical',
                'image' => '/images/medical.png'
            ]);

            Category::create([
                'name' => 'Programming',
                'image' => '/images/programming.png',
            ]);
        }

        if (ContactInfoType::count() == 0) {
            ContactInfoType::create([
                'name' => 'Address',
                'description' => 'Physical Address',
                'icon' => '/images/address.png',
            ]);

            ContactInfoType::create([
                'name' => 'Telephone',
                'description' => 'Telephone',
                'icon' => '/images/phone.png',
            ]);

            ContactInfoType::create([
                'name' => 'Whatsapp',
                'url' => 'https://api.whatsapp.com/send/?&text&app_absent=0&phone=',
                'description' => 'Whatsapp account',
                'icon' => '/images/whatsapp.png',
            ]);

            ContactInfoType::create([
                'name' => 'Github',
                'url' => 'https://github.com/',
                'description' => 'Github account',
                'icon' => '/images/github.png',
            ]);

            ContactInfoType::create([
                'name' => 'Facebook',
                'url' => 'https://www.facebook.com/',
                'description' => 'Facebook username',
                'icon' => '/images/facebook.png',
            ]);

            ContactInfoType::create([
                'name' => 'Twitter',
                'url' => 'https://www.twitter.com/',
                'description' => 'Twitter username',
                'icon' => '/images/twitter.png',
            ]);
        }
    }
}
