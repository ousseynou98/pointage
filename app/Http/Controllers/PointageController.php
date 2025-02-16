<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\QrCode;
use Illuminate\Support\Facades\Auth;

class PointageController extends Controller
{

    public function index()
    {
        return view('pointage.index');
    }

    public function __construct()
    {
        $this->middleware('auth:sanctum')->only(['store', 'retourSortie', 'historique']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:entrée,sortie_provisoire,descente',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'qrCode' => 'required|string'
        ]);

        // Vérifier si le QR Code existe dans la base
        $qr = QrCode::where('code', $request->qrCode)->first();

        if (!$qr) {
            return response()->json(['message' => 'QR Code invalide'], 400);
        }

        // Enregistrer l'entrée ou la sortie
        Entry::create([
            'user_id' => Auth::id(),
            'type' => (string) $request->type,  // Assurer que 'type' est une chaîne
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'heure_sortie' => ($request->type === 'sortie_provisoire') ? now() : null
        ]);
        

        return response()->json(['message' => 'Pointage enregistré avec succès !']);
    }

    public function retourSortie()
    {
        $entry = Entry::where('user_id', Auth::id())
                      ->where('type', 'sortie_provisoire')
                      ->whereNull('heure_retour')
                      ->latest()
                      ->first();

        if ($entry) {
            $entry->update(['heure_retour' => now()]);
            return response()->json(['message' => 'Retour enregistré avec succès']);
        }

        return response()->json(['message' => 'Aucune sortie provisoire en attente'], 404);
    }

    public function historique()
    {
        $entries = Entry::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return response()->json(['historique' => $entries]);
    }
}


