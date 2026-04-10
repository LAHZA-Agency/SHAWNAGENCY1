@extends('dashboard')
@php
$statusMapping = [
'pending' => 'En Attente',
'approved' => 'Approuvé',
'rejected' => 'Rejeté',
];
@endphp
@section('content')
<section class="">
    <div class="w-full flex flex-wrap items-center justify-between mb-4 gap-4 sm:flex-nowrap">

        <div class="relative dropdown w-full sm:w-fit">
            <a href="#"
                class="border-e px-4 py-2 w-full inline-flex items-center gap-2 rounded-md border shadow-sm border-c-border dropdown-trigger">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 stroke-main" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                </svg>
                <span class="text-sm text-main">
                    Filtrer
                </span>
            </a>
            <form method="GET" action="{{ route('models.filter') }}"
                class="absolute start-0 z-10 mt-2 mb-2 sm:mb-0 w-fit max-w-full sm:max-w-fit rounded-md border border-c-border/80 bg-primary-light shadow-lg dropdown-content hidden">

                <input type="hidden" name="search" value="{{ request('search') }}">

                {{-- Show this section only to admin users --}}
                @if(Auth::user()->role === 'admin')
                <!-- Status Filter -->
                <div class="sm:pb-2 pb-0 p-2">
                    <strong class="block sm:p-2 pb-0 px-2 text-xs font-medium uppercase opacity-65"> Status </strong>
                    <div class="flex flex-wrap sm:flex-nowrap gap-1 sm:gap-2">
                        @foreach($statusMapping as $key => $displayName)
                        <label for="{{ $key }}" class="w-[47%] sm:w-1/3 relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                            <input type="checkbox" id="{{ $key }}" name="status_model[]" value="{{ $key }}"
                                class="hidden peer/draft" {{ in_array($key, request('status_model', [])) ? 'checked' : '' }} />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor"
                                class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span
                                class="text-sm whitespace-nowrap text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/draft:-translate-x-1">
                                {{ $displayName }}
                            </span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Profile de mannequin Filter -->
                <div class="sm:mt-2 p-2 pb-0 pt-0 border border-transparent border-l-c-border">
                    <strong class="block p-2 pb-0 text-xs font-medium uppercase opacity-65"> Profile de
                        mannequin</strong>
                    <div class="flex sm:gap-2 gap-1 flex-wrap sm:flex-nowrap">
                        <label for="false" class="w-[47%] sm:w-1/3 relative sm:my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                            <input type="checkbox" id="false" name="verified[]" value="false"
                                class="hidden peer/false" {{ in_array('false', request('verified', [])) ? 'checked' : '' }} />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/false:opacity-100 peer-checked/false:translate-x-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span
                                class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/false:-translate-x-1">
                                Refusé
                            </span>
                        </label>
                        <label for="true" class="w-[47%] sm:w-1/3 relative sm:my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                            <input type="checkbox" id="true" name="verified[]" value="true" class="hidden peer/true"
                                {{ in_array('true', request('verified', [])) ? 'checked' : '' }} />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/true:opacity-100 peer-checked/true:translate-x-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span
                                class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/true:-translate-x-1">
                                Validé
                            </span>
                        </label>
                        <label for="pendingp" class="whitespace-nowrap w-[47%] sm:w-1/3 relative sm:my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                            <input type="checkbox" id="pendingp" name="verified[]" value="pending"
                                class="hidden peer/pendingp" {{ in_array('pending', request('verified', [])) ? 'checked' : '' }} />
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor"
                                class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/pendingp:opacity-100 peer-checked/pendingp:translate-x-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            <span
                                class="text-sm whitespace-nowrap text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/pendingp:-translate-x-1">
                                En attente
                            </span>
                        </label>
                    </div>
                </div>

                <!-- note filter-->
                <div class="p-2 pt-0 px-3 note-container mt-2 border border-transparent border-l-c-border">
                    <div>
                        <strong class="block pt-2 sm:pt-1 text-xs font-medium uppercase opacity-65">Filtre par évaluation du
                            jury (min/max)</strong>
                    </div>
                    <div class="sm:flex gap-4">
                        <div class="w-full max-w-sm relative pt-1 sm:pt-2">
                            <label class="block mb-1 text-sm">Min</label>
                            <div class="relative input-group">
                                <button type="button"
                                    class="decrease-min-note minus-btn absolute right-9 top-1 rounded-md border border-transparent p-1.5 text-center text-sm transition-all hover:bg-main/10 focus:bg-main/10 active:bg-main/10 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="w-4 h-4">
                                        <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
                                    </svg>
                                </button>
                                <input type="number" name="min"
                                    class="min-note w-full bg-transparent placeholder:text-secondary-light/50 text-sm border border-c-border rounded-md pl-3 pr-20 py-2 transition duration-300 ease focus:outline-none focus:border-c-border hover:border-c-border shadow-sm focus:shadow appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    value="0" value="{{ request('min', 0) }}" step="0.1" />
                                <button type="button"
                                    class="increase-min-note plus-btn absolute right-1 top-1 rounded-md border border-transparent p-1.5 text-center text-sm transition-all hover:bg-main/10 focus:bg-main/10 active:bg-main/10 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="w-4 h-4">
                                        <path
                                            d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="w-full max-w-sm relative pt-1 sm:pt-2">
                            <label class="block mb-1 text-sm">Max</label>
                            <div class="relative input-group">
                                <button type="button"
                                    class="decrease-max-note minus-btn absolute right-9 top-1 rounded-md border border-transparent p-1.5 text-center text-sm transition-all hover:bg-main/10 focus:bg-main/10 active:bg-main/10 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="w-4 h-4">
                                        <path d="M3.75 7.25a.75.75 0 0 0 0 1.5h8.5a.75.75 0 0 0 0-1.5h-8.5Z" />
                                    </svg>
                                </button>
                                <input type="number" name="max"
                                    class="max-note w-full bg-transparent placeholder:text-secondary-light/50 text-sm border border-c-border rounded-md pl-3 pr-20 py-2 transition duration-300 ease focus:outline-none focus:border-c-border hover:border-c-border shadow-sm focus:shadow appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                    value="0" value="{{ request('max', 0) }}" step="0.1" />
                                <button type="button"
                                    class="increase-max-note plus-btn absolute right-1 top-1 rounded-md border border-transparent p-1.5 text-center text-sm transition-all hover:bg-main/10 focus:bg-main/10 active:bg-main/10 disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor"
                                        class="w-4 h-4">
                                        <path
                                            d="M8.75 3.75a.75.75 0 0 0-1.5 0v3.5h-3.5a.75.75 0 0 0 0 1.5h3.5v3.5a.75.75 0 0 0 1.5 0v-3.5h3.5a.75.75 0 0 0 0-1.5h-3.5v-3.5Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="mt-2 mx-2 p-2 px-0 sm:px-2 pb-0 sm:pb-2 border flex flex-wrap sm:flex-nowrap border-transparent border-t-c-border">

                    <!-- gendre identity filter -->
                    <div class="w-full sm:w-1/2 pr-2">
                        <strong class="block pb-0 smpb-2 pt-0 sm:pt-2 p-2 text-xs font-medium uppercase opacity-65"> Sexe : </strong>
                        <div class="">
                            <label for="Homme" class="mt-1 relative w-full flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                <input type="checkbox" id="Homme" name="gender_identity[]" value="Homme"
                                    class="hidden peer/Homme" {{ in_array('Homme', request('gender_identity', [])) ? 'checked' : '' }} />
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor"
                                    class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/Homme:opacity-100 peer-checked/Homme:translate-x-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span
                                    class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/Homme:-translate-x-1">
                                    Masculin
                                </span>
                            </label>
                            <label for="Femme" class="mt-1 relative w-full flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                <input type="checkbox" id="Femme" name="gender_identity[]" value="Femme"
                                    class="hidden peer/Femme" {{ in_array('Femme', request('gender_identity', [])) ? 'checked' : '' }} />
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor"
                                    class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/Femme:opacity-100 peer-checked/Femme:translate-x-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span
                                    class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/Femme:-translate-x-1">
                                    Féminin
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Type filter -->
                    <div class="w-full sm:w-1/2 mt-2 sm:mt-0 sm:pl-2 border border-transparent sm:border-l-c-border border-t-c-border sm:border-t-transparent">
                        <strong class="block pb-0 smpb-2 p-2 text-xs font-medium uppercase opacity-65"> Type : </strong>
                        <div class=" gap-2 flex-nowrap">
                            <label for="Model"  class="mt-1 relative w-full flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                <input type="checkbox" id="Model" name="model_type[]" value="Model"
                                    class="hidden peer/Model" {{ in_array('Model', request('model_type', [])) ? 'checked' : '' }} />
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor"
                                    class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/Model:opacity-100 peer-checked/Model:translate-x-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span
                                    class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/Model:-translate-x-1">
                                    Modèle
                                </span>
                            </label>
                            <label for="Mannequin" class="mt-1 relative w-full flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                <input type="checkbox" id="Mannequin" name="model_type[]" value="Mannequin"
                                    class="hidden peer/Mannequin" {{ in_array('Mannequin', request('model_type', [])) ? 'checked' : '' }} />
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor"
                                    class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/Mannequin:opacity-100 peer-checked/Mannequin:translate-x-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span
                                    class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/Mannequin:-translate-x-1">
                                    Mannequin
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Submit and Reset --}}
                <div class="p-2 flex gap-2">
                    <x-primary-button type="submit" class="w-full select-none text-center justify-center">
                        Appliquer
                    </x-primary-button>
                    <div class="relative inline-block group">
                        <a href="{{ route('dashboard') }}"
                            class="w-min p-0 hover:bg-main/10 flex items-center justify-center px-4 py-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-6 stroke-main-dark">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                            </svg>
                        </a>
                        <span
                            class="absolute left-1/2 transform backdrop-blur-sm -translate-x-1/2 bottom-full mb-2 opacity-0 translate-y-2 transition-all group-hover:opacity-100 group-hover:translate-y-0 bg-main/10 text-main text-sm rounded py-1 px-2 whitespace-nowrap">
                            Réinitialiser
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <!--  filtres dates disponibilite-->
            <form method="GET" action="{{ route('models.search') }}" class="flex items-center gap-2 w-full sm:w-fit">
            <input type="hidden" name="search" value="{{ request('search') }}">

            <input
                type="date"
                id="date_debut"
                name="disponibilite_debut"
                value="{{ request('disponibilite_debut') }}"
                class="rounded-md border border-c-border py-2 px-2 text-sm bg-transparent focus:ring-main focus:border-main"
            >

            <span class="text-sm">au</span>

            <input
                type="date"
                id="date_fin"
                name="disponibilite_fin"
                value="{{ request('disponibilite_fin') }}"
                class="rounded-md border border-c-border py-2 px-2 text-sm bg-transparent focus:ring-main focus:border-main"
            >

            <div class="p-2 flex gap-2">
                <!-- Bouton Valider -->
                <button type="submit"
                    class="px-3 py-2 rounded-md bg-main text-primary text-sm hover:bg-main-dark">
                    Valider
                </button>

                <!-- Icône Réinitialiser -->
                <div class="relative inline-block group">
                    <a href="{{ route('models.search') }}"
                    class="w-min p-0 hover:bg-main/10 flex items-center justify-center px-4 py-2 rounded-md">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6 stroke-main-dark">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                    </a>
                    <span
                        class="absolute left-1/2 transform backdrop-blur-sm -translate-x-1/2 bottom-full mb-2 opacity-0 translate-y-2 transition-all group-hover:opacity-100 group-hover:translate-y-0 bg-main/10 text-main text-sm rounded py-1 px-2 whitespace-nowrap">
                        Réinitialiser
                    </span>
                </div>
            </div>
        </form>

        <form method="GET" action="{{ route('models.search') }}" class="relative w-full sm:w-fit">
            <label for="Search" class="sr-only"> Rechercher </label>
            <input type="text" id="Search" placeholder="rechercher..." name="search" value="{{ request('search') }}"
                class="w-full rounded-md !border-c-border py-2 sm:py-2.5 pe-10 shadow-sm sm:text-sm focus:!ring-main bg-transparent placeholder:text-main/70" />
            <span class="absolute inset-y-0 end-0 grid w-10 place-content-center">
                <button type="submit" class="!ring-main border-main">
                    <span class="sr-only">Search</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 stroke-main" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </span>
        </form>

    </div>

    <!-- Table  -->
    <div class="rounded-lg border border-c-border max-h-full mt-12">
        <div class="overflow-x-auto rounded-t-lg min-h-10 flex items-center">
            <table class="min-w-full divide-y-2 divide-c-border text-sm table-sort">
                <thead class="ltr:text-left rtl:text-right">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                #
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Nom du mannequin
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Photo de profil
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        @can('is-accueillant')
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Document d'identité
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                N° de téléphone
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        @endcan
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Email
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        @can('is-admin')
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Status
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Validation
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        @endcan
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Action
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class=" divide-y divide-c-border" >
                    <!-- all roles other then accueillan/mensurations -->
                    @cannot('is-accueillant')
                    @foreach ($models as $model)
                    <tr class="" data-id="{{ $model->id }}">
                        <td class="whitespace-nowrap px-4 py-2 font-medium ">{{ $model->id }}</td>
                        <td class="whitespace-nowrap px-4 py-2 ">{{ $model->name }}</td>
                        <td class="whitespace-nowrap px-4 py-2"><img loading="lazy"
                                class=" w-16 h-auto aspect-square object-cover rounded-full"
                                src="/storage/{{ $model->mannequinCandidate->profile }}"
                                alt="{{ $model->name }} profile"></td>
                        <td class="whitespace-nowrap px-4 py-2 ">{{ $model->email }}</td>

                        @can('is-admin')
                        <td class="whitespace-nowrap px-4 py-2">
                            {{$statusMapping[$model->mannequinCandidate->status_model] ?? $model->mannequinCandidate->status_model}}
                        </td>
                        <td class="min-w-32">
                            <span class="flex overflow-hidden justify-center rounded-md border border-c-border ">
                                <button type="button" class="inline-block p-3 w-2/3 cursor-default whitespace-nowrap">
                                    @if ($model->mannequinCandidate->verified === 'true')
                                    Validé
                                    @elseif ($model->mannequinCandidate->verified === 'false')
                                    Refusé
                                    @elseif ($model->mannequinCandidate->verified === 'pending')
                                    En attente
                                    @endif
                                </button>
                                <button type="button"
                                    class="flex justify-center items-center p-3 w-1/3 hover:bg-main/20 focus:relative change-model-status border-s border-c-border"
                                    title="Accepter/refuser Le mannequin" data-id="{{ $model->mannequinCandidate->id}}"
                                    data-status="{{$model->mannequinCandidate->verified}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                            </span>
                        </td>
                        @endcan

                        <td>
                            <div class="flex justify-center items-center my-2">
                                @can('is-admin')
                                <!-- delete model -->
                                <button class="delete-member-btn px-4" data-id="{{ $model->mannequinCandidate->id }}"
                                    data-action="{{ route('model.remove', $model->mannequinCandidate->id) }}"
                                    data-type="model">
                                    <svg class="stroke-accent hover:stroke-accent-dark size-5"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>


                                @endcan

                                @can('is-photographe')
                                <a class=" flex justify-center items-center whitespace-nowrap mr-2 rounded-lg px-4 py-2 transition closeDialogBtnsMS text-primary text-sm hover:bg-main-dark bg-main"
                                    href="{{ route('model.add.photos', $model->mannequinCandidate->id) }}">
                                    Ajouter Les photos
                                </a>
                                @elseif(auth()->user()->can('is-mensurations'))
                                <a class="flex justify-center items-center rounded-lg whitespace-nowrap mr-2 px-4 py-2 transition closeDialogBtnsMS text-primary text-sm hover:bg-main-dark bg-main"
                                    href="{{ route('model.create.measurements', $model->mannequinCandidate->id) }}">
                                    Ajouter les mensurations
                                </a>
                                @else
                                <!-- Modify model -->
                                <a
                                    title="@if(auth()->user()->can('is-admin')) Modifier Mannequin @elseif(auth()->user()->role == 'jury') Ajouter une note @else Ajouter votre commentaire @endif"
                                    class="border border-transparent px-3 {{ auth()->user()->can('is-admin') ? 'border-l-c-border' : '' }}"
                                    href="{{ route('model.modify', $model->id) }}">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="hover:stroke-main-dark stroke-main size-5">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                                <!-- View Model -->
                                <a class="border border-transparent px-3 relative flex justify-center items-center group border-l-c-border"
                                    href="{{ route('model.view', $model->slug) }}" title="voir détails du mannequin">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class=" stroke-main size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endcannot

                    <!-- accueillant only  -->
                    @can('is-accueillant')
                    @foreach ($models as $model)
                    <tr class="" data-id="{{ $model->id }}">
                        <td class="whitespace-nowrap px-4 py-2 font-medium ">{{ $model->id }}</td>
                        <td class="whitespace-nowrap px-4 py-2 ">{{ $model->name }}</td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <a href="/storage/{{$model->mannequinCandidate?->profile }}"
                                data-fancybox="{{ $model->mannequinCandidate->profile }}">
                                <img loading="lazy"
                                    class="w-16 h-auto aspect-square object-cover rounded-full cursor-pointer hover:brightness-75 transition-all duration-100"
                                    src="/storage/{{ $model->mannequinCandidate?->profile }}"
                                    alt="{{ $model->name }} profile">
                            </a>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2">

                            <div class="flex items-center gap-4 justify-start 2xl:w-fit w-full p-2 px-4 rounded-xl hover:bg-primary border border-c-border/50">
                                <a target="blank" href="/storage/{{ $model->mannequinCandidate->identity_document }}" class="w-full flex items-center gap-4 justify-start ">
                                    <div class="col-span-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 stroke-secondary">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                    </div>
                                    <div class="gap-4 justify-between w-full grid grid-cols-12 items-center text-secondary hover:underline">
                                        <span class="text-secondary col-span-10">
                                            <p>Pièce d'identité</p>
                                            <p class="text-secondary-light text-sm">Voir le document</p>
                                        </span>
                                        <div class="col-span-2 flex justify-end ">
                                            <svg class=" stroke-secondary size-6 cursor-pointer hover:stroke-secondary-light" data-document-id="id" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-4 py-2 ">{{ $model->mannequinCandidate->tel }}</td>
                        <td class="whitespace-nowrap px-4 py-2 ">{{ $model->email }}</td>
                        <td>
                            <div class="flex justify-center items-center p-2">
                                <div class="flex gap-4">
                                    <a href="{{ route('dashboard.model_verify', ['id' => $model->id, 'status' => 'approved']) }}"
                                        class="block rounded-lg px-4 py-2 whitespace-nowrap transition text-primary-light text-sm bg-main hover:bg-main-dark focus:ring-1 focus:bg-main-dark ring-offset-2 ring-main">
                                        Approuvé Candidat
                                    </a>
                                    <a href="{{ route('dashboard.model_verify', ['id' => $model->id, 'status' => 'rejected']) }}"
                                        class="block rounded-lg px-4 py-2 transition text-main text-sm bg-primary hover:bg-main/20 focus:ring-1 focus:bg-main/20 ring-offset-2 ring-main">
                                        Rejeté
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endcan

                </tbody>
            </table>
        </div>
    </div>

    <!-- pagination -->
    <div>
        {{ $models->links() }}
    </div>
</section>
<div
    class="alertDialogMS fixed inset-0 w-full h-full items-center justify-center bg-black/50 p-2 hidden backdrop-blur-sm z-[99999]">
    <div role="alert"
        class="alertDialogContentMS rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 w-full sm:max-w-96 min-w-72">
        <div class="flex items-start gap-4">

            <form action="{{route('model.update.verified', '0')}}" method="POST" class="flex-1">
                @csrf
                <strong class="block font-medium">Choisir le status</strong>
                <div class="flex w-full gap-2">
                    <label for="verified_true"
                        class="w-1/2 relative my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="radio" id="verified_true" name="verified" value="true" class="hidden peer/true" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="stroke-secondary size-3 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/true:opacity-100 peer-checked/true:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span
                            class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/true:-translate-x-1">
                            Validé
                        </span>
                    </label>
                    <label for="verified_false"
                        class="w-1/2 relative my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="radio" id="verified_false" name="verified" value="false"
                            class="hidden peer/false" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="stroke-secondary size-3 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/false:opacity-100 peer-checked/false:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span
                            class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/false:-translate-x-1">
                            Refusé
                        </span>
                    </label>
                    <label for="verified_pending"
                        class="w-1/2 relative my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="radio" id="verified_pending" name="verified" value="pending"
                            class="hidden peer/pending" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="stroke-secondary size-9 h-3 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/pending:opacity-100 peer-checked/pending:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span
                            class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/pending:-translate-x-1 whitespace-nowrap">
                            En Attente
                        </span>
                    </label>
                </div>
                <div class="mt-4 flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg hover:bg-main-dark bg-main px-4 py-2">
                        <span class="text-sm text-primary uppercase -mb-[2px]">Modifier le statut</span>

                    </button>
                </div>
            </form>
            <button class="transition hover:opacity-80 closeDialogBtnsMS">
                <span class="sr-only">Dismiss popup</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
<div
    class="alert-dialog fixed inset-0 p-2 w-full h-full items-center justify-center bg-black/50 hidden backdrop-blur-sm z-[99999]" id="delete-confirm-modal" data-user-role="{{ auth()->user()->role }}">

    <div role="alert"
        class="alert-dialog-content rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 max-w-96">
        <div class="flex items-start gap-4">

            <div class="flex-1">
                <strong class="block font-medium ">Êtes-vous sûr ?</strong>

                <p class="mt-1 text-sm ">
                    Cette action est irréversible, vous ne pouvez pas revenir en arrière lorsque vous l'avez supprimée.
                </p>

                <div class="mt-4 flex gap-2">
                    <a href="#"
                        class="inline-flex items-center gap-2 rounded-lg hover:bg-main-dark bg-main px-4 py-1 delete-action-btn">
                        <span class="text-sm text-primary uppercase -mb-[2px] content"> Oui supprimer </span>
                        <svg class=" hover:brightness-200 size-0 trash" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <svg version="1.1" id="L9" class="hover:brightness-200 size-9 -m-2 loading hidden"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                            y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#fff"
                                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                            </path>
                        </svg>
                    </a>
                    <form action="" method="POST" class="delte-model-form">
                        @csrf
                        @method('DELETE')
                        <button type='submit'
                            class="inline-flex items-center gap-2 rounded-lg hover:bg-main-dark bg-main px-4 py-2 delete-action-btn">
                            <span class="text-sm text-primary uppercase -mb-[2px] content"> Oui supprimer</span>
                        </button>
                    </form>
                    <button
                        class="block rounded-lg px-4 py-2 transition close-dialog-btn text-main text-sm hover:bg-main/10">
                        Annuler
                    </button>
                </div>
            </div>

            <button class="transition hover:opacity-80 close-dialog-btn">
                <span class="sr-only">Dismiss popup</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
@if (session('success'))
<div
    class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl border p-4 border-success text-success translate-y-[150%] session-alert">
    <div role="alert" class="alert alert-success flex gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="">{{ session('success') }}</span>
    </div>
</div>
{{ session()->forget('success') }}
@endif
@if(session('wordpress_response'))
<div class="alert alert-info">
    <strong>WordPress Response:</strong>
    @if(isset(session('wordpress_response')['message']))
    {{ session('wordpress_response')['message'] }}
    @elseif(isset(session('wordpress_response')['error']))
    {{ session('wordpress_response')['error'] }}
    @else
    {{ json_encode(session('wordpress_response')) }}
    @endif
</div>
@endif
@if ($errors->any())
<div
    class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error translate-y-[150%] text-error session-alert">
    <div role="alert" class="alert alert-error flex gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
            Erreur:
        </span>
    </div>
    <ul class="pl-8">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
{{-- Modal non autorisé pour bookeuse --}}
<div id="unauthorized-modal"
    class="fixed inset-0 p-2 w-full h-full items-center justify-center bg-black/50 hidden backdrop-blur-sm z-[999999]">
    <div class="unauthorized-content rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 max-w-96">
        <div class="flex items-start gap-4">
            <div class="flex-1">
                <strong class="block font-medium">Action non autorisée</strong>
                <p class="mt-1 text-sm">Cette action n'est pas autorisée. Vous n'avez pas les permissions nécessaires pour supprimer un mannequin.</p>
                <div class="mt-4">
                    <button class="close-unauthorized block rounded-lg px-4 py-2 transition text-main text-sm hover:bg-main/10 border border-c-border">
                        Fermer
                    </button>
                </div>
            </div>
            <button class="close-unauthorized transition hover:opacity-80">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endsection
