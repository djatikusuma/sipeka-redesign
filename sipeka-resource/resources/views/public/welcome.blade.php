@extends('layouts.guest')
@section('meta_title') Home @endsection

@section('content')
    <div class="card mb-5 mb-xl-8">
        <div class="card-body">
            @include('public._table_event')
        </div>
    </div>
@endsection


@push('scripts')

    <script>
        $(document).on('click', '#btn-absensi-list, #btn-absensi', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var topic = $(this).data('topic');
            var type = $(this).data('type');

            Swal.fire({
                title: "Masukkan Passcode",
                text: topic,
                input: 'text',
                showCancelButton: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: "password/" + id + "/"+type,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "meeting_passcode" : result.value
                        },
                        success: function(data) {
                            if (data.success) {
                                if (type == "input"){
                                    window.location = "/presence/" + id
                                }else {
                                    window.location = "/presence/" + id + "/" + type
                                }
                            }else {
                                swal(data.message, "info")
                            }
                        }
                    });
                }
            });
        });
    </script>

@endpush
