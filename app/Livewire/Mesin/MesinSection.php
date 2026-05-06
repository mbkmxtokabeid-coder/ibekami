<?php

namespace App\Livewire\Mesin;

use Livewire\Component;
use App\Models\Machine;

class MesinSection extends Component
{
    public array $machines = [];

    public function mount(): void
    {
        $this->loadMachines();
    }

    public function loadMachines(): void
    {
        $this->machines = Machine::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($machine) {
                return [
                    'id' => $machine->id,
                    'title' => $machine->title,
                    'image' => $this->getMachineImage($machine),
                ];
            })
            ->toArray();
    }

    private function getMachineImage($machine): string
    {
        $imageUrl = $machine->image_url;
        
        if (empty($imageUrl)) {
            return 'https://via.placeholder.com/400x400?text=' . urlencode($machine->title);
        }
        
        // Jika URL lengkap, gunakan langsung
        if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            return $imageUrl;
        }
        
        // Ekstrak nama file saja (jika ada path)
        $filename = basename($imageUrl);
        
        // Cek apakah file ada di local storage
        $localFilePath = public_path('storage/machine_picture/' . $filename);
        
        if (file_exists($localFilePath)) {
            // File ada di lokal, gunakan asset()
            return asset('storage/machine_picture/' . $filename);
        } else {
            // File tidak ada di lokal, gunakan URL ibekami.id sebagai fallback
            return 'https://ibekami.id/storage/machine_picture/' . $filename;
        }
    }

    public function render()
    {
        return view('livewire.mesin.mesin-section');
    }
}
