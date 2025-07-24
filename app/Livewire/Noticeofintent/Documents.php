<?php

namespace App\Livewire\Noticeofintent;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Documents extends Component
{
    use LivewireAlert;
    use WithFileUploads;
    public $results, $application_id,
        $vision_and_mission_statement_document,
        $social_security_document,
        $scanned_national_id_card_document,
        $staff_development_plan_document,
        $policies_document, $grievance_procedure_document,
        $fire_safety_certificate_document,
        $curriculum_and_language_policy_document,
        $inclusive_education_policy_document,
        $policy_clearance_report_document,
        $waste_management_plan_document,
        $business_continuity_plan_document;
    public $existing_vision_and_mission_statement_document,
        $existing_social_security_document,
        $existing_scanned_national_id_card_document,
        $existing_staff_development_plan_document,
        $existing_policies_document,
        $existing_grievance_procedure_document,
        $existing_fire_safety_certificate_document,
        $existing_curriculum_and_language_policy_document,
        $existing_inclusive_education_policy_document,
        $existing_policy_clearance_report_document,
        $existing_waste_management_plan_document,
        $existing_business_continuity_plan_document;
    public $visionAndMissionCertificatePreviewUrl,
        $socialSecurityDocumentPreviewUrl,
        $scannedNationalIdCardPreviewUrl,
        $staffDevelopmentPlanPreviewUrl,
        $policiesDocumentPreviewUrl,
        $grievanceProcedurePreviewUrl,
        $fireSafetyCertificatePreviewUrl,
        $curriculumAndLanguagePolicyPreviewUrl,
        $inclusiveEducationPolicyPreviewUrl,
        $policeClearanceReportPreviewUrl,
        $wasteManagementPlanPreviewUrl,
        $businessContinuityPlanPreviewUrl;

    public function mount($results)
    {
        $this->results = $results;
        // dd($this->results);
        $this->application_id = $results['result']['application_id'];
        $this->existing_vision_and_mission_statement_document = $results['result']['vision_and_mission_statement_document'] ?? null;
        $this->existing_social_security_document = $results['result']['social_security_document'] ?? null;
        $this->existing_scanned_national_id_card_document = $results['result']['scanned_national_id_card_document'] ?? null;
        $this->existing_staff_development_plan_document = $results['result']['staff_development_plan_document'] ?? null;
        $this->existing_policies_document = $results['result']['policies_document'] ?? null;
        $this->existing_grievance_procedure_document = $results['result']['grievance_procedure_document'] ?? null;
        $this->existing_fire_safety_certificate_document = $results['result']['fire_safety_certificate_document'] ?? null;
        $this->existing_curriculum_and_language_policy_document = $results['result']['curriculum_and_language_policy_document'] ?? null;
        $this->existing_inclusive_education_policy_document = $results['result']['inclusive_education_policy_document'] ?? null;
        $this->existing_policy_clearance_report_document = $results['result']['policy_clearance_report_document'] ?? null;
        $this->existing_waste_management_plan_document = $results['result']['waste_management_plan_document'] ?? null;
        $this->existing_business_continuity_plan_document = $results['result']['business_continuity_plan_document'] ?? null;

        if (!empty($this->existing_vision_and_mission_statement_document)) {
            $this->visionAndMissionCertificatePreviewUrl = $this->existing_vision_and_mission_statement_document;
        }
        if (!empty($this->existing_social_security_document)) {
            $this->socialSecurityDocumentPreviewUrl = $this->existing_social_security_document;
        }
        if (!empty($this->existing_scanned_national_id_card_document)) {
            $this->scannedNationalIdCardPreviewUrl = $this->existing_scanned_national_id_card_document;
        }
        if (!empty($this->existing_staff_development_plan_document)) {
            $this->staffDevelopmentPlanPreviewUrl = $this->existing_staff_development_plan_document;
        }
        if (!empty($this->existing_policies_document)) {
            $this->policiesDocumentPreviewUrl = $this->existing_policies_document;
        }
        if (!empty($this->existing_grievance_procedure_document)) {
            $this->grievanceProcedurePreviewUrl = $this->existing_grievance_procedure_document;
        }
        if (!empty($this->existing_fire_safety_certificate_document)) {
            $this->fireSafetyCertificatePreviewUrl = $this->existing_fire_safety_certificate_document;
        }
        if (!empty($this->existing_curriculum_and_language_policy_document)) {
            $this->curriculumAndLanguagePolicyPreviewUrl = $this->existing_curriculum_and_language_policy_document;
        }
        if (!empty($this->existing_inclusive_education_policy_document)) {
            $this->inclusiveEducationPolicyPreviewUrl = $this->existing_inclusive_education_policy_document;
        }
        if (!empty($this->existing_policy_clearance_report_document)) {
            $this->policeClearanceReportPreviewUrl = $this->existing_policy_clearance_report_document;
        }
        if (!empty($this->existing_waste_management_plan_document)) {
            $this->wasteManagementPlanPreviewUrl = $this->existing_waste_management_plan_document;
        }
        if (!empty($this->existing_business_continuity_plan_document)) {
            $this->businessContinuityPlanPreviewUrl = $this->existing_business_continuity_plan_document;
        }
    }

    public function updatedVisionAndMissionStatementDocument()
    {
        // dd($this->vision_and_mission_statement_document);
    } // vision_and_mission_statement_document

    public function updatedScannedNationalIdCardDocument()
    {
        // dd($this->scanned_national_id_card_document);
    }
    public function uploadDocuments($file, $document_type, $upload_type, $upload_name)
    {
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/upload-documents';

        // if (!$file || !$file->isValid()) {
        //     $this->alert('error', 'Invalid or missing file');
        //     return false;
        // }

        try {
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();

            if (!file_exists($filePath)) {
                $this->alert('error', 'File not found at: ' . $filePath);
                return false;
            }

            $response = Http::attach(
                'file',
                file_get_contents($filePath),
                $fileName
            )->post($base_url . $endpoint, [
                'api_key' => $api_key,
                'data' => json_encode([
                    'application_id' => $this->application_id,
                    'user_id' => Session::get('api_response')['user_id'],
                    'document_type' => $document_type,
                    'upload_type' => $upload_type,
                    'upload_name' => $upload_name,
                ]),
            ]);

            $results = $response->json();


            if ($results['status'] === "FAILED") {
                $this->alert('error', $results['statusText']);
                return false;
            }

            $this->alert('success', $results['statusText']);
            return true;
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred: ' . $e->getMessage());
            return false;
        }
    }
    public function saveDocuments()
    {
        $document_types = [
            'vision_and_mission_statement_document',
            'social_security_document',
            'scanned_national_id_card_document',
            'staff_development_plan_document',
            'policies_document',
            'grievance_procedure_document',
            'fire_safety_certificate_document',
            'curriculum_and_language_policy_document',
            'inclusive_education_policy_document',
            'policy_clearance_report_document',
            'waste_management_plan_document',
            'business_continuity_plan_document',
        ];

        $hasDocument = false;
        $uploadSuccess = false;
        $failedUploads = [];

        foreach ($document_types as $document_type) {
            $file = $this->$document_type ?? null;

            if ($file) {
                $hasDocument = true;

                $upload_type = 'pdf_photo';
                $upload_name = 'ngh-' . str_replace('_', '-', $document_type) . '-';

                $success = $this->uploadDocuments($file, $document_type, $upload_type, $upload_name);

                if ($success) {
                    $uploadSuccess = true;
                } else {
                    $failedUploads[] = str_replace('_', ' ', ucfirst($document_type));
                }
            }
        }

        if (!$hasDocument) {
            $this->alert('error', 'No documents to upload');
            return false;
        }

        if (!empty($failedUploads)) {
            $this->alert('error', 'Failed to upload: ' . implode(', ', $failedUploads));
            return false;
        }

       $this->fetchDocuments();

        $this->alert('success', 'All documents uploaded successfully');
        return true;
    }






    public function deleteDocument($documentType)
    {
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/remove-documents';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'application_id' => $this->application_id,
                'user_id' => Session::get('api_response')['user_id'],
                'document_type' => $documentType,
            ]
        ];
        // dd($payload);
        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if ($results['status'] == "FAILED") {
                $this->alert('error', $results['statusText']);
            } elseif ($results['status'] == "OK") {
                $this->alert('success', $results['statusText']);
                $this->fetchDocuments();
            }
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }

    private function fetchDocuments()
    {
        $api_key = config('nasia.api.key');
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/get-documents';
        $payload = [
            'api_key' => $api_key,
            'data' => [
                'application_id' => $this->application_id,
                'user_id' => Session::get('api_response')['user_id'],
            ]
        ];
        try {
            $response = Http::post($base_url . $endpoint, $payload);
            $this->results = $response->json();
        } catch (\Exception $e) {
            $this->alert('error', 'An error occurred. Please try again.');
        }
    }
    public function removeVisionAndMissionStatement()
    {
        $this->deleteDocument('vision_and_mission_statement_document');
    }
    public function removeSocialSecurityDocument()
    {
        $this->deleteDocument('social_security_document');
    }
    public function removeScannedNationalIdCardDocument()
    {
        $this->deleteDocument('scanned_national_id_card_document');
    }
    public function removeStaffDevelopmentPlanDocument()
    {
        $this->deleteDocument('staff_development_plan_document');
    }
    public function removePoliciesDocument()
    {
        $this->deleteDocument('policies_document');
    }
    public function removeGrievanceProcedureDocument()
    {
        $this->deleteDocument('grievance_procedure_document');
    }
    public function removeFireSafetyCertificateDocument()
    {
        $this->deleteDocument('fire_safety_certificate_document');
    }
    public function removeCurriculumAndLanguagePolicyDocument()
    {
        $this->deleteDocument('curriculum_and_language_policy_document');
    }
    public function removeInclusiveEducationPolicyDocument()
    {
        $this->deleteDocument('inclusive_education_policy_document');
    }
    public function removePolicyClearanceReportDocument()
    {
        $this->deleteDocument('policy_clearance_report_document');
    }
    public function removeWasteManagementPlanDocument()
    {
        $this->deleteDocument('waste_management_plan_document');
    }
    public function removeBusinessContinuityPlanDocument()
    {
        $this->deleteDocument('business_continuity_plan_document');
    }


    public function nextSection()
    {
        return redirect()->route('submitNoi', ['applicationId' => $this->application_id]);
    }
    public function render()
    {
        return view('livewire.noticeofintent.documents');
    }
}
