<?php

namespace App\Livewire\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Jantinnerezo\LivewireAlert\LivewireAlert;


class ForgotpasswordComponent extends Component
{
    use LivewireAlert;

    public $email;
    public $token;
    public $password;
    public $password_confirm;

    protected $rules = [
        'email' => 'required',
        //'token' => 'required',
        //'password' => 'required',
        //'password_confirm' =>'required',
    ];

    public function resetpassword()
    {
        // Validate the email input
        $this->validate();

        $payload = [
            'api_key' => "AWERFD12322425642",
            'data' =>[
                'email'=> $this->email
            ]
        ];

        try{
            $response = Http::POST('https://nasia-uat-api.rxhealthbeta.com/apis/user/send-user-password-change-token', $payload);
            $results = $response->json();
           if($results['status']==  'OK'){
            $this->dispatch('notify', icon: 'info', message: $results['statusText'] , color: 'info');

            }else{
                session()->flash('message', $results['statusText']);
                session()->flash('message_type', 'error');
           }
        } catch(\Exception $e){
            $this->alert('error', 'Password reset failed!, please try again');
        }


    }
    /*public function updatePassword(){
        $this->validate();

        $payload = [
            'api_key' => "AWERFD12322425642",
            'data' => [
                'token' => $this->token,
                'password' => $this->password,
                'password_confirm' => $this->password_confirm,
            ],
        ];

        $response = Http::POST('https://nasia-uat-api.rxhealthbeta.com/apis/user/change-user-password', $payload);
        if($response->successful()){
            $this->alert('success', 'password changed successfully');
            $this->redirectRoute('login');
        }else{
            $this->alert('error', 'password change failed: ' . $response->json('message', 'Please try again.'));
        }
    }*/

    public function render()
    {
        return view('livewire.auth.forgotpassword-component');
    }
}


