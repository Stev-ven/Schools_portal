<?php

namespace App\Http\Controllers\Dashboard;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class HomeController extends Controller
{
    use LivewireAlert;

    public function profile()
    {
        return view('dashboard.profile');
    }
    public function changepassword()
    {
        return view('dashboard.changePassword');
    }

    //get list of all applications
    public function viewApplication(Request $request, $special_school_id)
    {

        $client = new Client([
            'verify' => false,
        ]);
        $base_url = config('app.url');
        $endpoint = '/application/get-list-of-all-applications';
        $payload = [
            'api_key' => config('nasia.api.key'),
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'special_school_id' => $special_school_id,
            ]
        ];
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $results = $responseData['result'];
            return view('dashboard.viewApplications', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
    }
    public function eoidocument(Request $request, $application_id)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/expression-of-interest/get-documents';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);
            $responseData = json_decode($response->getBody()->getContents(), true);
            $results = $responseData['result'];
            return view('expressionofinterest.eoidocument', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
    }
    public function afadocument(Request $request, $application_id){
        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/authorization/get-authorization-document';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];
        // dd($payload);
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $results = $responseData;

            // dd($results);
            return view('license.afa_documents', compact('results'));

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }

    }

    //Edit application for authorisation details
    public function applicationforauthorisation(Request $request)
    {
        // $cachekey = $request->query('cachekey');
        // $results = Cache::get($cachekey);
        $results = session()->get('application_for_authorization');
        if (!$results) {
            return redirect()->back();
        }
        return view('license.appforauth', compact('results'));
    }

    //Edit application for authorisation documents


    public function submitAfa(Request $request, $application_id)
    {
        return view('license.submitAfa', compact('application_id'));
    }
    public function submitEoi(Request $request, $application_id)
    {
        return view('expressionofinterest.submitEoi', compact('application_id'));
    }
    public function proprietorInformation(Request $request, $application_id)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $base_url = config('app.url');
        $endpoint = '/application/expression-of-interest/get-expression-of-interest';
        $payload = [
            'api_key' => config('nasia.api.key'),
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $results = [];
            $results['application_id'] = $responseData['result']['application_details']['application_id'];
            $results['proprietors'] = $responseData['result']['data']['proprietors'];
            $results['organization'] = $responseData['result']['data']['organization'];
            // dd($results);
            return view('expressionofinterest.proprietorInfo', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
    }

    public function organisationDetails(Request $request, $application_id)
    {

        return view('expressionofinterest.organisationDetails', compact('application_id'));
    }
    public function individualDetails(Request $request, $application_id)
    {

        return view('expressionofinterest.individualDetails', compact('application_id'));
    }
    public function eoiSchoolDetails(Request $request)
    {
        $cachekey = $request->query('cachekey');
        $results = Cache::get($cachekey);
        // dd($results);
        if (!$results) {
            return redirect()->back();
        }
        return view('expressionofinterest.schooldetails', compact('results'));
    }



    public function noticeOfIntentSchoolDetails(Request $request)
    {
        $results = session('noi_school_details');
        if (!$results) {
             return redirect()->back()->with('error', 'No school data found.');
        }
        return view('noticeofintent.schooldetails', compact('results'));
    }
    public function noticeOfIntentSchoolFacilities(Request $request, $application_id)
    {
        // dd('jj');
        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/get-school-facility-details';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);


            $results = $responseData;
            if ($results['statusText'] == 'No data found.') {
                $results['result']['application_id'] = $application_id;
            }

            // dd($results);
            return view('noticeofintent.schoolfacilities', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
        return view('noticeofintent.schoolfacilities', compact('application_id'));
    }

    public function noticeofintentSchoolFacilityChecklist(Request $request, $application_id)
    {

        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/get-school-facility-check-details';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];
        // dd($payload);
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);


            $responseData = json_decode($response->getBody()->getContents(), true);
            if ($responseData['statusText'] === "No data found.") {
                $results['result']['application_id'] = $application_id;
            } else {
                $results['result'] = $responseData['result'];
            }

            // dd($results);
            return view('noticeofintent.facilitychecklist', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
    }

    public function noticeofintentDocuments(Request $request, $application_id)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/get-documents';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];

        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);
            // dd($response);

            $responseData = json_decode($response->getBody()->getContents(), true);
            // dd($responseData);
            $results = $responseData;
            if ($results['statusText'] == 'No data found.') {
                $results['result']['application_id'] = $application_id;
            } else {
                $results = $responseData;
            }


            // $results = $responseData['result']['data']['documents'];

            return view('noticeofintent.documents', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
        return view('noticeofintent.documents');
    }
    public function noticeofintentSchoolLeadership(Request $request, $application_id)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/get-school-leadership-details';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];
        // dd($payload);
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);


            $results = $responseData;
            $results['application_id'] = $application_id;
            // dd($results);
            return view('noticeofintent.schoolleadership', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
    }

    public function noticeofintentSchoolFees(Request $request, $application_id)
    {
        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/get-school-fee-structure-check-details';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'application_id' => $application_id,
            ]
        ];
        // dd($payload);
        try {
            $response = $client->request('POST', $base_url . $endpoint, [
                'json' => $payload,
            ]);


            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($responseData['statusText'] === "No data found.") {
                $results = [
                    'application_id' => $application_id,
                    'user_id' => session('api_response')['user_id'],
                ];
            } else {
                $results = $responseData['result'] ?? [];
            }

            return view('noticeofintent.schoolfeesstructure', compact('results'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred! Please try again.');
        }
    }

    public function noticeofintentSubmit(Request $request, $application_id)
    {
        return view('noticeofintent.submitnoi', compact('application_id'));
    }

    public function letterofintroductionSchoolDetails(Request $request)
    {

        $cachekey = $request->query('cachekey');
        $results = Cache::get($cachekey);
        if (!$results) {
            return redirect()->back();
        }
        return view('letterofintroduction.schooldetails', compact('results'));
    }
    public function viewSchoolPendingRenewal()
    {
        return view('dashboard.schPendingrenewal');
    }
    public function viewSchoolNotPendingRenewal()
    {
        return view('dashboard.schNotPendingRenewal');
    }


    public function registerSchool()
    {
        return view('license.registerSchool');
    }
    public function licenseSchool()
    {
        return view('license.license');
    }
    public function expressionofinterestdocument()
    {
        return view('expressionofinterest.document');
    }
    public function expressionofinterest()
    {
        return view('expressionofinterest.page1');
    }


    public function NewSchool()
    {
        return view('license.newSchool');
    }
}
