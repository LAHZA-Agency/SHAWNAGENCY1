@extends('dashboard')
@php
$statusMapping = [
'pending' => 'En Attente',
'approved' => 'Approuvé',
'rejected' => 'Rejeté',
];
@endphp
@section('content')
<!-- content -->
<main class="-mt-8">
    <!-- index -->
    <div class="flex gap-2 sm:gap-8 border-c-border border-b-[1px] mb-6 z-[99] bg-primary-light sticky -top-8 pt-4 overflow-x-scroll sm:overflow-x-auto pb-1 sm:pb-0">
        <button class=" tabTrigger h-min py-1 px-2 text-secondary border-b-2 border-secondary/70 font-normal"
            data-target="tab1" id="tab1-btn">
            Profil
        </button>
        <button class="tabTrigger h-min py-1 px-2 text-secondary-light/70 hover:text-secondary-light border-b-2 border-transparent font-normal"
            data-target="tab4" id="tab4-btn">
            Galerie
        </button>

        @can('is-admin')
        <button class="tabTrigger h-min py-1 px-2 text-secondary-light/70 hover:text-secondary-light border-b-2 border-transparent font-normal"
            data-target="tab2" id="tab2-btn">
            Observations
        </button>
        @endcan

        @cannot('is-admin')
        @cannot('is-jury')
        <button class="tabTrigger h-min py-1 px-2 text-secondary-light/70 hover:text-secondary-light border-b-2 border-transparent font-normal whitespace-nowrap"
            data-target="tab3" id="tab3-btn">
            Ajoutez votre commentaire
        </button>
        @endcannot
        @endcannot

        <a class="ml-auto px-3 relative flex justify-center items-center group"
            href="{{ route('model.view', $model->slug) }}" title="voir détails du mannequin">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class=" stroke-main size-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
            </svg>
        </a>
    </div>

    <!-- Info -->
    <section class="flex gap-4 tab flex-wrap lg:flex-nowrap" id="tab1">
        @php
        $order1 = 'order-2';
        $order2 = 'order-1';

        if (auth()->user()->can('is-admin') || auth()->user()->can('is-jury')) {
        $order1 = 'order-1';
        $order2 = 'order-2';
        }
        @endphp
        <div class="{{ $order1 }} w-full lg:w-2/3 bg-primary rounded-lg p-3 sm:p-6">
            <!-- Model Inof -->
            @can('is-admin')
            <div>
                <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                    <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Informations générales </h2>
                    <div class="w-full bg-main h-[1px] ml-4"></div>
                </div>
                <form method="POST" action="{{ route('model.update', ['id' => $model->id]) }}" enctype="multipart/form-data" class="v-form profile flex flex-wrap md:flex-nowrap 2xl:gap-12 gap-6 [&_label]:pb-1 [&_label]:font-normal [&_input]:w-full [&_input]:text-sm [&_input]:!bg-primary-light [&>*]:-mt-6 pt-8 w-full items-start justify-start">
                    @csrf
                    @method('PUT')
                    <div class="w-full md:w-fit flex gap-4 sm:justify-between justify-around md:block flex-wrap sm:flex-nowrap">
                        <!-- Profiel picture -->
                        <div>
                            <div class="lazy-image-container relative aspect-square w-fit md:w-full">
                                <div class="lazy-image-loader absolute rounded-xl inset-0 flex items-center justify-center bg-primary-light transition-opacity duration-300">
                                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                                <div class="aspect-square group profile-img relative w-32 m-auto group">
                                    <label for="profile_picture" title="Remplacer l'image du profil" class="cursor-pointer transition-all duration-300 absolute -top-1 right-0 translate-x-2 p-1 z-50 opacity-0 group-hover:opacity-100 -translate-y-1 rounded-full aspect-square bg-main hover:bg-primary group/hl border border-primary/0 hover:border-main">
                                        <svg class="stroke-primary group-hover/hl:stroke-main size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                        <input type="file" class="hidden v-form-input" accept=".jpeg, .jpg, .png, .webp, .tiff, .raw, .dng" name="profile_picture" id="profile_picture">
                                    </label>
                                    <a href="/storage/{{ $model->mannequinCandidate->profile }}" data-fancybox="profile" class="">
                                        <img
                                            loading="lazy"
                                            class="lazy-image profile-p-img w-full h-auto rounded-xl aspect-square object-cover cursor-pointer hover:brightness-75 transition-all duration-100"
                                            src="/storage/{{ $model->mannequinCandidate->profile }}"
                                            alt="{{ $model->name }} profile">
                                    </a>
                                </div>
                            </div>
                            <span class="profile_picture-error-message text-error text-xs"></span>
                        </div>

                        <!-- status -->
                        <div class="flex flex-col gap-1 md:mt-4">
                            <label class="block font-medium text-sm" for="password">
                                État actuel: <small>({{ __($statusMapping[$model->mannequinCandidate->status_model] ?? 'Statut Inconnu') }})</small>
                            </label>

                            @foreach($statusMapping as $key => $displayName)
                            <label for="{{ $key }}"
                                class="relative mb-2 flex items-center justify-start gap-2 hover:bg-primary-dark px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                <input type="radio" id="{{ $key }}" name="status_model" value="{{ $key }}"
                                    class="hidden peer/status_model" {{ $model->mannequinCandidate->status_model === $key ? 'checked' : '' }} />
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor"
                                    class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/status_model:opacity-100 peer-checked/status_model:translate-x-0">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                                <span
                                    class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/status_model:-translate-x-1">
                                    {{ $displayName }}
                                </span>
                            </label>
                            @endforeach
                        </div>

                        <!-- Type filter -->
                        <div class="border-transparent border-t-c-border md:border md:pt-4 md:mt-4 w-full sm:w-fit">
                            <label class="block font-medium text-sm"> Type : </label>
                            <div class="flex gap-2 sm:flex-col flex-wrap">
                                <label for="Model" class="mt-1 relative sm:w-full flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                    <input type="radio" id="Model" name="model_type" value="Model"
                                        class="hidden peer/model_type" {{ $model->mannequinCandidate->model_type == 'Model' ? 'checked' : '' }} />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor"
                                        class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/model_type:opacity-100 peer-checked/model_type:translate-x-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    <span
                                        class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/model_type:-translate-x-1">
                                        Modèle
                                    </span>
                                </label>
                                <label for="Mannequin" class="mt-1 relative sm:w-full flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                    <input type="radio" id="Mannequin" name="model_type" value="Mannequin"
                                        class="hidden peer/model_type" {{ $model->mannequinCandidate->model_type == 'Mannequin' ? 'checked' : '' }} />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor"
                                        class="stroke-secondary w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/model_type:opacity-100 peer-checked/model_type:translate-x-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    <span
                                        class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/model_type:-translate-x-1">
                                        Mannequin
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-3 gap-6 2xl:pl-12 pt-10 sm:pt-0">

                        <!-- username -->
                        <div>
                            <x-input-label for="username" :value="__('Nom du mannequin :')" />
                            <x-text-input id="username" class="{{ $errors->has('username') ? '!border-error' : '' }}" type="text" value="{{ $model->name }}" name="username" required />
                            <x-input-error :messages="$errors->get('username')" class="mt-2" />
                            <span class="username-error-message text-error text-xs"></span>
                        </div>

                        <!-- email -->
                        <div>
                            <x-input-label for="email" :value="__('Email :')" />
                            <x-text-input id="email" class="{ $errors->has('email') ? '!border-error' : '' }}" type="email" value="{{ $model->email }}" name="email" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            <span class="email-error-message text-error text-xs"></span>
                        </div>

                        <!-- Tel -->
                        <div>
                            <x-input-label for="tel" :value="__('Téléphone :')" />
                            <x-text-input
                                id="tel" class="{ $errors->has('tel') ? '!border-error' : '' }}"
                                type="tel" name="tel" required pattern="\+?[0-9]{10,15}" inputmode="numeric" minlength="8" maxlength="15"
                                value="{{ $model->mannequinCandidate->tel }}" />
                            <x-input-error :messages="$errors->get('tel')" class="mt-2" />
                            <span class="tel-error-message text-error text-xs"></span>
                        </div>

                        <!-- Sexe -->
                        <div class="">
                            <x-input-label for="gender_identity" :value="__('Sexe :')" />
                            <div class="options flex sm:justify-between items-start flex-wrap gap-4">
                                <label for="femme" class="relative flex items-center justify-start gap-2 hover:bg-primary-dark px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                    <input type="radio" id="femme" name="gender_identity" {{ $model->mannequinCandidate->gender_identity === 'Femme' ? 'checked' : '' }} value="Femme" class="hidden peer/draft" />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-main w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    <span class="text-sm select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:text-main peer-checked/draft:-translate-x-1">
                                        {{ __('Féminin') }}
                                    </span>
                                </label>
                                <label for="homme" class="hover:bg-primary-dark relative flex items-center justify-start gap-2 px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                                    <input type="radio" id="homme" name="gender_identity" {{ $model->mannequinCandidate->gender_identity === 'Homme' ? 'checked' : '' }} value="Homme" class="hidden peer/draft" />
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-main w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    <span class="text-sm select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:text-main peer-checked/draft:-translate-x-1">
                                        {{ __('Masculin') }}
                                    </span>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Mot de passe :')" />
                            <div class="relative">
                                <x-text-input
                                    id="password"
                                    class="pr-10 {{ $errors->has('password') ? 'border-error' : '' }}"
                                    type="password"
                                    name="password" />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer password-toggle"
                                    data-password-input="password">
                                    <!-- Eye Icon (Password Hidden) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 eye-icon show">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <!-- Eye-Slash Icon (Password Visible) -->
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 eye-icon hide hidden">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            <span class="password-error-message text-error text-xs"></span>
                        </div>

                        <!-- Languages -->
                        <div class="">
                            <x-input-label for="langues_parlees" :value="__('Langues parlées')" />
                            <x-text-input id="langues_parlees" class="block mt-1 w-full" type="text" name="langues_parlees" value="{{ $model->mannequinCandidate->langues_parlees}} " />
                        </div>

                        <!-- Physical Characteristics -->
                        <div>
                            <x-input-label for="couleur_cheveux" :value="__('Couleur des cheveux')" />
                            <x-text-input id="couleur_cheveux" class="block mt-1 w-full" type="text" name="couleur_cheveux" value="{{ $model->mannequinCandidate->couleur_cheveux}}" />
                        </div>
                        <div>
                            <x-input-label for="couleur_yeux" :value="__('Couleur des yeux')" />
                            <x-text-input id="couleur_yeux" class="block mt-1 w-full" type="text" name="couleur_yeux" value="{{ $model->mannequinCandidate->couleur_yeux}}" />
                        </div>

                       <!-- Extra  -->
                        <div class="sm:col-span-2 2xl:col-span-3 grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="sport_pratique" :value="__('Sport pratiqué')" />
                                <x-text-input 
                                    id="sport_pratique" 
                                    class="block mt-1 w-full" 
                                    type="text" 
                                    name="sport_pratique" 
                                    placeholder="Ex: Football, Natation, Yoga..." 
                                    value="{{ $model->mannequinCandidate->sport_pratique }}" 
                                />
                            </div>

                            <div>
                                <x-input-label for="piercings" :value="__('Piercings')" />
                                <x-text-input 
                                    id="piercings" 
                                    class="block mt-1 w-full" 
                                    type="text" 
                                    name="piercings" 
                                    placeholder="Ex: Oreilles, Nez, Nombril..." 
                                    value="{{ $model->mannequinCandidate->piercings }}" 
                                />
                            </div>

                            <div>
                                <x-input-label for="tatouages" :value="__('Tatouages')" />
                                <x-text-input 
                                    id="tatouages" 
                                    class="block mt-1 w-full" 
                                    type="text" 
                                    name="tatouages" 
                                    placeholder="Ex: Bras droit, Dos, Cheville..." 
                                    value="{{ $model->mannequinCandidate->tatouages }}" 
                                />
                            </div>
                        </div>


                        <!-- instagram_link -->
                        <div class="sm:col-span-2 2xl:col-span-3">
                            <x-input-label for="instagram_link" :value="__('Lien Instagram :')" />
                            <x-text-input
                                id="instagram_link" class="{ $errors->has('instagram_link') ? '!border-error' : '' }}"
                                type="text" name="instagram_link"
                                value="{{ $model->mannequinCandidate->instagram_link }}" />
                            <x-input-error :messages="$errors->get('instagram_link')" class="mt-2" />
                            <span class="instagram_link-error-message text-error text-xs"></span>
                        </div>
                               {{-- des disponibilités --}}
                         <div>
                            <x-input-label :value="__('Disponibilité')" />

                            <div class="flex gap-2 mt-1">
                                <input
                                    type="date"
                                    id="date_debut"
                                    name="disponibilite_debut"
                                    value="{{ old('disponibilite_debut', optional($model->mannequinCandidate)->disponibilite_debut?->format('Y-m-d')) }}"
                                    class="block w-full rounded-lg border border-c-border p-2 text-sm focus:ring-main focus:border-main"
                                >

                                <span class="self-center text-sm">au</span>

                                <input
                                    type="date"
                                    id="date_fin"
                                    name="disponibilite_fin"
                                    value="{{ old('disponibilite_fin', optional($model->mannequinCandidate)->disponibilite_fin?->format('Y-m-d')) }}"
                                    class="block w-full rounded-lg border border-c-border p-2 text-sm focus:ring-main focus:border-main"
                                >
                            </div>

                            @error('disponibilite_debut')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror

                            @error('disponibilite_fin')
                                <p class="text-error text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                        <!-- Identity document -->
                        <div class="sm:col-span-2 2xl:col-span-3 flex flex-wrap 2xl:flex-nowrap justify-between items-start">
                            <!-- identity document -->
                            <div>
                                <div class="flex items-center gap-4 justify-start 2xl:w-fit w-full p-2 px-4 rounded-xl hover:bg-primary border border-c-border/50 flex-wrap sm:flex-nowrap">
                                    <a target="blank" href="/storage/{{ $model->mannequinCandidate->identity_document }}" class="w-full flex items-center gap-4 justify-start ">
                                        <div class="col-span-2 hidden sm:block">
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
                                    <label for="identity_document" title="Remplacer la pièce d'identité" class="cursor-pointer !p-0 ml-auto">
                                        <svg class="stroke-main hover:stroke-secondary-light size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                        </svg>
                                        <input type="file" class="v-form-input hidden" name="identity_document" accept=".jpeg, .jpg, .png, .webp, .tiff, .raw, .dng, .pdf" id="identity_document">
                                    </label>
                                </div>
                                <span class="identity_document-error-message text-error text-xs col-span-2 2xl:col-span-3"></span>
                            </div>
                            <!-- identity document preview-->
                            <label for="identity_document" class="cursor-pointer rounded-lg overflow-hidden relative hidden identity_document_prev aspect-video w-full mt-2 2xl:mt-0 2xl:w-5/12 -mb-[6%] group">
                                <span class="w-full inset absolute items-center p-2 justify-center bg-main/50 backdrop-blur flex opacity-0 group-hover:opacity-100 z-10 h-full transition-all duration-200">
                                    <p class="text-primary-light text-center">Remplacer</p>
                                </span>
                                <img class="absolute w-full h-full inset-0 object-cover hidden identity-document-preview" src="" alt="">
                                <div class="identity-document-preview-pdf absolute w-full h-full inset-0">
                                    <div class="w-full h-full flex justify-center items-center bg-primary-light"></div>
                                </div>
                            </label>
                        </div>

                        <div class="sm:col-span-2 2xl:col-span-3">
                            <!-- submit -->
                            <x-primary-button class='sm:whitespace-nowrap'>
                                Mettre à jour les informations
                            </x-primary-button>
                        </div>

                    </div>
                </form>
            </div>
            @endcan

            @cannot('is-admin')
            <div>
                <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                    <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Photos</h2>
                    <div class="w-full bg-main h-[1px] ml-4"></div>
                </div>
                <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-6 gap-2 gap-y-4">
                    <!-- Profile picture -->
                    <div class="lazy-image-container rounded-xl relative aspect-square bg-primary">
                        <div class="lazy-image-loader absolute rounded-xl inset-0 flex items-center justify-center bg-primary-light transition-opacity duration-300">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="aspect-square w-full relative group rounded-xl bg-primary">
                            <a href="/storage/{{ $model->mannequinCandidate->profile }}" data-fancybox="gallery" class="">
                                <img
                                    loading="lazy"
                                    class="lazy-image select-none rounded-xl absolute inset-0 w-11/12 h-full object-cover cursor-pointer hover:brightness-75 transition-all duration-100"
                                    src="/storage/{{ $model->mannequinCandidate->profile }}"
                                    alt="{{ $model->name }} profile">
                            </a>
                        </div>
                    </div>
                    @foreach($images->sortByDesc('created_at')->take(10) as $image)
                    <div class="lazy-image-container rounded-xl relative aspect-square bg-primary">
                        <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center bg-primary-light transition-opacity duration-300">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <div class="aspect-square w-full relative group rounded-xl bg-primary">
                            <a href="{{ asset('storage/' . $image->image_url) }}" data-fancybox="gallery_non_admin" class="">
                                <img loading="lazy" class="lazy-image select-none rounded-xl absolute inset-0 w-11/12 h-full object-cover cursor-pointer hover:brightness-75 transition-all duration-100" src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $model->name }} profile">
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>


                @if(!empty($model->mannequinCandidate->instagram_link))
                <div class="sm:pt-16 pt-8">
                    <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                        <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Lien Instagram</h2>
                        <div class="w-full bg-main h-[1px] ml-4"></div>
                    </div>
                    <a href="{{$model->mannequinCandidate->instagram_link}}" class="text-sm uppercase flex flex-row items-center gap-4 justify-center rounded text-primary bg-main hover:bg-main-dark px-4 py-2 w-fit">
                        <span class="text-primary">
                            Profil
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-5 stroke-primary-light">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                </div>
                @endif
            </div>
            @endcannot

            <!-- measurements -->
            <div class="sm:pt-16 pt-8">
                <div class="flex items-center justify-start max-w-full overflow-hidden pb-2">
                    <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Mensurations (cm)</h2>
                    <div class="w-full bg-main h-[1px] ml-4"></div>
                </div>
                <form
                    @if($model->mannequinCandidate->measurements->isNotEmpty())
                    @php $measurement = $model->mannequinCandidate->measurements->first(); @endphp
                    action="{{ route('model.update.measurements', $measurement->id) }}"
                    method="POST"
                    @else
                    action="{{ route('model.store.measurements', $model->mannequinCandidate->id) }}"
                    method="POST"
                    @endif
                    >
                    @csrf
                    @if($model->mannequinCandidate->measurements->isNotEmpty())
                    @method('PUT')
                    @endif
                    <input type="hidden" name="model_id" value="{{ $model->mannequinCandidate->id }}">
                    <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 md:grid-cols-3 gap-12 [&_input]:!bg-primary-light [&>*]:-mt-6 pt-8">
                        <x-measurement-input name="head_circumference" label="Tour de tête" value="{{ $model->mannequinCandidate->measurements->first()->head_circumference ?? '' }}" />
                        <x-measurement-input name="neck_base_circumference" label="Tour bas encolure" value="{{ $model->mannequinCandidate->measurements->first()->neck_base_circumference ?? '' }}" />
                        <x-measurement-input name="shoulder_length" label="Longueur de l'épaule" value="{{ $model->mannequinCandidate->measurements->first()->shoulder_length ?? '' }}" />
                        <x-measurement-input name="arm_length" label="Longueur du bras" value="{{ $model->mannequinCandidate->measurements->first()->arm_length ?? '' }}" />
                        <x-measurement-input name="front_width" label="Carrure devant" value="{{ $model->mannequinCandidate->measurements->first()->front_width ?? '' }}" />
                        @if($model->mannequinCandidate->gender_identity === 'Homme')
                        <x-measurement-input name="belt_circumference" label="Tour de ceinture" value="{{ $model->mannequinCandidate->measurements->first()->belt_circumference ?? '' }}" />
                        @endif
                        @if($model->mannequinCandidate->gender_identity === 'Femme')
                        <x-measurement-input name="chest_circumference" label="Tour de poitrine" value="{{ $model->mannequinCandidate->measurements->first()->chest_circumference ?? '' }}" />
                        <x-measurement-input name="waist_circumference" label="Tour de taille" value="{{ $model->mannequinCandidate->measurements->first()->waist_circumference ?? '' }}" />
                        <x-measurement-input name="small_hips_circumference" label="Tour des petites hanches" value="{{ $model->mannequinCandidate->measurements->first()->small_hips_circumference ?? '' }}" />
                        @endif
                        <x-measurement-input name="tour_de_hanches" label="Tour de hanche" value="{{ $model->mannequinCandidate->measurements->first()->tour_de_hanches ?? '' }}" />
                        <!-- <x-measurement-input name="hips_circumference" label="Tour de bassin" value="{{ $model->mannequinCandidate->measurements->first()->hips_circumference ?? '' }}" /> -->
                        <x-measurement-input name="thigh_circumference" label="Tour de cuisse" value="{{ $model->mannequinCandidate->measurements->first()->thigh_circumference ?? '' }}" />
                        <x-measurement-input name="knee_circumference" label="Tour de genou" value="{{ $model->mannequinCandidate->measurements->first()->knee_circumference ?? '' }}" />
                        <x-measurement-input name="calf_circumference" label="Tour de mollet" value="{{ $model->mannequinCandidate->measurements->first()->calf_circumference ?? '' }}" />
                        <x-measurement-input name="ankle_circumference" label="Tour de cheville" value="{{ $model->mannequinCandidate->measurements->first()->ankle_circumference ?? '' }}" />
                        <x-measurement-input name="upper_arm_circumference" label="Tour du bras" value="{{ $model->mannequinCandidate->measurements->first()->upper_arm_circumference ?? '' }}" />
                        <x-measurement-input name="elbow" label="Tour de coude" value="{{ $model->mannequinCandidate->measurements->first()->elbow ?? '' }}" />
                        <x-measurement-input name="forearm_circumference" label="Tour de l'avant-bras" value="{{ $model->mannequinCandidate->measurements->first()->forearm_circumference ?? '' }}" />
                        <x-measurement-input name="wrist_size" label="Tour de poignet" value="{{ $model->mannequinCandidate->measurements->first()->wrist_size ?? '' }}" />
                        <x-measurement-input name="wrist_to_elbow" label="Longueur du poignet au coude" value="{{ $model->mannequinCandidate->measurements->first()->wrist_to_elbow ?? '' }}" />
                        <x-measurement-input name="inseam_length" label="Longueur de l'entrejambe" value="{{ $model->mannequinCandidate->measurements->first()->inseam_length ?? '' }}" />
                        <x-measurement-input name="knee_height" label="Hauteur du genou" value="{{ $model->mannequinCandidate->measurements->first()->knee_height ?? '' }}" />
                        <x-measurement-input name="side_height" label="Hauteur latérale à terre" value="{{ $model->mannequinCandidate->measurements->first()->side_height ?? '' }}" />
                        <x-measurement-input name="total_height" label="Taille" value="{{ $model->mannequinCandidate->measurements->first()->total_height ?? '' }}" />
                        <x-measurement-input
                            name="pointure"
                            label="Pointure"
                            value="{{ $model->mannequinCandidate->measurements->first()->pointure ?? '' }}" />
                        <x-measurement-input
                            name="poids"
                            label="Poids (kg)"
                            value="{{ $model->mannequinCandidate->measurements->first()->poids ?? '' }}" />
                    </div>

                    <div class="pt-6">
                        <x-primary-button>
                            @if($model->mannequinCandidate->measurements->isNotEmpty())
                            Mettre à jour
                            @else
                            Enregistrer
                            @endif
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        <div class="{{ $order2 }} w-full lg:w-1/3 bg-primary rounded-lg p-3 sm:p-6">

            <!-- Note / Contract-->
            @can('is-admin')
            <!-- Note -->
            <div>
                <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                    <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Évaluation</h2>
                    <div class="w-full bg-main h-[1px] ml-4"></div>
                </div>
                @php
                $ratingRanges = [
                [
                'min' => 18,
                'max' => 20,
                'mention' => 'Très bien - (félicitations du jury)',
                'comment' => 'Performance exceptionnelle',
                'classes' => 'bg-blue-500/30 border-blue-500'
                ],
                [
                'min' => 16,
                'max' => 17.99,
                'mention' => 'Très bien',
                'comment' => 'Excellente performance',
                'classes' => 'bg-orange-500/30 border-orange-500'
                ],
                [
                'min' => 14,
                'max' => 15.99,
                'mention' => 'Bien',
                'comment' => 'Le niveau de la performance dépasse le niveau attendu mais avec quelques erreurs',
                'classes' => 'bg-red-500/30 border-red-500'
                ],
                [
                'min' => 12,
                'max' => 13.99,
                'mention' => 'Assez bien',
                'comment' => 'Performance correcte mais avec un certain nombre d\'erreurs notables',
                'classes' => 'bg-blue-900/30 border-blue-900'
                ],
                [
                'min' => 10,
                'max' => 11.99,
                'mention' => 'Passable',
                'comment' => 'Le niveau de la performance remplit juste les critères requis',
                'classes' => 'bg-yellow-500/30 border-yellow-500'
                ],
                [
                'min' => 0,
                'max' => 9.99,
                'mention' => 'Faible',
                'comment' => 'Le niveau de la performance ne remplit pas les critères requis',
                'classes' => 'bg-green-500/30 border-green-500'
                ]
                ];

                $getRatingStyle = function($rating) use ($ratingRanges) {
                foreach ($ratingRanges as $range) {
                if ($rating >= $range['min'] && $rating <= $range['max']) {
                    return $range;
                    }
                    }
                    return $ratingRanges[count($ratingRanges) - 1];
                    };
                    @endphp

                    @if($model->mannequinCandidate->ratings->isNotEmpty())
                    <div class="flow-root ">
                        <dl class="text-sm">
                            @foreach($model->mannequinCandidate->ratings as $rating)
                            @php $style = $getRatingStyle($rating->rating); @endphp
                            <div class="min-h-24 overflow-hidden grid gap-1 p-2 sm:p-4 relative group rounded-xl grid-cols-3 sm:gap-4 border {{ $style['classes'] }}">
                                <dt class="font-medium text-secondary col-span-2 pl-2">
                                    Note par {{ $rating->judge->name }} ({{ $style['mention'] }})
                                </dt>
                                <dd class="text-secondary-light text-end text-xl">{{ $rating->rating }}/20</dd>
                                @if($rating->comment)
                                <dd class="col-span-2 sm:col-span-3 text-secondary-light pl-2 pt-2 sm:pt-0">
                                    {{ $rating->comment }}
                                </dd>
                                @endif

                                <button title="supprimer l'évaluation" class="remove-note-btn trnasition-all -translate-y-4 sm:translate-y-full sm:opacity-0 duration-300 group-hover:opacity-100 group-hover:-translate-y-4 remove-rating absolute right-4 bottom-0" data-note-id="{{$rating->id}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 hover:stroke-secondary-light stroke-main">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                            @endforeach
                        </dl>
                    </div>
                    @else
                    <div class="bg-primary/40 rounded-lg">
                        <form action="{{ route('model-rating.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="candidate_id" value="{{ $model->mannequinCandidate->id }}">

                            <div>
                                <label class="block text-sm font-medium text-secondary">Note (0-20)</label>
                                <input type="number"
                                    name="rating"
                                    min="0"
                                    max="20"
                                    required
                                    class="mt-1 bg-primary-light w-full rounded-md border-c-border pearance-none shadow-sm focus:border-c-border focus:ring-main [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none ">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-secondary">Commentaire</label>
                                <textarea name="comment"
                                    rows="3"
                                    class="mt-1 bg-primary-light w-full rounded-md border-c-border pearance-none shadow-sm focus:border-c-border focus:ring-main"></textarea>
                            </div>

                            <x-primary-button>
                                Soumettre l'évaluation
                            </x-primary-button>
                        </form>
                    </div>
                    @endif
            </div>

            <!-- Contract -->
            <div class="sm:pt-16 pt-8">
                <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                    <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Contrats ({{ $model->mannequinCandidate->contracts->count() }})</h2>
                    <div class="w-full bg-main h-[1px] ml-4"></div>
                </div>
                <div class="w-full">
                    @if($model->mannequinCandidate->contracts && $model->mannequinCandidate->contracts->count() > 0)
                    @foreach($model->mannequinCandidate->contracts as $contract)
                    <div class="pdf-widgetcursor-default gap-2 p-4 w-full rounded-xl hover:bg-primary mb-4 border border-c-border/50">
                        <div class="flex items-center gap-4 justify-start">
                            <div class="hidden sm:block col-span-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 stroke-secondary">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </div>
                            <div class="w-full flex items-center gap-4 justify-between">
                                <a href="{{ asset('storage/' . $contract->contract_url) }}" target="_blank" class="w-full grid grid-cols-12 items-center text-secondary hover:underline">
                                    <span class="text-secondary col-span-10">
                                        <p>Contrat #{{$contract->id}}</p>
                                        <p class="text-secondary-light flex items-center text-sm">Voir <span class="hidden 2xl:block text-secondary-light"> le document</span></p>
                                    </span>
                                    <div class="col-span-2 flex justify-end ">
                                        <svg class=" stroke-secondary size-6 contractDoc cursor-pointer hover:stroke-secondary-light" data-document-id="id" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </div>
                                </a>
                                <button title="supprimer le contrat" class="remove-contract" data-contract-id="{{$contract->id}}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 hover:stroke-secondary-light stroke-main">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-6 items-center w-full flex flex-wrap 2xl:flex-nowrap justify-between gap-4">
                            <h4 class='whitespace-nowrap text-sm'>Statut du contrat: </h4>
                            <small class="bg-main/20 px-4 rounded-full py-1 text-center">
                                @switch($contract->status)
                                @case('pending')
                                <span class="text-sm">En attente de signature</span>
                                @break

                                @case('active')
                                <span class="text-sm">Signé et valide</span>
                                @break

                                @case('inactive')
                                <span class="text-sm">Terminé</span>
                                @break

                                @default
                                <span class="text-sm">Statut inconnu</span>
                                @endswitch
                            </small>
                        </div>
                        <div class="w-full items-center flex flex-wrap sm:flex-nowrap justify-between gap-4 mt-3">
                            <h4 class='whitespace-nowrap text-sm'>Changer: </h4>
                            <form class="" action="{{ route('contracts.status.update', $contract) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="relative w-full">
                                    <input
                                        type="text"
                                        list="HeadlineActArtist"
                                        name="status"
                                        class="w-full bg-primary-dark rounded-lg border-c-border ring-main outline-main focus:!border-main pe-10 sm:text-sm [&::-webkit-calendar-picker-indicator]:opacity-0"
                                        placeholder="sélectionnez un statut"
                                        onchange="this.form.submit()" />

                                    <span class="absolute inset-y-0 end-0 flex w-8 items-center">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            class="size-5 stroke-secondary">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </span>
                                </div>
                                <datalist name="HeadlineAct" class="bg-secondary text-primary" id="HeadlineActArtist">
                                    <option value="En attente" {{ $contract->status === 'pending' ? 'selected' : '' }}>En attente de signature</option>
                                    <option value="Actif" {{ $contract->status === 'active' ? 'selected' : '' }}>Contrat signé et valide </option>
                                    <option value="Inactif" {{ $contract->status === 'inactive' ? 'selected' : '' }}>Contrat terminé </option>
                                </datalist>
                            </form>
                        </div>
                    </div>
                    @endforeach
                    @endif

                    <!-- Add new contract -->
                    <form action="{{ route('contracts.store', $model->mannequinCandidate->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <x-input-label for="contract" :value="__('Ajouter un contrat')" />
                        <div class="upload-container">
                            <div class="upload-widget w-full p-4 bg-primary rounded-xl border border-main border-dashed shadow-sm mt-1">
                                <div class="grid place-items-center">
                                    <div class="preview-area place-items-center relative rounded-lg overflow-hidden  group w-full">
                                        <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-main">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                            </svg>
                                        </div>
                                        <div class="text-center mt-3">
                                            <h4 class="font-medium ">Téléverser un fichier</h4>
                                            <p class="text-sm text-secondary-light">La taille de l'image doit être inférieure à 1.5 Mo</p>
                                        </div>
                                    </div>
                                    <label class="w-full">
                                        <input id="contract" hidden accept=".pdf" type="file" name="contract"
                                            accept=".pdf"
                                            capture="environment" />
                                        <div class="w-full py-2 px-4 mt-3 text-main border-main border hover:bg-main/30 text-sm font-medium rounded-lg text-center cursor-pointer transition-colors">
                                            Sélectionner un fichier
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <x-primary-button class="w-full mt-2 justify-center">
                                Télécharger le contrat
                            </x-primary-button>
                            <span class="error-message text-error text-sm mt-1 profile-error-message"></span>
                            <x-input-error :messages="$errors->get('profile')" class="mt-2" />
                        </div>
                    </form>
                </div>
            </div>

            <!-- Downlaod PDF -->
            <div class="sm:pt-16 pt-8 w-full">
                <div class="flex items-center justify-start max-w-full pb-6">
                    <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Fiche du mannequin</h2>
                    <div class="w-full bg-main h-[1px] ml-4"></div>
                </div>
                <x-primary-button class="justify-center w-full downloadPdfBtn" data-id="{{$model->id}}">
                    Télécharger la fiche du mannequin
                </x-primary-button>
            </div>
            @endcan

            <!-- Mensurations / Note-->
            @cannot('is-admin')
            <div>
                <!-- Note -->
                @can('is-jury')
                <div class="pb-12">
                    <div class="flex items-center justify-start max-w-full overflow-hidden pb-4">
                        <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Évaluation</h2>
                        <div class="w-full bg-main h-[1px] ml-4"></div>
                    </div>
                    @php
                    $ratingRanges = [
                    [
                    'min' => 18,
                    'max' => 20,
                    'mention' => 'Très bien - (félicitations des jury)',
                    'comment' => 'Performance exceptionnelle',
                    'classes' => 'bg-blue-500/30 border-blue-500'
                    ],
                    [
                    'min' => 16,
                    'max' => 17.99,
                    'mention' => 'Très bien',
                    'comment' => 'Excellente performance',
                    'classes' => 'bg-orange-500/30 border-orange-500'
                    ],
                    [
                    'min' => 14,
                    'max' => 15.99,
                    'mention' => 'Bien',
                    'comment' => 'Le niveau de la performance dépasse le niveau attendu mais avec quelques erreurs',
                    'classes' => 'bg-red-500/30 border-red-500'
                    ],
                    [
                    'min' => 12,
                    'max' => 13.99,
                    'mention' => 'Assez bien',
                    'comment' => 'Performance correcte mais avec un certain nombre d\'erreurs notables',
                    'classes' => 'bg-blue-900/30 border-blue-900'
                    ],
                    [
                    'min' => 10,
                    'max' => 11.99,
                    'mention' => 'Passable',
                    'comment' => 'Le niveau de la performance remplit juste les critères requis',
                    'classes' => 'bg-yellow-500/30 border-yellow-500'
                    ],
                    [
                    'min' => 0,
                    'max' => 9.99,
                    'mention' => 'Faible',
                    'comment' => 'Le niveau de la performance ne remplit pas les critères requis',
                    'classes' => 'bg-green-500/30 border-green-500'
                    ]
                    ];

                    $getRatingStyle = function($rating) use ($ratingRanges) {
                    foreach ($ratingRanges as $range) {
                    if ($rating >= $range['min'] && $rating <= $range['max']) {
                        return $range;
                        }
                        }
                        return $ratingRanges[count($ratingRanges) - 1];
                        };
                        @endphp

                        @if($model->mannequinCandidate->ratings->isNotEmpty())
                        <div class="flow-root">
                            <dl class="text-sm">
                                @foreach($model->mannequinCandidate->ratings as $rating)
                                @php $style = $getRatingStyle($rating->rating); @endphp
                                <div class="overflow-hidden grid grid-cols-1 gap-1 sm:p-4 p-2 relative group rounded-xl sm:grid-cols-3 sm:gap-4 border {{ $style['classes'] }}">
                                    <dt class="font-medium text-secondary col-span-2 pl-2">
                                        Note par {{ $rating->judge->name }} ({{ $style['mention'] }})
                                    </dt>
                                    <dd class="text-secondary-light text-end text-lg sm:text-xl">{{ $rating->rating }}/20</dd>
                                    @if($rating->comment)
                                    <dd class="col-span-3 text-secondary-light pl-2">
                                        {{ $rating->comment }}
                                    </dd>
                                    @endif

                                    @can('is-admin')
                                    <button title="supprimer l'évaluation" class="remove-note-btn trnasition-all translate-y-full opacity-0 duration-300 group-hover:opacity-100 group-hover:-translate-y-4 remove-rating absolute right-4 bottom-0" data-note-id="{{$rating->id}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 hover:stroke-secondary-light stroke-main">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                    @endcan
                                </div>
                                @endforeach
                            </dl>
                        </div>
                        @else
                        <div class="bg-primary/40 rounded-lg">
                            <form action="{{ route('model-rating.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="candidate_id" value="{{ $model->mannequinCandidate->id }}">

                                <div>
                                    <label class="block text-sm font-medium text-secondary">Note (0-20)</label>
                                    <input type="number"
                                        name="rating"
                                        min="0"
                                        max="20"
                                        required
                                        class="mt-1 bg-primary-light w-full rounded-md border-c-border pearance-none shadow-sm focus:border-c-border focus:ring-main [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none ">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-secondary">Commentaire</label>
                                    <textarea name="comment"
                                        rows="3"
                                        class="mt-1 bg-primary-light w-full rounded-md border-c-border pearance-none shadow-sm focus:border-c-border focus:ring-main"></textarea>
                                </div>

                                <x-primary-button>
                                    Soumettre l'évaluation
                                </x-primary-button>
                            </form>
                        </div>
                        @endif
                </div>
                @endcan

                <!-- list of mensurations-->
                <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                    <h2 class="text-base sm:text-lg font-semibold sm:whitespace-nowrap">Les mensurations du candidat (cm)</h2>
                    <div class="hidden sm:block w-full bg-main h-[1px] ml-4"></div>
                </div>
                <div class="flow-root">
                    @if($model->mannequinCandidate->measurements->isNotEmpty())
                    @php
                    $measurement = $model->mannequinCandidate->measurements->first();
                    $measurementFields = [
                    'head_circumference' => 'Tour de tête/Head Circumference',
                    'neck_base_circumference' => 'Tour bas encolure/Neck Base Circumference',
                    'shoulder_length' => 'Longueur de l\'épaule/Shoulder Length',
                    'arm_length' => 'Longueur du bras/Arm Length',
                    'front_width' => 'Carrure devant/Front Width',
                    'belt_circumference' => 'Tour de ceinture / Belt circumference',
                    'chest_circumference' => 'Tour de poitrine / Bust ',
                    'waist_circumference' => 'Tour de taille/Waist ',
                    'small_hips_circumference' => 'Tour des petites hanches/Lower Hip',
                    'tour_de_hanches' => 'Tour de hanche/Hip ',
                    'thigh_circumference' => 'Tour de cuisse/Thigh Circumference',
                    'knee_circumference' => 'Tour de genou/Knee Circumference',
                    'calf_circumference' => 'Tour de mollet/Calf Circumference',
                    'ankle_circumference' => 'Tour de cheville/Ankle Circumference',
                    'upper_arm_circumference' => 'Tour du bras/Upper Arm Circumference',
                    'elbow' => 'Tour de coude /Elbow',
                    'forearm_circumference' => 'Tour de l\'avant-bras/Forearm Circumference',
                    'wrist_size' => 'Tour de poignet/Wrist Size',
                    'wrist_to_elbow' => 'Longueur du poignet au coude/Wrist to Elbow',
                    'inseam_length' => 'Longueur de l\'entrejambe/Inseam Length',
                    'knee_height' => 'Hauteur du genou/Knee Height',
                    'side_height' => 'Hauteur latérale à terre/Side Height',
                    'total_height' => 'Taille/Total Height',
                    'pointure' => 'Pointure/Shoe size',
                    'poids' => 'Poids/Weight (kg)',
                    ];

                    $femaleOnlyFields = [
                    'chest_circumference' => 'Tour de poitrine / Bust ',
                    'waist_circumference' => 'Tour de taille/Waist ',
                    'small_hips_circumference' => 'Tour des petites hanches/Lower Hip ',
                    ];
                    $maleOnlyFields = [
                    'belt_circumference' => 'Tour de ceinture / Belt circumference',
                    ];

                    $isFemale = $model->mannequinCandidate->gender_identity === 'Femme';
                    @endphp

                    <dl class="text-sm">
                        @foreach($measurementFields as $field => $label)
                        @php
                        $isFemaleOnlyField = array_key_exists($field, $femaleOnlyFields);
                        $isMaleOnlyField = array_key_exists($field, $maleOnlyFields);
                        @endphp

                        @if((!$isFemaleOnlyField && !$isMaleOnlyField) || ($isFemaleOnlyField && $isFemale) || ($isMaleOnlyField && !$isFemale))
                        <div class="sm:py-3 py-2 px-2 sm:px-4 even:bg-primary-light flex justify-between items-center gap-4 rounded">
                            <dt class="font-medium text-secondary col-span-2 pl-2">{{ $label }}</dt>
                            <dd class="text-secondary-light whitespace-nowrap">
                                {{ $measurement->$field ?? 'N/A' }}
                            </dd>
                        </div>
                        @endif
                        @endforeach
                    </dl>
                    @else
                    <p class="text-secondary-light py-4">Aucune mensuration n'a été enregistrée pour ce mannequin.</p>
                    @endif
                </div>
            </div>
            @endcannot
        </div>
    </section>

    <!-- images -->
    <section class="hidden flex-wrap lg:flex-nowrap gap-4 tab" id="tab4">
        <div class="w-full bg-primary rounded-lg p-3 sm:p-6">
            <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">
                    Photos
                    @can('is-admin')
                    ({{ $model->mannequinCandidate->images->count() }})
                    @endcan
                </h2>
                <div class="w-full bg-main h-[1px] ml-4"></div>
                @can('is-admin')
                <button title="supprimer les images sélectionnées" class="ml-auto pl-2 scale-0 opacity-0  transition-all duration-300 delete-selected-images-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="size-6 hover:stroke-secondary-light stroke-main">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                </button>
                @endcan
            </div>
            @php
            $images = $images->sortBy(function ($image) {
            return [$image->position, -strtotime($image->created_at)];
            });
            @endphp
            @can('is-admin')
            <div id="sortable-grid" class="grid grid-cols-3 sm:grid-cols-5 gap-2 gap-y-4">
                @foreach($images as $image)
                <div class="lazy-image-container rounded-xl relative aspect-square bg-primary" data-id="{{ $image->id }}">
                    <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center bg-primary-light transition-opacity duration-300">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div class="aspect-square w-full relative group rounded-xl bg-primary">
                        <label for="{{ $image->id }}" title="Supprimer l'image" class="has-[:checked]:!opacity-100 has-[:checked]:bg-primary has-[:checked]:border-main cursor-pointer transition-all duration-300 absolute -top-1 right-3 translate-x-2 p-1 z-50 lg:opacity-0 group-hover:opacity-100 -translate-y-1 rounded-full aspect-square bg-main hover:bg-primary group/hl border border-primary/0 hover:border-main">
                            <input type="checkbox" class="peer/img-select hidden" name="model-imgs" value="{{ $image->id }}" id="{{ $image->id }}">
                            <svg class="stroke-primary peer-checked/img-select:hidden group-hover/hl:stroke-main size-3 sm:size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                            <svg class="stroke-main hidden peer-checked/img-select:block size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </label>
                        <a href="{{ asset('storage/' . $image->image_url) }}" data-fancybox="gallery" class="">
                            <img loading="lazy" class="lazy-image select-none rounded-xl absolute inset-0 w-11/12 h-full object-cover cursor-pointer hover:brightness-75 transition-all duration-100" src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $model->name }} profile">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endcan
            @cannot('is-admin')
            <div class="grid grid-cols-3 sm:grid-cols-5 gap-2 gap-y-4">
                @foreach($images as $image)
                <div class="lazy-image-container rounded-xl relative aspect-square bg-primary">
                    <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center bg-primary-light transition-opacity duration-300">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div class="aspect-square w-full relative group rounded-xl bg-primary">
                        <a href="{{ asset('storage/' . $image->image_url) }}" data-fancybox="gallery_non_admin4" class="">
                            <img loading="lazy" class="lazy-image select-none rounded-xl absolute inset-0 w-11/12 h-full object-cover cursor-pointer hover:brightness-75 transition-all duration-100" src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $model->name }} profile">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endcannot

        </div>
        @can('is-admin')
        <div class="w-full lg:w-1/3 bg-primary rounded-lg p-3 sm:p-6">
            <div class="flex items-center justify-start max-w-full overflow-hidden sm:pb-6 pb-3">
                <h2 class="text-base sm:text-lg font-semibold whitespace-nowrap">Ajouter plus d'images</h2>
                <div class="w-full bg-main h-[1px] ml-4"></div>
            </div>
            <form action="{{ route('model.store.photos') }}" method="POST" class="w-full" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="model_id" value="{{ $model->mannequinCandidate->id }}">
                <div class="hidden bg-secondary/50 aspect-square w-48 min-w-28"></div>
                <div class="upload-container images-upload-container stroke-primary-light">
                    <div class="flex flex-wrap [&_.add-more]:min-h-32">
                        <div class="images-grid flex flex-wrap gap-2 mb-2 pr-2"></div>
                        <label class="upload-widget bg-primary p-4 rounded-xl w-full flex justify-center items-center border border-dashed border-main" for="images">
                            <div class="place-items-center no-files-selected-widget">
                                <div class=" place-items-center relative rounded-lg overflow-hidden mb-4 group w-full">
                                    <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-main">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                        </svg>
                                    </div>
                                    <div class="text-center mt-3">
                                        <h4 class="font-medium ">Ajouter plus d'images</h4>
                                        <p class="text-sm text-secondary-light">La taille du fichier doit être inférieure à 1.5 Mo</p>
                                    </div>
                                </div>
                                <div class="w-full pt-2 pb-2 px-4 bg-main hover:bg-main-dark text-primary text-sm font-medium rounded-lg text-center cursor-pointer transition-colors">
                                    Téléverser des images
                                </div>
                            </div>
                            <div class="files-selected-widget hidden cursor-pointer min-w-20 justify-center items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 m-auto stroke-main hover:stroke-main-dark">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                            </div>
                            <input
                                id="images"
                                hidden
                                type="file"
                                name="images[]"
                                accept=".jpeg, .jpg, .png, .webp, .tiff, .raw, .dng"
                                multiple
                                class="images-input" />
                        </label>
                    </div>
                    <span class="error-message text-error text-sm mt-1 images-error-message"></span>
                    <x-input-error :messages="$errors->get('images[]')" class="mt-2" />
                </div>

                <div class="pt-6 hidden upload-images-btn">
                    <x-primary-button>
                        Télécharger les images
                    </x-primary-button>
                </div>
            </form>
            @if ($errors->any())
            <div class="mt-2 text-red-500">
                @foreach ($errors->all() as $error)
                @if (str_starts_with($error, 'Le champ images.'))
                <p>{{ $error }}</p>
                @endif
                @endforeach
            </div>
            @endif
        </div>
        @endcan
    </section>

    <!-- All Observations -->
    @can('is-admin')
    <div id="tab2" class="tab hidden pt-6">
        @php
        $roles = [
        'photographe' => 'Observation du photographe',
        'styliste' => "Observation de l'équipe de mensuration",
        'psychologue' => 'Observation de Psychologue',
        'osteopathe' => 'Observation de Ostéopathe',
        'nutritionniste' => 'Observation de Nutritionniste',
        'dieteticien' => 'Observation de Diététicien',
        'coach' => 'Observation de Coach sportif'
        ];
        @endphp
        @foreach($roles as $role => $title)
        <div class="md:flex w-full {{ $loop->first ? 'pt-4' : 'md:pt-16 pt-8' }} items-start gap-2">
            <h3 class="text-lg md:text-xl font-semibold text-secondary md:w-1/3">
                {{ $title }}
            </h3>
            <div class="comment md:w-5/6 md:border border-transparent border-l-c-border md:pl-4 pt-2 md:pt-0">
                <h3 class="text-base md:text-lg font-semibold text-secondary">
                    Commentaire
                </h3>
                <hr class="border-c-border mb-2 mt-1 w-1/6">
                <div class="w-full max-h-48 overflow-y-auto pr-2 comments-conts">
                    @php
                    $roleComments = $comments->filter(function($comment) use ($role) {
                    return $comment->user->role == $role;
                    });
                    @endphp
                    @if($roleComments->isEmpty())
                    <p class="text-secondary-light/50 select-none cursor-default placeholder text-sm md:text-base">
                        Il n'y a pas de commentaire de la part {{ $role == 'mensuration' ? "de l'équipe de mensuration" : "du $role" }} pour le moment...
                    </p>
                    @else
                    @foreach($roleComments as $index => $comment)
                    <div class="group">
                        <div class="comment-replay-cotainer" data-comment-cont-id="{{ $comment->id }}">
                            <div class="flex justify-end">
                                <span class="text-secondary-light/50 text-sm">
                                    {{ $comment->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <p class="text-secondary-light pt-1">
                                {{ $comment->comment_content }}
                            </p>
                            @if($comment->replays->isNotEmpty())
                            @foreach($comment->replays as $replay)
                            <div class="replay-container group">
                                <div class="bg-primary p-4 pl-6 rounded-t-full rounded-l-full mt-2 text-start">
                                    {{ $replay->reply_content }}
                                </div>
                                <span class='text-sm text-secondary-light/50 pl-4'>{{ $replay->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        <div class="flex justify-end mb-4 lg:opacity-0 lg:translate-y-1 group-hover:opacity-100 group-hover:translate-y-0 transition-all">
                            <span class="select-none text-main/70 text-sm hover:text-main cursor-pointer flex items-center gap-1 reply-trigger" data-key="{{ $comment->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                </svg>
                                <span>Répondre</span>
                            </span>
                        </div>
                        <div class="reply-widget mb-1 ml-1 hidden transform transition-all duration-300 opacity-0"
                            data-key="{{ $comment->id }}"
                            data-comment-id="{{ $comment->id }}">
                            <label for="commentReplay" class="sr-only">Relecture administrative</label>
                            <div class="overflow-hidden rounded-lg border border-c-border shadow-sm focus-within:border-main focus-within:ring-1 focus-within:ring-main">
                                <textarea id="commentReplay" class="w-full resize-none border-none align-top focus:ring-0 sm:text-sm bg-primary/40 placeholder:text-secondary-light/70" rows="1" placeholder="Répondre à ce commentaire..."></textarea>
                                <div class="flex items-center justify-end gap-2 bg-primary/40 p-3">
                                    <button type="button" class="clear-reply-btn rounded bg-primary px-3 py-1.5 text-sm font-medium hover:bg-primary-dark/50">
                                        Clair
                                    </button>
                                    <button type="button" class="add-reply-btn rounded bg-main px-3 py-1.5 text-sm font-medium text-primary-light hover:bg-main-dark">
                                        Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($index > 0)
                    <hr class="border-c-border w-full mb-8">
                    @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        @endforeach

    </div>
    @endcan

    <!-- Observation pf the others -->
    @cannot('is-admin')
    @cannot('is-jury')
    <div id="tab3" class="tab hidden md:pt-8">
        <div class="md:flex w-full items-start gap-2">
            <h3 class="text-lg md:text-xl font-semibold text-secondary md:w-1/3">
                Votre commentaires:
            </h3>
            <div class="comment md:w-5/6 md:border border-transparent border-l-c-border md:pl-4 pt-2 md:pt-0">
                <h3 class="text-base sm:text-lg font-semibold text-secondary">
                    Commentaire
                </h3>
                <hr class="border-c-border mb-2 mt-1 w-1/6">
                <div class="w-full max-h-32 overflow-y-auto comment-content">
                    @if($comments->isEmpty())
                    <p class="text-secondary-light/50 select-none cursor-default placeholder">
                        Votre commentaire sera affiché ici...
                    </p>
                    @else
                    @foreach($comments as $index => $comment)
                    @if($index > 0)
                    <hr class="border-c-border w-1/6 my-6 mb-1">
                    @endif
                    <div class="flex justify-end">
                        <span class="text-secondary-light/50 text-sm">
                            {{ $comment->created_at->format('d/m/Y H:i') }}
                        </span>
                    </div>
                    <p class="text-secondary-light pt-1">
                        {{ $comment->comment_content }}
                    </p>
                    @if($comment->replays->isNotEmpty())
                    @foreach($comment->replays as $replay)
                    <div class="replay-container group">
                        <div class="bg-primary p-4 pl-6 rounded-t-full rounded-l-full mt-2 text-start">
                            {{ $replay->reply_content }}
                        </div>
                        <span class='text-sm text-primary-dark pl-4'>{{ $replay->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @endforeach
                    @endif
                    @endforeach
                    @endif
                </div>
                <form class="mt-4 overflow-hidden rounded-lg border border-c-border shadow-sm focus-within:border-main focus-within:ring-1 focus-within:ring-main add-cemment">
                    @csrf
                    <textarea
                        class="comment-textarea w-full resize-none border-none align-top focus:ring-0 sm:text-sm bg-primary/40 placeholder:text-secondary-light/70"
                        rows="1"
                        placeholder="Commentaire..."
                        data-id="{{$model->mannequinCandidate->id}}"
                        data-username="{{Auth::user()->name}}"></textarea>

                    <div class="flex items-center justify-end gap-2 bg-primary/40 p-3">
                        <button type="button"
                            class="clear-comment-btn rounded bg-primary px-3 py-1.5 text-sm font-medium hover:bg-primary-dark/50">
                            Clair
                        </button>

                        <button type="button"
                            class="add-comment-btn rounded bg-main px-3 py-1.5 text-sm font-medium text-primary-light hover:bg-main-dark">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcannot
    @endcannot
</main>

<!-- confermation alert -->
<div
    class="delete-confirm fixed inset-0 w-full h-full items-center justify-center hidden bg-black/50 backdrop-blur-sm z-[99999]">

    <div role="alert"
        class="delete-confirm-content rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 max-w-96">
        <div class="flex items-start gap-4">

            <div class="flex-1">
                <strong class="block font-medium ">Êtes-vous sûr ?</strong>

                <p class="mt-1 text-sm ">
                    Cette action est irréversible, vous ne pouvez pas revenir en arrière lorsque vous l'avez supprimée.
                </p>

                <div class="mt-4 flex gap-2 flex-wrap sm:flex-nowrap">
                    <form action="" method="POST" class="delete-confirm-from">
                        @csrf
                        @method('DELETE')
                        <button type='submit'
                            class="inline-flex items-center gap-2 rounded-lg hover:bg-main-dark bg-main px-4 py-2">
                            <span class="text-sm text-primary uppercase -mb-[2px] content"> Oui supprimer</span>
                        </button>
                    </form>
                    <button
                        class="block rounded-lg px-4 py-2 transition close-delete-confirm-btn text-main text-sm hover:bg-main/10">
                        Annuler
                    </button>
                </div>
            </div>

            <button class="transition hover:opacity-80 close-delete-confirm-btn">
                <span class="sr-only">Dismiss popup</span>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

@if ($errors->any())
<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error translate-y-[150%] text-error session-alert">
    <div role="alert" class="alert alert-error flex gap-2">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>
            Erreur
        </span>
    </div>
    <ul class="pl-8">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@if (session('success'))
<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl border p-4 border-success text-success translate-y-[150%] session-alert">
    <div role="alert" class="alert alert-success flex gap-2">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="">{{ session('success') }}</span>
    </div>
</div>
{{ session()->forget('success') }}
@endif

<!-- Error message frontend only -->
<div class="toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 focus:!ring-error !border-error/80 text-error translate-y-[150%]">
    <div role="alert" class="alert alert-error flex gap-2">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Veuillez corriger les champs surlignés..</span>
    </div>
</div>

<!-- Succss/Error message frontend only -->
<div class="toast-success-order transition-all duration-700 fixed z-100 bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl border p-4 focus:!ring-success !border-success/80 text-success translate-y-[150%]">
    <div role="alert" class="alert alert-success flex gap-2">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>L'ordre a été mis à jour avec succès</span>
    </div>
</div>
<div class="toast-error-order transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 focus:!ring-error !border-error/80 text-error translate-y-[150%]">
    <div role="alert" class="alert alert-error flex gap-2">
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6 shrink-0 stroke-current"
            fill="none"
            viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Erreur de mise à jour de l'ordre</span>
    </div>
</div>
@endsection
