<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class ZipStatusListener extends Component
{
    public $showModal = false;
    public $downloadUrl = null;

    #[On('zip-gerado')]
    public function handleZipGerado($payload)
    {
        $this->downloadUrl = $payload['download_url'] ?? null;
        $this->showModal   = true;

        if ($this->downloadUrl) {
            $this->dispatch('download-zip', [
                'url' => $this->downloadUrl,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.zip-status-listener');
    }
}
