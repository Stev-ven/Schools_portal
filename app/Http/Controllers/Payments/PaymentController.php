<?php

namespace App\Http\Controllers\Payments;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class PaymentController extends Controller
{

    public function payForAfa(Request $request, $application_id){
        return view('license.payForAfa', compact('application_id'));
    }
    public function afapaymentsuccess(){
        return view('license.afapaymentsuccess');
    }

    public function payForEoi(Request $request, $application_id){
        return view('expressionofinterest.payForEoi', compact('application_id'));
    }
    public function eoipaymentsuccess(){
        return view('expressionofinterest.eoiPaymentSuccess');
    }
    public function payForNoi(Request $request, $application_id){
        return view('noticeofintent.payForNoi', compact('application_id'));
    }

    public function noipaymentsuccess(){
        return view('noticeofintent.noiPaymentSuccess');
    }

    public function payForLoi(Request $request, $application_id){
        return view('letterofintroduction.payForLoi', compact('application_id'));
    }

    public function loipaymentsuccess(){
        return view('letterofintroduction.loiPaymentSuccess');
    }

    public function allPayments(){
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/areas/get-list-of-areas';

        $paylaod = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => Session::get('api_response')['user_id'],
            ]
            ];
        try{
            $response = Http::post($base_url.$endpoint, $paylaod);
            $results = $response->json();
            // dd($results);44
        }catch(\Exception $e){
            dd('hhi');
        }

        return view('payments.allpayments', compact('results'));
    }

    public function arrears(){
        return view('payments.arrears');
    }

}
