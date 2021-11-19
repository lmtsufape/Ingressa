<?php

namespace Database\Seeders;

use App\Models\Cota;
use App\Models\Remanejamento;
use Illuminate\Database\Seeder;

class RemanejamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cotaA0 = Cota::where('cod_cota', 'A0')->first();
        $cotaL1 = Cota::where('cod_cota', 'L1')->first();
        $cotaL2 = Cota::where('cod_cota', 'L2')->first();
        $cotaL5 = Cota::where('cod_cota', 'L5')->first();
        $cotaL6 = Cota::where('cod_cota', 'L6')->first();
        $cotaL9 = Cota::where('cod_cota', 'L9')->first();
        $cotaL10 = Cota::where('cod_cota', 'L10')->first();
        $cotaL13 = Cota::where('cod_cota', 'L13')->first();
        $cotaL14 = Cota::where('cod_cota', 'L14')->first();

        $vagasL1 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaL9->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaL10->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaL2->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaL14->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaL6->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaL13->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaL5->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL1->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        $vagasL2 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaL10->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaL9->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaL1->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaL14->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaL6->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaL13->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaL5->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL2->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        $vagasL5 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaL13->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaL10->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaL2->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaL9->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaL1->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaL14->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaL6->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL5->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        $vagasL6 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaL14->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaL10->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaL2->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaL9->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaL1->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaL13->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaL5->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL6->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        $vagasL9 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaL1->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaL10->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaL2->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaL14->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaL6->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaL13->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaL5->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL9->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        $vagasL10 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaL2->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaL9->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaL1->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaL14->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaL6->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaL13->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaL5->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL10->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        $vagasL13 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaL5->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaL10->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaL2->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaL9->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaL1->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaL14->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaL6->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL13->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        $vagasL14 = [
            [
                'ordem' => 1,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaL6->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaL10->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaL2->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaL9->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaL1->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaL13->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaL5->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaL14->id,
                'id_prox_cota' => $cotaA0->id,
            ],
        ];

        Remanejamento::insert($vagasL1);
        Remanejamento::insert($vagasL2);
        Remanejamento::insert($vagasL5);
        Remanejamento::insert($vagasL6);
        Remanejamento::insert($vagasL9);
        Remanejamento::insert($vagasL10);
        Remanejamento::insert($vagasL13);
        Remanejamento::insert($vagasL14);
    }
}
