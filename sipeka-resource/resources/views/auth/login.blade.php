@extends('layouts.guest')
@section('meta_title') Login Akun @endsection

@section('content')
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <!--begin::Form-->
        <!--begin::Heading-->
        <div class="text-center mb-10">
            <!--begin::Title-->
            <h1 class="text-dark mb-3">Masuk ke SIPEKA</h1>
            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />
            <!--end::Title-->
        </div>
        <form method="POST" class="form w-100" id="kt_sign_in_form" action="{{ route('login') }}">
            @csrf
            <!--begin::Heading-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label fs-6 fw-bolder text-dark">{{ __('Username') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="text" name="email" autocomplete="off"
                    value="{{ old('email') }}" required />
                @error('email')
                    <div class="fv-plugins-message-container invalid-feedback">
                        <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                    </div>
                @enderror
                <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                <!--begin::Wrapper-->
                <div class="d-flex flex-stack mb-2">
                    <!--begin::Label-->
                    <label class="form-label fw-bolder text-dark fs-6 mb-0">{{ __('Password') }}</label>
                    <!--end::Label-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Input-->
                <input class="form-control form-control-lg form-control-solid" type="password" name="password"
                    autocomplete="off" required />
                <!--end::Input-->
                @error('password')
                    <div class="fv-plugins-message-container invalid-feedback">
                        <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                    </div>
                @enderror
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="fv-row mb-10">
                {!! htmlFormSnippet() !!}
            </div>
            <!--end::Input group-->
            <!--begin::Actions-->
            <div class="text-center">
                <!--begin::Submit button-->
                <button type="submit" id="kt_sign_in_submit" class="btn btn-lg btn-primary w-100 mb-5">
                    <span class="indicator-label">{{ __('Log in') }}</span>
                    <span class="indicator-progress">Please wait...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
                <!--end::Submit button-->
            </div>
            <!--end::Actions-->
        </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
@endsection
