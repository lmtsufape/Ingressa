<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Jobs\GerarZipTodosDocumentosCandidatosJob;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ZipStatusListener extends Component
{
    use AuthorizesRequests;
    
    public $showModal = false;
    public $downloadUrl = null;
    public $processando = false;

    // Escuta o evento global do Livewire disparado pelo JS
    #[On('zip-gerado')]
    public function onZipGerado($url)
    {
        $this->processando = false;
        $this->downloadUrl = $url;
        $this->showModal = true;
        $this->dispatch('zip-finalizado');
    }

    #[On('gerar-zip')]
    public function gerarZip(int $cursoId, int $chamadaId)
    {
        $this->authorize('isAdmin', User::class);
        $this->processando = true;

        GerarZipTodosDocumentosCandidatosJob::dispatch(
            $cursoId,
            $chamadaId,
            auth()->id()
        );
    }


    public function render()
    {
        return view('livewire.zip-status-listener');
    }
}
