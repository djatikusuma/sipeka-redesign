<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\MstZoom;
use App\Models\Setting;
use App\Models\TrxEvent;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class EventController extends Controller
{

    private $formInternal = '[{"id":"nama_lengkap","label":"Nama Lengkap","type":"text","mandatory":"required","source":"input"},{"id":"nip","label":"NIP","type":"text","mandatory":"required","source":"input"},{"id":"jabatan","label":"Jabatan","type":"text","mandatory":"required","source":"input"},{"id":"unit_kerja","label":"Unit Kerja","type":"text","mandatory":"required","source":"input"}]';
    private $formWebinar = '[{"id":"nama_lengkap","label":"Nama Lengkap","type":"text","mandatory":"required","source":"input"},{"id":"jabatan","label":"Jabatan","type":"text","mandatory":"","source":"input"},{"id":"unit_kerja","label":"Instansi/Lembaga","type":"text","mandatory":"","source":"input"},{"id":"no_telp","label":"No. Telp / WA","type":"text","mandatory":"","source":"input"},{"id":"email","label":"Alamat Email","type":"text","mandatory":"required","source":"input"}]';
    private $clientId;
    private $clientSecret;
    private $token;
    private $zoomAccountId;

    function __construct()
    {
        // $this->middleware('permission:event.index|event.create|event.read|event.update|event.delete', ['only' => ['index', 'showDatatable']]);
        $this->middleware('permission:event.index', ['only' => ['index', 'showDatatable']]);
        $this->middleware('permission:event.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:event.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:event.delete', ['only' => ['destroy']]);

        $this->generate_setting();
    }

    private function generate_setting()
    {
        // get variable zoom
        $setting = Setting::first();
        $this->clientId = $setting->zoom->client_id;
        $this->clientSecret = $setting->zoom->client_secret;
        $this->token = $setting->zoom->access_token;
        $this->zoomAccountId = $setting->zoom->id;
    }

    public function index()
    {
        return view('pages.event.index');
    }

    public function create()
    {
        return view('pages.event.create');
    }

    public function store(Request $request)
    {
        // validation
        $request->validate([
            'meeting_topic' => [
                'required'
            ],
            'meeting_date' => [
                'required'
            ],
            'meeting_passcode' => [
                'required'
            ],
            'meeting_duration' => [
                'required'
            ],
        ]);

        $response = $this->create_zoom($request->all());

        if ($response || $response == null) {
            return redirect()->route('event.index')->with('success', 'Berhasil menambah kegiatan');
        }else {
            return redirect()->route('event.index')->with('error', 'Gagal menambah kegiatan');
            // dd($response);
        }
    }

    public function edit($id)
    {
        $event = TrxEvent::findOrFail($id);

        return view('pages.event.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = TrxEvent::findOrFail($id);

        $response = $this->update_zoom($request->all(), json_decode($event->zoom_json), $id);

        if ($response || $response == null) {
            return redirect()->route('event.index')->with('success', 'Berhasil mengubah kegiatan');
        }else {
            return redirect()->route('event.index')->with('error', 'Gagal mengubah kegiatan');
            // dd($response);
        }
    }

    public function showDatatable()
    {
        $data = (Auth::user()->roles[0]->name === 'coordinator')
            ? TrxEvent::orderBy('meeting_date', 'desc')->where('user_id', Auth::user()->id)->get()
            : TrxEvent::orderBy('meeting_date', 'desc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('topic', function ($row) {
                $zoom = json_decode($row->zoom_json);
                $data = json_encode([
                    "topic" => $row->topic,
                    "meeting_date" => Carbon::parse($row->meeting_date)->translatedFormat('d F Y G:i'),
                    "url" => $zoom->join_url,
                    "presensi" => route('presence.index', $row->id),
                    "meeting_id" => $row->meeting_id,
                    "meeting_passcode" => $row->meeting_passcode
                ]);
                return '<a id="zoom_meeting"
                data-bs-toggle="modal" data-bs-target=".bs-example-modal-center"
                data-json=\''.$data.'\'
                href="#" class="fw-bolder fs-7">' . $row->topic . '</a>';
            })
            ->addColumn('meeting_id', function ($row) {
                return preg_replace('/(\d{3})(\d{4})(\d{4})/', "$1 $2 $3", $row->meeting_id);
            })
            ->addColumn('status', function ($row) {
                $status = check_time($row->meeting_date, $row->meeting_duration);
                return '<span class="badge rounded-pill w-100 p-2 badge-light-' . $status['type'] . '"><strong>' . $status['status'] . '</strong></span>';
            })
            ->addColumn('action', function ($row) {
                $status = check_time($row->meeting_date, $row->meeting_duration);

                if ($status['type'] === 'danger') {
                    return '
                    <a href="' . route('event.edit', $row->id) . '" id="btn-edit" title="Edit Kegiatan" data-href="http://disbun.jabarprov.go.id/sipeka/meeting/edit/ec96b468-d978-48ff-8857-b046926016eb" class="btn btn-sm btn-light-success px-2 py-2">
                        <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Design/Edit.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                    <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                    </a>
                    <a href="' . route('presence.index', $row->id) . '" id="btn-absensi" title="Link Form Kehadiran" data-href="' . route('presence.index', $row->id) . '" class="btn btn-sm btn-light-primary px-2 py-2">
                        <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                    </a>
                    <a href="' . route('presence.list', $row->id) . '" target="_blank" id="btn-absensi-list" title="List Form Kehadiran" data-href="http://disbun.jabarprov.go.id/sipeka/absen/cek/ec96b468-d978-48ff-8857-b046926016eb" class="btn btn-sm btn-light-warning px-2 py-2">
                        <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Text/Bullet-list.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                    <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                    </a>
                    <button id="btn-delete" data-topic="' . $row->topic . '" data-id="' . $row->id . '" class="btn btn-sm btn-light-danger px-2 py-2">
                        <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                    <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                    </button>';
                } else {
                    return '<a href="' . route('print_certificate.index', $row->id) . '" target="_blank" id="btn-print-certificate" title="Cetak Sertifikat" data-href="' . route('print_certificate.index', $row->id) . '" class="btn btn-sm btn-light-primary px-2 py-2">
                                <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M16,17 L16,21 C16,21.5522847 15.5522847,22 15,22 L9,22 C8.44771525,22 8,21.5522847 8,21 L8,17 L5,17 C3.8954305,17 3,16.1045695 3,15 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,15 C21,16.1045695 20.1045695,17 19,17 L16,17 Z M17.5,11 C18.3284271,11 19,10.3284271 19,9.5 C19,8.67157288 18.3284271,8 17.5,8 C16.6715729,8 16,8.67157288 16,9.5 C16,10.3284271 16.6715729,11 17.5,11 Z M10,14 L10,20 L14,20 L14,14 L10,14 Z" fill="#000000"/>
                                            <rect fill="#000000" opacity="0.3" x="8" y="2" width="8" height="2" rx="1"/>
                                        </g>
                                    </svg><!--end::Svg Icon-->
                                </span>
                            </a>
                            <a href="' . route('presence.print', $row->id) . '" target="_blank" id="btn-print" title="Cetak Kehadiran" data-href="' . route('presence.print', $row->id) . '" class="btn btn-sm btn-light-info px-2 py-2">
                                <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M8,3 L8,3.5 C8,4.32842712 8.67157288,5 9.5,5 L14.5,5 C15.3284271,5 16,4.32842712 16,3.5 L16,3 L18,3 C19.1045695,3 20,3.8954305 20,5 L20,21 C20,22.1045695 19.1045695,23 18,23 L6,23 C4.8954305,23 4,22.1045695 4,21 L4,5 C4,3.8954305 4.8954305,3 6,3 L8,3 Z" fill="#000000" opacity="0.3"/>
                                            <path d="M11,2 C11,1.44771525 11.4477153,1 12,1 C12.5522847,1 13,1.44771525 13,2 L14.5,2 C14.7761424,2 15,2.22385763 15,2.5 L15,3.5 C15,3.77614237 14.7761424,4 14.5,4 L9.5,4 C9.22385763,4 9,3.77614237 9,3.5 L9,2.5 C9,2.22385763 9.22385763,2 9.5,2 L11,2 Z" fill="#000000"/>
                                            <rect fill="#000000" opacity="0.3" x="10" y="9" width="7" height="2" rx="1"/>
                                            <rect fill="#000000" opacity="0.3" x="7" y="9" width="2" height="2" rx="1"/>
                                            <rect fill="#000000" opacity="0.3" x="7" y="13" width="2" height="2" rx="1"/>
                                            <rect fill="#000000" opacity="0.3" x="10" y="13" width="7" height="2" rx="1"/>
                                            <rect fill="#000000" opacity="0.3" x="7" y="17" width="2" height="2" rx="1"/>
                                            <rect fill="#000000" opacity="0.3" x="10" y="17" width="7" height="2" rx="1"/>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            <a href="' . route('presence.list', $row->id) . '" target="_blank" id="btn-absensi-list" title="List Form Kehadiran" data-href="http://disbun.jabarprov.go.id/sipeka/absen/cek/ec96b468-d978-48ff-8857-b046926016eb" class="btn btn-sm btn-light-warning px-2 py-2">
                                <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Text/Bullet-list.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                            <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg><!--end::Svg Icon-->
                                </span>
                            </a>';
                }
            })
            ->rawColumns([
                'topic' => 'topic',
                'status' => 'status',
                'action' => 'action',
            ])
            ->make(true);
    }

    public function showDatatablePublic()
    {
        $data = TrxEvent::orderBy('meeting_date', 'desc')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('topic', function ($row) {
                return $row->topic;
            })
            ->addColumn('meeting_id', function ($row) {
                return preg_replace('/(\d{3})(\d{4})(\d{4})/', "$1 $2 $3", $row->meeting_id);
            })
            ->addColumn('status', function ($row) {
                $status = check_time($row->meeting_date, $row->meeting_duration);
                return '<span class="badge rounded-pill w-100 p-2 badge-light-' . $status['type'] . '"><strong>' . $status['status'] . '</strong></span>';
            })
            ->addColumn('action', function ($row) {
                $status = check_time($row->meeting_date, $row->meeting_duration);

                if ($status['type'] === 'info') {
                    return '
                    <button id="btn-absensi" title="Link Form Kehadiran" data-id="'.$row->id.'" data-type="input" data-topic="'.$row->topic.'" class="btn btn-sm btn-light-primary px-2 py-2">
                        <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24"/>
                                    <path d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>
                                    <path d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                    </button>
                    <button id="btn-absensi-list" title="List Form Kehadiran" data-id="'.$row->id.'" data-type="list" data-topic="'.$row->topic.'" class="btn btn-sm btn-light-warning px-2 py-2">
                        <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                            <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Text/Bullet-list.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24"/>
                                    <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                    <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                    </button>';
                } else if ($status['type'] === 'success') {
                    return '<button id="btn-absensi-list" title="List Form Kehadiran" data-id="'.$row->id.'" data-type="list" data-topic="'.$row->topic.'" class="btn btn-sm btn-light-warning px-2 py-2">
                                <span class="svg-icon svg-icon-muted svg-icon-md m-0">
                                    <!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Text/Bullet-list.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <rect x="0" y="0" width="24" height="24"/>
                                            <path d="M10.5,5 L19.5,5 C20.3284271,5 21,5.67157288 21,6.5 C21,7.32842712 20.3284271,8 19.5,8 L10.5,8 C9.67157288,8 9,7.32842712 9,6.5 C9,5.67157288 9.67157288,5 10.5,5 Z M10.5,10 L19.5,10 C20.3284271,10 21,10.6715729 21,11.5 C21,12.3284271 20.3284271,13 19.5,13 L10.5,13 C9.67157288,13 9,12.3284271 9,11.5 C9,10.6715729 9.67157288,10 10.5,10 Z M10.5,15 L19.5,15 C20.3284271,15 21,15.6715729 21,16.5 C21,17.3284271 20.3284271,18 19.5,18 L10.5,18 C9.67157288,18 9,17.3284271 9,16.5 C9,15.6715729 9.67157288,15 10.5,15 Z" fill="#000000"/>
                                            <path d="M5.5,8 C4.67157288,8 4,7.32842712 4,6.5 C4,5.67157288 4.67157288,5 5.5,5 C6.32842712,5 7,5.67157288 7,6.5 C7,7.32842712 6.32842712,8 5.5,8 Z M5.5,13 C4.67157288,13 4,12.3284271 4,11.5 C4,10.6715729 4.67157288,10 5.5,10 C6.32842712,10 7,10.6715729 7,11.5 C7,12.3284271 6.32842712,13 5.5,13 Z M5.5,18 C4.67157288,18 4,17.3284271 4,16.5 C4,15.6715729 4.67157288,15 5.5,15 C6.32842712,15 7,15.6715729 7,16.5 C7,17.3284271 6.32842712,18 5.5,18 Z" fill="#000000" opacity="0.3"/>
                                        </g>
                                    </svg><!--end::Svg Icon-->
                                </span>
                            </button>';
                }
            })
            ->rawColumns([
                'topic' => 'topic',
                'status' => 'status',
                'action' => 'action',
            ])
            ->make(true);
    }

    // fungsi zoom
    public function create_zoom($data = NULL)
    {
        $this->generate_setting();
        $token = json_decode($this->token);

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer ' . $token->access_token;

        $client = 'https://api.zoom.us/v2/users/me/meetings';
        $curl = curl_init($client);

        $postData = [
            "topic" => $data['meeting_topic'],
            "type" => 2,
            "start_time" => date("Y-m-d\TH:i:s", strtotime(date($data['meeting_date']))),
            "duration" => $data['meeting_duration'], // 30 mins
            "password" => $data['meeting_passcode'],
            "timezone" => "ID",
            "settings" => [
                "participant_video" => true,
                "join_before_host" => true,
                "mute_upon_entry" => true,
                "waiting_room" => false
            ]
        ];

        curl_setopt_array($curl, array(
            CURLOPT_POSTFIELDS => json_encode($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTPHEADER => $headr,
            CURLOPT_POST => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_SSL_VERIFYPEER => false
        ));

        $result = json_decode(curl_exec($curl));
        $err = curl_error($curl);
        curl_close($curl);



        if ($err) {
            return redirect()->route('event.index')->with('error', 'Terjadi kesalahan sistem.');
            exit(0);
        } else {
            if (!isset($result->code)) {
                $form = (int)$data['is_internal'] !== 1 ? $this->formWebinar : $this->formInternal;

                $uniqId = uniqid();
                $certificate_file = isset($data['certificate_file']) ? $data['certificate_file'] : null;

                TrxEvent::create([
                    'user_id' => Auth::user()->id,
                    'topic' => $result->topic,
                    'meeting_id' => "{$result->id}",
                    'meeting_passcode' => $result->password,
                    'meeting_date' => date('Y-m-d H:i:s', strtotime($result->start_time)),
                    'meeting_duration' => $result->duration,
                    'zoom_json' => json_encode($result),
                    'field_json' => $form,
                    'file_certificate' => isset($certificate_file)
                                            ? $name_file = $uniqId . '_' . trim($certificate_file->getClientOriginalName())
                                            : null
                ]);

                if(isset($certificate_file)){
                    $path = storage_path('app/public/user_certificate/');
                    $certificate_file->move($path, $name_file);
                }

                return true;
            } else {
                $response = $this->refresh_token();
                if (isset($response->error) || isset($response->code)) {
                    return redirect()->route('event.index')->with('error', 'Terjadi kesalahan pada saat membuat kegiatan.');
                    exit(0);
                } else {
                    $zoomData = MstZoom::findOrFail($this->zoomAccountId);
                    $zoomData->access_token = json_encode($response);
                    $zoomData->save();

                    $this->create_zoom($data);
                }
            }
        }
    }


    public function update_zoom($data = NULL, $zoom = NULL, $id = NULL)
    {
        $this->generate_setting();
        $token = json_decode($this->token);

        $headr = array();
        $headr[] = 'Content-type: application/json';
        $headr[] = 'Authorization: Bearer ' . $token->access_token;

        $client = 'https://api.zoom.us/v2/meetings/' . $zoom->id;
        $curl = curl_init($client);

        $postData = [
            "topic" => $data['meeting_topic'],
            "type" => 2,
            "start_time" => date("Y-m-d\TH:i:s", strtotime(date($data['meeting_date']))),
            "duration" => $data['meeting_duration'], // 30 mins
            "password" => $data['meeting_passcode'],
            "timezone" => "ID",
            "settings" => [
                "participant_video" => true,
                "join_before_host" => true,
                "mute_upon_entry" => true,
                "waiting_room" => false
            ]
        ];

        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postData));
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headr);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = json_decode(curl_exec($curl));

        curl_close($curl);

        // print_data($result);

        if (!isset($result->code)) {
            $form = (int)$data['is_internal'] !== 1 ? $this->formWebinar : $this->formInternal;

            // edit zoom json
            $zoom->topic = $data['meeting_topic'];
            $zoom->duration = $data['meeting_duration'];
            $zoom->password = $data['meeting_passcode'];
            $zoom->start_time = date("Y-m-d\TH:i:s", strtotime(date($data['meeting_date'])));

            $event = TrxEvent::findOrFail($id);

            $uniqId = uniqid();
            $certificate_file = isset($data['certificate_file']) ? $data['certificate_file'] : null;

            $path = storage_path('app/public/user_certificate/');
            if(isset($certificate_file) && !is_null($certificate_file)){
                if(!is_null($event->file_certificate)){
                    $file = storage_path('app/public/user_certificate/'.$event->file_certificate);
                    if(File::exists($file)){
                        File::delete($file);
                    }else{
                        dd('File does not exists.');
                    }
                }

                $certificate_file->move($path, $name_file = $name_file = $uniqId . '_' . trim($certificate_file->getClientOriginalName()));
            }

            $event->zoom_json = json_encode($zoom);
            $event->topic = $data['meeting_topic'];
            $event->meeting_id = $zoom->id;
            $event->meeting_duration = $data['meeting_duration'];
            $event->meeting_passcode = $data['meeting_passcode'];
            $event->field_json = $form;
            $event->meeting_date = date("Y-m-d\TH:i:s", strtotime(date($data['meeting_date'])));
            $event->file_certificate = isset($certificate_file)
                                        ? $name_file
                                        : $event->file_certificate;

            $event->update();

            return true;
        } else {
            $response = $this->refresh_token();
            if (isset($response->error) || isset($response->code)) {
                return redirect()->route('event.index')->with('error', 'Terjadi kesalahan pada sistem.');
            } else {
                $zoomData = MstZoom::findOrFail($this->zoomAccountId);
                $zoomData->access_token = json_encode($response);
                $zoomData->save();

                $this->update_zoom($data, $zoom, $id);
            }
        }
    }


    private function refresh_token()
    {
        $token = json_decode($this->token);

        $client = 'https://api.zoom.us/oauth/token';
        $curl = curl_init($client);
        $postData = [
            "grant_type" => "refresh_token",
            "refresh_token" => $token->refresh_token
        ];

        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Authorization: Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($curl));

        curl_close($curl);

        return $result;
    }

    public function destroy($id)
    {
        try {
            $token = json_decode($this->token);

            $data = TrxEvent::findOrFail($id);

            $client = 'https://api.zoom.us/v2/meetings/' . $data->meeting_id;
            $curl = curl_init($client);
            curl_setopt($curl, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $token->access_token
            ]);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");

            $result = json_decode(curl_exec($curl));

            curl_close($curl);

            if (!isset($result->code)) {
                TrxEvent::destroy($id);

                return response()->json([
                    'success' => true,
                ]);
            } else {
                $response = $this->refresh_token();
                if (isset($response->error)) {
                    return response()->json([
                        'success' => false,
                        'message' => json_encode($response->error)
                    ]);
                } else {
                    $zoomData = MstZoom::findOrFail($this->zoomAccountId);
                    $zoomData->access_token = json_encode($response);
                    $zoomData->save();

                    $this->destroy($id);
                }
            }


        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => json_encode($e)
            ]);
        }
    }
}
