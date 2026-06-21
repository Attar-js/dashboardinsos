<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'System Admin',
                'first_name' => 'System',
                'last_name' => 'Admin',
                'username' => 'systemadmin',
                'email' => 'admin@mail.com',
                'password' => bcrypt('admin123'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'admin',
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'name' => 'Demo Admin',
                'first_name' => 'Demo',
                'last_name' => 'Admin',
                'username' => 'demoadmin',
                'email' => 'demo@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'demo_admin',
                'role' => 'admin',
                'status' => 'active',
            ],
            [
                'name' => 'John User',
                'first_name' => 'John',
                'last_name' => 'User',
                'username' => 'user',
                'email' => 'user@example.com',
                'password' => bcrypt('password'),
                'phone_number' => '+12398190255',
                'email_verified_at' => now(),
                'user_type' => 'user',
                'role' => 'mahasiswa',
                'status' => 'inactive',
            ],
        ];

        User::unguard();

        try {
            $hasRoleColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'role');

            foreach ($users as $value) {
                $roleName = $value['user_type'];

                if (!$hasRoleColumn) {
                    unset($value['role']);
                }

                $user = User::updateOrCreate(
                    ['email' => $value['email']],
                    $value
                );

                if (!$user->hasRole($roleName)) {
                    $user->assignRole($roleName);
                }
            }
        } finally {
            User::reguard();
        }
    }
}
