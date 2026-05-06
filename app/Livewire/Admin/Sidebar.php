<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Sidebar extends Component
{
    public bool $frontendOpen = false;
    public bool $backendOpen  = false;
    public bool $collapsed    = false;

    public function mount(): void
    {
        $route = request()->route()?->getName() ?? '';

        $this->frontendOpen = str_starts_with($route, 'admin.frontend.');
        $this->backendOpen  = str_starts_with($route, 'admin.backend.');
    }

    public function toggleFrontend(): void
    {
        $this->frontendOpen = ! $this->frontendOpen;
    }

    public function toggleBackend(): void
    {
        $this->backendOpen = ! $this->backendOpen;
    }

    public function toggleCollapse(): void
    {
        $this->collapsed = ! $this->collapsed;
    }

    public function render()
    {
        return view('livewire.admin.sidebar');
    }
}
