<?php

namespace App\Livewire\License;

use App\Models\Region;
use Livewire\Component;
use App\Models\District;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class Appforauth extends Component
{
    use LivewireAlert;
    public $applicationId;
    public $afa_details_endpoint = '/application/authorization/get-authorization-details';


    public $person_title;
    public $person_titles = [
        'Dr.' => 'Dr.',
        'Prof' => 'Prof.',
        'Eng.' => 'Eng.',
        'Mr.' => 'Mr.',
        'Mrs.' => 'Mrs.',
        'Ms.' => 'Ms.',
        'Miss' => 'Miss',
        'Lawyer' => 'Lawyer',
        'other' => 'other',
    ];


    public $location_of_school, $city_or_town, $name_of_proprietor, $phone_number, $email;
    public $gps_address, $website_address, $regions, $districts = [], $selectedRegion = null;
    public $selectedDistrict = null;
    public $school_name; public $application_id;
    public $results, $api_key, $base_url;
    public $edit_afa_endpoint = '/application/apply-for-authorization/send-details';

    public $rules = [
        'location_of_school' => 'required',
        'city_or_town' => 'required',
        'person_title' => 'required',
        'name_of_proprietor' => 'required',
        'phone_number' => 'required|numeric|digits:10',
        'email' => 'required|email',
        // 'gps_address' => 'required',
        'website_address' => 'required',
        "school_name" => "required",
        // "SelectedRegion" => "required",
        // "selectedDistrict" => "required",
    ];

    //custom validation message
    protected $messages = [
        'location_of_school.required' => 'Enter School Location',
        'city_or_town.required' => 'Enter city or town',
        'person_title.required' => 'Select title',
        'name_of_proprietor.required' => 'Enter name of proprietor',
        'phone_number.required' => 'Enter phone number',
        'phone_number.numeric' => 'Enter valid phone number',
        'phone_number.digits' => 'Enter valid phone number',
        'email.required' => 'Enter email',
        'email.email' => 'Enter valid email',
        'gps_address.required' => 'Enter gps address',
        'website_address.required' => 'Enter website address',
        "school_name.required" => "Enter school name",
        "selectedRegion.required" => "Select a region",
        "selectedDistrict.required" => "Select a district",
    ];

    public function mount($results)
    {
        $this->results = session('application_for_authorization');
        // dd($this->results);
        $this->api_key = config('nasia.api.key');
        $this->base_url = config('app.url');

        $this->regions = Region::all();
        if ($this->selectedRegion) {
            $this->districts = District::where('region_code', $this->selectedRegion)->get();
        }
        $this->selectedRegion = $this->regions->where('name', str_replace(' region', '', $results['region']))->first()?->code;
        $this->updatedSelectedRegion($this->selectedRegion);
        $this->selectedDistrict = $results['district'] ?? '';
        $this->school_name = $results['school_name'] ?? '';
        $this->gps_address = $results['gps_address'] ?? '';
        $this->city_or_town = $results['city_or_town'] ?? '';
        $this->location_of_school = $results['location_of_school'] ?? '';
        $this->application_id = $results['application_id'] ?? '';
        $this->person_title = $results['person_title'] ?? '';
        $this->name_of_proprietor = $results['name_of_proprietor'] ?? '';
        $this->phone_number = $results['phone_number'] ?? '';
        $this->email = $results['email'] ?? '';
        $this->website_address = $results['website_address'] ?? '';
    }

    public function updatedSelectedRegion($regionCode)
    {
        $this->districts = District::where('region_code', $regionCode)->get();
    }
    public function back()
    {
        return redirect()->route('viewApplication', ['special_school_id' => $this->results['special_school_id']]);
    }
    public function editAfa()
    {
        $region_name = Region::where('code', $this->selectedRegion)->first();
        $this->validate([
            'location_of_school' => 'required',
            'city_or_town' => 'required',
            'person_title' => 'required',
            'name_of_proprietor' => 'required',
            'phone_number' => 'required|numeric|digits:10',
            'email' => 'required|email',
            // 'gps_address' => 'required',
            'website_address' => 'required',
            "school_name" => "required",
            // "SelectedRegion" => "required",
            // "selectedDistrict" => "required",
        ]);
        $payload = [
            "api_key" => $this->api_key,
            "data" => [
                "application_id" => $this->results['application_id'],
                "user_id" => Session::get('api_response')['user_id'],
                "location_of_school" => $this->location_of_school,
                "city_or_town" => $this->city_or_town,
                "person_title" => $this->person_title,
                "name_of_proprietor" => $this->name_of_proprietor,
                "phone_number" => $this->phone_number,
                "email" => $this->email,
                "gps_address" => $this->gps_address,
                "website_address" => $this->website_address,
                "school_name" => $this->school_name,
                "region" => $region_name->name . ' region',
                "district" => $this->selectedDistrict,
            ]
        ];
        // dd($payload);
        try {
            $response = Http::post($this->base_url.$this->edit_afa_endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if ($results['status'] == 'FAILED') {
                $this->alert('error', $results['errors'][0]['errMsg']);

            } else {
                $this->fetchAfaDetails();
                $text = $results['statusText'];
                $this->dispatch('notify', icon: 'success', message: $text, color: 'success');

            }
        } catch (\Exception $e) {
            $this->alert('error', 'application update failed. Please try again.');
        }
    }

    private function fetchAfaDetails(){
        $payload = [
            "api_key" => $this->api_key,
            "data" => [
                "application_id" => $this->application_id,
                "user_id" => Session::get('api_response')['user_id'],
            ]
            ];
            try{
                $response = Http::post($this->base_url.$this->afa_details_endpoint, $payload);
                $this->results = $response['result'];
                session()->put('application_for_authorization', $this->results);

            }catch(\Exception $e){
                $this->alert('error', 'An error occurred. Please try again.');
            }
    }

    #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('afadocument', ['applicationId' => $this->application_id]);
    }
    public function render()
    {
        return view('livewire.license.appforauth');
    }
}
