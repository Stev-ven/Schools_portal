<?php

namespace App\Livewire\License;

use App\Models\Region;
use Livewire\Component;
use App\Models\District;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class RegisterSchool extends Component
{   use LivewireAlert;
    public $school_name;
    public $school_established_before_august_26_2020;
    public $registered_with_registrar_general_department;
    public $regions;
    public $districts = [];
    public $selectedRegion = null;
    public $selectedDistrict = null;
    public $type_of_school;
    public function mount(){
        $this->regions = Region::all();
    }
    public function updatedSelectedRegion($regionCode){
        $this->districts = District::where('region_code', $regionCode)->get();

        // $this->selectedDistrict = null;

    }
    public $rules = [
        'school_name' => 'required',
        'school_established_before_august_26_2020' => 'required',
        'registered_with_registrar_general_department' => 'required',
        'selectedRegion' => 'required',
        'selectedDistrict' => 'required',
        'type_of_school' => 'required',
    ];

    protected $messages = [
        'school_name.required' => 'Enter school name',
        'school_established_before_august_26_2020.required' => 'Select an option',
        'registered_with_registrar_general_department.required' => 'Select an option',
        'selectedRegion.required' => 'Select a region',
        'selectedDistrict.required' => 'Select a district',
        'type_of_school.required' => 'Select a type of school',

    ];

    #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('dashboard');
    }


    public function registerSchool(){

        $this->validate();
        $region_name = Region::where('code', $this->selectedRegion)->first();
        // dd($region_name->name);
        $payload = [
            "api_key" => "AWERFD12322425642",
            "data" => [
                "user_id" => Session::get('api_response')['user_id'],
                "school_name" => $this->school_name,
                "school_established_before_august_26_2020" => $this->school_established_before_august_26_2020,
                "registered_with_registrar_general_department" => $this->registered_with_registrar_general_department,
                "region" => $region_name->name. ' region',
                "district" => $this->selectedDistrict,
                "type_of_school" => $this->type_of_school]
            ];
            $base_url = config('app.url');
            $endpoint = '/application/generate-new-applications';

            try{
               $response = Http::post($base_url.$endpoint, $payload);
               $results = $response->json();

               if($results['status'] == 'FAILED'){
                    $this->alert('error', $results['statusText']);
                }else{
                    $text = $results['statusText'];
                    $this->dispatch('notify', icon: 'success', message: $text , color: 'success');
                }
            } catch (\Exception $e) {
                 $this->alert('error', 'School registration failed. Please try again.');
            }
        }
    public function back(){
        return $this->redirectRoute('dashboard');
    }
    public function render(){
        return view('livewire.license.register-school');
    }
}
