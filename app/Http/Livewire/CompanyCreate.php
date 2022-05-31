<?php

namespace App\Http\Livewire;

use App\Models\Company;
use Livewire\Component;

class CompanyCreate extends Component
{
    public $name;

    public function render()
    {
        return view('livewire.company-create');
    }
 
   
}
