@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6">
    <h2 class="text-center text-2xl font-bold mb-6">Tableau de Bord - Pointage RH</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-4 bg-green-200 rounded shadow">
            <h3 class="text-lg font-semibold">Aujourd'hui</h3>
            <p class="text-3xl font-bold">{{ $entriesToday }}</p>
        </div>
        <div class="p-4 bg-blue-200 rounded shadow">
            <h3 class="text-lg font-semibold">Cette semaine</h3>
            <p class="text-3xl font-bold">{{ $entriesWeek }}</p>
        </div>
        <div class="p-4 bg-purple-200 rounded shadow">
            <h3 class="text-lg font-semibold">Ce mois</h3>
            <p class="text-3xl font-bold">{{ $entriesMonth }}</p>
        </div>
    </div>

    <h3 class="mt-6 text-xl font-bold">Statistiques des Pointages</h3>
    <!-- <canvas id="chartPointage" class="mt-4"></canvas> -->

    <h3 class="mt-6 text-xl font-bold">Liste des employés</h3>
    <table id="employeesTable" class="w-full mt-4 border">
        <thead>
            <tr class="bg-gray-300">
                <th class="p-2 border">Nom</th>
                <th class="p-2 border">Dernier pointage</th>
                <th class="p-2 border">Total d'heures</th>
                <th class="p-2 border">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
                <tr class="text-center">
                    <td class="p-2 border">{{ $employee->name }}</td>
                    <td class="p-2 border">
                        {{ optional($employee->entries->last())->created_at ?? 'Aucun pointage' }}
                    </td>
                    <td class="p-2 border">
                        {{ $employee->entries->sum(fn($entry) => \Carbon\Carbon::parse($entry->heure_sortie)->diffInHours(\Carbon\Carbon::parse($entry->created_at))) ?? 0 }} h
                    </td>
                    <td class="p-2 border">
                        <a href="{{ route('admin.details', $employee->id) }}" class="bg-blue-500 text-white px-3 py-1 rounded">Détails</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let ctx = document.getElementById('chartPointage').getContext('2d');
        let chartData = {
            labels: {!! json_encode($chartData->pluck('date')) !!},
            datasets: [{
                label: 'Nombre d\'entrées/sorties',
                data: {!! json_encode($chartData->pluck('count')) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        };
        new Chart(ctx, { type: 'bar', data: chartData });

        $('#employeesTable').DataTable();
    });
</script>
@endsection
