<?php

namespace App\Livewire\Expressionofinterest;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;



class Document extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public  $application_id,

    // land owned by proprietor === yes
    $police_clearance_report,
    $business_operating_permit,
    $tenancy_agreement,
    $approved_building_plan,
    $national_id_card,
    $local_authority_certificate_document,
    $land_documents_with_prop_name_document;
    public $existing_business_operating_permit,
    $existing_tenancy_agreement,
    $existing_police_clearance_report,
    $existing_approved_building_plan,
    $existing_national_id_card,
    $existing_local_authority_certificate_document,
    $existing_land_documents_with_prop_name_document;
    public $commence_date, $commence_date_no, $agreement_no, $land_owned_by_proprietor;
    public $agreement;
    public $declaration = false;
    public $certificatePreviewUrl, $policeClearanceReportPreviewUrl, $nationalIdPreviewUrl, $businessPermitPreviewUrl, $tenancyAgreementPreviewUrl, $approvedBuildingPlanPreviewUrl, $localAuthorityCertificatePreviewUrl,
        $landDocumentsWithProprietorNamePreviewUrl;


    //land owned by proprietor == no;

    public $business_operating_permit_no,
            $tenancy_agreement_no,
            $national_id_card_no,
            $police_clearance_report_no;
    public $existing_business_operating_permit_no,
            $existing_tenancy_agreement_no,
            $existing_national_id_card_no,
            $existing_police_clearance_report_no;



    public function updatedDeclarationChecked($value)
    {
        $this->declaration = $value ? 'yes' : 'no';
    }
    public $declarationChecked = false;
    public $fileName;
    public $results, $api_key, $base_url, $endpoint, $payload;
    public function mount($results)
    {
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $results['application_id'];
        $this->existing_police_clearance_report = $results['police_clearance_report'];
        $this->existing_business_operating_permit = $results['business_operating_permit'];
        $this->existing_tenancy_agreement = $results['tenancy_agreement'];
        $this->existing_approved_building_plan = $results['approved_building_plan'];
        $this->existing_national_id_card = $results['national_id_card'];
        $this->existing_local_authority_certificate_document = $results['local_authority_certificate_document'];
        $this->existing_land_documents_with_prop_name_document = $results['land_documents_with_prop_name_document'];

        $this->existing_business_operating_permit_no = $results['business_operating_permit'];
        $this->existing_tenancy_agreement_no = $results['tenancy_agreement'];
        $this->existing_national_id_card_no = $results['national_id_card'];
        $this->existing_police_clearance_report_no = $results['police_clearance_report'];

        if (!empty($this->existing_police_clearance_report)) {
            $this->policeClearanceReportPreviewUrl = $this->existing_police_clearance_report;
        }
        if(!empty($this->existing_approved_building_plan)){
            $this->approvedBuildingPlanPreviewUrl = $this->existing_approved_building_plan;
        }
        if(!empty($this->existing_land_documents_with_prop_name_document)){
            $this->landDocumentsWithProprietorNamePreviewUrl = $this->existing_land_documents_with_prop_name_document;
        }
        if(!empty($this->existing_national_id_card)){
            $this->nationalIdPreviewUrl = $this->existing_national_id_card;
        }
        if(!empty($this->existing_business_operating_permit)){
            $this->businessPermitPreviewUrl = $this->existing_business_operating_permit;
        }
        if(!empty($this->existing_tenancy_agreement)){
            $this->tenancyAgreementPreviewUrl = $this->existing_tenancy_agreement;
        }
        if(!empty($this->existing_local_authority_certificate_document)){
            $this->localAuthorityCertificatePreviewUrl = $this->existing_local_authority_certificate_document;
        }


        $this->land_owned_by_proprietor = $results['land_owned_by_proprietor'];
        $this->commence_date = $results['commencement_operation_date'];
        $this->declaration = $results['declaration'] ?? 'no';
        $this->declarationChecked = $this->declaration === 'yes';
    }

    public function updatedBusinessOperatingPermit($value){
        if ($value) {
            $fileName = $this->business_operating_permit
                ? $this->business_operating_permit->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }
    public function updatedTenancyAgreement($value){
        if ($value) {
            $fileName = $this->tenancy_agreement
                ? $this->tenancy_agreement->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }
    public function updatedPoliceClearanceReport($value){
        if ($value) {
            $fileName = $this->police_clearance_report
                ? $this->police_clearance_report->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }
    public function updatedApprovedBuildingPlan($value){
        if ($value) {
            $fileName = $this->approved_building_plan
                ? $this->approved_building_plan->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }
    public function updatedNationalIdCard($value){
        if ($value) {
            $fileName = $this->national_id_card
                ? $this->national_id_card->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }
    public function updatedLandDocumentsWithProprietorName($value){
        if ($value) {
            $fileName = $this->land_documents_with_prop_name_document
                ? $this->land_documents_with_prop_name_document->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }
    public function updatedLocalAuthorityCertificateDocument($value){
        if ($value) {
            $fileName = $this->local_authority_certificate_document
                ? $this->local_authority_certificate_document->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
    }

    public function updatedBusinessOperatingPermitNo($value){
        if($value){
            $fileName = $this->business_operating_permit_no
                ? $this->business_operating_permit_no->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
        $this->business_operating_permit = $this->business_operating_permit_no;
    }
    public function updatedTenancyAgreementNo($value){
        if($value){
            $fileName = $this->tenancy_agreement_no
                ? $this->tenancy_agreement_no->getClientOriginalName()
                : 'Document';
            $this->alert('info', $fileName . ' uploaded successfully.');
        }
        $this->tenancy_agreement = $this->tenancy_agreement_no;
    }

    public function updatedNationalIdCardNo(){
        $this->national_id_card = $this->national_id_card_no;
    }

    public function updatedPoliceClearanceReportNo(){
        $this->police_clearance_report = $this->police_clearance_report_no;
    }
    public function landDetails()
    {
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/expression-of-interest/certify-documents';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'application_id' => $this->application_id,
                'user_id' => Session::get('api_response')['user_id'],
                'land_owned_by_proprietor' => $this->land_owned_by_proprietor,
                'commencement_operation_date' => $this->commence_date,
                'declaration' => $this->declaration
            ]
        ];

        try {
            $response = Http::post($base_url . $endpoint, $payload);
            // dd($response->json());
        } catch (\Exception $e) {
            $this->alert('error', 'An error occured!');
        }
    }


    public function uploadDocuments($document, $documentType)
    {
        $file = $this->$document;

        // If not a valid file object, skip
        if (!is_object($file) || !method_exists($file, 'getRealPath')) {
            $this->alert('info', 'No files selected');
            return;
        }
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/expression-of-interest/upload-documents';
        try {
            $filePath = $this->$document->getRealPath();
            $fileName = $this->$document->getClientOriginalName();

            $response = Http::attach(
                'file',
                file_get_contents($filePath),
                $fileName
            )->post($base_url . $endpoint, [

                'api_key' => $api_key,
                'data' => json_encode([
                    'application_id' => $this->application_id,
                    'user_id' => Session::get('api_response')['user_id'],
                    'document_type' => $documentType,
                ]),
            ]);
            $results = $response->json();
            // dd($results);

            if ($results['status'] == "FAILED") {
                $this->alert('error', $results['statusText']);
                return false;
            } else {
                return true;
            }
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred. Please try again.');
            return false;
        }
    }
    public function saveLandLordDocument()
    {
        $this->landDetails();



        $documents = [
            'police_clearance_report',
            'business_operating_permit',
            'approved_building_plan',
            'national_id_card',
            'tenancy_agreement',
            'local_authority_certificate_document',
            'land_documents_with_prop_name_document',
        ];

        $hasDocument = false;
        $uploadSuccess = false;

        foreach ($documents as $doc) {
            if (isset($this->$doc)) {
                $hasDocument = true;

                // Only if upload succeeded
                if ($this->uploadDocuments($doc, $doc)) {
                    $uploadSuccess = true;
                }
            }
        }

        if (!$hasDocument) {
            $this->alert('error', 'No documents to upload');
            return;
        }

        if (!$uploadSuccess) {
            $this->alert('error', 'No file selected');
            return;
        }

        $this->flash('success', 'Documents uploaded successfully!');
        return $this->redirectRoute('eoiDocument', ['applicationId' => $this->application_id]);
    }

    public function saveTenantDocument()
    {
        $this->validate([
            'land_owned_by_proprietor' => 'in:yes,no',
            'commence_date' => 'required',
            'declaration' => 'required|in:yes',
        ]);

        $this->landDetails();


        $rules = [];

        if (
            $this->results['business_operating_permit'] != '' && $this->results['tenancy_agreement'] != '' &&
            $this->results['national_id_card'] != ''
        ) {
            return redirect()->route('submitEoi', ['applicationId' => $this->application_id]);
        }



        // $this->validate($rules);

        $allSuccessful = true;

        if (isset($this->business_operating_permit)) {
            $allSuccessful = $allSuccessful && $this->uploadDocuments('business_operating_permit', 'business_operating_permit');
        }
        if (isset($this->tenancy_agreement)) {
            $allSuccessful = $allSuccessful && $this->uploadDocuments('tenancy_agreement', 'tenancy_agreement');
        }
        if (isset($this->national_id_card)) {
            $allSuccessful = $allSuccessful && $this->uploadDocuments('national_id_card', 'national_id_card');
        }
        // if(isset($this->police_clearance_report)){
        //    $allSuccessful = $allSuccessful && $this->uploadDocuments('police_clearance_report', 'police_clearance_report');
        // }

        if ($allSuccessful) {
            $this->dispatch('notify', icon: 'success', message: 'Documents uploaded successfully!', color: 'success');
        } else {
            $this->alert('error', 'An error occurred. Please try again.');
        }
        $this->dispatch('notify', icon: 'success', message: 'Documents uploaded successfully!', color: 'success');
    }

    public function removeDocument($documentType)
    {
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/expression-of-interest/remove-documents';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'application_id' => $this->application_id,
                'user_id' => Session::get('api_response')['user_id'],
                'document_type' => $documentType,
            ]
        ];
        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if ($results['status'] == "FAILED") {
                session()->flash('message', $results['statusText']);
                session()->flash('message_type', 'error');
            } elseif ($results['status'] == "OK") {
                session()->flash('message', $results['statusText']);
                session()->flash('message_type', 'success');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }
    public function removeBusinessOperatingPermit()
    {
        $this->removeDocument('business_operating_permit');
    }
    public function removeTenancyAgreement()
    {
        $this->removeDocument('tenancy_agreement');
    }

    public function removeApprovedBuildingPlan()
    {
        $this->removeDocument('approved_building_plan');
    }
    public function removePoliceClearanceReport()
    {
        $this->removeDocument('police_clearance_report');
    }
    public function removeNationalIdCard()
    {
        $this->removeDocument('national_id_card');
    }
    public function removeLocalAuthorityCertificateDocument()
    {
        $this->removeDocument('local_authority_certificate_document');
    }
    public function removeLandDocumentsWithPropNameDocument()
    {
        $this->removeDocument('land_documents_with_prop_name_document');
    }

    protected $messages = [
        'commence_date.required' => 'Please enter commence date.',
        'land_owner.required' => 'Please select land owner.',
        'declaration.in' => 'You must agree to the terms and conditions.',
    ];



    public function back(){
        return redirect()->route('proprietorInformation', ['applicationId' => $this->application_id]);
    }

    #[On('redirect')]
    public function updatePostList(){
        return $this->redirectRoute('submitEoi', ['applicationId' => $this->application_id]);
    }
    public function nextSection(){

        if ($this->land_owned_by_proprietor == 'no') {
            if(
                empty($this->existing_business_operating_permit) ||
                empty($this->existing_tenancy_agreement) ||
                empty($this->existing_national_id_card)) {
                $this->alert('info', 'Please upload all mandory documents!');
                return;
            }
            return redirect()->route('submitEoi', ['applicationId' => $this->application_id]);
        }
        if ($this->land_owned_by_proprietor == 'yes') {

            if(
                empty($this->existing_approved_building_plan) ||
                empty($this->existing_business_operating_permit) ||
                empty($this->existing_national_id_card) ||
                empty($this->existing_local_authority_certificate_document) ||
                empty($this->existing_land_documents_with_prop_name_document)) {
                $this->alert('info', 'Please upload all mandory documents!');
                return;
            }
            return redirect()->route('submitEoi', ['applicationId' => $this->application_id]);
        }

        $this->alert('info', 'Please upload all mandory documents!');
    }
    public function render()
    {
        return view('livewire.expressionofinterest.document', ['results' => $this->results]);
    }
}
