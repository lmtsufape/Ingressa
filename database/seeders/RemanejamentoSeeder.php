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
        $cotaAC = Cota::where('cod_novo', 'AC')->first();
        $cotaLB_PPI = Cota::where('cod_novo', 'LB_PPI')->first();
        $cotaLB_Q = Cota::where('cod_novo', 'LB_Q')->first();
        $cotaLB_PCD = Cota::where('cod_novo', 'LB_PCD')->first();
        $cotaLB_EP = Cota::where('cod_novo', 'LB_EP')->first();
        $cotaLI_PPI = Cota::where('cod_novo', 'LI_PPI')->first();
        $cotaLI_Q = Cota::where('cod_novo', 'LI_Q')->first();
        $cotaLI_PCD = Cota::where('cod_novo', 'LI_PCD')->first();
        $cotaLI_EP = Cota::where('cod_novo', 'LI_EP')->first();

        $vagasLB_EP = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaLB_PPI->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaLB_Q->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaLB_PCD->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaLI_PPI->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaLI_Q->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaLI_PCD->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaLI_EP->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLB_EP->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        $vagasLI_PPI = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaLB_PPI->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaLB_Q->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaLB_PCD->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaLB_EP->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaLI_Q->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaLI_PCD->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaLI_EP->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLI_PPI->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        $vagasLI_EP = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaLB_PPI->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaLB_Q->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaLB_PCD->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaLB_EP->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaLI_PPI->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaLI_Q->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaLI_PCD->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLI_EP->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        $vagasLB_PPI = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaLB_Q->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaLB_PCD->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaLB_EP->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaLI_PPI->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaLI_Q->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaLI_PCD->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaLI_EP->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLB_PPI->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        $vagasLB_PCD = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaLB_PPI->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaLB_Q->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaLB_EP->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaLI_PPI->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaLI_Q->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaLI_PCD->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaLI_EP->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLB_PCD->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        $vagasLB_Q = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaLB_PPI->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaLB_PCD->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaLB_EP->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaLI_PPI->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaLI_Q->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaLI_PCD->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaLI_EP->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLB_Q->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        $vagasLI_PCD = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaLB_PPI->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaLB_Q->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaLB_PCD->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaLB_EP->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaLI_PPI->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaLI_Q->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaLI_EP->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLI_PCD->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        $vagasLI_Q = [
            [
                'ordem' => 1,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaLB_PPI->id,
            ],
            [
                'ordem' => 2,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaLB_Q->id,
            ],
            [
                'ordem' => 3,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaLB_PCD->id,
            ],
            [
                'ordem' => 4,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaLB_EP->id,
            ],
            [
                'ordem' => 5,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaLI_PPI->id,
            ],
            [
                'ordem' => 6,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaLI_PCD->id,
            ],
            [
                'ordem' => 7,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaLI_EP->id,
            ],
            [
                'ordem' => 8,
                'cota_id' => $cotaLI_Q->id,
                'id_prox_cota' => $cotaAC->id,
            ],
        ];

        Remanejamento::insert($vagasLB_PPI);
        Remanejamento::insert($vagasLB_Q);
        Remanejamento::insert($vagasLB_PCD);
        Remanejamento::insert($vagasLB_EP);
        Remanejamento::insert($vagasLI_PPI);
        Remanejamento::insert($vagasLI_Q);
        Remanejamento::insert($vagasLI_PCD);
        Remanejamento::insert($vagasLI_EP);
    }
}
