@extends('dashboard')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-xl sm:text-3xl font-semibold text-center">Mettre à jour un membre</h1>
    <p class="text-center  mt-2">Veuillez modifier les détails du membre.</p>

    <form action="{{ route('member.update', $member->id) }}" method="POST" class="mt-8 space-y-6 update-form">
        @csrf
        @method('PUT')
        <!-- Username Field -->
        <div>
            <x-input-label for="username" :value="__('Nom d\'utilisateur')" />
            <x-text-input id="username" value="{{ $member->name }}" class="block mt-1 w-full  {{ $errors->has('username') ? 'border-error' : '' }}" type="text" name="username" required aria-autocomplete="username"/>
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
            <span class="username-error-message"></span>
        </div>

        <!-- Email Field -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" value="{{ $member->email }}" class="block mt-1 w-full  {{ $errors->has('email') ? 'border-error' : '' }}" type="email" name="email" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <span class="email-error-message"></span>
        </div>

        <!-- Password Field -->
        <div class="mt-4 relative">
            <x-input-label for="password" :value="__('Mot de passe')" />
            <div class="relative">
                <x-text-input
                    id="password"
                    class="block mt-1 w-full pr-10  {{ $errors->has('password') ? 'border-error' : '' }}"
                    type="password"
                    name="password" />
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

        <!-- Role Field -->
        <div class="mt-4">
            <x-input-label for="" :value="__('Le rôle')" />
            <div class="options grid grid-cols-2 sm:flex flex-wrap gap-2 sm:gap-4">
                <label for="accueillant" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="accueillant" name="role" {{ $member->role === 'accueillant' ? 'checked' : '' }} value="accueillant" class="hidden peer/draft" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Accueillant') }}
                    </span>
                </label>

                <label for="admin" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="admin" name="role" value="admin" {{ $member->role === 'admin' ? 'checked' : '' }} class="hidden peer/draft" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Admin') }}
                    </span>
                </label>
                <!-- bookeuse option -->
                <label for="bookeuse" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
    
                    <input type="radio" id="bookeuse" name="role" value="bookeuse" {{ $member->role === 'bookeuse' ? 'checked' : '' }} class="hidden peer/draft" />

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-main w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>

                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:text-main peer-checked/draft:-translate-x-1">
                        {{ __('Bookeuse') }}
                    </span>
                </label>

                <label for="coach" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="coach" name="role" value="coach" {{ $member->role === 'coach' ? 'checked' : '' }} class="hidden peer/draft" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Coach sportif') }}
                    </span>
                </label>

                <label for="dieteticien" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="dieteticien" name="role" value="dieteticien" {{ $member->role === 'dieteticien' ? 'checked' : '' }} class="hidden peer/draft" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Diététicien') }}
                    </span>
                </label>

                <label for="jury" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="jury" name="role" value="jury" {{ $member->role === 'jury' ? 'checked' : '' }} class="hidden peer/draft" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Jury') }}
                    </span>
                </label>

                <label for="styliste" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="styliste" name="role" value="styliste" {{ $member->role === 'styliste' ? 'checked' : '' }} class="hidden peer/draft" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Mensurations / Styliste') }}
                    </span>
                </label>

                <label for="osteopathe" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="osteopathe" name="role" value="osteopathe" {{ $member->role === 'osteopathe' ? 'checked' : '' }} class="hidden peer/draft" />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Ostéopathe') }}
                    </span>
                </label>

                <label for="photographe" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="photographe" name="role" value="photographe" class="hidden peer/draft" {{ $member->role === 'photographe' ? 'checked' : '' }} />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Photographe') }}
                    </span>
                </label>

                <label for="psychologue" class="relative flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                    <input type="radio" id="psychologue" name="role" value="psychologue" class="hidden peer/draft" {{ $member->role === 'psychologue' ? 'checked' : '' }} />
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-3 peer-checked/draft:-translate-x-1">
                        {{ __('Psychologue') }}
                    </span>
                </label>
            </div>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>
                Mettre à jour
            </x-primary-button>
        </div>
    </form>

    @if (session('success'))
    <div class="z-[9999999] right-6 max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 bg-success/50 backdrop-blur rounded-xl border p-4 border-success text-success translate-y-[150%] session-alert">
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
    <div class="z-[9999999] right-6 max-w-full ml-6 toast transition-all duration-700 fixed bottom-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error translate-y-[150%] text-error session-alert">
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
    <div class="toast transition-all duration-700 fixed z-[9999999] bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 focus:!ring-error !border-error text-error translate-y-[150%]">
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