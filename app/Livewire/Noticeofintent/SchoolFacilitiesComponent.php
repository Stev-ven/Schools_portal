<?php

namespace App\Livewire\Noticeofintent;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SchoolfacilitiesComponent extends Component
{   use LivewireAlert;
    public $results, $application_id;
    public $share_premises, $share_premises_with, $share_building, $share_building_with, $number_of_classrooms, $project_enrollment;

    //washrooms
    public $number_of_washrooms_staff_male, $number_of_washrooms_staff_female, $number_of_washrooms_staff_total = 0;
    public $number_of_washrooms_students_male, $number_of_washrooms_students_female, $number_of_washrooms_students_total = 0;

    //toilets
    public $number_of_toilets_staff_male, $number_of_toilets_staff_female, $number_of_toilets_staff_total = 0;
    public $number_of_toilets_students_male, $number_of_toilets_students_female, $number_of_toilets_students_total = 0;

    //urinals
    public $number_of_urinals_staff_male, $number_of_urinals_staff_female, $number_of_urinals_staff_total = 0;
    public $number_of_urinals_students_male, $number_of_urinals_students_female, $number_of_urinals_students_total = 0;

    public function updated(){
        $this->number_of_washrooms_staff_total = (int)($this->number_of_washrooms_staff_male ?? 0) + (int)($this->number_of_washrooms_staff_female ?? 0);
        $this->number_of_washrooms_students_total = (int)($this->number_of_washrooms_students_male ?? 0 ) + (int)($this->number_of_washrooms_students_female ?? 0);

        $this->number_of_toilets_staff_total = (int)($this->number_of_toilets_staff_male ?? 0) + (int)($this->number_of_toilets_staff_female ?? 0);
        $this->number_of_toilets_students_total = (int)($this->number_of_toilets_students_male ?? 0) + (int)($this->number_of_toilets_students_female ?? 0);

        $this->number_of_urinals_staff_total = (int)($this->number_of_urinals_staff_male ?? 0) + (int)($this->number_of_urinals_staff_female ?? 0);
        $this->number_of_urinals_students_total = (int)($this->number_of_urinals_students_male ?? 0) + (int)($this->number_of_urinals_students_female ?? 0);
    }

    protected $cast = [
        'number_of_washrooms_staff_male' => 'integer', 'number_of_washrooms_staff_female'=> 'integer',
        'number_of_washrooms_students_male'=>'integer', 'number_of_washrooms_stedents_female'=>'integer',

        'number_of_toilets_staff_male'=> 'integer', 'number_of_toilets_staff_female' => 'integer',
        'number_of_toilets_students_male'=>'integer', 'number_of_toilets_students_female'=>'integer',

        'number_of_urinals_staff_male'=>'integer', 'number_of_urinals_staff_female'=>'integer',
        'number_of_urinals_students_male'=>'integer', 'number_of_urinals_students_fmale'=>'integer'
    ];
    public function mount($results){
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $results['result']['application_id'];
        // dd($this->results);
        $this->share_premises_with = $results['result']['share_premises_with'] ?? null;
        $this->share_premises = $results['result']['share_premises'] ?? null;
        $this->share_building_with = $results['result']['share_building_with'] ?? null;
        $this->share_building = $results['result']['share_building'] ?? null;
        $this->number_of_classrooms = $results['result']['number_of_classrooms'] ?? null;
        $this->project_enrollment = $results['result']['project_enrollment'] ?? null;
        $this->project_enrollment = $results['result']['project_enrollment'] ?? null;

        $this->number_of_washrooms_staff_male = $results['result']['number_of_washrooms_staff_male'] ?? null;
        $this->number_of_washrooms_staff_female = $results['result']['number_of_washrooms_staff_female'] ?? null;
        $this->number_of_washrooms_staff_total = $this->number_of_washrooms_staff_male + $this->number_of_washrooms_staff_female;
        $this->number_of_washrooms_students_male = $results['result']['number_of_washrooms_students_male'] ?? null;
        $this->number_of_washrooms_students_female = $results['result']['number_of_washrooms_students_female'] ?? null;
        $this->number_of_washrooms_students_total = $this->number_of_washrooms_students_male + $this->number_of_washrooms_students_female;

        $this->number_of_toilets_staff_male = $results['result']['number_of_toilets_staff_male'] ?? null;
        $this->number_of_toilets_staff_female = $results['result']['number_of_toilets_staff_female'] ?? null;
        $this->number_of_toilets_staff_total = $this->number_of_toilets_staff_male + $this->number_of_toilets_staff_female;
        $this->number_of_toilets_students_male = $results['result']['number_of_toilets_students_male'] ?? null;
        $this->number_of_toilets_students_female = $results['result']['number_of_toilets_students_female'] ?? null;
        $this->number_of_toilets_students_total = $this->number_of_toilets_students_male + $this->number_of_toilets_students_female;

        $this->number_of_urinals_staff_male = $results['result']['number_of_urinals_staff_male'] ?? null;
        $this->number_of_urinals_staff_female = $results['result']['number_of_urinals_staff_female'] ?? null;
        $this->number_of_urinals_staff_total = $this->number_of_urinals_staff_male + $this->number_of_urinals_staff_female;
        $this->number_of_urinals_students_male = $results['result']['number_of_urinals_students_male'] ?? null;
        $this->number_of_urinals_students_female = $results['result']['number_of_urinals_students_female'] ?? null;
        $this->number_of_urinals_students_total = $this->number_of_urinals_students_male + $this->number_of_urinals_students_female;

    }
    protected $rules = [
        'share_premises' => 'required',
        'share_building'=> 'required',
        'number_of_classrooms'=> 'required'
    ];
    protected $messages = [
        'share_premises.required' => 'select an option to continue',
        'share_building'=> 'select an option to continue',
    ];

    public function sendFacilityDetails(){

        $this->validate();
        if ($this->share_premises === "yes") {
            $this->validate([
                'share_premises_with' => 'required'
            ]);
        }
        if($this->share_building === "yes"){
            $this->validate([
                'share_building_with' => 'required'
            ]);
        }
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/send-school-facility-details';

        $payload = [
            'api_key'=> $api_key,
            'data'=> [
                "user_id" => Session::get('api_response')['user_id'],
                "application_id" => $this->application_id,
                'share_premises'=> $this->share_premises,
                'share_building'=> $this->share_building,
                'share_premises_with'=> $this->share_premises_with??'no',
                'share_building_with'=> $this->share_building_with??'no',
                'number_of_classrooms'=> $this->number_of_classrooms,
                'project_enrollment'=> $this->project_enrollment,
                'number_of_washrooms_staff_male'=> $this->number_of_washrooms_staff_male??0,
                'number_of_washrooms_staff_female'=> $this->number_of_washrooms_staff_female??0,

                'number_of_washrooms_students_male'=> $this->number_of_washrooms_students_male??0,
                'number_of_washrooms_students_female'=> $this->number_of_washrooms_students_female??0,

                'number_of_toilets_staff_male'=> $this->number_of_toilets_staff_male??0,
                'number_of_toilets_staff_female'=> $this->number_of_toilets_staff_female??0,

                'number_of_toilets_students_male'=> $this->number_of_toilets_students_male??0,
                'number_of_toilets_students_female'=> $this->number_of_toilets_students_female??0,

                'number_of_urinals_staff_male'=> $this->number_of_urinals_staff_male??0,
                'number_of_urinals_staff_female'=> $this->number_of_urinals_staff_female??0,

                'number_of_urinals_students_male'=> $this->number_of_urinals_students_male??0,
                'number_of_urinals_students_female'=> $this->number_of_urinals_students_female??0,
                'number_of_washrooms_staff_total'=> $this->number_of_washrooms_staff_total,



            ]
        ];
        // dd($payload);
        try{
            $response = Http::post($base_url.$endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if($results['status'] == 'FAILED'){
                $this->alert('error', $results['statusText']);
            }else{
                $text = $results['statusText'];
                $this->dispatch('notify', icon: 'success', message: $text , color: 'success');
            }
        }catch(\Exception $e){
            $this->alert('error', 'something went wrong! please try again later');
        }
    }
    public function back(){
        $this->dispatch('go-back');
    }

    #[On('redirect')]
    public function updatePostList(){
        return $this->redirectRoute('noticeofintentschoolleadership', ['applicationId' => $this->application_id]);
    }
    public function render(){
        return view('livewire.noticeofintent.schoolfacilities-component');
    }
}
