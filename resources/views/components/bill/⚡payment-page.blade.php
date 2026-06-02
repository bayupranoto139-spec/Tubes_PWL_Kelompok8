<?php

namespace App\Livewire\Patient;

use Livewire\Component;
use App\Models\Patient;

class PatientPage extends Component
{
    public $patients;

    public function mount()
    {
        $this->patients = Patient::all();
    }

    public function render()
    {
        return view('livewire.patient.patient-page');
    }
}