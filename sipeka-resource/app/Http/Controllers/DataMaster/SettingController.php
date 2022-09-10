<?php

namespace App\Http\Controllers\DataMaster;

use App\Http\Controllers\Controller;
use App\Models\MstZoom;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $zoom = MstZoom::firstOrFail();

        return view('pages.setting.index', compact('zoom'));
    }

    public function store(Request $request)
    {
         // validation
         $request->validate([
            'uuid' => [
                'required'
            ],
            'client_id' => [
                'required'
            ],
            'client_secret' => [
                'required'
            ]
        ]);

        $zoomData = MstZoom::findOrFail($request->post('uuid'));
        $zoomData->client_id = $request->post('client_id');
        $zoomData->client_secret = $request->post('client_secret');

        if($zoomData->save()) {
            return redirect()->route('settings.index')->with('success', 'Berhasil merubah data zoom.');
        }else {
            return redirect()->route('settings.index')->with('error', 'Terjadi kesalahan saat merubah data zoom.');
        }
    }

    public function sync(Request $request)
    {
        $zoom = MstZoom::firstOrFail();

        try {
            $client = new \GuzzleHttp\Client(['base_uri' => 'https://zoom.us']);

            $response = $client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic ". base64_encode($zoom->client_id.':'.$zoom->client_secret)
                ],
                'form_params' => [
                    "grant_type" => "authorization_code",
                    "code" => $request->get('code'),
                    "redirect_uri" => route('settings.sync')
                ],
            ]);

            $token = json_decode($response->getBody()->getContents(), true);

            $zoomData = MstZoom::findOrFail($zoom->id);
            $zoomData->access_token = $token;

            if($zoomData->save()) {
                return redirect()->route('settings.index')->with('success', 'Berhasil sinkronisasi dengan zoom.');
            }else {
                return redirect()->route('settings.index')->with('error', 'Terjadi kesalahan saat sinkronisasi zoom.');
            }
        } catch(GuzzleException $e) {
            echo $e->getMessage();
        }
    }
}
