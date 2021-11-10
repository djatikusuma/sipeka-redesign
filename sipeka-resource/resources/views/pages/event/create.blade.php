@extends('layouts.app')
@section('meta_title', 'Buat Kegiatan Baru')

@section('content')
    <div class="card mb-5 mb-xl-8">
        <div class="card-body">
            <form class="form w-100" id="form_create_user" action="{{ route('event.store') }}" method="POST"
                autocomplete="off">
                @csrf
                @method('POST')
                <div class="fv-row mb-8">
                    <label for="akun_zoom" class="fw-bold form-label">Tipe Akun</label>
                    <input type="text" class="form-control form-control-solid" value="Zoom 500 Partisipan" readonly />
                </div>
                <div class="fv-row mb-8">
                    <label for="claim_host" class="fw-bold form-label">Claim Host Code</label>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                        title="Kode ini diperlukan untuk melakukan klaim host pada aplikasi zoom"></i>
                    <input type="text" class="form-control form-control-solid" value="637106" readonly />
                </div>

                <x-field-input label-name="Nama Kegiatan" field-name="meeting_topic" placeholder="Isikan nama kegiatan" />

                <div class="fv-row mb-8 w-250px">
                    <label for="meeting_date" class="fw-bold form-label required">Tanggal Kegiatan</label>
                    <input class="form-control form-control-solid" name="meeting_date" placeholder="Isikan tanggal kegiatan"
                        id="kt_meeting_date" />
                    @error('meeting_date')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                    @enderror
                </div>

                <div class="fv-row mb-8 w-250px">
                    <label for="meeting_duration" class="fw-bold form-label required">Durasi Absensi</label>
                    <select class="form-select form-select-solid" name="meeting_duration" data-control="select2"
                        data-placeholder="Select an option" data-hide-search="true">
                        @php
                            $time = 60;
                            for ($i = 1; $i <= 10; $i++) {
                                echo '<option value="' . $time . '">' . $i . ' Jam</option>';
                                $time += 60;
                            }
                        @endphp
                    </select>
                </div>

                <div class="fv-row mb-8 w-250px">
                    <label for="meeting_passcode" class="fw-bold form-label required">Passcode Kegiatan</label>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                        title="Maksimal passcode 10 digit"></i>
                    <input type="text" maxlength="10" name="meeting_passcode" id="meeting_passcode"
                        class="form-control form-control-solid" placeholder="Isikan passccode kegiatan" />
                    @error('meeting_passcode')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                    @enderror
                </div>

                <button type="submit" id="form_create_user_submit" class="btn btn-light-info fs-5 w-100">
                    <span class="indicator-label"> Simpan </span>
                    <span class="indicator-progress"> Menyimpan...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>

            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        jQuery(document).ready(function() {
            $("#kt_meeting_date").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                minDate: "today",
            });

            // Define form element
            const form = document.getElementById('form_create_user');

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                form, {
                    fields: {
                        'meeting_topic': {
                            validators: {
                                notEmpty: {
                                    message: 'Nama Kegiatan tidak boleh kosong'
                                }
                            }
                        },
                        'meeting_passcode': {
                            validators: {
                                notEmpty: {
                                    message: 'Passcode Kegiatan tidak boleh kosong'
                                },
                            }
                        },
                        'meeting_date': {
                            validators: {
                                notEmpty: {
                                    message: 'Tanggal Kegiatan tidak boleh kosong'
                                },
                            }
                        },
                    },

                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap: new FormValidation.plugins.Bootstrap5({
                            rowSelector: '.fv-row',
                            eleInvalidClass: '',
                            eleValidClass: ''
                        })
                    }
                }
            );

            // Submit button handler
            const submitButton = document.getElementById('form_create_user_submit');
            submitButton.addEventListener('click', function(e) {
                // Prevent default button action
                e.preventDefault();

                // Validate form before submit
                if (validator) {
                    validator.validate().then(function(status) {
                        console.log('validated!');

                        if (status == 'Valid') {
                            // Show loading indication
                            submitButton.setAttribute('data-kt-indicator', 'on');

                            // Disable button to avoid multiple click
                            submitButton.disabled = true;

                            // submit form
                            form.submit()
                        }
                    });
                }
            });
        })
    </script>
@endpush
