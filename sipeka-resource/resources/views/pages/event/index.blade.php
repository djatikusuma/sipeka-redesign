@extends('layouts.app')
@section('meta_title', 'Manajemen Kegiatan')


@section('content')
    <div class="card mb-5 mb-xl-8">
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bolder fs-3 mb-1">Manajemen Kegiatan</span>
                <span class="text-muted mt-1 fw-bold fs-7">Kelola kegiatan daring anda.</span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('event.create') }}" class="btn btn-light-primary">
                    <span class="svg-icon svg-icon-muted svg-icon-2"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none">
                            <path opacity="0.25" fill-rule="evenodd" clip-rule="evenodd"
                                d="M6.54184 2.36899C4.34504 2.65912 2.65912 4.34504 2.36899 6.54184C2.16953 8.05208 2 9.94127 2 12C2 14.0587 2.16953 15.9479 2.36899 17.4582C2.65912 19.655 4.34504 21.3409 6.54184 21.631C8.05208 21.8305 9.94127 22 12 22C14.0587 22 15.9479 21.8305 17.4582 21.631C19.655 21.3409 21.3409 19.655 21.631 17.4582C21.8305 15.9479 22 14.0587 22 12C22 9.94127 21.8305 8.05208 21.631 6.54184C21.3409 4.34504 19.655 2.65912 17.4582 2.36899C15.9479 2.16953 14.0587 2 12 2C9.94127 2 8.05208 2.16953 6.54184 2.36899Z"
                                fill="#12131A" />
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12 17C12.5523 17 13 16.5523 13 16V13H16C16.5523 13 17 12.5523 17 12C17 11.4477 16.5523 11 16 11H13V8C13 7.44772 12.5523 7 12 7C11.4477 7 11 7.44772 11 8V11H8C7.44772 11 7 11.4477 7 12C7 12.5523 7.44771 13 8 13H11V16C11 16.5523 11.4477 17 12 17Z"
                                fill="#12131A" />
                        </svg>
                    </span>
                    <!--end::Svg Icon-->
                    Buat Kegiatan Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            @include('pages.event._table')
        </div>
    </div>

@endsection

@section('modals')
<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mt-0">Undangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <textarea id="invite_zoom" class="form-control" readonly="readonly" rows="15"></textarea>

            </div>
            <div class="modal-footer">
                <button type="button" id="select-all" class="btn btn-primary">Copy</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '#zoom_meeting', (e) => {
                e.preventDefault();
                var data = JSON.parse($(e.target).attr("data-json"));

                var topic = data.topic;
                var meeting_id = data.meeting_id;
                var time = data.meeting_date;
                var url = data.url;
                var password = data.meeting_passcode;
                var presensi = data.presensi;
                var nama = "";

                var text = `Dinas Perkebunan Provinsi Jawa Barat mengundang anda bergabung pada Zoom Meeting.

Topik \t: ${topic}
Waktu \t: ${time}

Join Zoom Meeting
${url}

Meeting ID\t: ${meeting_id.replace(/(\d{3})(\d{4})(\d{4})/, "$1 $2 $3")}
Passcode\t: ${password}

Link Form Kehadiran\t : ${presensi}`;

                $("#invite_zoom").text(text);
            });

            $(document).on('click', '#select-all', (e) => {
                e.preventDefault();

                $("#invite_zoom").select();
                Swal.fire({
                    title: "Berhasil menyalin undangan",
                    showConfirmButton: !1,
                    timer: 1500
                })
                document.execCommand("copy");
            });
            $(document).on('click', '#btn-delete', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                var topic = $(this).data('topic');
                Swal.fire({
                    title: "Apakah anda Yakin?",
                    text: "Anda akan menghapus kegiatan " + topic + "!",
                    icon: "warning",
                    showCancelButton: !0,
                    confirmButtonText: "Hapus!",
                    cancelButtonText: "Batalkan!",
                    reverseButtons: !0
                }).then(function(result) {
                    if (result.isConfirmed) {

                        $.ajax({
                            type: 'DELETE',
                            url: "events/" + id,
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Sedang memproses !',
                                    html: 'Mohon tunggu',// add html attribute if you want or remove
                                    allowOutsideClick: false,
                                    showCancelButton: false,
                                    showConfirmButton: false,
                                    onBeforeOpen: () => {
                                        Swal.showLoading()
                                    },
                                });
                            },
                            success: function(data) {
                                if (data.success) {
                                    window.location.reload(false);
                                }
                            }
                        });

                    }
                });
            });
        });
    </script>
@endpush
