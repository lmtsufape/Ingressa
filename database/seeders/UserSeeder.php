<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('12345678'),
                'role'           => User::ROLE_ENUM['admin'],
                'primeiro_acesso' => false,
            ],
            [
                'name'           => 'Analista',
                'email'          => 'analista@analista.com',
                'password'       => bcrypt('12345678'),
                'role'           => User::ROLE_ENUM['analista'],
                'primeiro_acesso' => false,
            ],
            [
                'name'           => 'Candidato',
                'email'          => 'candidato@candidato.com',
                'password'       => bcrypt('12345678'),
                'role'           => User::ROLE_ENUM['candidato'],
                'primeiro_acesso' => false,
            ],
        ];

        User::insert($users);
    }
}
