<?php

namespace App\Livewire\Expressionofinterest;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SubmitEoi extends Component
{
    use LivewireAlert;
    public $application_id;
    public function mount($application_id)
    {
        $this->application_id = $application_id;
    }

    public function submitEoi(){
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/applications/submit-application';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'application_id' => $this->application_id,
                'user_id' => Session::get('api_response')['user_id'],
            ]
            ];
            // dd($payload);
        try{
            $response = Http::post($base_url.$endpoint, $payload);
            // dd($response->json());
            $results = $response->json();
            // dd($results);
            if($results['status'] == "FAILED"){
                $this->alert('error', $results['statusText']);
            }else{
                $this->alert('success', $results['statusText']);
            }


        }catch(\Exception $e){
            $this->alert('error', 'Something went wrong. Please try again');
        }
    }
    public function back(){
        return redirect()->route('eoiDocument', ['applicationId'=> $this->application_id]);
    }
    public function render()
    {
        return view('livewire.expressionofinterest.submit-eoi');


}

}
