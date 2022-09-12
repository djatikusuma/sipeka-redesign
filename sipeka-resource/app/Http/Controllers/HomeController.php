<?php

namespace App\Http\Controllers;

use App\Models\TrxEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin')){
            return $this->dashboard_admin();
        }else {
            return $this->dashboard_user($user);
        }
    }

    public function dashboard_user($user)
    {
        // get Count Kegiatan User
        $events = TrxEvent::where('user_id', $user->id);
        $presence = DB::table('trx_event_presences')
                    ->select(DB::raw('count(trx_event_presences.id) as presence_count'))
                    ->join('trx_events', 'trx_event_presences.event_id', '=', 'trx_events.id')
                    ->where('user_id', '=', $user->id)->first();

        $data = [
            "total_event" => $events->count(),
            "total_peserta" => $presence->presence_count
        ];

        return view('dashboard', compact('data'));
    }

    public function dashboard_admin()
    {
        // get Count Kegiatan User
        $events = TrxEvent::all();
        $users = User::all();
        $presence = DB::table('trx_event_presences')
                    ->select(DB::raw('count(trx_event_presences.id) as presence_count'))
                    ->join('trx_events', 'trx_event_presences.event_id', '=', 'trx_events.id')->first();

        $data = [
            "total_event" => $events->count(),
            "total_peserta" => $presence->presence_count,
            "total_user" => $users->count()
        ];

        return view('dashboard', compact('data'));
    }
}
