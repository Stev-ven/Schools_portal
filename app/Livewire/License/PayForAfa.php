<?php

namespace App\Livewire\License;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PayForAfa extends Component{
    use LivewireAlert;
    public $application_id;
    public $api_key, $base_url;
    public $payment_url = '/payments/initiate-application-payment';

    public function mount($application_id){
        $this->api_key = config('nasia.api.key');
        $this->base_url = config('app.url');
        $this->application_id = $application_id;
    }
    public function makePayment(){
        try{
             $payload = [
               "api_key"=> "5e42d5bb22de085cdcf37d86a22104be9af34193499babfc88b5ca7e022770811feb940e0d38b6fd60044a431f92cc87dae3a1c43e3f2aa97b95c910866d45244471ee788b873aa05054945ef19c70683b914c59ce2b2a0aee69e0a09246c26e8fedf0081508f19b62f440605ed85291",
                "request" => "create",
                "mda_branch_code" => "NaSIAHQ",
                "firstname" => session('api_response')['first_name'],
                "lastname" => session('api_response')['last_name'],
                "application_id" => $this->application_id,
                "invoice_items" => [
                    [
                        "service_code" => "P776B3",
                        "amount" => 1,
                        "currency" =>"GHS",
                        "memo" => "Payment for Application for Authorization",
                        "account_number"=> session('api_response')['user_id']
                    ]
                ],
                "redirect_url" => "http://127.0.0.1:8000/applicationforauthorisation/paymentsuccess",
                "post_url" => "https://webhook.site/3114586d-e3bf-4139-ad41-e0b1a0b44a61",
                "phonenumber" => session('api_response')['mobile_number'],
                "email" => session('api_response')['email_address'],
            ];
                $response = Http::post('https://api.ghana.gov.gh/api/v1.0/checkout/invoice.php', $payload);
                // dd($response->json());
            if($response->json()['status'] == 0){
                $results = $response->json();
                $invoice_number = $results['invoice_number'];

                // Retrieve the existing 'api_response' session data
                $apiResponse = Session::get('api_response', []);


                // Add the invoice number to the existing data
                $apiResponse['invoice_number'] = $invoice_number;

                // Store the updated data back in the session
                Session::put('api_response', $apiResponse);
                //save response

                $payload = [
                    "api_key" => $this->api_key,
                    "data" => [
                        "application_id" => $this->application_id,
                        "user_id" => session('api_response')['user_id'],
                        "invoice_number" => $results['invoice_number'],
                        "checkout_url" => $results['checkout_url'],
                        "expected_amount" => $results['invoice_total_amounts'][0]['total_amount'],
                    ]
                ];
                $savedInvoiceResponse = Http::post($this->base_url.$this->payment_url, $payload);
                // dd($savedInvoiceResponse->json());
                if($savedInvoiceResponse->json()['status'] == 'OK'){
                    return redirect($results['checkout_url']);
                }else{
                $this->alert('error', "something went wrong, try again later");
            }
            }else{
                $this->alert('error', "something went wrong, try again later");
            }
        }catch(\Exception $e){
            return redirect('/applicationforauthorisation/payforapplication/'.$this->application_id)->with('error', "something went wrong, try again later");
        }


    }


    #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('expressionofinterestdocument');
    }



    public function render()
    {
        return view('livewire.license.pay-for-afa');
    }

    public function back(){
        $this->dispatch('go-back');
    }
}
