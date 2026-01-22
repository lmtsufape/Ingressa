<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class ZipStatusListener extends Component
{
    public $showModal = false;
    public $downloadUrl = null;

    #[On('zip-gerado')]
    public function handleZipGerado($download_url = null)
    {
        $this->showModal = true;

        $this->downloadUrl = $download_url;
        if ($this->downloadUrl) {
            $this->dispatch('download-zip', url: $this->downloadUrl);
        }
    }

    public function render()
    {
        return view('livewire.zip-status-listener');
    }
}
