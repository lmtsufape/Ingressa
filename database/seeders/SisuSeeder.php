<?php

namespace Database\Seeders;

use App\Models\Sisu;
use Illuminate\Database\Seeder;

class SisuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sisus = [
            [
                'edicao'           => '2022',
                'created_at'       => now(),
            ],
        ];

        Sisu::insert($sisus);
    }
}
