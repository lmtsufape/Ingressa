<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sisu;
use App\Models\DataChamada;
use App\Models\Listagem;

class WelcomeController extends Controller
{
    public function index()
    {
        $intervalo_inicio = now()->subMonth(2);
        $intervalo_fim = now()->addMonth(2);
        
        $edicao_atual = Sisu::where([['created_at', '>=', $intervalo_inicio], ['created_at', '<=', $intervalo_fim]])->first();
        $chamadas = $edicao_atual->chamadas()->orderBy('created_at', 'DESC')->get();

        return view('welcome', compact('chamadas', 'edicao_atual'))->with(['tipos_data' => DataChamada::TIPO_ENUM, ['tipos_listagem' => Listagem::TIPO_ENUM]]);
    }

    public function login()
    {
        return view('auth.login');
    }
}
