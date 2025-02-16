<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entry;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();

        $entriesToday = Entry::whereDate('created_at', $today)->count();
        $entriesWeek = Entry::whereBetween('created_at', [$weekStart, Carbon::now()])->count();
        $entriesMonth = Entry::whereBetween('created_at', [$monthStart, Carbon::now()])->count();

        $employees = User::with(['entries'])->get();

        // Générer les données pour le graphique
        $chartData = Entry::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return view('admin.dashboard', compact('entriesToday', 'entriesWeek', 'entriesMonth', 'employees', 'chartData'));
    }

    public function details($id)
    {
        $user = User::findOrFail($id);
        $entries = $user->entries()->orderBy('created_at', 'desc')->get();

        $totalHours = $entries->whereNotNull('heure_sortie')->sum(fn ($entry) => 
            Carbon::parse($entry->heure_sortie)->diffInHours(Carbon::parse($entry->created_at))
        );

        return view('admin.details', compact('user', 'entries', 'totalHours'));
    }
}
