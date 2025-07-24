<?php

namespace App\Livewire\Home;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\On;

class PasswordChange extends Component
{
    use LivewireAlert;

    public $current_password, $password, $password_confirmation;

    public function render()
    {
        return view('livewire.home.password-change');
    }

    public function changePassword(){
        //https://nasia-uat-api.rxhealthbeta.com/apis/user/change-user-password-manual
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/user/change-user-password-manual';
        $this->validate([
            'current_password' => 'string|required',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                 // will check against password_confirmation
            ],
            'password_confirmation' => 'required|same:password',
         ]);

            $payload = [
                'api_key' => $api_key,
                'data' => [
                    'user_id' => Session::get('api_response')['user_id'],
                    'old_password' => $this->current_password,
                    'password' => $this->password,
                    'password_confirm' => $this->password_confirmation,
                ],
            ];

            try {
                $response = Http::post($base_url.$endpoint, $payload);
                $results = $response->json();
                dd($results);

                if ($results['status'] == 'FAILED') {
                    session()->flash('message', $results['statusText']);
                    session()->flash('message_type', 'error');
                }else{
                    $statusText = $results['statusText'];
                    $this->dispatch('notify', icon: 'error', message: $statusText , color: 'error');
                }
            } catch (\Exception $e) {
                 $this->alert('error', 'Password change failed! Please try again.');
            }


    }

    #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('dashboard');
    }
}
