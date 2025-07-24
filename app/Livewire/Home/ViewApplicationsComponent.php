<?php

namespace App\Livewire\Home;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ViewApplicationsComponent extends Component
{
    use LivewireAlert;
    public $results;
    public $api_key;
    public $base_url;
    public $afa_endpoint = '/application/authorization/get-authorization-details';
    public $eoi_endpoint = '/application/expression-of-interest/get-expression-of-interest';
    public $noi_endpoint = '/application/notice-of-intent/get-notice-of-intent';
    public $loi_endpoint = '/application/letter-of-introduction/get-letter-of-introduction';
    public function mount($results){
        $this->results = $results;
        $this->api_key = config('nasia.api.key');
        $this->base_url = config('app.url');
    }

    public function back(){
        return redirect()->route('dashboard');
    }
    public function render(){
        return view('livewire.home.view-applications-component', [
            'results' => $this->results,
        ]);
    }
    public function payForAfa($application_id){
        return view('license.submitAfa', compact('application_id'));
    }
    public function editApplication($resultJson){

        $result = json_decode($resultJson, true);
        //abort process if result is not valid
        // dd($result);
        if (!$result || !isset($result['application_type'])) {
            abort(404, 'Invalid application data');
        }
        //check application type(application_for_authorization)
        if ($result['application_type'] == 'application_for_authorization') {
            //get Application_for_authorization details
            $payload = [
                "api_key" => $this->api_key,
                "data" => [
                    "application_id" => $result['application_id'],
                    "user_id" => Session::get('api_response')['user_id'],
                ]
            ];
            // dd($payload);
            try {
                $response = Http::post($this->base_url.$this->afa_endpoint, $payload);
                $results = $response['result'];

                if($results){
                    session()->put('application_for_authorization', $results);
                    return redirect()->route('applicationforauthorisation');
                    // $cachekey = Str::uuid()->toString();
                    // cache::put($cachekey, $results, now()->addMinutes(10));
                    // return redirect()->route('applicationforauthorisation', ['cachekey' => $cachekey]);
                } else {
                    $statusText = $response['statusText'];
                    session()->flash('message', $statusText);
                }
            } catch (\Exception $e) {
                $this->alert('error', 'An error occurred. Please try again.');
            }

            //check application type(expression_of_interest)
        } elseif ($result['application_type'] == 'expression_of_interest') {
            //get EOI details
            $payload = [
                "api_key" => $this->api_key,
                "data" => [
                    "application_id" => $result['application_id'],
                    "user_id" => Session::get('api_response')['user_id'],
                ]
            ];

            try {
                $response = Http::post($this->base_url.$this->eoi_endpoint, $payload);
                $results = $response['result'];
                if ($results) {
                    $cachekey = Str::uuid()->toString();
                    cache::put($cachekey, $results, now()->addMinutes(10));
                    return redirect()->route('schoolDetails', ['cachekey' => $cachekey]);
                } else {
                    $statusText = $response['statusText'];
                    $this->dispatch('notify', icon: 'error', message: $statusText, color: 'error');
                }
            } catch (\Exception $e) {
                $this->alert('error', 'An error occurred. Please try again.');
            }
        } elseif ($result['application_type'] == 'notice_of_intent') {
            $payload = [
                "api_key" => $this->api_key,
                "data" => [
                    "application_id" => $result['application_id'],
                    "user_id" => Session::get('api_response')['user_id'],
                ]
            ];
            // dd($payload);
            try {
                $response = Http::post('http://localhost/nasia/apis/application/notice-of-intent/get-school-details', $payload);

                $results = $response->json();
                // dd($results);
                if($results['statusText'] == 'No data found.'){
                    $results['result']['application_id'] = $result['application_id'];
                    $results['result']['type_of_school'] = $result['type_of_school'];
                }


                //save result data in cache
                if ($results) {
                    session(['noi_school_details' => $results]);
                    return redirect()->route('noticeofintentschooldetails');
                } else {
                    $statusText = $response['statusText'];
                    return redirect()->back()->with('error', $statusText);
                }
            } catch (\Exception $e) {
                $this->alert('error', 'An error occured. Please try again!');
            }
        }elseif($result['application_type'] == 'letter_of_introduction'){
            $payload = [
                "api_key" => $this->api_key,
                "data" => [
                    "application_id" => $result['application_id'],
                    "user_id" => Session::get('api_response')['user_id'],
                ]
            ];
            // dd($payload);
            try {
                $response = Http::post($this->base_url.$this->loi_endpoint, $payload);

                $results = $response->json();
                // dd($results);
                if($results['statusText'] == 'No data found.'){
                    $results['result']['application_id'] = $result['application_id'];
                    // $results['result']['type_of_school'] = $result['type_of_school'];
                }
                if ($results) {
                    $cachekey = Str::uuid()->toString();
                    cache::put($cachekey, $results, now()->addMinutes(30));
                    return redirect()->route('loischooldetails', ['cachekey' => $cachekey]);
                } else {
                    $statusText = $response['statusText'];
                    return redirect()->back()->with('error', $statusText);
                }

            }catch (\Exception $e) {
                $this->alert('error', 'An error occured. Please try again!');
            }
        }
    }
}
