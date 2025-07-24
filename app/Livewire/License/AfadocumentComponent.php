<?php

namespace App\Livewire\License;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AfadocumentComponent extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public $certificate_of_incorporation;
    public $existing_certificate;
    public $business_certificate;
    public $agreement;
    public $results;
    public $application_id;
    public $endpoint = '/application/apply-for-authorization/upload-certificate-of-incorporation';
    public $certificatePreviewUrl;

    public function mount($results)
    {
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $results['result']['application_id'];
        $this->existing_certificate = $results['result']['certificate_of_incorporation_document'] ?? null;
        if (!empty($this->existing_certificate)) {
            $this->certificatePreviewUrl = $this->existing_certificate;
        }
    }
    protected $rules = [
        'certificate_of_incorporation' => 'required|file|max:5120|mimes:pdf,jpg,jpeg,png',
        'business_certificate' => 'nullable|file|max:5120|mimes:pdf,jpg,jpeg,png',
        'agreement' => 'required',
    ];
    protected function messages()
    {
        return [
            'business_certificate.required' => 'Please upload certificate to continue.',
            'certificate_of_incorporation.required' => 'Please upload certificate to continue.',
            'agreement.required' => 'You must agree to the terms and conditions to continue.',
            'at_least_one_file' => 'You must upload at least one document.',
        ];
    }
    public function updatedCertificateOfIncorporation($value){
        if ($value) {
            $fileName = $this->certificate_of_incorporation
                ? $this->certificate_of_incorporation->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }
    public function save()
    {
        $this->validate();
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/apply-for-authorization/upload-certificate-of-incorporation';
        try {


            // Prepare the file details
            $filePath = $this->certificate_of_incorporation->getRealPath();
            $fileName = $this->certificate_of_incorporation->getClientOriginalName();
            //Prepare the payload and send the file using multipart/form-data
            $response = Http::attach(
                'file',
                file_get_contents($filePath),
                $fileName,
                [
                    'Content-Type' => $this->certificate_of_incorporation->getMimeType()
                ]
            )->post($base_url . $endpoint, [
                'api_key' => $api_key,
                'data' => json_encode([
                    'application_id' => $this->application_id,
                    'user_id' => Session::get('api_response')['user_id'],
                ]),
            ]);

            $results = $response->json();
            if ($results['status'] == "FAILED") {
                $this->alert('error', $results['statusText']);
            } else {
                $text = $results['statusText'];
                $this->dispatch('notify', icon: 'success', message: $text, color: 'success');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }

    public function nextSection()
    {
        if (empty($this->certificate_of_incorporation) && empty($this->existing_certificate)) {
            $this->alert('error', 'Please upload certificate of incorporation to continue.');
        }
        return $this->redirectRoute('submitAfa', ['applicationId' => $this->application_id]);
    }

    public function render()
    {
        return view('livewire.license.afadocument-component');
    }
    #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('submitAfa', ['applicationId' => $this->application_id]);
    }

    public function back()
    {
        return $this->redirectRoute('applicationforauthorisation');
    }
}
