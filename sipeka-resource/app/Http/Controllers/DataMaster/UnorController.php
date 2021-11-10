<?php

namespace App\Http\Controllers\DataMaster;

use App\Models\MstUnor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UnorController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:unor.index|unor.create|unor.read|unor.update|unor.delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:unor.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:unor.update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:unor.delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        return view('pages.unor.index');
    }

    public function create()
    {
        return view('pages.unor.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        // validation
        $request->validate([
            'unor_code' => [
                'required',
                'max:100'
            ],
            'unor_name' => [
                'required'
            ]
        ]);

        try {
            DB::beginTransaction();

            // create unor
            MstUnor::create($request->all());

            DB::commit();

            // return view
            return redirect()->route('unor.index')->with('success', 'Berhasil menambahkan unit kerja');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('unor.index')->with('error', $e);
        }
    }

    public function edit($id)
    {
        $model = MstUnor::findOrFail($id);

        return view('pages.unor.edit', compact('model'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // validation
        $request->validate([
            'unor_code' => [
                'required',
                'max:100'
            ],
            'unor_name' => [
                'required'
            ]
        ]);

        try {
            DB::beginTransaction();

            // edit unor
            $unor = MstUnor::findOrFail($id);
            $unor->update($request->all());

            DB::commit();

            // return view
            return redirect()->route('unor.index')->with('success', "Berhasil mengubah data unit kerja {$unor->unor_name}");
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->route('unor.index')->with('error', $e);
        }
    }

    public function destroy($id)
    {
        try{
            MstUnor::destroy($id);

            return redirect()->route('unor.index')->with('success', 'Berhasil menghapus data unit kerja');
        }catch(\Throwable $e) {
            DB::rollBack();
            return redirect()->route('unor.index')->with('error', $e);
        }
    }

    public function showDatatable()
    {
        return DataTables::of(MstUnor::all())
            ->addColumn('action', function ($row) {
                return '<div class="dropdown">
                    <button id="dLabel" type="button" data-bs-toggle="dropdown" aria-expanded="false" class="btn btn-light btn-active-light-primary btn-sm btn-icon">
                    <!--begin::Svg Icon | path: assets/media/icons/duotone/General/Other2.svg-->
                    <span class="svg-icon svg-icon-muted svg-icon-2 m-0"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                            <rect x="0" y="0" width="24" height="24"/>
                            <circle fill="#000000" cx="5" cy="12" r="2"/>
                            <circle fill="#000000" cx="12" cy="12" r="2"/>
                            <circle fill="#000000" cx="19" cy="12" r="2"/>
                        </g>
                    </svg></span>
                    <!--end::Svg Icon-->
                    </button>
                    <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" aria-labelledby="dLabel">
                    <li class="menu-item px-3"><a class="dropdown-item menu-link px-3" href="' . route('unor.edit', $row->id) . '">
                        <!--begin::Svg Icon | path: assets/media/icons/duotone/Design/Edit.svg-->
                        <span class="svg-icon svg-icon-muted svg-icon-2 me-2"><svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero" transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>
                                <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>
                        </svg></span>
                        <!--end::Svg Icon-->
                        Edit
                    </a></li>
                    <li class="menu-item px-3">
                        <form onsubmit="return confirm(\'Apakah Anda Yakin ?\');"
                                action="' . route('unor.delete', $row->id) . '" method="POST"
                                style="display:block">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button class="dropdown-item menu-link px-3 w-100">
                                <span class="svg-icon svg-icon-muted svg-icon-2 me-2"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Trash.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24"/>
                                        <path d="M6,8 L18,8 L17.106535,19.6150447 C17.04642,20.3965405 16.3947578,21 15.6109533,21 L8.38904671,21 C7.60524225,21 6.95358004,20.3965405 6.89346498,19.6150447 L6,8 Z M8,10 L8.45438229,14.0894406 L15.5517885,14.0339036 L16,10 L8,10 Z" fill="#000000" fill-rule="nonzero"/>
                                        <path d="M14,4.5 L14,3.5 C14,3.22385763 13.7761424,3 13.5,3 L10.5,3 C10.2238576,3 10,3.22385763 10,3.5 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>
                                    </g>
                                    </svg><!--end::Svg Icon-->
                                </span>
                                Hapus
                            </button>
                        </form></li>
                    </ul>
                </div>';
            })
            ->rawColumns(['action' => 'action'])
            ->make(true);
    }
}
