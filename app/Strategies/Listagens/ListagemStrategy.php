<?php

namespace App\Strategies\Listagens;

use App\Models\Listagem;

interface ListagemStrategy{

    public function key();
    public function generate($request, Listagem $listagem);
}
