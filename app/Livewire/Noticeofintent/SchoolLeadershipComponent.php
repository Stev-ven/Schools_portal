<?php

namespace App\Livewire\Noticeofintent;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SchoolleadershipComponent extends Component
{   use LivewireAlert;
    public $results;
    public $email_address, $telephone_number, $ntc_license_number, $permanent_residential_address, $gender, $highest_academic_qualification, $highest_professional_qualification,
            $person_title, $full_name, $role, $selected_leader;
    public $leader_genders = [
        'Male' => 'Male',
        'Female' => 'Female',
    ];
    public $leader_titles = [
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

    public function mount($results){
        $this->results = $results;

    }

    protected $rules = [
        'email_address' => 'required|email',
        'telephone_number' => 'required|numeric|digits:10',
        'ntc_license_number' => 'required',
        'permanent_residential_address' => 'required',
        'gender' => 'required',
        'highest_academic_qualification' => 'required',
        'highest_professional_qualification' => 'required',
        'person_title' => 'required',
        'full_name' => 'required',
        'role' => 'required',
    ];
    public function addNewLeader(){

        $this->validate();

         $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/edit-single-school-leadership-details';
        // dd($base_url.$endpoint);
        $payload = [
            'api_key'=> $api_key,
            'data'=> [
                'user_id' => Session::get('api_response')['user_id'],
                'application_id' => $this->results['application_id'],
                'email_address' => $this->email_address,
                'telephone_number' => $this->telephone_number,
                'ntc_license_number' => $this->ntc_license_number,
                'permanent_residential_address' => $this->permanent_residential_address,
                'gender' => $this->gender,
                'highest_academic_qualification' => $this->highest_academic_qualification,
                'highest_professional_qualification' => $this->highest_professional_qualification,
                'person_title' => $this->person_title,
                'full_name' => $this->full_name,
                'role' => $this->role
            ]
            ];
            // dd($payload);
        try{
            $response = Http::post($base_url.$endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if($results['status'] == "FAILED"){
               $this->alert('error', $results['statusText']);
            }else{
                $this->alert('success', $results['statusText']);
                $this->fetchLeadership();
            }
        }catch(\Exception $e){
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }

    private function fetchLeadership(){
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/get-school-leadership-details';
        $payload = [
            'api_key'=> $api_key,
            'data'=> [
                'user_id' => Session::get('api_response')['user_id'],
                'application_id' => $this->results['application_id'],
            ]
        ];
        try{
            $response = Http::post($base_url.$endpoint, $payload);
            $results = $response->json();
            // dd($results);
            $this->results['result'] = $results['result'];
        }catch(\Exception $e){
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }

    public function editLeader($leader){
        $this->selected_leader = $leader;
        // dd($this->selected_leader);
    }
    public function updateLeader(){
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');

        $endpoint = '/application/notice-of-intent/edit-single-school-leadership-details';

        if (!$this->selected_leader) {
            $this->alert('error', 'No leader selected for update.');
            return;
        }
        $validatedLeader = $this->validate([
            'selected_leader.full_name' => 'required',
            'selected_leader.role' => 'required',
            'selected_leader.email_address' => 'required|email',
            'selected_leader.telephone_number' => 'required|numeric|digits:10',
            'selected_leader.permanent_residential_address' => 'required|string',
            'selected_leader.gender' => 'required|in:male,female',
            'selected_leader.highest_academic_qualification' => 'required',
            'selected_leader.highest_professional_qualification' => 'required',
            'selected_leader.person_title' => 'required',
        ]);
        if(!$validatedLeader){
            $this->alert('error', 'Please fill in all required fields.');
            return;
        }
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'leader_id' => $this->selected_leader['id'],
                'application_id' => $this->selected_leader['application_id'],
                'user_id' => $this->selected_leader['user_id'],
                'role' => $this->selected_leader['role'],
                'full_name' => $this->selected_leader['full_name'],
                'email_address' => $this->selected_leader['email_address'],
                'telephone_number' => $this->selected_leader['telephone_number'],
                'gender' => $this->selected_leader['gender'],
                'person_title' => $this->selected_leader['person_title'],
                'permanent_residential_address' => $this->selected_leader['permanent_residential_address'],
                'highest_academic_qualification' => $this->selected_leader['highest_academic_qualification'],
                'highest_professional_qualification' => $this->selected_leader['highest_professional_qualification'],
            ],
        ];
        // dd($payload);

        try {
            $response = Http::post($base_url.$endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if ($results['status'] == 'OK') {
                $this->fetchLeadership();
                $this->alert('success', $results['statusText']);

            } else {
                $this->alert('error', $results['statusText']);
                // $this->dispatch('notify', icon: 'error', message: 'Proprietor update failed' , color: 'danger');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'something went wrong! please try again later');
        }
    }

    public function nextSection(){
        if($this->results['result'] == []){
            $this->alert('info', 'Please add at least one leader to continue.');
            return;
        }
        return redirect()->route('noticeofintentschoolfees', $this->results['application_id']);
    }
    public function render()
    {
        return view('livewire.noticeofintent.schoolleadership-component');
    }
}
