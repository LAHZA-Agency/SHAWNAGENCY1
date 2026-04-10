@extends('dashboard')
@section('content')

<div class="max-w-5xl mx-auto">
    <h1 class="text-lg sm:text-3xl font-semibold text-center ">Ajouter un mannequin</h1>

    <p class="text-center  mt-2">Veuillez remplir les détails du nouveau mannequin.</p>

    <form action="{{ route('models.store') }}" method="POST" class="mt-8 space-y-6 required-password" enctype="multipart/form-data">
        @csrf

        <!-- current user id -->
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

        <div class="flex gap-4 flex-col sm:flex-row">

            <!-- Username Field -->
            <div class="w-full sm:w-1/3">
                <x-input-label for="username" :value="__('Nom du mannequin')" />
                <x-text-input id="username" class="block mt-1 w-full  {{ $errors->has('username') ? '!border-error' : '' }}" type="text" name="username" required autofocus />
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
                <span class="username-error-message"></span>
            </div>

            <!-- Email Field -->
            <div class="w-full sm:w-1/3">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full {{ $errors->has('email') ? '!border-error' : '' }}" type="email" name="email" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                <span class="email-error-message"></span>
            </div>

            <!-- Tel Field -->
            <div class="w-full sm:w-1/3">
                <x-input-label for="tel" :value="__('Téléphone')" />
                <x-text-input
                    id="tel"
                    class="block mt-1 w-full {{ $errors->has('tel') ? '!border-error' : '' }}"
                    type="tel"
                    name="tel"
                    required
                    pattern="\+?[0-9]{10,15}"
                    inputmode="numeric"
                    minlength="8"
                    maxlength="15"
                    :value="old('tel')" />
                <x-input-error :messages="$errors->get('tel')" class="mt-2" />
                <span class="tel-error-message"></span>
            </div>
        </div>

        <div class="flex gap-4 flex-wrap sm:flex-nowrap justify-between">

            <!-- Password Field -->
            <div class="w-full sm:w-1/3 relative">
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative">
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full pr-10  {{ $errors->has('password') ? 'border-error' : '' }}"
                        type="password"
                        name="password"
                        required />
                    <button
                        type="button"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center  cursor-pointer password-toggle"
                        data-password-input="password">
                        <!-- Eye Icon (Password Hidden) -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-icon show">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <!-- Eye-Slash Icon (Password Visible) -->
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 eye-icon hide hidden">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                <span class="password-error-message"></span>
            </div>

            <!-- Sexe Field -->
            <div class="w-2/5 sm:w-1/3">
                <x-input-label for="" :value="__('Sexe :')" />
                <div class="options flex flex-wrap gap-4 mt-1">
                    <label for="femme" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="radio" id="femme" name="gender_identity" checked value="Femme" class="hidden peer/draft" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-main w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span class="text-sm select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:text-main peer-checked/draft:-translate-x-1">
                            {{ __('Féminin') }}
                        </span>
                    </label>
                    <label for="homme" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="radio" id="homme" name="gender_identity" value="Homme" class="hidden peer/draft" />
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

            <!-- Type Field -->
            <div class="w-2/5 sm:w-1/3">
                <x-input-label for="" :value="__('Type :')" />
                <div class="options flex flex-wrap gap-4 mt-1">
                    <label for="Model" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="radio" id="Model" name="model_type" checked value="Model" class="hidden peer/draft" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-main w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span class="text-sm select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:text-main peer-checked/draft:-translate-x-1">
                            {{ __('Model') }}
                        </span>
                    </label>
                    <label for="Mannequin" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="radio" id="Mannequin" name="model_type" value="Mannequin" class="hidden peer/draft" />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-main w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span class="text-sm select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:text-main peer-checked/draft:-translate-x-1">
                            {{ __('Mannequin') }}
                        </span>
                    </label>
                </div>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>
        </div>

        <div class="flex gap-4 flex-col sm:flex-row">

            <!-- Languages -->
            <div class="w-full sm:w-1/3">
                <x-input-label for="langues_parlees" :value="__('Langues parlées')" />
                <x-text-input id="langues_parlees" required class="block mt-1 w-full" type="text" name="langues_parlees" />
            </div>

            <!-- Hair color -->
            <div class="w-full sm:w-1/3">
                <x-input-label for="couleur_cheveux" :value="__('Couleur des cheveux')" />
                <x-text-input id="couleur_cheveux" required class="block mt-1 w-full" type="text" name="couleur_cheveux" />
            </div>

            <!-- Eye color -->
            <div class="w-full sm:w-1/3">
                <x-input-label for="couleur_yeux" :value="__('Couleur des yeux')" />
                <x-text-input id="couleur_yeux" required class="block mt-1 w-full" type="text" name="couleur_yeux" />
            </div>
        </div>

        <!-- Extra -->
        <div class="flex gap-4 flex-col sm:flex-row flex-wrap">

    <!-- Sport pratiqué + Piercings -->
    <div class="flex gap-4 w-full">
        <div class="w-full sm:w-1/2">
            <x-input-label for="sport_pratique" :value="__('Sport pratiqué')" />
            <x-text-input
                id="sport_pratique"
                class="block mt-1 w-full"
                type="text"
                name="sport_pratique"
                placeholder="Ex: Football, Natation..."
                :value="old('sport_pratique')" />
        </div>

        <div class="w-full sm:w-1/2">
            <x-input-label for="piercings" :value="__('Piercings')" />
            <x-text-input
                id="piercings"
                class="block mt-1 w-full"
                type="text"
                name="piercings"
                placeholder="Ex: Oreilles, Nez, Nombril..."
                :value="old('piercings')" />
        </div>
    </div>

    <!-- Tatouages + Instagram -->
    <div class="flex gap-4 w-full">
        <div class="w-full sm:w-1/2">
            <x-input-label for="tatouages" :value="__('Tatouages')" />
            <x-text-input
                id="tatouages"
                class="block mt-1 w-full"
                type="text"
                name="tatouages"
                placeholder="Ex: Bras droit, Dos, Cheville..."
                :value="old('tatouages')" />
        </div>

        <div class="w-full sm:w-1/2">
            <x-input-label for="instagram_link" :value="__('Lien Instagram')" />
            <x-text-input
                id="instagram_link"
                class="block mt-1 w-full"
                type="url"
                name="instagram_link"
                :value="old('instagram_link')" />
        </div>
    </div>

        </div>

         <!-- Disponibilité -->
        <div class="mb-4">
            <h3 class="text-lg font-medium mb-2">Disponibilité</h3> <!-- titre ajouté -->

            <div class="flex gap-4 flex-col sm:flex-row">
                <!-- Date début -->
                <div class="w-full sm:w-1/2">
                    <x-text-input
                        id="date_debut"
                        class="block mt-1 w-full {{ $errors->has('disponibilite_debut') ? '!border-error' : '' }}"
                        type="date"
                        name="disponibilite_debut"
                        required />
                    <x-input-error :messages="$errors->get('disponibilite_debut')" class="mt-2" />
                </div>

                <!-- Date fin -->
                <div class="w-full sm:w-1/2">
                    <x-text-input
                        id="date_fin"
                        class="block mt-1 w-full {{ $errors->has('disponibilite_fin') ? '!border-error' : '' }}"
                        type="date"
                        name="disponibilite_fin"
                        required />
                    <x-input-error :messages="$errors->get('disponibilite_fin')" class="mt-2" />
                </div>
            </div>
        </div>
        <!-- Profile and indentity -->
        <div class="flex gap-6 flex-col sm:flex-row">
            <div class="hidden bg-main/50"></div>

            <!-- Profile Field -->
            <div class="w-full sm:w-1/3">
                <x-input-label for="profile" :value="__('Photo de profil')" />
                <div class="upload-container">
                    <div class="upload-widget w-full p-4 bg-primary rounded-xl border border-main border-dashed shadow-sm mt-1">
                        <div class="grid place-items-center">
                            <div class="preview-area place-items-center relative rounded-lg overflow-hidden min-h-40 group w-full">
                                <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-main">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                    </svg>
                                </div>
                                <div class="text-center mt-3">
                                    <h4 class="font-medium ">Téléverser une image</h4>
                                    <p class="text-sm text-secondary-light">La taille de l'image doit être inférieure à 1.5 Mo</p>
                                </div>
                            </div>
                            <label class="w-full">
                                <input id="profile" hidden type="file" accept=".jpeg, .jpg, .png, .webp, .tiff, .raw, .dng" name="profile"
                                    accept="image/*"
                                    capture="environment" />
                                <div class="w-full py-2 px-4 mt-3 bg-main hover:bg-main-dark text-primary text-sm font-medium rounded-lg text-center cursor-pointer transition-colors">
                                    Sélectionner une image
                                </div>
                            </label>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1 profile-error-message"></span>
                    <x-input-error :messages="$errors->get('profile')" class="mt-2" />
                </div>
            </div>

            <!-- Identity Document Field -->
            <div class="w-full sm:w-2/3">
                <x-input-label for="identity" :value="__('Document d\'identité')" />
                <div class="upload-container">
                    <div class="upload-widget w-full p-4 bg-primary rounded-xl border border-main border-dashed shadow-sm mt-1">
                        <div class="grid place-items-center">
                            <div class="preview-area place-items-center relative rounded-lg overflow-hidden min-h-40 group w-full">
                                <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-main">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                    </svg>
                                </div>
                                <div class="text-center mt-3">
                                    <h4 class="font-medium ">Téléverser un fichier</h4>
                                    <p class="text-sm text-secondary-light">La taille du fichier doit être inférieure à 1.5 Mo</p>
                                </div>
                            </div>
                            <label class="w-full">
                                <input id="identity" hidden type="file" name="identity_document"
                                    accept=".jpeg, .jpg, .png, .webp, .tiff, .raw, .dng, .pdf"
                                    capture="environment" />
                                <div class="w-full py-2 px-4 mt-3 bg-main hover:bg-main-dark text-primary text-sm font-medium rounded-lg text-center cursor-pointer transition-colors">
                                    Sélectionner un fichier
                                </div>
                            </label>
                        </div>
                    </div>
                    <span class="error-message text-red-500 text-sm mt-1 identity_document-error-message"></span>
                    <x-input-error :messages="$errors->get('identity')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- images[] Document Field -->
        <div class="w-full ">
            <x-input-label for="images[]" :value="__('Images (optionnelle)')" />
            <div class="hidden bg-secondary/50 aspect-square w-48 min-w-28 min-h-32"></div>
            <div class="upload-container images-upload-container stroke-primary-light">
                <div class="flex flex-wrap gap-2">
                    <div class="images-grid flex gap-1 flex-wrap"></div>
                    <label class="upload-widget p-4 bg-primary rounded-xl w-full flex justify-center items-center border border-dashed border-main" for="images">
                        <div class="grid place-items-center no-files-selected-widget">
                            <div class=" place-items-center relative rounded-lg overflow-hidden min-h-40 group w-full">
                                <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-main">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                    </svg>
                                </div>
                                <div class="text-center mt-3">
                                    <h4 class="font-medium ">Téléverser des images</h4>
                                    <p class="text-sm text-secondary-light">La taille du fichier doit être inférieure à 1.5 Mo</p>
                                </div>
                            </div>
                            <div class="w-full pt-2 pb-2 px-4 bg-main hover:bg-main-dark text-primary text-sm font-medium rounded-lg text-center cursor-pointer transition-colors">
                                Téléverser des images
                            </div>
                        </div>
                        <div class="files-selected-widget cursor-pointer min-w-20 justify-center items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 stroke-main hover:stroke-main-dark">
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
                <span class="error-message text-red-500 text-sm mt-1 images-error-message"></span>
                <x-input-error :messages="$errors->get('images[]')" class="mt-2" />
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>
                Ajouter un Candidat
            </x-primary-button>
        </div>
    </form>

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
                Erreur lors de la création du candidat:
            </span>
        </div>
        <ul class="pl-8">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 focus:!ring-error !border-error text-error translate-y-[150%]">
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

</div>


@endsection