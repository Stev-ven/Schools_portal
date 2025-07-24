<?php

namespace App\Livewire\Loi;

use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PayForLoi extends Component
{
    use LivewireAlert;
    public $application_id;
    public function mount($application_id){
        $this->application_id = $application_id;
    }

    public function makePayment(){

        $payload = [
               "api_key"=> "5e42d5bb22de085cdcf37d86a22104be9af34193499babfc88b5ca7e022770811feb940e0d38b6fd60044a431f92cc87dae3a1c43e3f2aa97b95c910866d45244471ee788b873aa05054945ef19c70683b914c59ce2b2a0aee69e0a09246c26e8fedf0081508f19b62f440605ed85291",
                "request" => "create",
                "mda_branch_code" => "NaSIAHQ",
                "firstname" => session('api_response')['first_name'],
                "lastname" => session('api_response')['last_name'],
                "application_id" => $this->application_id,
                "invoice_items" => [
                    [
                        "service_code" => "N825R1",
                        "amount" => 1,
                        "currency" =>"GHS",
                        "memo" => "Payment for Letter of Introduction",
                        "account_number"=> session('api_response')['user_id']
                    ]
                ],
                "redirect_url" => "http://127.0.0.1:8000/noticeofintent/paymentsuccess",
                "post_url" => "https://webhook.site/2f62fa7f-4e68-46c6-83be-d73fad6b8920",
                "phonenumber" => session('api_response')['mobile_number'],
                "email" => session('api_response')['email_address'],
            ];


        $response = Http::post('https://api.ghana.gov.gh/api/v1.0/checkout/invoice.php', $payload);
        // dd($response->json());
        $results = $response->json();

        if($results['status'] == 0){
            $invoice_number = $results['invoice_number'];


        // Retrieve the existing 'api_response' session data
        $apiResponse = Session::get('api_response', []);


        // Add the invoice number to the existing data
        $apiResponse['invoice_number'] = $invoice_number;

        // Store the updated data back in the session
        Session::put('api_response', $apiResponse);
        //save response
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/payments/initiate-application-payment';
        $payload = [
            "api_key"=> $api_key,
            "data" => [
                "application_id" => $this->application_id,
                "user_id" => session('api_response')['user_id'],
                "invoice_number" => $results['invoice_number'],
                "checkout_url" => $results['checkout_url'],
                "expected_amount" => $results['invoice_total_amounts'][0]['total_amount'],
                // "total_enrollments" => '10',
                // "enrollments_range" => '10-14',
            ]
        ];
        $savedInvoiceResponse = Http::post($base_url.$endpoint, $payload);
        // dd($savedInvoiceResponse->json());

        if($savedInvoiceResponse->json()['status'] == 'OK'){
            return redirect($results['checkout_url']);
            }else{
            $this->alert('error', 'something went wrong, try again');
        }
        }else{
            $this->alert('error', 'something went wrong, try again');
        }

    }
    public function render()
    {
        return view('livewire.loi.pay-for-loi');
    }
}
