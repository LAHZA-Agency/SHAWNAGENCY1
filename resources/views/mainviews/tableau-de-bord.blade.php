@extends('dashboard')
@section('content')
<section class="">
    <!-- Overview -->
    @can('is-admin')
    <h1
        class="text-lg font-bold mb-4 p-2 rounded-lg">
        <span>
            Suivi et statistiques des mannequins
        </span>
    </h1>
    <div class="grid grid-cols-1 md:grid-cols-3 grid-rows-3 gap-4 mb-4 statistiques-cont">
        <!-- Total Members Card -->
        <div class="bg-primary-light col-span-2 md:col-span-1 p-4 rounded-xl border border-c-border shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium">Total Membres</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-main/20">
                    Total
                </span>
            </div>
            <p class="text-2xl font-bold">{{ $statistics['total_members'] }}</p>
            <div class="flex items-center mt-2 text-xs text-secondary/70">
                <span class="text-secondary/70">{{ $statistics['active_members'] }} actifs</span>
                <span class="mx-1 text-secondary/70">•</span>
                <span class="text-secondary/70">{{ $statistics['inactive_members'] }} inactifs</span>
            </div>
        </div>

        <!-- Models Growth Chart -->
        <div class="bg-primary-light p-4 rounded-xl border border-c-border shadow-sm col-span-2 row-span-3 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium">Croissance</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-main/20">
                    Comparaison
                </span>
            </div>

            <canvas id="modelsGrowthChart" data-this-month="{{ json_encode($statistics['this_month_weeks']) }}"
                data-last-month="{{ json_encode($statistics['last_month_weeks']) }}" class="w-full !h-72">
            </canvas>
        </div>

        <!-- New Models This Month -->
        <div class="col-span-2 sm:col-span-1 bg-primary-light p-4 rounded-xl border border-c-border shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium">Nouveaux ce mois</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-main/20">
                    Mensuel
                </span>
            </div>
            <p class="text-2xl font-bold">{{ $statistics['new_models_this_month'] }}</p>
            @php
            $percentChange = $statistics['models_last_month'] ?
            round((($statistics['new_models_this_month'] - $statistics['models_last_month']) / $statistics['models_last_month']) * 100) :
            100;
            @endphp
            <div class="flex items-center mt-2 text-xs">
                <span class="{{ $percentChange >= 0 ? 'text-success-dark' : 'text-error-dark' }}">
                    {{ $percentChange }}%
                    @if($percentChange >= 0)
                    <span>▲</span>
                    @else
                    <span>▼</span>
                    @endif
                </span>
                <span class="ml-1 text-secondary/70">vs mois dernier</span>
            </div>
        </div>

        <!-- Total Models Card -->
        <div class="col-span-2 sm:col-span-1 bg-primary-light p-4 rounded-xl border border-c-border shadow-sm">
            <div class="flex items-center justify-between mb-2">
                <h3 class="text-sm font-medium">Total Mannequins</h3>
                <span class="text-xs px-2 py-1 rounded-full bg-main/20">
                    Total
                </span>
            </div>
            <p class="text-2xl font-bold">{{ $statistics['total_models'] }}</p>
            <div class="flex items-center mt-2 text-xs">
                <span class="text-secondary/70">{{ $statistics['verified_models'] }} vérifiés</span>
                <span class="mx-1 text-secondary/70">•</span>
                <span class="text-secondary/70">{{ $statistics['unverified_models'] }} non vérifiés</span>
            </div>
        </div>
    </div>
    @endcan
</section>

@endsection