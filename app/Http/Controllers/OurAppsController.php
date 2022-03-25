<?php

namespace App\Http\Controllers;

use App\Models\OurApp;
use Illuminate\Http\Request;

class OurAppsController extends Controller
{
    public function ourApps($app)
    {
        if ($app == 'CKG-BarcoderXML') {

            $config = [
                'template' => '<item></item>',
                'rowName' => 'name'
            ];

            $app = OurApp::where('name', 'CKG-Barcoder')->first();

            $data = [
                'version' => $app->current_version,
                'url' => 'https://ckgsis.com/files/apps/CKG-Barcoder/app.zip',
                'mandatory' => 'false'
            ];

            return response()->xml($data, 200, $config);

        } else {

            $app = OurApp::where('name', $app)->first();
            if ($app == null)
                return response()->json(['status' => 0, 'message' => 'No App']);

            return response()
                ->json(['status' => 1, 'app' => $app]);
        }

    }
}
