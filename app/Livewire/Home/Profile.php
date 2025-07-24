<?php

namespace App\Livewire\Home;

use Illuminate\Support\Facades\Session;
use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Profile extends Component
{
    use WithFileUploads , LivewireAlert;

    // public $image;
    public $person_title;
    public $person_titles = [
        'dr.' => 'Dr.',
        'prof' => 'Prof.',
        'eng' => 'Eng.',
        'mr' => 'Mr.',
        'mrs' => 'Mrs.',
        'ms' => 'Ms.',
        'miss' => 'Miss',
        'lawyer' => 'Lawyer',
        'other' => 'other',
    ];
    public $first_name;
    public $last_name;
    public $other_names;
    public $email;
    public $mobile_number_country_number;
    public $mobile_number;
    public $person_gender;
    public $person_genders = [
        'male' => 'Male',
        'female' => 'Female',
    ];
    public function mount(){
        // dd(Session::get('api_response'));
        $title_from_session = Session::get('api_response')['person_title'] ?? null;
        if($title_from_session){
            $this->person_title = strtolower($title_from_session);
        }
        $this->first_name = Session::get('api_response')['first_name'] ?? null;
        $this->last_name = Session::get('api_response')['last_name'];
        $this->other_names = Session::get('api_response')['other_names'] ?? null;
        $this->person_gender = Session::get('api_response')['gender'] ?? null;
        $this->email = Session::get('api_response')['email_address'] ?? null;
        $this->mobile_number = Session::get('api_response')['mobile_number'] ?? null;
        $this->mobile_number_country_number = Session::get('api_response')['mobile_number_country_number'] ?? null;

    }


    public function updateProfile()
    {
        //Validate request
        $this->validate([
            // 'image' => "mimes:jpeg,png,jpg,gif|max:2048",
            'email' => "required|email",
            'mobile_number_country_number' => "required",
            'person_title' => "required",
            'first_name' => "string|required",
            'last_name' => "string|required",
            'other_names' => "required",
            'person_gender' => "required",
            'mobile_number' => "required|digits:10",
        ]);

        $payload = [
            'api_key' => "AWERFD12322425642",
            'data' => [
                "user_id" => Session::get('api_response')['user_id'],
                "email" => $this->email,
                "mobile_number_country_number" => $this->mobile_number_country_number,
                "mobile_number" => $this->mobile_number,
                "person_title" => $this->person_title,
                "first_name" => $this->first_name,
                "last_name" => $this->last_name,
                "other_names" => $this->other_names,
                "person_gender" => $this->person_gender,
            ],
        ];
        // dd($payload);
        try {
            $response = Http::post('https://nasia-uat-api.rxhealthbeta.com/apis/user/update-profile', $payload);
            dd($response->json());
            if ($response->successful()) {
                return redirect()->route('dashboard')->with('success', 'Profile updated.');
            } else {
                $this->alert('error', 'Profile updates fail ' . $response->json('message', 'Please try again.'));
            }
        } catch (\Exception $e) {
            $this->alert('error', 'Profile update failed. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.home.profile');
    }
}
