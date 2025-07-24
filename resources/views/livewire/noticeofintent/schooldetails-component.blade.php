<div>
    <div class="kt-subheader   kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">
                    Notice of Intent to Open a New School
                </h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            </div>
        </div>
    </div>

    <div class="kt-portlet" style="margin-top: 25px;">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">School Details</h3>
            </div>
        </div>
    </div>

    {{-- @dd($results); --}}
    <div class="kt-portlet__body">
        <form class="row" action="#!" wire:submit.prevent="sendSchoolDetails" style="margin-top: 20px;">
        <div class="row" style="width: 70%; margin: 0 auto; padding: 20px">
            {{-- select type of school --}}
            <div class="form-group">
                <label style="font-size: 15px">Type of school: {{ $results['result']['type_of_school'] }}</label><br>
                <small>school type cannot be changed</small>
            </div>

            {{-- end select type of school --}}

            {{-- Select specialized school if type of school is specialized --}}
            @if($this->type_of_school === 'specialized')
                <div class="form-group">
                    <label style="font-size: 15px">Select Specialized School</label>

                    <div class="kt-checkbox-inline">
                        @php
                            $specializedOptions = [
                                'specialized_music' => 'Music',
                                'specialized_fashion' => 'Fashion',
                                'specialized_aviation' => 'Aviation',
                                'specialized_remedial' => 'Remedial',
                                'specialized_media' => 'Media',
                                'specialized_bible_training' => 'Bible Training',
                                'specialized_it_training' => 'IT Training',
                                'specialized_language' => 'Language',
                                'specialized_beauty_therapy_hairdressing' => 'Beauty Therapy/Hairdressing',
                                'specialized_catering' => 'Catering',
                                'specialized_cosmetology' => 'Cosmetology',
                                'specialized_training' => 'Training',
                                'specialized_other' => 'Other',
                            ];
                        @endphp

                        @foreach ($specializedOptions as $field => $label)
                            <label class="kt-checkbox">
                                <input type="checkbox" wire:model.live="{{ $field }}"
                                    @if (($$field ?? null) === 'yes') checked @endif>
                                {{ $label }}
                                <span></span>
                            </label><br>
                        @endforeach

                        @error('specialized_other')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            @endif


            {{-- Select curriculum type if type of school is private international curricular school --}}
            @if ($this->$type_of_school === 'private_international_curricular_school')

                <label style="font-size: 15px">Select Curriculum type @error('curriculum_other')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </label>

                <div class="kt-radio-inline">
                    @php
                        $curriculumTypes = [
                            'curriculum_ges' => 'Ghana Education Servicess',
                            'curriculum_caie' => 'Cambridge',
                            'curriculum_ib' => 'International Baccalaureate',
                            'curriculum_fb ' => 'French Baccalaureate',
                            'curriculum_bnc' => 'British National Curriculum',
                            'curriculum_pearson' => 'Pearson',
                            'curriculum_other' => 'Other',
                        ];
                    @endphp

                    @foreach ($curriculumTypes as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" value="yes" wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                </div>

            @endif

            @if ($this->type_of_school === 'public_national_curriculum')
                <label style="font-size: 15px;">Ghana Education Service (GES) || NACCA (select departments)</label>

                <div class="kt-checkbox-inline">
                    @php
                        $gesDepartments = [
                            'pp_nacca_ges_kindergarten' => 'Kindergarten',
                            'pp_nacca_ges_primary' => 'Primary',
                            'pp_nacca_ges_junior_high_school' => 'Junior High School',
                            'pp_nacca_ges_high_school_tvet' => 'High School',
                        ];
                    @endphp

                    @foreach ($gesDepartments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" value="yes" wire:model="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach
                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif


            @if ($this->type_of_school === 'private_national_curriculum')
                <label style="font-size: 15px;">Ghana Education Service (GES) || NACCA (select departments)</label>

                <div class="kt-checkbox-inline">
                    @php
                        $gesDepartments = [
                            'pp_nacca_ges_kindergarten' => 'Kindergarten',
                            'pp_nacca_ges_primary' => 'Primary',
                            'pp_nacca_ges_junior_high_school' => 'Junior High School',
                            'pp_nacca_ges_senior_high_school_tvet' => 'Senior High School',
                        ];
                    @endphp

                    @foreach ($gesDepartments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" value="yes" wire:model="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif


            {{-- select departments if type of school is private international curricular school and curriculum is GES --}}
            @if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_ges === 'yes')
                <label style="font-size: 15px;">Ghana Education Service (GES) || NACCA (select departments)</label>

                <div class="kt-checkbox-inline">
                    @php
                        $departments = [
                            'pp_nacca_ges_kindergarten' => 'Kindergarten',
                            'pp_nacca_ges_primary' => 'Primary',
                            'pp_nacca_ges_junior_high_school' => 'Junior High School',
                            'pp_nacca_ges_senior_high_school_tvet' => 'Senior High School',
                        ];
                    @endphp


                    @foreach ($departments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="departments" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- select department is type of school is private international curricular school and curriculum is  caie - cambridge -- --}}
            @if ($this->type_of_school === 'private_international_curricular_school' && $$this->curriculum_caie === 'yes')
                <label style="font-size: 15px;">Curriculum CAIE - Cambridge</label>

                <div class="kt-checkbox-inline">
                    @php
                        $departments = [
                            'p_cam_programmes_caie_kindergarten' => 'Kindergarten',
                            'p_cam_programmes_caie_primary' => 'Primary',
                            'p_cam_programmes_caie_junior_high_school' => 'Junior High School',
                            'p_cam_programmes_caie_senior_high_school' => 'Senior High School',
                            'p_cam_programmes_caie_advanced_any_level_above_shs3' => 'Advanced Any Level Above SHS3',
                        ];
                    @endphp

                    @foreach ($departments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="departments" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- select department is type of school is private international curricular school and curriculum is  ib - international baccalaureate --}}
            @if ($this->type_of_school === 'private_international_curricular_school' && $$this->curriculum_ib === 'yes')
                <label style="font-size: 15px;">Curriculum IB - International Baccalaureate</label>

                <div class="kt-checkbox-inline">
                    @php
                        $departments = [
                            'p_int_baccalaureate_kindergarten' => 'Kindergarten',
                            'p_int_baccalaureate_primary' => 'Primary',
                            'p_int_baccalaureate_junior_high_school' => 'Junior High School',
                            'p_int_baccalaureate_senior_high_school' => 'Senior High School',
                            'p_int_baccalaureate_advanced_any_level_above_shs3' => 'Advanced Any Level Above SHS3',
                        ];
                    @endphp

                    @foreach ($departments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="departments" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- select department is type of school is private international curricular school and curriculum is  fb - french baccalaureate --}}
            @if ($this->type_of_school === 'private_international_curricular_school' && $this->curriculum_fb === 'yes')
                <label style="font-size: 15px;">Curriculum FB - French Baccalaureate</label>

                <div class="kt-checkbox-inline">
                    @php
                        $departments = [
                            'french_baccalaureate_kindergarten' => 'Kindergarten',
                            'french_baccalaureate_primary' => 'Primary',
                            'french_baccalaureate_junior_high_school' => 'Junior High School',
                            'french_baccalaureate_senior_high_school' => 'Senior High School',
                            'french_baccalaureate_advanced_any_level_above_shs3' => 'Advanced Any Level Above SHS3',
                        ];
                    @endphp

                    @foreach ($departments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="departments" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- Select departmen if type of school is private international curricular school and curriculum is bnc - british national curriculum --}}
            @if ($this->type_of_school === 'private_international_curricular_school' && $this->$curriculum_bnc === 'yes')
                <label style="font-size: 15px;">Curriculum BNC - British National Curriculum</label>

                <div class="kt-checkbox-inline">
                    @php
                        $departments = [
                            'british_national_kindergarten' => 'Kindergarten',
                            'british_national_primary' => 'Primary',
                            'british_national_junior_high_school' => 'Junior High School',
                            'british_national_senior_high_school' => 'Senior High School',
                            'british_national_advanced_any_level_above_shs3' => 'Advanced Any Level Above SHS3',
                        ];
                    @endphp

                    @foreach ($departments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="departments" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif


            {{-- Select departmen if type of school is private international curricular school and curriculum is bnc - british national curriculum --}}
            @if ($this->type_of_school === 'private_international _curricular_school' && $curriculum_pearson === 'yes')
                <label style="font-size: 15px;">Curriculum pearson - Pearson</label>

                <div class="kt-checkbox-inline">
                    @php
                        $departments = [
                            'pearson_kindergarten' => 'Kindergarten',
                            'pearson_primary' => 'Primary',
                            'pearson_junior_high_school' => 'Junior High School',
                            'pearson_senior_high_school' => 'Senior High School',
                            'pearson_advanced_any_level_above_shs3' => 'Advanced Any Level Above SHS3',
                        ];
                    @endphp

                    @foreach ($departments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="departments" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- select department if type of school is private international curricular school and curriculum is other - curriculum oher --}}
            @if ($this->type_of_school === 'private_international_curricular_school' && $curriculum_other === 'yes')
                <label style="font-size: 15px;">Others</label>

                <div class="kt-checkbox-inline">
                    @php
                        $departments = [
                            'other_curriculum_kindergarten' => 'Kindergarten',
                            'other_curriculum_primary' => 'Primary',
                            'other_curriculum_junior_high_school' => 'Junior High School',
                            'other_curriculum_senior_high_school' => 'Senior High School',
                            'other_curriculum_advanced_any_level_above_shs3' => 'Advanced Any Level Above SHS3',
                        ];
                    @endphp

                    @foreach ($departments as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="departments" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('pp_nacca_ges_senior_high_school_tvet')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            @endif

            {{-- select courses if type of school is private international curricular school and curriculum is GES and senior high school is yes --}}
            @if (
                $this->type_of_school === 'private_international_curricular_school' &&
                    $curriculum_ges === 'yes' &&
                    $pp_nacca_ges_senior_high_school_tvet === 'yes')
                <label style="font-size: 15px;">Select Courses</label>

                <div class="kt-checkbox-inline">
                    @php
                        $courses = [
                            'shs_general_science' => 'General Science',
                            'shs_agricultural_science' => 'Agricultural Science',
                            'shs_general_arts' => 'General Arts',
                            'shs_business' => 'Business',
                            'shs_visual_arts' => 'Visual Arts',
                            'shs_home_economics' => 'Home Economics',
                            'shs_technical_vocational_education_and_training' =>
                                'Technical Vocational Education and Training',
                        ];
                    @endphp

                    @foreach ($courses as $field => $label)
                        <label class="kt-checkbox">
                            <input type="checkbox" name="courses" value="yes"
                                wire:model.live="{{ $field }}">
                            {{ $label }}
                            <span></span>
                        </label><br>
                    @endforeach

                    @error('shs_technical_vocational_education_and_training')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

            @endif




            {{-- select courses if type of school is public national curriculum (GES curriculum by default) and senior high school is yes --}}
            <div class="form-group">
                @if ($this->type_of_school === 'public_national_curriculum' && $pp_nacca_ges_senior_high_school_tvet === 'yes')
                    <label style="font-size: 15px;">Select Courses</label>

                    <div class="kt-checkbox-inline">
                        @php
                            $courses = [
                                'shs_general_science' => 'General Science',
                                'shs_agricultural_science' => 'Agricultural Science',
                                'shs_general_arts' => 'General Arts',
                                'shs_business' => 'Business',
                                'shs_visual_arts' => 'Visual Arts',
                                'shs_home_economics' => 'Home Economics',
                                'shs_technical_vocational_education_and_training' =>
                                    'Technical Vocational Education and Training',
                            ];
                        @endphp

                        @foreach ($courses as $field => $label)
                            <label class="kt-checkbox">
                                <input type="checkbox" name="courses" value="yes"
                                    wire:model.live="{{ $field }}">
                                {{ $label }}
                                <span></span>
                            </label><br>
                        @endforeach

                        @error('shs_technical_vocational_education_and_training')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif


                @if (
                    ($results['type_of_school'] ?? $type_of_school) === 'private national curriculum' &&
                        $pp_nacca_ges_senior_high_school_tvet === 'yes')
                    <label style="font-size: 15px;">Select Courses</label>

                    <div class="kt-checkbox-inline">
                        @php
                            $courses = [
                                'shs_general_science' => 'General Science',
                                'shs_agricultural_science' => 'Agricultural Science',
                                'shs_general_arts' => 'General Arts',
                                'shs_business' => 'Business',
                                'shs_visual_arts' => 'Visual Arts',
                                'shs_home_economics' => 'Home Economics',
                                'shs_technical_vocational_education_and_training' =>
                                    'Technical Vocational Education and Training',
                            ];
                        @endphp

                        @foreach ($courses as $field => $label)
                            <label class="kt-checkbox">
                                <input type="checkbox" name="courses" value="yes"
                                    wire:model.live="{{ $field }}">
                                {{ $label }}
                                <span></span>
                            </label><br>
                        @endforeach

                        @error('shs_technical_vocational_education_and_training')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif

            </div>


            {{-- @dd($results['result']['emis_code']); --}}

            <div class="form-group">
                <label class="form-control-label">EMIS CODE</label>
                <input type="text" class="form-control" wire:model.live="emis_code"
                    value="{{ $results['emis_code'] ?? '' }}">
                <p class="small text-muted">Generated by NaSIA</p>
                @error('emis_code')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-control-label">Total Enrolment</label>
                <input type="number" class="form-control" wire:model.live="total_enrollments">
                @error('total_enrollments')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-control-label">Enrolment Range</label>
                <input type="number" class="form-control" wire:model.live="enrolment_range"
                    value="{{ $results['enrolment_range'] ?? '' }}">
                <p class="small text-muted">Generated by NaSIA</p>
                @error('enrolment_range')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>




            <div class="form-group">
                <div class="kt-checkbox-inline">

                    <label style="font-size: 15px">Primary language of instruction (medium of instruction)</label><br>
                    <label class="kt-checkbox">
                        <input type="checkbox" value="yes" wire:model.live="primary_language_instruction_english"
                            {{ $primary_language_instruction_english === 'yes' ? 'checked' : '' }}>
                        English
                        <span></span>
                    </label><br>
                    <label class="kt-checkbox">
                        <input type="checkbox" value="yes" wire:model.live="primary_language_instruction_french"
                            {{ $primary_language_instruction_french === 'yes' ? 'checked' : '' }}>
                        French <br>
                        <span></span>
                    </label><br>
                    <label class="kt-checkbox">
                        <input type="checkbox" class="kt-checkbox" value="yes"
                            wire:model.live="primary_language_instruction_other"
                            {{ $primary_language_instruction_other === 'yes' ? 'checked' : '' }}>
                        Other <br>
                        <span></span>
                    </label><br>
                    @if ($primary_language_instruction_other === 'yes' && $primary_language_instruction_other_specified_value != null)
                        <div class="form-group">
                            <input type="text" class="form-control"
                                wire:model.live="primary_language_instruction_other_specified_value"
                                placeholder="Please specify">
                        </div>
                        @error('primary_language_instruction_other_specified_value')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    @endif
                    @error('primary_language_instruction_other')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>






            <div class="form-group">
                <label class="form-control-label">Date of establishment</label>
                <input type="date" class="form-control" wire:model.live="date_of_establishment">
                @error('date_of_establishment')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label style="font-size: 15px">Status of School</label>
                <div class="kt-radio-inline">
                    <label class="kt-radio">
                        <input type="radio" name="status_of_school" value="day"
                            wire:model.live="status_of_school">
                        Day
                        <span></span>
                    </label>

                    <label class="kt-radio">
                        <input type="radio" name="status_of_school" value="boarding"
                            wire:model.live="status_of_school">
                        Boarding
                        <span></span>
                    </label>

                    <label class="kt-radio">
                        <input type="radio" name="status_of_school" value="both"
                            wire:model.live="status_of_school">
                        Both
                        <span></span>
                    </label>
                </div>
                @error('status_of_school')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-control-label">When was the school last inspected by NaSIA</label>
                <input type="date" class="form-control" wire:model.live="last_inspection_date">
                @error('last_inspection_date')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label style="font-size: 15px">Mode of Delivery</label>
                <div class="kt-radio-inline">
                    <label class="kt-radio">
                        <input type="radio" name="mode_of_delivery" value="face-to-face"
                            wire:model.live="mode_of_delivery">
                        Face-to-face
                        <span></span>
                    </label>

                    <label class="kt-radio">
                        <input type="radio" name="mode_of_delivery" value="virtual"
                            wire:model.live="mode_of_delivery">
                        Virtual
                        <span></span>
                    </label>

                    <label class="kt-radio">
                        <input type="radio" name="mode_of_delivery" value="both"
                            wire:model.live="mode_of_delivery">
                        Both
                        <span></span>
                    </label>
                </div>
                @error('mode_of_delivery')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="kt-portlet__foot">

                <div class="kt-form__actions kt-align-right">

                    <span>&nbsp;</span>
                    <button type="submit" class="btn btn-outline-success">Submit & Continue</button>
                </div>

            </div>
        </div>
    </form>

    </div>



</div>
@script
    <script>
        $wire.on('notify', (items) => {
            Swal.fire({
                title: "Details have been updated successfully",
                text: "Proceed to Next section",
                icon: "success",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes! Proceed",
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('redirect');
                }
            });
        })
    </script>
@endscript
