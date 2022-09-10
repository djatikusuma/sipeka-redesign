<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function print_certificate()
    {
        // try {
            $image = new \claviska\SimpleImage();

            $image
                ->fromString(file_get_contents(storage_path("app/public/certificate_tmp/template_sertifikat.png")))
                ->text("Rangga Djatikusuma Lukman", [
                    "xOffset" => 200,
                    "yOffset" => 600
                ])
                ->toScreen();

        // }catch( $err){
        //     echo $err->getMessage();
        // }
    }
}
