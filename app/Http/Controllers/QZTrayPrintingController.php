<?php

namespace App\Http\Controllers;

use App\Facades\QZTray;
use Illuminate\Http\Request;

class QZTrayPrintingController extends Controller
{
    public function sign(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string'
        ]);

        $signature = QZTray::signMessage($validated['message']);

        return response($signature, 200, [
            'Content-type' => 'text/plain'
        ]);
    }

    public function certificate()
    {
        $CERTIFICATE_PATH = config('qzTray.certificate_path');
        $certificate = file_get_contents($CERTIFICATE_PATH);

        return response($certificate, 200, [
            'Content-type' => 'text/plain'
        ]);
    }
}
