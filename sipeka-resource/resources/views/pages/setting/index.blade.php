@extends('layouts.app')
@section('meta_title', 'Pengaturan Aplikasi')

@section('content')
    <div class="card mb-5 mb-xl-8">
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
              <span class="card-label fw-bolder fs-3 mb-1">Data Zoom</span>
            </h3>
        </div>
        <div class="card-body">
            <form class="form w-100" id="form_create_menu" action="{{ route('settings.store') }}" method="POST">
                @csrf
                @method('PUT')
                <x-field-input label-name="Client ID" field-name="client_id" placeholder="Client ID" model="{{$zoom->client_id}}" />
                <x-field-input label-name="Client Secret" field-name="client_secret" placeholder="Client Secret" model="{{$zoom->client_secret}}" />

                <input type="hidden" name="uuid" value="{{$zoom->id}}"/>

                <button type="submit" id="form_create_submit" class="btn btn-light-primary">
                    <span class="indicator-label"> Simpan </span>
                    <span class="indicator-progress"> Menyimpan...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>

            </form>
        </div>
    </div>


    <div class="card mb-5 mb-xl-8">
        @php
            $zoomToken = json_decode($zoom->access_token);
        @endphp
        <div class="card-header">
            <h3 class="card-title align-items-start flex-column">
              <span class="card-label fw-bolder fs-3 mb-1">Zoom Token</span>
            </h3>
            <div class="card-toolbar">
                <a href="https://zoom.us/oauth/authorize?response_type=code&client_id={{$zoom->client_id}}&redirect_uri={{route('settings.sync')}}" class="btn btn-light-info">
                  <span class="svg-icon svg-icon-muted svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <path d="M12,8 L8,8 C5.790861,8 4,9.790861 4,12 L4,13 C4,14.6568542 5.34314575,16 7,16 L7,18 C4.23857625,18 2,15.7614237 2,13 L2,12 C2,8.6862915 4.6862915,6 8,6 L12,6 L12,4.72799742 C12,4.62015048 12.0348702,4.51519416 12.0994077,4.42878885 C12.264656,4.2075478 12.5779675,4.16215674 12.7992086,4.32740507 L15.656242,6.46136716 C15.6951359,6.49041758 15.7295917,6.52497737 15.7585249,6.56395854 C15.9231063,6.78569617 15.876772,7.09886961 15.6550344,7.263451 L12.798001,9.3840407 C12.7118152,9.44801079 12.607332,9.48254921 12.5,9.48254921 C12.2238576,9.48254921 12,9.25869158 12,8.98254921 L12,8 Z" fill="#000000"/>
                            <path d="M12.0583175,16 L16,16 C18.209139,16 20,14.209139 20,12 L20,11 C20,9.34314575 18.6568542,8 17,8 L17,6 C19.7614237,6 22,8.23857625 22,11 L22,12 C22,15.3137085 19.3137085,18 16,18 L12.0583175,18 L12.0583175,18.9825492 C12.0583175,19.2586916 11.8344599,19.4825492 11.5583175,19.4825492 C11.4509855,19.4825492 11.3465023,19.4480108 11.2603165,19.3840407 L8.40328311,17.263451 C8.18154548,17.0988696 8.13521119,16.7856962 8.29979258,16.5639585 C8.32872576,16.5249774 8.36318164,16.4904176 8.40207551,16.4613672 L11.2591089,14.3274051 C11.48035,14.1621567 11.7936615,14.2075478 11.9589099,14.4287888 C12.0234473,14.5151942 12.0583175,14.6201505 12.0583175,14.7279974 L12.0583175,16 Z" fill="#000000" opacity="0.3"/>
                        </g>
                    </svg>
                  </span>
                  Sync Zoom
                </a>
              </div>
        </div>
        <div class="card-body">
            <div class="fv-row mb-8">
                <label for="claim_host" class="fw-bold form-label">Access Token</label>
                <textarea class="form-control form-control-solid" rows="6" readonly>{{$zoomToken->access_token ?? ""}}</textarea>
            </div>

            <div class="fv-row mb-8">
                <label for="claim_host" class="fw-bold form-label">Refresh Token</label>
                <textarea class="form-control form-control-solid" rows="6" readonly>{{$zoomToken->refresh_token ?? ""}}</textarea>
            </div>
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
                        'client_id': {
                            validators: {
                                notEmpty: {
                                    message: 'CLient ID tidak boleh kosong'
                                }
                            }
                        },
                        'client_secret': {
                            validators: {
                                notEmpty: {
                                    message: 'Client Secret tidak boleh kosong'
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
