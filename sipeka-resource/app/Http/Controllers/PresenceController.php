<?php

namespace App\Http\Controllers;

use App\Models\TrxEvent;
use Illuminate\Http\Request;
use App\Models\TrxEventPresence;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PresenceController extends Controller
{

    public function input($id)
    {
        $event = TrxEvent::findOrFail($id);
        $zoomData = json_decode($event->zoom_json);
        $status = check_time($event->meeting_date, $zoomData->duration);

        if ($status['type'] === 'success'){
            return redirect()->to(route('home'))->with('error', 'Kegiatan tidak ditemukan atau mungkin telah selesai');
        }

        return view('public.absen', [
            'event' => $event
        ]);
    }

    public function store(Request $request, $id)
    {
        $event = TrxEvent::findOrFail($id);
        $field = json_decode($event->field_json);

        $rules = [];
        $rules['g-recaptcha-response'] = ['recaptcha'];
        $rules['ttd'] = ['required'];
        foreach($field as $form){
            if ($form->mandatory === 'required'){
                $rules[$form->id] = ['required'];
            }
        }

        $request->validate($rules);

        try {
            DB::beginTransaction();

            TrxEventPresence::create([
                'event_id' => $id,
                'form_json' => json_encode($request->except(['g-recaptcha-response', '_token']))
            ]);

            DB::commit();

            return redirect()->to(route('presence.list', $id))->with('success', 'Berhasil mengisi form kehadiran');
        }catch(\Exception $e){
            DB::rollBack();

            return redirect()->to(route('home'))->with('error', 'Gagal mengisi form kehadiran');
        }
    }

    public function list($id)
    {
        $event = TrxEvent::findOrFail($id);
        $fields = json_decode($event->field_json);

        $column[] = [
            'data' => 'DT_RowIndex',
            'className' => 'text-center'
        ];
        foreach ($fields as $field) {
            $column[] = [
                'data' => $field->id
            ];
        }

        if(Auth::check()){
            $column[] = [
                'data' => 'ttd',
                'className' => 'text-center'
            ];
        }

        return view('pages.presence.index', compact('event', 'column'));
    }

    public function showDatatable($id)
    {
        $data = TrxEvent::with('presences')
                    ->where('id', $id)
                    ->orderBy('created_at', 'desc')
                    ->first();


        $dataTable = $data->presences->map(function ($row) {
            $field = json_decode($row->form_json);
            $form = [];
            $i = 0;
            foreach ($field as $key => $value) {
                $form[$key] = $value;
                $i++;
            }
            return $form;
        });

        $table = DataTables::of($dataTable);
        $table->addIndexColumn();
        $table->addColumn('ttd', function($row) {
            return (Auth::check())
                ? '<img src="data:'.$row['ttd'].'" style="width:60px;max-height:20px" />'
                : '';
        });

        $table->rawColumns(['ttd' => 'ttd']);

        return $table->make(true);
    }

    public function checkPassword(Request $request, $id)
    {
        $event = TrxEvent::findOrFail($id);

        if ($request->get('meeting_passcode') == $event->meeting_passcode){
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => "Meeting Passcode tidak sama"
            ]);
        }
    }

    public function print($id)
    {
        $event = TrxEvent::with('presences')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->first();

        return view('pages.presence.print', compact('event'));
    }
}
