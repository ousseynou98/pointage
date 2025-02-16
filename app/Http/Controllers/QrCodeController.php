<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QrCode as QrCodeModel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    public function generate()
    {
        $code = uniqid(); // Génère un code unique
        $location = request('location'); // Exemple: Entrée principale

        $qr = QrCodeModel::create([
            'code' => $code,
            'location_name' => $location
        ]);

        return view('qr.show', ['qrCode' => $code, 'location' => $location]);
    }

    public function show(QrCodeModel $qr)
    {
        return QrCode::size(200)->generate(route('qr.validate', ['code' => $qr->code]));
    }
}