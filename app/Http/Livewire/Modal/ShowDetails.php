<?php

namespace App\Http\Livewire\Modal;

use Livewire\Component;

class ShowDetails extends Component
{
    public $showModal;

    protected $listeners = ['showDetails' => 'showDetails'];

    public function showDetails()
    {
        $this->showModal = true;
    }

    public function render()
    {
        return view('livewire.modal.show-details');
    }
}
