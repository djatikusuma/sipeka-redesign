@extends('layouts.app')
@section('meta_title', 'Buat Pengguna Baru')

@section('content')
    <div class="card mb-5 mb-xl-8">
        <div class="card-body">
            <form class="form w-100" id="form_create_user" action="{{ route('users.update', $model->id) }}" method="POST">
                @csrf
                @method('PUT')
                <x-field-input label-name="Nama Lengkap" field-name="name" model="{{ $model->name }}" placeholder="Nama Lengkap" />
                <x-field-input label-name="Email" field-name="email" model="{{ $model->email }}" placeholder="Email" />

                <div class="fv-row mb-10">
                    <label for="roles" class="required fw-bold form-label">Roles</label>
                    <select class="form-control" name="roles" id="roles_select">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ ( $role->id == $model->roles[0]->id) ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('roles')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                    @enderror
                </div>

                <div class="fv-row mb-10">
                    <label for="roles" class="required fw-bold form-label">Unit Kerja</label>
                    <select class="form-control" name="unors" id="unors_select">
                        @foreach ($unors as $unor)
                            <option value="{{ $unor->id }}" {{ ( $unor->id == $model->unor_id) ? 'selected' : '' }}>{{ $unor->unor_name }}</option>
                        @endforeach
                    </select>
                    @error('unors')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                    @enderror
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
            $('#roles_select').select2();
            $('#unors_select').select2();

            $(document).on('select2:open', () => {
                let allFound = document.querySelectorAll('.select2-container--open .select2-search__field');
                allFound[allFound.length - 1].focus();
            });

            // Define form element
            const form = document.getElementById('form_create_user');

            // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
            var validator = FormValidation.formValidation(
                form, {
                    fields: {
                        'nama': {
                            validators: {
                                notEmpty: {
                                    message: 'Nama lengkap tidak boleh kosong'
                                }
                            }
                        },
                        'email': {
                            validators: {
                                notEmpty: {
                                    message: 'Email tidak boleh kosong'
                                },
                                emailAddress: {
                                    message: 'Format email tidak valid'
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
