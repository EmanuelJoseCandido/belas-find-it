<?php

namespace Database\Seeders;

use App\Models\UserModel;
use App\Enums\Users\RoleEnum;
use App\Enums\Users\GenderEnum;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultPassword = bcrypt('Password12#12');
        $users = [
            [
                'name' => 'Administrador',
                'slug' => 'administrador',
                'email' => 'admin@belasfind.com',
                'phone' => '+244900000001',
                'password' => $defaultPassword,
                'role' => RoleEnum::ADMIN,
                'gender' => GenderEnum::OTHER,
                'is_active' => true,
            ],
            [
                'name' => 'Emanuel Cândido',
                'slug' => 'emanuel-candido',
                'email' => 'emanueljosecandido@hotmail.com',
                'phone' => '921297434',
                'password' => $defaultPassword,
                'role' => RoleEnum::USER,
                'gender' => GenderEnum::MALE,
                'is_active' => true,
            ],
            [
                'name' => 'Martina',
                'slug' => 'martina',
                'email' => 'martina@hotmail.com',
                'phone' => '939722301',
                'password' => $defaultPassword,
                'role' => RoleEnum::USER,
                'gender' => GenderEnum::FEMALE,
                'is_active' => true,
            ]
        ];

        foreach ($users as $userData) {
            UserModel::create($userData);
        }
    }
}
