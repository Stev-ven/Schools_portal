<?php

namespace App\Livewire\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class LoginComponent extends Component
{
    use LivewireAlert;
    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required'
    ];
    public function login(){
        $this->validate();
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/user/login';

        $payload = [
            'api_key' => $api_key,
            'data' => [
                'email' => $this->email,
                'password' => $this->password
            ]
        ];



        try{
            $response = Http::post($base_url.$endpoint, $payload);
            $apiResponse = $response->json();
            // dd($apiResponse);

            if($apiResponse['status'] == 'OK'){
                Session::put('api_response', $apiResponse['result']);
                $this->flash('success', $apiResponse['statusText'] . ' Welcome back ' . $apiResponse['result']['first_name']);
                return $this->redirectRoute('dashboard');
            }
            $this->alert('error', $apiResponse['statusText']?? 'Something went wrong. Please try again');
            return;
        }catch(\Exception $e) {
            $this->alert('error', 'Something went wrong. Please try again');
        }
    }

    public function render(){
        return view('livewire.auth.login-component');
    }
}
