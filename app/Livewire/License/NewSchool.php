<?php

namespace App\Livewire\License;

use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

class NewSchool extends Component
{
    
    public $regions ;
    #[Validate('required')]
    public $selectedRegion ; 
    #[Validate('required')]
    public $is_registered ;
    #[Validate('required')]
    public $is_established;
    #[Validate('required|string')]
    public $school_name;
    #[Validate('required')]
    public $school_type;
    #[Validate('required')]
   
    #[Validate('required')]
    public $district;

    public $all_districts = [];


    public function updatedSelectedRegion($region){
       
        $region = DB::table('regions')
                        // ->select('name') 
                        ->where('name' , $region)
                        ->first(); 

        

        $this->all_districts = DB::table('districts')
                                ->where('region_code' , $region->code)
                                ->get();
    }

    public function submit(){

        $this->validate();
        
        $this->dispatch('success' , type: "success" , title: "success" ,  msg : "Data saved successfully");
    }


    public function render()
    {
        $this->regions =  DB::table('regions')
                        ->select('name')  
                        ->distinct()
                        ->get();

        return view('livewire.license.new-school' );
    }
}
