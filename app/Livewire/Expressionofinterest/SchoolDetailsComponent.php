<?php

namespace App\Livewire\Expressionofinterest;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SchoolDetailsComponent extends Component
{   use LivewireAlert;
    public $results, $application_id;
    public $school_name, $suburb, $postal_address, $streetname, $landmark;
    public function mount($results){
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $this->results['application_details']['application_id'];
        $this->school_name = $this->results['application_details']['school_name']??'';
        $this->suburb = $this->results['data']['school_details'][0]['suburb']??'';
        $this->postal_address = $this->results['data']['school_details'][0]['postal_address']??'';
        $this->streetname = $this->results['data']['school_details'][0]['streetname']??'';
        $this->landmark = $this->results['data']['school_details'][0]['landmark']??'';
    }

    protected $rules = [
        'school_name' => 'required',
         'suburb' => 'required|string',
         'postal_address' => 'required',
         'streetname' => 'required',
         'landmark' => 'required|string',
    ];
    //custom error messages

    protected $messages= [
        'school_name.required'=> 'Enter school name',
        'suburb.required'=> 'Enter suburb',
        'postal_address.required'=> 'Enter postal address',
        'streetname.required'=> 'Enter streetname',
        'landmark.required'=> 'Enter landmark'
    ];
     public function editSchoolDetails(){
        $this->validate();
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/apply-for-expression-of-interest/send-school-details';
        $payload = [
             'api_key' => $api_key,
             'data' => [
                 'user_id' => Session::get('api_response')['user_id'],
                 'application_id' => $this->application_id,
                 'suburb' => $this->suburb,
                 'postal_address' => $this->postal_address,
                 'streetname' => $this->streetname,
                 'landmark' => $this->landmark,
                 'land_owned_by_proprietor' => 'yes',
             ]
             ];
            //  dd($payload);
             try{
                 $response = Http::post($base_url.$endpoint, $payload);
                 $results = $response->json();
                //  dd($results);
                if ($results['status'] == 'FAILED') {
                    $this->alert('error', $results['statusText']);

                } else {
                    $text = $results['statusText'];
                   $this->dispatch('notify', icon: 'success', message: $text , color: 'success');

                }
             }catch(\Exception $e){
                $this->alert('error', 'Something went wrong. Please try again.');
            }
     }


    #[On('redirect')]
    public function updatePostList()
    {
        // return $this->redirectRoute('eoiDocument', ['applicationId' => $this->results['application_details']['application_id']]);
        return $this->redirectRoute('proprietorInformation', ['applicationId' => $this->results['application_details']['application_id']]);
    }

    public function back(){
        // dd($this->results);
        return redirect()->route('viewApplication', ['special_school_id' => $this->results['application_details']['special_school_id']]);
    }
    public function render()
    {
        return view('livewire.expressionofinterest.schooldetails-component');
    }
}
