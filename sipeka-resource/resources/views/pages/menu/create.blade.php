@extends('layouts.app')
@section('meta_title', 'Buat Pengguna Baru')

@section('content')
    <div class="card mb-5 mb-xl-8">
        <div class="card-body">
            <form class="form w-100" id="form_create_menu" action="{{ route('menus.store') }}" method="POST">
                @csrf
                @method('POST')
                <x-field-input label-name="Nama Menu" field-name="label" placeholder="Nama Menu" />
                <x-field-input label-name="Permission" field-name="permission" placeholder="Permission" />
                <x-field-input label-name="Urutan" field-name="order" model="{{ count($menus) }}" placeholder="Urutan Menu" />

                <div class="fv-row mb-10">
                    <label for="menus" class="required fw-bold form-label">Parent Menu</label>
                    <select class="form-control" name="menus" id="menus_select">
                        <option value="">Main Menu</option>
                        @foreach ($menus as $menu)
                            <option value="{{ $menu->id }}">{{ $menu->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="fv-row mb-10">
                    <label for="icon" class="fw-bold form-label">Icon</label>
                    <textarea rows="5" class="form-control form-control-solid" name="icon" value="{{ old('icon') }}"
                        placeholder="Icon" autocomplete="off">{{ old('icon') }}</textarea>
                </div>


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
            $('#menus_select').select2();

            // Define form element
            const form = document.getElementById('form_create_menu');

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                form, {
                    fields: {
                        'label': {
                            validators: {
                                notEmpty: {
                                    message: 'Nama Menu tidak boleh kosong'
                                }
                            }
                        },
                        'permission': {
                            validators: {
                                notEmpty: {
                                    message: 'Permission tidak boleh kosong'
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
