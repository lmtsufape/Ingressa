<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ZipStatusListener extends Component
{
    public $showModal = false;
    public $downloadUrl = null;
    public $temArquivo = false;

    #[On('zip-gerado')]
    public function handleZipGerado(array $payload = [])
    {
        $this->showModal = true;

        $this->downloadUrl = $payload['download_url'] ?? null;

        if ($this->downloadUrl) {
            $this->temArquivo = true;
            $this->dispatch('download-zip', url: $this->downloadUrl);
        }
    }

    public function render()
    {
        return view('livewire.zip-status-listener');
    }
}
