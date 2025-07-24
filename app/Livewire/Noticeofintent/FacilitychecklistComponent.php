<?php

namespace App\Livewire\Noticeofintent;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class FacilitychecklistComponent extends Component
{ use LivewireAlert;

    public $results, $application_id, $administration_block,$administration_block_comment, $assembly_hall, $assembly_hall_comment,
           $staff_common_room, $staff_common_room_comment, $school_library, $school_library_comment,
           $science_laboratory, $science_laboratory_comment, $ict_laboratory, $ict_laboratory_comment,
           $workshop, $workshop_comment, $dining, $dining_comment, $music_room, $music_room_comment,
           $guidance_counselling_office, $guidance_counselling_office_comment, $infirmary, $infirmary_comment,
           $recreational_facilities, $recreational_facilities_comment, $boundaries, $boundaries_comment,
           $security_post, $security_post_comment, $school_dormitories, $school_dormitories_comment,
           $staff_residence, $staff_residence_comment, $school_transportation, $school_transportation_comment,
           $other_specified_value, $other, $other_comment;
    protected $rules = [
        'administration_block' => 'required', 'assembly_hall' => 'required', 'staff_common_room' => 'required',
        'school_library' => 'required', 'science_laboratory' => 'required', 'ict_laboratory' => 'required',
        'workshop' => 'required', 'dining' => 'required', 'music_room' => 'required', 'guidance_counselling_office' => 'required',
        'infirmary' => 'required', 'recreational_facilities' => 'required', 'boundaries' => 'required', 'security_post' => 'required',
        'school_dormitories' => 'required', 'staff_residence' => 'required', 'school_transportation' => 'required',
    ];
    protected $messages = [
        'required' => 'All fields are required',
    ];
    public function mount($results){
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $results['result']['application_id'];
        $this->administration_block = $results['result']['administration_block'] ?? null;
        $this->administration_block_comment = $results['result']['administration_block_comment'] ?? null;
        $this->assembly_hall = $results['result']['assembly_hall'] ?? null;
        $this->assembly_hall_comment = $results['result']['assembly_hall_comment'] ?? null;
        $this->staff_common_room = $results['result']['staff_common_room'] ?? null;
        $this->staff_common_room_comment = $results['result']['staff_common_room_comment'] ?? null;
        $this->school_library = $results['result']['school_library'] ?? null;
        $this->school_library_comment = $results['result']['school_library_comment'] ?? null;
        $this->science_laboratory = $results['result']['science_laboratory'] ?? null;
        $this->science_laboratory_comment = $results['result']['science_laboratory_comment'] ?? null;
        $this->ict_laboratory = $results['result']['ict_laboratory'] ?? null;
        $this->ict_laboratory_comment = $results['result']['ict_laboratory_comment'] ?? null;
        $this->workshop = $results['result']['workshop'] ?? null;
        $this->workshop_comment = $results['result']['workshop_comment'] ?? null;
        $this->dining = $results['result']['dining'] ?? null;
        $this->dining_comment = $results['result']['dining_comment'] ?? null;
        $this->music_room = $results['result']['music_room'] ?? null;
        $this->music_room_comment = $results['result']['music_room_comment'] ?? null;
        $this->guidance_counselling_office = $results['result']['guidance_counselling_office'] ?? null;
        $this->guidance_counselling_office_comment = $results['result']['guidance_counselling_office_comment'] ?? null;
        $this->infirmary = $results['result']['infirmary'] ?? null;
        $this->infirmary_comment = $results['result']['infirmary_comment'] ?? null;
        $this->recreational_facilities = $results['result']['recreational_facilities'] ?? null;
        $this->recreational_facilities_comment = $results['result']['recreational_facilities_comment'] ?? null;
        $this->boundaries = $results['result']['boundaries'] ?? null;
        $this->boundaries_comment = $results['result']['boundaries_comment'] ?? null;
        $this->security_post = $results['result']['security_post'] ?? null;
        $this->security_post_comment = $results['result']['security_post_comment'] ?? null;
        $this->school_dormitories = $results['result']['school_dormitories'] ?? null;
        $this->school_dormitories_comment = $results['result']['school_dormitories_comment'] ?? null;
        $this->staff_residence = $results['result']['staff_residence'] ?? null;
        $this->staff_residence_comment = $results['result']['staff_residence_comment'] ?? null;
        $this->school_transportation = $results['result']['school_transportation'] ?? null;
        $this->school_transportation_comment = $results['result']['school_transportation_comment'] ?? null;
        $this->other_specified_value = $results['result']['other_specified_value'] ?? null;
        $this->other = $results['result']['other'] ?? null;
        $this->other_comment = $results['result']['other_comment'] ?? null;

    }
    public function sendFacilityChecklist(){
        // $this->validate();
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/send-school-facility-check-details';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                "user_id" => Session::get('api_response')['user_id'],
                "application_id" => $this->application_id,
                "administration_block" => $this->administration_block == "yes" ? "yes" : "no",
                "administration_block_comment" => $this->administration_block_comment,
                "assembly_hall" => $this->assembly_hall,
                "assembly_hall_comment" => $this->assembly_hall_comment,
                "staff_common_room" => $this->staff_common_room,
                "staff_common_room_comment" => $this->staff_common_room_comment,
                "school_library" => $this->school_library,
                "school_library_comment" => $this->school_library_comment,
                "science_laboratory" => $this->science_laboratory,
                "science_laboratory_comment" => $this->science_laboratory_comment,
                "ict_laboratory" => $this->ict_laboratory,
                "ict_laboratory_comment" => $this->ict_laboratory_comment,
                "workshop" => $this->workshop,
                "workshop_comment" => $this->workshop_comment,
                "dining" => $this->dining,
                "dining_comment" => $this->dining_comment,
                "music_room" => $this->music_room,
                "music_room_comment" => $this->music_room_comment,
                "guidance_counselling_office" => $this->guidance_counselling_office,
                "guidance_counselling_office_comment" => $this->guidance_counselling_office_comment,
                "infirmary" => $this->infirmary,
                "infirmary_comment" => $this->infirmary_comment,
                "recreational_facilities" => $this->recreational_facilities,
                "recreational_facilities_comment" => $this->recreational_facilities_comment,
                "boundaries" => $this->boundaries,
                "boundaries_comment" => $this->boundaries_comment,
                "security_post" => $this->security_post,
                "security_post_comment" => $this->security_post_comment,
                "school_dormitories" => $this->school_dormitories,
                "school_dormitories_comment" => $this->school_dormitories_comment,
                "staff_residence" => $this->staff_residence,
                "staff_residence_comment" => $this->staff_residence_comment,
                "school_transportation" => $this->school_transportation,
                "school_transportation_comment" => $this->school_transportation_comment,
                "other_specified_value" => $this->other_specified_value,
                "other" => $this->other,
                "other_comment" => $this->other_comment,
            ]
            ];
            // dd($payload);
            try{
                $response = Http::post($base_url.$endpoint, $payload);
                $results = $response->json();
                // dd($results);
                if($results['status'] == 'FAILED'){

                    $this->alert('error', $results['errors'][0]['errMsg']);
                }else{
                    $this->dispatch('notify', icon: 'success', message: $results['statusText'] , color: 'success');
                }
            }catch(\Exception $e){
                $this->alert('error', 'Something went wrong. Please try again.');
            }
    }

    #[On('redirect')]
    public function updatePostList(){
        return $this->redirectRoute('noticeofintentdocuments', ['applicationId' => $this->application_id]);
    }
    public function render()
    {
        return view('livewire.noticeofintent.facilitychecklist-component');
    }
}
