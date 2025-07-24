<?php

namespace App\Livewire\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SignupComponent extends Component
{
    use LivewireAlert;

    public $title;
    public $titles = [
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
    public $proprietorfirstname;
    public $proprietorlastname;
    public $proprietorothername;
    public $gender;
    public $genders = [
        'male' => 'Male',
        'female' => 'Female',
    ];
    public $email;
    public $password;
    public $repeatpassword;
    public $countryCode ;
    public $phonenumber;

    protected $rules = [
        'title' => 'required|',
        'proprietorfirstname' => 'required|string',
        'proprietorlastname' => 'required',
        'proprietorothername' => 'nullable',
        'gender' => 'required',
        'countryCode' => 'required',
        'phonenumber' => 'required|numeric|digits:10',
        'email' => 'required|email',
        'password' => ['required','string','regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&]/',],
        'repeatpassword' => 'required|same:password',
    ];



    public function signup(){
        // dd($this->phonenumber);
        $this->validate();

        $payload = [
            'api_key' => 'AWERFD12322425642',
            'data' => [
                'person_title' => $this->title,
                'first_name' => $this->proprietorfirstname,
                'last_name' => $this->proprietorlastname,
                'gender' => $this->gender,
                'email' => $this->email,
                'mobile_number_country_number' => $this->countryCode,
                'mobile_number' => $this->phonenumber,
                'password' => $this->password,
                'confirm_password' => $this->repeatpassword,
                'user_id' => '',
            ],
        ];
        try {
            $response = Http::post('http://localhost/nasia/apis/user/signup', $payload);
            $results = $response->json();
            dd($results);
            if($results['status'] == 'FAILED'){
                session()->flash('message', $results['errors'][0]['errMsg']);
                session()->flash('message_type', 'error');
                return redirect()->route('signup');
            }
            session()->flash('message', $results['statusText']);
            session()->flash('message_type', 'success');

        } catch (\Exception $e) {
            $this->alert('error', 'Signup failed. Please try again.');
        }
    }

    public function render(){
        return view('livewire.auth.signup-component');
    }
}
