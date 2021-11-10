@extends('layouts.guest')

@section('meta_title') {{ $event->topic }} @endsection

@section('content')
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header px-10 py-10">
            <table class="w-100 min-w-100">
                <tr>
                    <td style="width: 10%;">Topik Kegiatan</td>
                    <td style="width: 3%;">:</td>
                    <td><strong>{{ $event->topic }}</strong></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><strong>{{ $event->meeting_date }}</strong></td>
                </tr>
                <tr>
                    <td>Meeting ID</td>
                    <td>:</td>
                    <td><strong>{{ $event->meeting_id }}</strong></td>
                </tr>
            </table>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body">
            <form action="{{ route('presence.store', $event->id) }}" method="POST">
                @csrf

                @php
                    $forms = json_decode($event->field_json);
                @endphp
                @foreach ($forms as $form)
                    <div class="fv-row mb-8">
                        <label for="{{ $form->label }}"
                            class="{{ $form->mandatory }} fw-bold form-label">{{ $form->label }}</label>
                        <input type="text" class="form-control form-control-solid" name="{{ $form->id }}"
                            value="{{ old($form->id) }}" placeholder="{{ $form->label }}"
                            autocomplete="off" />
                        @error($form->id)
                            <div class="fv-plugins-message-container invalid-feedback">
                                <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                            </div>
                        @enderror
                    </div>

                @endforeach

                <div class="input-group">
                    <div class="input-group">
                        <label class="label required">Tanda Tangan</label>
                        <div id="signature"></div>
                        @error('ttd')
                            <div class="fv-plugins-message-container invalid-feedback">
                                <div data-field="flatpickr_input" data-validator="notEmpty">{{ $message }}</div>
                            </div>
                        @enderror
                    </div>
                </div>
                *Saya menyatakan ini adalah benar tanda tangan saya

                <textarea id='output' name="ttd"
                    placeholder="Tanda tangan pada area yang disediakan, kemudian klik Approve Digital Signature"
                    style="width: 100%;display:none" rows="4" readonly required></textarea><br />
                <!-- Preview image -->
                <img src='' id='sign_prev' style='display: none;' />

                {!! htmlFormSnippet() !!}

                <br>
                <button type="submit" id="form_create_user_submit" class="btn btn-light-primary fs-5 w-100">
                    <span class="indicator-label"> Kirim </span>
                    <span class="indicator-progress"> Mengirim...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                </button>
            </form>
        </div>
        <!--end::Card body-->
    </div>

@endsection

@push('styles')
    <style>
        #signature {
            width: 100%;
            height: auto;
            border: 1px solid black;
        }

        .ui-widget {
            font-family: inherit;
        }

    </style>
@endpush

@push('scripts')
    <script src="{{ asset('themes/metronic/js/jSignature.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var $sigdiv = $("#signature").jSignature({
                'UndoButton': true
            });

            $("#signature").bind('change', function(e) {
                // Get response of type image
                var data = $sigdiv.jSignature('getData', 'svgbase64');

                // Storing in textarea
                $('#output').val(data);

                if ($('#output').val() !== '') {
                    $('#btn-submit-absen').attr("disabled", false);
                }
                // Alter image source
                // $('#sign_prev').attr('src', "data:" + data);
                // $('#sign_prev').show();
            });
        });
    </script>

@endpush
