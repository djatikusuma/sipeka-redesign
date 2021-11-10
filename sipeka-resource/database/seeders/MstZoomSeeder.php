<?php

namespace Database\Seeders;

use App\Models\MstZoom;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class MstZoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zoom = MstZoom::create([
            'client_id' => '1ns8yHgSWKCVFpdhxKtxw',
            'client_secret' => 'H4efb097lsbmsNNzKmjcGEES4Uk7s5io',
            'access_token' => '{"access_token":"eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI3ZGJjMTNiOS04ZDVmLTRkMDItYTM2OS01MjBjNjhlODk0ODQifQ.eyJ2ZXIiOjcsImF1aWQiOiI3YmMxYzhlM2Y5YzhhMDljYzNhNTZmYjE5ZTE4OTc3NyIsImNvZGUiOiJYWm1YaVhSdXdrX0Q2NmNNWHduUkZHVVlDQnFPU1BUTHciLCJpc3MiOiJ6bTpjaWQ6MW5zOHlIZ1NXS0NWRnBkaHhLdHh3IiwiZ25vIjowLCJ0eXBlIjowLCJ0aWQiOjUsImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsInVpZCI6IkQ2NmNNWHduUkZHVVlDQnFPU1BUTHciLCJuYmYiOjE2MzI2MjMxMTEsImV4cCI6MTYzMjYyNjcxMSwiaWF0IjoxNjMyNjIzMTExLCJhaWQiOiJoWVN5RlBRV1IyZVFmUnM5WGZKcmtBIiwianRpIjoiOWUyYmVjNTEtMDc5OS00OTY0LTkzYzYtMGRjOWExZjgwMWU1In0.pcmqJLebheCmCEGMt4egAUaF5MhR3xuEOQUd0COdxTW0NDwqMuZfp2CBb4nYA6ler_0Vfmv1nB3xoJDeOtM2tw","token_type":"bearer","refresh_token":"eyJhbGciOiJIUzUxMiIsInYiOiIyLjAiLCJraWQiOiI3OTYxMzEyZi1lMTUyLTQ5MmQtOWU4Yy03MzA5MWM3ZDdmMGMifQ.eyJ2ZXIiOjcsImF1aWQiOiI3YmMxYzhlM2Y5YzhhMDljYzNhNTZmYjE5ZTE4OTc3NyIsImNvZGUiOiJYWm1YaVhSdXdrX0Q2NmNNWHduUkZHVVlDQnFPU1BUTHciLCJpc3MiOiJ6bTpjaWQ6MW5zOHlIZ1NXS0NWRnBkaHhLdHh3IiwiZ25vIjowLCJ0eXBlIjoxLCJ0aWQiOjUsImF1ZCI6Imh0dHBzOi8vb2F1dGguem9vbS51cyIsInVpZCI6IkQ2NmNNWHduUkZHVVlDQnFPU1BUTHciLCJuYmYiOjE2MzI2MjMxMTEsImV4cCI6MjEwNTY2MzExMSwiaWF0IjoxNjMyNjIzMTExLCJhaWQiOiJoWVN5RlBRV1IyZVFmUnM5WGZKcmtBIiwianRpIjoiM2I0N2JkN2YtODk1Ny00MjY0LWIwZmMtYzA5ZjhhMjFiNzgyIn0.i6EFLFmJ26fZHEfLYaHp4wrs1n3YlxiEjkhLaYNdcwdJ9tvZ0t11kZtYCuFpQTBpivu-antZFoWIw9f72XtboA","expires_in":3599,"scope":"meeting:master meeting:read:admin meeting:write:admin"}'
        ]);

        Setting::create([
            'zoom_id' => $zoom->id,
        ]);
    }
}
