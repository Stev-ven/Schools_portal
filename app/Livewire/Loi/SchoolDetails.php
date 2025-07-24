<?php

namespace App\Livewire\Loi;

use App\Models\Region;
use Livewire\Component;
use App\Models\District;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;

class SchoolDetails extends Component
{
    use LivewireAlert;
    public $application_id;
    public $results, $region, $district, $selectedRegion = null, $selectedDistrict = null, $regions, $districts = [];
    public $school_name, $type_of_school, $location_of_school, $city_or_town;
    public $name_of_proprietor, $phone_number, $email, $website_address, $letter_of_introduction_document, $gps_address;

    public function updatedSelectedRegion($regionCode)
    {
        $this->districts = District::where('region_code', $regionCode)->get();
    }
    public function mount($results){
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $results['result']['data']['application_id'];

         $this->regions = Region::all();
        if ($this->selectedRegion) {
            $this->districts = District::where('region_code', $this->selectedRegion)->get();
        }
        $this->selectedRegion = $this->regions->where('name', str_replace(' region', '', $results['result']['data']['region']))->first()?->code;
        $this->updatedSelectedRegion($this->selectedRegion);
        $this->selectedDistrict = $results['result']['data']['district'];
        $this->school_name = $results['result']['data']['school_name'];
        $this->type_of_school = $results['result']['data']['type_of_school'];
        $this->location_of_school = $results['result']['data']['location_of_school'];
        $this->city_or_town = $results['result']['data']['city_or_town'];
        $this->region = $results['result']['data']['region'];
        $this->district = $results['result']['data']['district'];
        $this->name_of_proprietor = $results['result']['data']['name_of_proprietor'];
        $this->phone_number = $results['result']['data']['phone_number'];
        $this->email = $results['result']['data']['email'];
        $this->gps_address = $results['result']['data']['gps_address'];
        $this->website_address = $results['result']['data']['website_address'];
        $this->letter_of_introduction_document = $results['result']['data']['letter_of_introduction_document'];
    }

    public function sendSchoolDetails(){
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');

        $region_name = Region::where('code', $this->selectedRegion)->first();


        $this->validate([
            'school_name' => 'required',
            'type_of_school' => 'required',
            'location_of_school' => 'required',
            'city_or_town' => 'required',
            'name_of_proprietor' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'website_address' => 'required',
            'selectedRegion' => 'required',
            'selectedDistrict' => 'required',
            'gps_address' => 'required',
        ]);
        $endpoint = 'applications/apply-for-letter-of-introduction/send-details';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'application_id' => $this->application_id,
               'user_id' => Session::get('api_response')['user_id'],
                'school_name' => $this->school_name,
                'type_of_school' => $this->type_of_school,
                'location_of_school' => $this->location_of_school,
                'city_or_town' => $this->city_or_town,
                'name_of_proprietor' => $this->name_of_proprietor,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'gps_address' => $this->gps_address,
                'region' => $region_name->name . ' region',
                'district' => $this->selectedDistrict,
                'website_address' => $this->website_address
            ]

        ];
        // dd($payload);

        try {
            $response = Http::post('http://localhost/nasia/apis/application/apply-for-letter-of-introduction/send-details', $payload);
            $results = $response->json();
            // dd($results);
            if($results['status'] == "FAILED"){
                $this->alert('error', $results['errors'][0]['errMsg']);
            }else{
                 $this->dispatch('notify', icon: 'success', message: $results['statusText'] , color: 'success');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'something went wrong!');
        }
    }
    public function render()
    {
        return view('livewire.loi.school-details');
    }

     #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('payForLoi', ['applicationId' => $this->application_id]);
    }
}
