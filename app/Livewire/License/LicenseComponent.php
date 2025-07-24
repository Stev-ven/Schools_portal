<?php

namespace App\Livewire\License;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LicenseComponent extends Component
{


    public function viewApplication($id){
        return redirect()->route('viewApplication', ['id' => $id]);
    }




    public function render()
    {
        return view('livewire.license.license-component');
    }
}
