<?php

namespace App\Livewire\Home;

use GuzzleHttp\Client;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;

class RegisteredschoolsComponent extends Component
{
    use WithPagination;

    public $paginationData = [];
    public $page = 1;
    public $responseData;
    public $showModal = false;
    public $specialSchoolId;
    public function mount()
    {
        $this->fetchData($this->page);
    }

    public function fetchData($page){
        $client = new Client([
            'verify' => false,
        ]);
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/authorization/get-list-of-schools';

        $payload = [
            'api_key' => $api_key,
            'data' => [
                'user_id' => session('api_response')['user_id'],
                'view' => 'primary',
                'pending_license_renewal' => 'no',
                'search_query' => '',
                'result_per_page' => 25,
                'page_num' => $page
            ],
        ];
        try{
            $response = $client->request('POST', $base_url.$endpoint, [
                'json' => $payload,
            ]);
            $this->responseData = json_decode($response->getBody()->getContents(), true);
            $data = $this->responseData['result']['data'];
            $total = $this->responseData['result']['total_records'];
            $perPage = $this->responseData['result']['result_per_page'];
            $currentPage = $this->responseData['result']['current_page'];
            $pagination = $this->responseData['result']['pagination'] ?? [];

            $paginatedData = new LengthAwarePaginator(
                $data,
                $total,
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $this->paginationData = $pagination;
            return $paginatedData;

        } catch (\Exception $e) {
            return new LengthAwarePaginator([], 0, 25, $page);
        }
    }
     public function gotoPage($page){
         $this->page = $page;
        $this->fetchData($page);
    }


    public function render()
    {
       $paginatedData = $this->fetchData($this->page);


        return view('livewire.home.registeredschools-component', [
            'paginatedData' => $paginatedData,
            'paginationData' => $this->paginationData
        ]);
    }

    public function registerSchool(){
        return redirect()->route('register-school');
    }

    public function viewSchPendingRenewal(){
        return redirect()->route('view-sch-pending-renewal');
    }

    public function viewSchNotPendingRenewal(){
        return redirect()->route('view-sch-not-pending-renewal');
    }



}
