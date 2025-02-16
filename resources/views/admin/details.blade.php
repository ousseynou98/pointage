@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6">
    <h2 class="text-center text-2xl font-bold mb-6">Détails de {{ $user->name }}</h2>

    <div class="grid grid-cols-3 gap-4">
        <div class="p-4 bg-green-200 rounded shadow">
            <h3 class="text-lg font-semibold">Aujourd'hui</h3>
            <p class="text-3xl font-bold">{{ $entries->where('created_at', '>=', now()->startOfDay())->count() }}</p>
        </div>
        <div class="p-4 bg-blue-200 rounded shadow">
            <h3 class="text-lg font-semibold">Cette semaine</h3>
            <p class="text-3xl font-bold">{{ $entries->where('created_at', '>=', now()->startOfWeek())->count() }}</p>
        </div>
        <div class="p-4 bg-purple-200 rounded shadow">
            <h3 class="text-lg font-semibold">Ce mois</h3>
            <p class="text-3xl font-bold">{{ $entries->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
        </div>
    </div>

    <h3 class="mt-6 text-xl font-bold">Historique des Pointages</h3>
    <table id="historyTable" class="w-full mt-4 border">
        <thead>
            <tr class="bg-gray-300">
                <th class="p-2 border">Date</th>
                <th class="p-2 border">Type</th>
                <th class="p-2 border">Heure</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
                <tr class="text-center">
                    <td class="p-2 border">{{ $entry->created_at->format('d/m/Y H:i') }}</td>
                    <td class="p-2 border">{{ $entry->type }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
