<?php

namespace App\Livewire\Noticeofintent;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SchoolfeesComponent extends Component
{   use LivewireAlert;
    public $results, $application_id;
    public $tuition_fee_kindergarten_first_term, $tuition_fee_kindergarten_second_term, $tuition_fee_kindergarten_third_term;
    public $tuition_fee_primary_first_term, $tuition_fee_primary_second_term, $tuition_fee_primary_third_term;


    public $tuition_fee_junior_high_first_term, $tuition_fee_junior_high_second_term, $tuition_fee_junior_high_third_term;

    public $tuition_fee_senior_high_first_term, $tuition_fee_senior_high_second_term, $tuition_fee_senior_high_third_term;
    public $additional_fees_admission_fee_amount, $additional_fees_boarding_fees_amount, $additional_fees_boarding_fees_remarks, $additional_fees_admission_fee_remarks, $additional_fees_maintenance_fee_amount, $additional_fees_maintenance_fee_remarks, $additional_fees_boarding_fee_amount, $additional_fees_boarding_fee_remarks, $additional_fees_extracurricular_amount, $additional_fees_extracurricular_remarks,
           $additional_fees_other_facility_charges_amount, $additional_fees_other_facility_charges_remarks, $launch_charges, $launch_charges_remarks, $course_books_charges, $course_books_charges_remarks, $other_facility_charges, $other_facility_charges_remarks, $others_charges_amount, $others_charges_remarks,
           $additional_fees_others_amount, $additional_fees_others_specified_value, $additional_fees_others_remarks;

    public function mount($results){
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $results['application_id'];
        $this->tuition_fee_junior_high_first_term = $results['tuition_fee_junior_high_first_term'] ?? null;
        $this->tuition_fee_junior_high_second_term = $results['tuition_fee_junior_high_second_term'] ?? null;
        $this->tuition_fee_junior_high_third_term = $results['tuition_fee_junior_high_third_term'] ?? null;

        $this->tuition_fee_kindergarten_first_term = $results['tuition_fee_kindergarten_first_term'] ?? null;
        $this->tuition_fee_kindergarten_second_term = $results['tuition_fee_kindergarten_second_term'] ?? null;
        $this->tuition_fee_kindergarten_third_term = $results['tuition_fee_kindergarten_third_term'] ?? null;

        $this->tuition_fee_primary_first_term = $results['tuition_fee_primary_first_term'] ?? null;
        $this->tuition_fee_primary_second_term = $results['tuition_fee_primary_second_term'] ?? null;
        $this->tuition_fee_primary_third_term = $results['tuition_fee_primary_third_term'] ?? null;

        $this->tuition_fee_senior_high_first_term = $results['tuition_fee_senior_high_first_term'] ?? null;
        $this->tuition_fee_senior_high_second_term = $results['tuition_fee_senior_high_second_term'] ?? null;
        $this->tuition_fee_senior_high_third_term = $results['tuition_fee_senior_high_third_term'] ?? null;

        $this->additional_fees_others_remarks = $results['additional_fees_others_remarks'] ?? null;
        $this->additional_fees_others_specified_value = $results['additional_fees_others_specified_value'] ?? null;
        $this->additional_fees_admission_fee_amount = $results['additional_fees_admission_fee_amount'] ?? null;
        $this->additional_fees_admission_fee_remarks = $results['additional_fees_admission_fee_remarks'] ?? null;
        $this->additional_fees_maintenance_fee_remarks = $results['additional_fees_maintenance_fee_remarks'] ?? null;
        $this->additional_fees_maintenance_fee_amount = $results['additional_fees_maintenance_fee_amount'] ?? null;
        $this->additional_fees_boarding_fees_remarks = $results['additional_fees_boarding_fees_remarks'] ?? null;
        $this->additional_fees_boarding_fees_amount = $results['additional_fees_boarding_fees_amount'] ?? null;
        $this->additional_fees_extracurricular_remarks = $results['additional_fees_extracurricular_remarks'] ?? null;
        $this->additional_fees_extracurricular_amount = $results['additional_fees_extracurricular_amount'] ?? null;
        $this->launch_charges = $results['launch_charges'] ?? null;
        $this->launch_charges_remarks = $results['launch_charges_remarks'] ?? null;
        $this->course_books_charges = $results['course_books_charges'] ?? null;
        $this->course_books_charges_remarks = $results['course_books_charges_remarks'] ?? null;
        $this->other_facility_charges = $results['other_facility_charges'] ?? null;
        $this->other_facility_charges_remarks = $results['other_facility_charges_remarks'] ?? null;
        $this->additional_fees_other_facility_charges_amount = $results['additional_fees_other_facility_charges_amount'] ?? null;
        $this->additional_fees_others_amount = $results['additional_fees_others_amount'] ?? null;
    }

    protected $rules = [
        'tuition_fee_kindergarten_first_term' => 'required',
        'tuition_fee_kindergarten_second_term' => 'required',
        'tuition_fee_kindergarten_third_term' => 'required',

        'tuition_fee_primary_first_term' => 'required',
        'tuition_fee_primary_second_term' => 'required',
        'tuition_fee_primary_third_term' => 'required',

        'tuition_fee_junior_high_first_term' => 'required',
        'tuition_fee_junior_high_second_term' => 'required',
        'tuition_fee_junior_high_third_term' => 'required',
    ];
    public function sendFeesDetails(){

        // $this->validate();
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/send-school-fee-structure-check-details';

        $payload = [
            'api_key' => $api_key,
            'data' => [
                "user_id" => Session::get('api_response')['user_id'],
                "application_id" => $this->results['application_id'],
                "tuition_fee_kindergarten_first_term" => $this->tuition_fee_kindergarten_first_term??'0',
                "tuition_fee_kindergarten_second_term" => $this->tuition_fee_kindergarten_second_term??'0',
                "tuition_fee_kindergarten_third_term" => $this->tuition_fee_kindergarten_third_term??'0',

                "tuition_fee_primary_first_term" => $this->tuition_fee_primary_first_term??'0',
                "tuition_fee_primary_second_term" => $this->tuition_fee_primary_second_term??'0',
                "tuition_fee_primary_third_term" => $this->tuition_fee_primary_third_term??'0',

                "tuition_fee_junior_high_first_term" => $this->tuition_fee_junior_high_first_term??'0',
                "tuition_fee_junior_high_second_term" => $this->tuition_fee_junior_high_second_term??'0',
                "tuition_fee_junior_high_third_term" => $this->tuition_fee_junior_high_third_term??'0',

                "tuition_fee_senior_high_first_term" => $this->tuition_fee_senior_high_first_term??'0',
                "tuition_fee_senior_high_second_term" => $this->tuition_fee_senior_high_second_term??'0',
                "tuition_fee_senior_high_third_term" => $this->tuition_fee_senior_high_third_term??'0',

                "additional_fees_others_remarks" => $this->additional_fees_others_remarks,
                "additional_fees_admission_fee_amount" => $this->additional_fees_admission_fee_amount??'0',
                "additional_fees_admission_fee_remarks" => $this->additional_fees_admission_fee_remarks,
                "additional_fees_maintenance_fee_remarks" => $this->additional_fees_maintenance_fee_remarks,
                "additional_fees_maintenance_fee_amount" => $this->additional_fees_maintenance_fee_amount??'0',
                "additional_fees_boarding_fees_remarks" => $this->additional_fees_boarding_fees_remarks,
                "additional_fees_boarding_fees_amount" => $this->additional_fees_boarding_fees_amount??'0',
                "additional_fees_extracurricular_remarks" => $this->additional_fees_extracurricular_remarks,
                "additional_fees_extracurricular_amount" => $this->additional_fees_extracurricular_amount??'0',
                "additional_fees_others_amount" => $this->additional_fees_others_amount??'0',
                "launch_charges" => $this->launch_charges??'0',
                "launch_charges_remarks" => $this->launch_charges_remarks,
                "course_books_charges" => $this->course_books_charges??'0',
                "course_books_charges_remarks" => $this->course_books_charges_remarks,
                "other_facility_charges" => $this->other_facility_charges??'0',
                "other_facility_charges_remarks" => $this->other_facility_charges_remarks,
                "additional_fees_others_specified_value"=> $this->additional_fees_others_specified_value,
            ]
            ];
            try{
                $response = Http::post($base_url.$endpoint, $payload);
                $results = $response->json();

                if($results['status'] == 'FAILED'){
                    $text = $results['statusText'];
                    $this->alert('error', $text);
                }else{
                    $text = $results['statusText'];
                    $this->dispatch('notify', icon: 'success', message: $text , color: 'success');

                }
            }catch(\Exception $e){
                $this->alert('error', 'An error occurred. Please try again.');

            }
    }
    public function back(){
        $this->dispatch('go-back');
    }
    #[On('redirect')]
    public function updatePostList(){
        return $this->redirectRoute('noticeofintentschoolfacilitychecklist', ['applicationId' => $this->results['application_id']]);
    }
    public function render()
    {
        return view('livewire.noticeofintent.schoolfees-component');
    }
}
