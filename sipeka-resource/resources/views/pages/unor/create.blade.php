@extends('layouts.app')
@section('meta_title', 'Buat Pengguna Baru')

@section('content')
    <div class="card mb-5 mb-xl-8">
        <div class="card-body">
            <form class="form w-100" id="form_create_unor" action="{{ route('unor.store') }}" method="POST">
                @csrf
                @method('POST')
                <x-field-input label-name="Kode Unit" field-name="unor_code" placeholder="Kode Unit" />
                <x-field-input label-name="Unit Kerja" field-name="unor_name" placeholder="Unit Kerja" />


                <button type="submit" id="form_create_user_submit" class="btn btn-light-primary">
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
            const form = document.getElementById('form_create_unor');

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                form, {
                    fields: {
                        'unor_code': {
                            validators: {
                                notEmpty: {
                                    message: 'Kode unit tidak boleh kosong'
                                }
                            }
                        },
                        'unor_name': {
                            validators: {
                                notEmpty: {
                                    message: 'Unit Kerja tidak boleh kosong'
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
