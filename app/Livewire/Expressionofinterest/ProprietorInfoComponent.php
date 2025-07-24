<?php

namespace App\Livewire\Expressionofinterest;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use GuzzleHttp\Client;

class ProprietorInfoComponent extends Component
{
    use LivewireAlert;
    public $results;
    public $selected_proprietor = null;
    public $selected_organisation = null;
    public $organisation_name;
    public $organisation_email;
    public $organisation_telephone;
    public $proprietor_name;
    public $proprietor_email;
    public $proprietor_telephone;
    public $proprietor_gender;
    public $proprietor_genders = [
        'Male' => 'Male',
        'Female' => 'Female',
    ];
    public $proprietor_occupation;
    public $proprietor_qualification;
    public $proprietor_title;
    public $proprietor_titles = [
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
    public $proprietor_info_type;
    public $list_of_proprietors;
    public $list_of_organisations;
    public $proprietors = [], $organisations = [];

    public $person_name, $person_title, $person_email, $person_telephone, $person_gender, $person_occupation, $person_qualification;
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
    public $person_genders = [
        'Male' => 'Male',
        'Female' => 'Female',
    ];
    public function mount($results)
    {
        $this->results = $results;
        $this->proprietors = $results['proprietors'];
        $this->organisations = $results['organization'];
    }

    public function editProprietor($proprietor)
    {
        $this->selected_proprietor = $proprietor;
    }
    public function updateProprietor()
    {
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/apply-for-expression-of-interest/add-individual-proprietors';
        if (!$this->selected_proprietor) {
            $this->alert('error', 'No proprietor selected for update.');
            return;
        }
        $this->validate([
            'selected_proprietor.name' => 'required|string',
            'selected_proprietor.email_address' => 'required|email',
            'selected_proprietor.telephone_number' => 'required|numeric|digits:10',
            'selected_proprietor.gender' => 'required',
            'selected_proprietor.occupation' => 'required',
            'selected_proprietor.qualification' => 'required',
            'selected_proprietor.person_title' => 'required',
        ]);
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => $this->selected_proprietor['user_id'],
                'application_id' => $this->selected_proprietor['application_id'],
                'proprietor_info_type' => 'individuals',
                'proprietor_id' => $this->selected_proprietor['id'],
                'name' => $this->selected_proprietor['name'],
                'email_address' => $this->selected_proprietor['email_address'],
                'telephone_number' => $this->selected_proprietor['telephone_number'],
                'gender' => $this->selected_proprietor['gender'],
                'occupation' => $this->selected_proprietor['occupation'],
                'qualification' => $this->selected_proprietor['qualification'],
                'person_title' => $this->selected_proprietor['person_title'],
            ],
        ];

        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $results = $response->json();
            if ($results['status'] == 'OK') {
                $this->proprietorInformation();
                $this->closeEditProprietorModal();
                $this->alert('success', $results['statusText']);
            } else {
                $this->alert('error', $results['statusText']);
                // $this->dispatch('notify', icon: 'error', message: 'Proprietor update failed' , color: 'danger');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'something went wrong! please try again later');
        }
    }

    public function closeEditProprietorModal(){
        $this->selected_proprietor['name'] = '';
        $this->selected_proprietor['email_address'] = '';
        $this->selected_proprietor['telephone_number'] = '';
        $this->selected_proprietor['gender'] = '';
        $this->selected_proprietor['occupation'] = '';
        $this->selected_proprietor['qualification'] = '';
        $this->selected_proprietor['person_title'] = '';
    }
    public function removeProprietor($proprietor){
        $this->selected_proprietor = $proprietor;
        $this->dispatch('show-delete-proprietor-modal');
    }
    public function deleteProprietory()
    {
        if (!isset($this->selected_proprietor)) {
            $this->alert('error', 'No proprietor selected for deletion.');
            return;
        }
        Log::info('deleteProprietor called');
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/apply-for-expression-of-interest/delete-individual-proprietors';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => $this->selected_proprietor['user_id'],
                'application_id' => $this->selected_proprietor['application_id'],
                'proprietor_id' => $this->selected_proprietor['id'],
            ]
        ];
        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if ($results['statusText'] !== 'Proprietor has been remove successfully.') {
                $this->selected_proprietor = null;
                $this->dispatch('close-delete-proprietor-modal');
                return;
                $this->alert('error', $results['statusText']);
                // return;
            }
            // Remove deleted proprietor from the array
            $this->proprietors = collect($this->proprietors)
                ->reject(fn($item) => $item['id'] === $this->selected_proprietor['id'])
                ->values()
                ->toArray();
            // dd($results['statusText']);
            // $this->dispatch('notifydelete', icon: 'success', message: $results['statusText'], color: 'success');
            $this->alert('success', 'Proprietor deleted successfully');
            return;
            $this->selected_proprietor = null;
            $this->dispatch('close-delete-proprietor-modal');
        } catch (\Exception $e) {
            $this->alert('error', 'something went wrong! please try again');
        }
    }

    public function closeDeleteProprietorModal()
    {
        $this->dispatch('close-delete-proprietor-modal');
    }
    public function closeAddProprietorModal()
    {
        $this->person_title = '';
        $this->person_name = '';
        $this->person_email = '';
        $this->person_telephone = '';
        $this->person_gender = '';
        $this->person_occupation = '';
        $this->person_qualification = '';
    }
    public function removeOrganisation($organisation)
    {
        $this->selected_organisation = $organisation;
        // dd($this->selected_organisation);
    }

    public function deleteOrganisation()
    {
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/apply-for-expression-of-interest/delete-organization';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => $this->selected_organisation['user_id'],
                'application_id' => $this->selected_organisation['application_id'],
                'proprietor_id' => $this->selected_organisation['id'],
            ]
        ];
        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $results = $response->json();
            dd($results);

            if ($results['status'] == 'OK') {
                $this->dispatch('notifydelete', icon: 'success', message: 'Organisation Deleted', color: 'success');
            } else {
                $this->alert('error', $results['statusText']);
            }
        } catch (\Exception $e) {
            $this->alert('error', 'something went wrong! please try again');
        }
    }
    public function resetSelectedProprietor()
    {
        $this->selected_proprietor = null;
    }
    public function addOrganisation()
    {
        $this->validate([
            'organisation_name' => 'required|string',
            'organisation_email' => 'required|email',
            'organisation_telephone' => 'required|numeric|digits:10',
            'proprietor_name' => 'required|string',
            'proprietor_email' => 'required|email',
            'proprietor_telephone' => 'required|numeric|digits:10',
            'proprietor_gender' => 'required',
            'proprietor_occupation' => 'required',
            'proprietor_qualification' => 'required',
            'proprietor_title' => 'required',
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/apply-for-expression-of-interest/add-proprietors';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'application_id' => $this->results['application_id'],
                'user_id' => session::get('api_response')['user_id'],
                'proprietor_info_type' => 'organization',
                'name' => $this->organisation_name,
                'email_address' => $this->organisation_email,
                'telephone_number' => $this->organisation_telephone,
                'individuals' => [
                    [
                        'name' => $this->proprietor_name,
                        'email_address' => $this->proprietor_email,
                        'telephone_number' => $this->proprietor_telephone,
                        'gender' => $this->proprietor_gender,
                        'occupation' => $this->proprietor_occupation,
                        'qualification' => $this->proprietor_qualification,
                        'person_title' => $this->proprietor_title,
                    ]
                ],
            ]

        ];
        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $results = $response->json();
            if ($results['status'] == "OK") {
                $this->proprietorInformation();
                $this->alert('success', $results['statusText']);
                $this->closeOrgModal();
            } else {
                $this->alert('error', $results['errors'][0]['errMsg']);
            }
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }
    private function proprietorInformation()
    {
        $client = new Client([
            'verify' => false,
        ]);
        $base_url = config('app.url');
        $endpoint = '/application/expression-of-interest/get-expression-of-interest';
        $payload = [
            'api_key' => config('nasia.api.key'),
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $this->results['application_id'],
            ]
        ];
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $this->proprietors = $responseData['result']['data']['proprietors'];
            $this->organisations = $responseData['result']['data']['organization'];
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }
    public function addIndividualProprietor()
    {
        $this->validate([
            'person_title' => 'required',
            'person_name' => 'required|string',
            'person_email' => 'required|email',
            'person_telephone' => 'required|numeric|digits:10',
            'person_gender' => 'required|string',
            'person_occupation' => 'required|string',
            'person_qualification' => 'required|string',
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/apply-for-expression-of-interest/add-individual-proprietors';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'proprietor_info_type' => 'individuals',
                'user_id' => Session::get('api_response')['user_id'],
                'application_id' => $this->results['application_id'],
                'person_title' => $this->person_title,
                'name' => $this->person_name,
                'email_address' => $this->person_email,
                'telephone_number' => $this->person_telephone,
                'gender' => $this->person_gender,
                'occupation' => $this->person_occupation,
                'qualification' => $this->person_qualification,
            ],

        ];
        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $results = $response->json();

            if ($results['status'] == 'OK') {
                $this->closeAddProprietorModal();
                $this->proprietorInformation();
                $this->alert('success', $results['statusText']);
            } else {
                $this->alert('error', $results['statusText']);
            }
        } catch (\Exception $e) {
            $this->alert('error', 'something went wrong! please try again later');
        }
    }

    public function closeOrgModal()
    {
        $this->organisation_name = '';
        $this->organisation_email = '';
        $this->organisation_telephone = '';
        $this->proprietor_title = '';
        $this->proprietor_name = '';
        $this->proprietor_email = '';
        $this->proprietor_telephone = '';
        $this->proprietor_gender = '';
        $this->proprietor_occupation = '';
        $this->proprietor_qualification = '';
    }


    #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('proprietorInformation', ['applicationId' => $this->results['application_id']]);
    }
    public function nextSection()
    {
        if (empty($this->results['proprietors']) && empty($this->results['organization'])) {
            session()->flash('message', 'Please add at least one proprietor ');
            session()->flash('message_type', 'error');
            return;
        }
        return redirect()->route('eoiDocument', ['applicationId' => $this->results['application_id']]);
    }
    public function render()
    {
        return view('livewire.expressionofinterest.proprietorInfo-component');
    }
}
