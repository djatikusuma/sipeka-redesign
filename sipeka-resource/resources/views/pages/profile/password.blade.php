@extends('layouts.app')
@section('meta_title', 'Pengaturan Aplikasi')

@section('content')
<div class="card mb-5 mb-xl-8">
    <div class="card-header">
        <h3 class="card-title align-items-start flex-column">
          <span class="card-label fw-bolder fs-3 mb-1">Ubah Kata Sandi</span>
        </h3>
    </div>
    <div class="card-body align-center">
        <form class="form w-100" id="form_create_menu" action="" method="POST">
            @csrf
            @method('PUT')

            <div class="fv-row mb-8 w-250px">
                <label for="old_password" class="fw-bold form-label required">Kata Sandi Sebelumnya</label>
                <input type="password" name="old_password" id="old_password"
                    class="form-control form-control-solid" placeholder="Isikan kata sandi sebelumnya"/>
                @error('old_password')
                    <div class="fv-plugins-message-container invalid-feedback">
                        <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                    </div>
                @enderror
            </div>

            <div class="fv-row mb-8 w-250px">
                <label for="password" class="fw-bold form-label required">Kata Sandi Baru</label>
                <input type="password" name="password" id="password"
                    class="form-control form-control-solid" placeholder="Isikan kata sandi baru"/>
                @error('password')
                    <div class="fv-plugins-message-container invalid-feedback">
                        <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                    </div>
                @enderror
            </div>

            <div class="fv-row mb-8 w-250px">
                <label for="password_confirmation" class="fw-bold form-label required">Ulangi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="form-control form-control-solid" placeholder="ulangi kata sandi baru"/>
                @error('password_confirmation')
                    <div class="fv-plugins-message-container invalid-feedback">
                        <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                    </div>
                @enderror
            </div>

            <button type="submit" id="form_create_submit" class="btn btn-light-primary">
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
            // Define form element
            const form = document.getElementById('form_create_menu');

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                form, {
                    fields: {
                        'password': {
                            validators: {
                                notEmpty: {
                                    message: 'Kata Sandi baru tidak boleh kosong'
                                }
                            }
                        },
                        'old_password': {
                            validators: {
                                notEmpty: {
                                    message: 'Kata Sandi sebelumnya tidak boleh kosong'
                                }
                            }
                        },
                        'password_confirmation': {
                            validators: {
                                notEmpty: {
                                    message: 'Konfirmasi Kata Sandi tidak boleh kosong'
                                }
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
            const submitButton = document.getElementById('form_create_submit');
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

