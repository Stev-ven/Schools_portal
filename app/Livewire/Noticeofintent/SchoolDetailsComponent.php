<?php

namespace App\Livewire\Noticeofintent;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class SchoolDetailsComponent extends Component
{
    use LivewireAlert;
    public $results;
    public $curriculum_type;
    public $curriculum_ges, $curriculum_caie = 'no', $curriculum_ib = 'no', $curriculum_fb = 'no',
        $curriculum_bnc = 'no', $curriculum_other = 'no', $curriculum_pearson = 'no';

    //curriculums under ges;
    public $specialized_music;
    public $specialized_fashion;
     public $type_of_school,
        $private_national_curriculum = 'no',
        $public_national_curriculum = 'no',
        $private_international_curricular_school = 'no', $emis_code;

    public function mount($results)
    {
        // $this->results = session('noi_school_details');
        $this->results = session('noi_school_details');
        // dd($this->results);

        $rawType = $results['result']['type_of_school'] ?? null;

        $this->type_of_school = $rawType
            ? Str::of($rawType)->lower()->replace(' ', '_')->value()
            : null;

        $this->curriculum_ges = $results['curriculum_ges'] ?? 'no';
        // $this->curriculum_type = $results['curriculum_ges'] === 'yes' ? 'curriculum_ges' : 'no';
        $this->primary_language_instruction = $results['result']['primary_language_instruction'] ?? 'no';
        $this->primary_language_instruction_english = $results['result']['primary_language_instruction_english'] ?? 'no';
        $this->primary_language_instruction_french = $results['result']['primary_language_instruction_french'] ?? 'no';
        $this->primary_language_instruction_other = $results['result']['primary_language_instruction_other'] ?? 'no';
        $this->primary_language_instruction_other_specified_value = $results['result']['primary_language_instruction_other_specified_value'] ?? null;
        $this->specialized = $results['result']['specialized'] ?? 'no';
        $this->specialized_music = $results['result']['specialized_music'] ?? null;
        $this->specialized_fashion = $results['result']['specialized_fashion'] ?? null;
        $this->specialized_aviation = $results['result']['specialized_aviation'] ?? 'no';
        $this->specialized_remedial = $results['result']['specialized_remedial'] ?? 'no';
        $this->specialized_media = $results['result']['specialized_media'] ?? 'no';
        $this->specialized_bible_training = $results['result']['specialized_bible_training'] ?? 'no';
        $this->specialized_it_training = $results['result']['specialized_it_training'] ?? 'no';
        $this->specialized_language = $results['result']['specialized_language'] ?? 'no';
        $this->specialized_beauty_therapy_hairdressing = $results['result']['specialized_beauty_therapy_hairdressing'] ?? 'no';
        $this->specialized_catering = $results['result']['specialized_catering'] ?? 'no';
        $this->specialized_cosmetology = $results['result']['specialized_cosmetology'] ?? 'no';
        $this->specialized_training = $results['result']['specialized_training'] ?? 'no';
        $this->specialized_other = $results['result']['specialized_other'] ?? 'no';
        $this->emis_code = $results['result']['emis_code'] ?? null;
        $this->total_enrollments = $results['result']['total_enrollments'] ?? null;
        // dd($this->total_enrollments);
        $this->date_of_establishment = $results['result']['date_of_establishment'] ?? null;
        $this->status_of_school = $results['result']['status_of_school'] ?? null;
        $this->last_inspection_date = $results['result']['last_inspection_date'] ?? null;
        $this->mode_of_delivery = $results['result']['mode_of_delivery'] ?? null;
        $this->primary_language_instruction_other_specified_value = $results['result']['primary_language_instruction_other_specified_value'] ?? null;
        $this->pp_nacca_ges_junior_high_school = $results['result']['pp_nacca_ges_junior_high_school'] ?? null;
        $this->pp_nacca_ges_kindergarten = $results['result']['pp_nacca_ges_kindergarten'] ?? null;
        $this->pp_nacca_ges_primary = $results['result']['pp_nacca_ges_primary'] ?? null;
        
        $this->pp_nacca_ges_senior_high_school_tvet = $results['result']['pp_nacca_ges_senior_high_school'] ?? null;
        $this->p_cam_programmes_caie_kindergarten = $results['result']['p_cam_programmes_caie_kindergarten'] ?? null;
        $this->p_cam_programmes_caie_primary = $results['result']['p_cam_programmes_caie_primary'] ?? null;
        $this->p_cam_programmes_caie_junior_high_school = $results['result']['p_cam_programmes_caie_junior_high_school'] ?? null;
        $this->p_cam_programmes_caie_senior_high_school = $results['result']['p_cam_programmes_caie_senior_high_school'] ?? null;
    }

    //type of school


    //specialized
    public $specialized = 'no', $specialized_aviation,
        $specialized_remedial, $specialized_media, $specialized_bible_training,
        $specialized_it_training, $specialized_language, $specialized_beauty_therapy_hairdressing,
        $specialized_catering, $specialized_cosmetology, $specialized_training, $specialized_other;


    //curriculums

    public $pp_nacca_ges_kindergarten = 'no', $pp_nacca_ges_primary = 'no',
        $pp_nacca_ges_junior_high_school = 'no', $pp_nacca_ges_senior_high_school_tvet = 'no';
    //    $pp_nacca_ges_advanced_any_level_above_shs3 = 'no';
    public $department;
    //curriculums under caie;
    public $p_cam_programmes_caie_kindergarten = 'no', $p_cam_programmes_caie_primary = 'no',
        $p_cam_programmes_caie_junior_high_school = 'no', $p_cam_programmes_caie_senior_high_school = 'no',
        $p_cam_programmes_caie_advanced_any_level_above_shs3 = 'no';

    //curriculums under ib;
    public $p_int_baccalaureate_kindergarten = 'no', $p_int_baccalaureate_primary = 'no',
        $p_int_baccalaureate_junior_high_school = 'no', $p_int_baccalaureate_senior_high_school = 'no',
        $p_int_baccalaureate_advanced_any_level_above_shs3 = 'no';


    //curriculums under fb;
    public $french_baccalaureate_kindergarten = 'no', $french_baccalaureate_primary = 'no',
        $french_baccalaureate_junior_high_school = 'no', $french_baccalaureate_senior_high_school = 'no',
        $french_baccalaureate_advanced_any_level_above_shs3 = 'no';

    //curriculums under bnc;
    public $british_national_kindergarten = 'no', $british_national_primary = 'no',
        $british_national_junior_high_school = 'no', $british_national_senior_high_school = 'no',
        $british_national_advanced_any_level_above_shs3 = 'no';

    //curriculums under other;
    public $other_curriculum_kindergarten, $other_curriculum_primary = 'no',
        $other_curriculum_junior_high_school, $other_curriculum_senior_high_school = 'no',
        $other_curriculum_advanced_any_level_above_shs3 = 'no';

    //curriculums under pearson;
    public $pearson_kindergarten = "no", $pearson_primary = "no",
        $pearson_junior_high_school = 'no', $pearson_senior_high_school = 'no',
        $pearson_advanced_any_level_above_shs3 = 'no';

    //shs courses
    public $shs_general_science = 'no', $shs_general_arts = 'no', $shs_business = 'no',
        $shs_agricultural_science = 'no', $shs_visual_arts = 'no', $shs_home_economics = 'no',
        $shs_technical_vocational_education_and_training = 'no';

    public $department_number_of_courses, $date_of_establishment, $primary_language_instruction,
        $status_of_school, $mode_of_delivery, $total_enrollments, $enrolment_range, $last_inspection_date,
        $language = 'no', $primary_language_instruction_english = 'no', $primary_language_instruction_french = 'no',
        $primary_language_instruction_other = 'no',
        $curriculum_other_specified_value = 'no', $primary_language_instruction_other_specified_value;
    public function updated($propertyName)
    {
        if (in_array($propertyName, [
            'primary_language_instruction_english',
            'primary_language_instruction_french',
            'primary_language_instruction_other',
            'specialized_music',
            'specialized_fashion',
            'specialized_aviation',
            'specialized_remedial',
            'specialized_media',
            'specialized_bible_training',
            'specialized_it_training',
            'specialized_language',
            'specialized_beauty_therapy_hairdressing',
            'specialized_catering',
            'specialized_cosmetology',
            'specialized_training',
            'specialized_other',
            'pp_nacca_ges_kindergarten',
            'pp_nacca_ges_primary',
            'pp_nacca_ges_junior_high_school',
            'pp_nacca_ges_senior_high_school_tvet',
            'shs_general_science',
            'shs_agricultural_science',
            'shs_visual_arts',
            'shs_home_economics',
            'shs_business',
            'shs_general_arts',
            'shs_technical_vocational_education_and_training',
            'curriculum_ges',
            'curriculum_caie',
            'curriculum_ib',
            'curriculum_fb',
            'curriculum_bnc',
            'curriculum_other',
            'curriculum_pearson',
            'p_cam_programmes_caie_kindergarten',
            'p_cam_programmes_caie_primary',
            'p_cam_programmes_caie_junior_high_school',
            'p_cam_programmes_caie_senior_high_school',
            'p_cam_programmes_caie_advanced_any_level_above_shs3',
            'p_int_baccalaureate_kindergarten',
            'p_int_baccalaureate_primary',
            'p_int_baccalaureate_junior_high_school',
            'p_int_baccalaureate_senior_high_school',
            'p_int_baccalaureate_advanced_any_level_above_shs3',
            'french_baccalaureate_kindergarten',
            'french_baccalaureate_primary',
            'french_baccalaureate_junior_high_school',
            'french_baccalaureate_senior_high_school',
            'french_baccalaureate_advanced_any_level_above_shs3',
            'british_national_kindergarten',
            'british_national_primary',
            'british_national_junior_high_school',
            'british_national_senior_high_school',
            'british_national_advanced_any_level_above_shs3',
            'pearson_kindergarten',
            'pearson_primary',
            'pearson_junior_high_school',
            'pearson_senior_high_school',
            'pearson_advanced_any_level_above_shs3',
            'other_curriculum_kindergarten',
            'other_curriculum_primary',
            'other_curriculum_junior_high_school',
            'other_curriculum_senior_high_school',
            'other_curriculum_advanced_any_level_above_shs3',
        ])) {
            $this->$propertyName = $this->$propertyName ? 'yes' : 'no';
        }

        if (in_array($propertyName, [
            'primary_language_instruction_english',
            'primary_language_instruction_french',
            'primary_language_instruction_other'
        ]) && $this->$propertyName === 'yes') {
            $this->primary_language_instruction = 'yes';
        }
    }


    // protected $rules = [

    //     'type_of_school' => 'required',
    //     'specialized_music' => 'nullable|in:yes,no',
    //     'specialized_fashion' => 'nullable|in:yes,no',
    //     'specialized_aviation' => 'nullable|in:yes,no',
    //     'specialized_remedial' => 'nullable|in:yes,no',
    //     'specialized_media' => 'nullable|in:yes,no',
    //     'specialized_bible_training' => 'nullable|in:yes,no',
    //     'specialized_it_training' => 'nullable|in:yes,no',
    //     'specialized_language' => 'nullable|in:yes,no',
    //     'specialized_beauty_therapy_hairdressing' => 'nullable|in:yes,no',
    //     'specialized_catering' => 'nullable|in:yes,no',
    //     'specialized_cosmetology' => 'nullable|in:yes,no',
    //     'specialized_training' => 'nullable|in:yes,no',
    //     'specialized_other' => 'nullable|in:yes,no',
    //     'date_of_establishment' => 'required',
    //     'status_of_school' => 'required',
    //     'mode_of_delivery' => 'required',
    //     'last_inspection_date' => 'required',
    //     'emis_code' => 'nullable|numeric',
    //     'last_inspection_date' => 'nullable|date|before_or_equal:today',

    // ];
    public function updatedTypeOfSchool($value)
    {
        $this->type_of_school = $value;
        $this->results['type_of_school'] = $value;
        // dd($this->results);
    }
    public function validateSelections()
    {
        $hasErrors = false;

            if ($this->type_of_school === 'specialized') {
                $this->specialized = 'yes';
                $selected = collect([
                    $this->specialized_music,
                    $this->specialized_fashion,
                    $this->specialized_aviation,
                    $this->specialized_remedial,
                    $this->specialized_media,
                    $this->specialized_bible_training,
                    $this->specialized_it_training,
                    $this->specialized_language,
                    $this->specialized_beauty_therapy_hairdressing,
                    $this->specialized_catering,
                    $this->specialized_cosmetology,
                    $this->specialized_training,
                    $this->specialized_other
                ])->filter(fn($value) => $value === 'yes');

                if ($selected->isEmpty()) {
                    $this->addError('specialized_other', 'At least one option must be selected.');
                    $hasErrors = true;
                    return;
                }
            }
            if ($this->type_of_school === 'private_national_curriculum') {
                $this->curriculum_ges = 'yes';
                $this->private_national_curriculum = 'yes';
            }
            if ($this->type_of_school === 'public_national_curriculum') {
                $this->public_national_curriculum = 'yes';
                $this->curriculum_ges = 'yes';
            }

            if ($this->type_of_school === 'private_national_curriculum' || $this->type_of_school === 'public_national_curriculum') {
                $selected = collect([
                    $this->pp_nacca_ges_kindergarten,
                    $this->pp_nacca_ges_primary,
                    $this->pp_nacca_ges_junior_high_school,
                    $this->pp_nacca_ges_senior_high_school_tvet,
                ])->filter(fn($value) => $value === 'yes');

                if ($selected->isEmpty()) {
                    $this->addError('pp_nacca_ges_senior_high_school_tvet', 'At least one option must be selected.');
                    $hasErrors = true;
                    return;
                }
            }
            if ($this->primary_language_instruction_other === 'yes' && $this->primary_language_instruction_other_specified_value === null) {
                $this->addError('primary_language_instruction_other_specified_value', 'Please specify the other primary language of instruction.');
            }
            if ($this->primary_language_instruction_english !== 'yes' && $this->primary_language_instruction_french !== 'yes' && $this->primary_language_instruction_other !== 'yes') {
                $this->addError('primary_language_english', 'Please select at least one primary language of instruction.');
            }
            if ($this->pp_nacca_ges_senior_high_school_tvet === 'yes') {
                $selected = collect([
                    $this->shs_general_science,
                    $this->shs_agricultural_science,
                    $this->shs_visual_arts,
                    $this->shs_home_economics,
                    $this->shs_business,
                    $this->shs_general_arts,
                    $this->shs_technical_vocational_education_and_training,
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $this->addError('shs_technical_vocational_education_and_training', 'At least one option must be selected.');
                    $hasErrors = true;
                    return;
                }
            }

            if ($this->type_of_school === 'private_international_curricular_school') {
                $selected = collect([
                    $this->curriculum_ges,
                    $this->curriculum_caie,
                    $this->curriculum_ib,
                    $this->curriculum_fb,
                    $this->curriculum_bnc,
                    $this->curriculum_other,
                    $this->curriculum_pearson,
                ])->filter(fn($value) => $value === 'yes');

                if ($selected->isEmpty()) {
                    $this->addError('curriculum_other', 'At least one curriculum must be selected.');
                    $hasErrors = true;
                    return;
                }
            }

            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_ges === 'yes') {
                $selected = collect([
                    $this->pp_nacca_ges_kindergarten,
                    $this->pp_nacca_ges_primary,
                    $this->pp_nacca_ges_junior_high_school,
                    $this->pp_nacca_ges_senior_high_school_tvet
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $this->addError('pp_nacca_ges_senior_high_school_tvet', 'select one department to continue');
                }
            }

            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_ges === 'yes' && $this->pp_nacca_ges_senior_high_school_tvet === 'yes') {
                $selected = collect([
                    $this->shs_general_science,
                    $this->shs_general_arts,
                    $this->shs_business,
                    $this->shs_agricultural_science,
                    $this->shs_visual_arts,
                    $this->shs_home_economics,
                    $this->shs_technical_vocational_education_and_training

                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $this->addError('shs_technical_vocational_education_and_training', 'At least one course must be selected');
                }
            }

            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_caie === 'yes') {
                $selected = collect([
                    $this->p_cam_programmes_caie_kindergarten,
                    $this->p_cam_programmes_caie_primary,
                    $this->p_cam_programmes_caie_junior_high_school,
                    $this->p_cam_programmes_caie_senior_high_school,
                    $this->p_cam_programmes_caie_advanced_any_level_above_shs3
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $hasErrors = true;
                    $this->addError('p_cam_programmes_caie_advanced_any_level_above_shs3', 'At least one option must be selected');
                    return;
                }
            }

            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_ib === 'yes') {
                $selected = collect([
                    $this->p_int_baccalaureate_kindergarten,
                    $this->p_int_baccalaureate_primary,
                    $this->p_int_baccalaureate_junior_high_school,
                    $this->p_int_baccalaureate_senior_high_school,
                    $this->p_int_baccalaureate_advanced_any_level_above_shs3
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $hasErrors = true;
                    $this->addError('p_int_baccalaureate_advanced_any_level_above_shs3', 'At least one option must be selected');
                    return;
                }
            }
            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_fb === 'yes') {
                $selected = collect([
                    $this->french_baccalaureate_kindergarten,
                    $this->french_baccalaureate_primary,
                    $this->french_baccalaureate_junior_high_school,
                    $this->french_baccalaureate_senior_high_school,
                    $this->french_baccalaureate_advanced_any_level_above_shs3
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $hasErrors = true;
                    $this->addError('french_baccalaureate_advanced_any_level_above_shs3', 'At least one option must be selected');
                    return;
                }
            }
            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_bnc === 'yes') {
                $selected = collect([
                    $this->british_national_kindergarten,
                    $this->british_national_primary,
                    $this->british_national_junior_high_school,
                    $this->british_national_senior_high_school,
                    $this->british_national_advanced_any_level_above_shs3
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $hasErrors = true;
                    $this->addError('british_national_advanced_any_level_above_shs3', 'At least one option must be selected');
                    return;
                }
            }
            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_pearson === 'yes') {
                $selected = collect([
                    $this->pearson_kindergarten,
                    $this->pearson_primary,
                    $this->pearson_junior_high_school,
                    $this->pearson_senior_high_school,
                    $this->pearson_advanced_any_level_above_shs3
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $hasErrors = true;
                    $this->addError('pearson_advanced_any_level_above_shs3', 'At least one option must be selected');
                    return;
                }
            }
            if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_other === 'yes') {
                $selected = collect([
                    $this->other_curriculum_kindergarten,
                    $this->other_curriculum_primary,
                    $this->other_curriculum_junior_high_school,
                    $this->other_curriculum_senior_high_school,
                    $this->other_curriculum_advanced_any_level_above_shs3
                ])->filter(fn($value) => $value === 'yes');
                if ($selected->isEmpty()) {
                    $hasErrors = true;
                    $this->addError('other_curriculum_advanced_any_level_above_shs3', 'At least one option must be selected');
                    return;
                }
            }

            if (empty($this->total_enrollments)) {
                $this->addError('total_enrollments', 'Total enrolment is required.');
                $hasErrors = true;
            }
            if (empty($this->emis_code)) {
                $this->addError('emis_code', 'EMIS Code is required.');
                $hasErrors = true;
            }
            if (empty($this->enrollment_range)) {
                $this->addError('enrollment_range', 'Enrollment range is required.');
                $hasErrors = true;
            }
            if (empty($this->date_of_establishment)) {
                $this->addError('date_of_establishment', 'Date of Establishment is required.');
                $hasErrors = true;
            }
            if (empty($this->last_inspection_date)) {
                $this->addError('last_inspection_date', 'Last Inspection Date is required.');
                $hasErrors = true;
            }

            return !$hasErrors;
    }

    public function getPrimaryLanguageInstructionStatus(){
        return ($this->primary_language_instruction_english === 'yes' ||
                $this->primary_language_instruction_french === 'yes' ||
                $this->primary_language_instruction_other === 'yes') ? 'yes' : 'no';
        }


    protected $messages = [
        'type_of_school.required' => 'select type of school',
        'department_number_of_courses.required' => 'select departments',
        'type_of_curriculums.required' => 'select type of curriculum',
        'date_of_establishment.required' => 'enter date of establishment',
        'primary_language.required' => 'select primary language',
        'status_of_school.required' => 'select status of school',
        'mode_of_delivery.required' => 'select mode of delivery',
        'emis_code.numeric' => 'emis code must be numeric',
        'total_enrollments.required'=> 'enter enrollmenet',
        'last_inspection_date' => 'enter last inspection date'
    ];

    public function sendSchoolDetails()
    {
        try {
            // $this->validate();
            if (!$this->validateSelections()) {
                // $this->alert('error', 'validation error');
                // return;
            }

            // dd('ddd');
            $api_key = config('nasia.api.key');
            $payload = [
                "api_key" => $api_key,
                "data" => [
                    "application_id" => $this->results['result']['application_id'],
                    "user_id" => Session::get('api_response')['user_id'],
                    'last_inspection_date' => $this->last_inspection_date,

                    "type_of_schools" => $this->type_of_school,
                    "specialized" => $this->type_of_school == 'specialized' ? 'yes' : 'no',
                    "private_national_curriculum" => $this->type_of_school == 'private_national_curriculum' ? 'yes' : 'no',
                    "public_national_curriculum" => $this->type_of_school == 'public_national_curriculum' ? 'yes' : 'no',
                    "private_international_curricular_school" => $this->type_of_school == 'private_international_curricular_school' ? 'yes' : 'no',

                    //options under specialized school
                    "specialized_music" => $this->specialized_music ?? 'no',
                    "specialized_fashion" => $this->specialized_fashion ?? 'no',
                    "specialized_aviation" => $this->specialized_aviation ?? 'no',
                    "specialized_remedial" => $this->specialized_remedial ?? 'no',
                    "specialized_media" => $this->specialized_media ?? 'no',
                    "specialized_bible_training" => $this->specialized_bible_training ?? 'no',
                    "specialized_it_training" => $this->specialized_it_training ?? 'no',
                    "specialized_language" => $this->specialized_language ?? 'no',
                    "specialized_beauty_therapy_hairdressing" => $this->specialized_beauty_therapy_hairdressing ?? 'no',
                    "specialized_catering" => $this->specialized_catering ?? 'no',
                    "specialized_cosmetology" => $this->specialized_cosmetology ?? 'no',
                    "specialized_training" => $this->specialized_training ?? 'no',
                    "specialized_other" => $this->specialized_other ?? 'no',

                    //options under private_national_curriculum
                    "curriculum_ges" => $this->curriculum_ges ?? 'no',
                    "curriculum_caie" => $this->curriculum_caie ?? 'no',
                    "curriculum_ib" => $this->curriculum_ib ?? 'no',
                    "curriculum_fb" => $this->curriculum_fb ?? 'no',
                    "curriculum_bnc" => $this->curriculum_bnc ?? 'no',
                    "curriculum_pearson" => $this->curriculum_pearson ?? 'no',
                    "curriculum_other" => $this->curriculum_other ?? 'no',
                    "curriculum_other_specified_value" => "no",

                    //ges
                    "pp_nacca_ges_kindergarten" => $this->pp_nacca_ges_kindergarten ?? 'no',
                    "pp_nacca_ges_primary" => $this->pp_nacca_ges_primary ?? 'no',
                    "pp_nacca_ges_junior_high_school" => $this->pp_nacca_ges_junior_high_school ?? 'no',
                    "pp_nacca_ges_senior_high_school_tvet" => $this->pp_nacca_ges_senior_high_school_tvet ?? 'no',

                    //caie
                    "p_cam_programmes_caie_kindergarten" => $this->p_cam_programmes_caie_kindergarten ?? 'no',
                    "p_cam_programmes_caie_primary" => $this->p_cam_programmes_caie_primary ?? 'no',
                    "p_cam_programmes_caie_junior_high_school" => $this->p_cam_programmes_caie_junior_high_school ?? 'no',
                    "p_cam_programmes_caie_senior_high_school" => $this->p_cam_programmes_caie_senior_high_school ?? 'no',
                    "p_cam_programmes_caie_advanced_any_level_above_shs3" => $this->p_cam_programmes_caie_advanced_any_level_above_shs3 ?? 'no',

                    //ib
                    "p_int_baccalaureate_kindergarten" => $this->p_int_baccalaureate_kindergarten ?? 'no',
                    "p_int_baccalaureate_primary" => $this->p_int_baccalaureate_primary ?? 'no',
                    "p_int_baccalaureate_junior_high_school" => $this->p_int_baccalaureate_junior_high_school ?? 'no',
                    "p_int_baccalaureate_senior_high_school" => $this->p_int_baccalaureate_senior_high_school ?? 'no',
                    "p_int_baccalaureate_advanced_any_level_above_shs3" => $this->p_int_baccalaureate_advanced_any_level_above_shs3 ?? 'no',

                    //fb
                    "french_baccalaureate_kindergarten" => $this->french_baccalaureate_kindergarten ?? 'no',
                    "french_baccalaureate_primary" => $this->french_baccalaureate_primary ?? 'no',
                    "french_baccalaureate_junior_high_school" => $this->french_baccalaureate_junior_high_school ?? 'no',
                    "french_baccalaureate_senior_high_school" => $this->french_baccalaureate_senior_high_school ?? 'no',
                    "french_baccalaureate_advanced_any_level_above_shs3" => $this->french_baccalaureate_advanced_any_level_above_shs3 ?? 'no',

                    //bnc
                    "british_national_kindergarten" => $this->british_national_kindergarten ?? 'no',
                    "british_national_primary" => $this->british_national_primary ?? 'no',
                    "british_national_junior_high_school" => $this->british_national_junior_high_school ?? 'no',
                    "british_national_senior_high_school" => $this->british_national_senior_high_school ?? 'no',
                    "british_national_advanced_any_level_above_shs3" => $this->british_national_advanced_any_level_above_shs3 ?? 'no',

                    //pearson
                    "pearson_kindergarten" => $this->pearson_kindergarten ?? 'no',
                    "pearson_primary" => $this->pearson_primary ?? 'no',
                    "pearson_junior_high_school" => $this->pearson_junior_high_school ?? 'no',
                    "pearson_senior_high_school" => $this->pearson_senior_high_school ?? 'no',
                    "pearson_advanced_any_level_above_shs3" => $this->pearson_advanced_any_level_above_shs3 ?? 'no',

                    //other
                    "other_curriculum_kindergarten" => $this->other_curriculum_kindergarten ?? 'no',
                    "other_curriculum_primary" => $this->other_curriculum_primary ?? 'no',
                    "other_curriculum_junior_high_school" => $this->other_curriculum_junior_high_school ?? 'no',
                    "other_curriculum_senior_high_school" => $this->other_curriculum_senior_high_school ?? 'no',
                    "other_curriculum_advanced_any_level_above_shs3" => $this->other_curriculum_advanced_any_level_above_shs3 ?? 'no',

                    //shs courses
                    "shs_general_science" => $this->shs_general_science ?? 'no',
                    "shs_general_arts" => $this->shs_general_arts ?? 'no',
                    "shs_business" => $this->shs_business ?? 'no',
                    "shs_agricultural_science" => $this->shs_agricultural_science ?? 'no',
                    "shs_visual_arts" => $this->shs_visual_arts ?? 'no',
                    "shs_home_economics" => $this->shs_home_economics ?? 'no',
                    "shs_technical_vocational_education_and_training" => $this->shs_technical_vocational_education_and_training ?? 'no',


                    "date_of_establishment" => $this->date_of_establishment,
                    "status_of_school" => $this->status_of_school,
                    "total_enrollments" => $this->total_enrollments,
                    "mode_of_delivery" => $this->mode_of_delivery,
                    "primary_language_instruction" => $this->getPrimaryLanguageInstructionStatus(),
                    "primary_language_instruction_english" => $this->primary_language_instruction_english,
                    "primary_language_instruction_french" => $this->primary_language_instruction_french,
                    "primary_language_instruction_other" => $this->primary_language_instruction_other,
                    "primary_language_instruction_other_specified_value" => $this->primary_language_instruction_other === 'yes' ? $this->primary_language_instruction_other_specified_value : null,
                    "emis_code" => $this->emis_code
                ]
            ];
            // dd($payload);
            $this->confirmNOIDetails($payload);
        } catch (\Illuminate\Validation\ValidationException $e) {
            logger($e->getMessage());
        }
    }

    private function confirmNOIDetails(array $payload)
    {
        // dd($payload);
        $base_url = config('app.url');
        $endpoint = '/application/notice-of-intent/send-school-details';

        try {

            $response = Http::post($base_url.$endpoint, $payload);
            $results = $response->json();
            // dd($results);
            if ($results['status'] == 'FAILED') {
                $text = $results['statusText'];
                $this->alert('error', $text);
            } else {
                $text = $results['statusText'];
                $this->dispatch('notify', icon: 'success', message: $text, color: 'success');
            }
        } catch (\Exception $e) {
            $this->alert('error', 'something went wrong! please try again later');
        }
    }











    #[On('redirect')]
    public function updatePostList()
    {
        return $this->redirectRoute('noticeofintentschoolfacilities', ['applicationId' => $this->results['result']['application_id']]);
    }
    public function back()
    {
        $this->dispatch('go-back');
    }

    public function render()
    {
        return view('livewire.noticeofintent.schooldetails-component');
    }
}
