@extends('dashboard')
@section('content')
<style>
    article.overflow-scroll {
        padding: 0;
        padding-top: 0;
    }

    @media (min-width: 768px) {
        article.overflow-scroll {
            overflow: hidden;
            padding: 2rem;
            padding-top: 6rem;
        }
    }


    @media (max-width: 380px) {
        .submit-cont {
            flex-direction: column;
            gap: 1rem;
        }
        .submit-cont *{
            width: 100%;margin: 0;justify-content: center;text-align: center;
        }
    }

    nav {
        display: none;
    }
</style>
<section class="relative flex h-[103vh] w-[104%] -mt-[7rem] pb-16 md:pb-0 bg-primary" style="margin-top: -6rem;width: 107%;    margin-left: -4%;">
    <div class="w-full md:w-8/12 lg:w-1/2 flex flex-col justify-center items-center gap-4 bg-primary !px-8 sm:!px-20" style="padding: 5rem;padding-top: 6rem;">
        <a href="/">
            <x-application-logo class="w-20 h-20 fill-current " />
        </a>
        <h1 class="text-2xl font-bold text-primary-100">Connexion</h1>
        <p class="text-sm text-primary-100">Connectez-vous pour accéder à votre compte</p>

        <form method="POST" action="{{ route('login') }}" class="w-full max-w-xl h-fit sm:m-auto flex flex-col justify-center items-center gap-4 bg-primary-light rounded-lg border border-c-border !p-6 sm:!p-8" style="padding:2rem;">
            @csrf

            <!-- Email Address -->
            <div class="w-full min-w-44">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="nom d'utilisateur" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4 w-full">
                <x-input-label for="password" :value="__('Mot de passe')" />

                <x-text-input id="password" class="block mt-1 w-full"
                    type="password"
                    name="password"
                    required autocomplete="current-password" />

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4 w-full">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-c-border text-primary-100 shadow-sm focus:ring-primary-100" name="remember">
                    <span class="ms-2 text-sm ">{{ __('Souvenez-vous de moi') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4 w-full xl:flex-row flex-col gap-4 xl:gap-1">
                <a class="underline text-sm  hover: rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-main order-2 xl:order-1" href="/mannequin/inscription">
                    {{ __('Nouvelle inscription') }}
                </a>
                <div class="flex submit-cont ml-auto items-center justify-between xl:justify-end order-1 xl:order-2 w-full xl:w-fit">

                    @if (Route::has('password.request'))
                    <a class="underline text-sm  hover: rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-100" href="{{ route('password.request') }}">
                        {{ __('Mot de passe oublié?') }}
                    </a>
                    @endif

                    <x-primary-button class="ms-3">
                        {{ __('Se connecter') }}
                    </x-primary-button>
                </div>
            </div>

        </form>
    </div>

    <div class="md:block hidden w-4/12 lg:w-1/2 relative h-screen ml-auto">
        <img
            alt=""
            src="{{ asset('storage/inscription.jpeg') }}"
            class="absolute inset-0 h-full w-full object-cover" />
    </div>
</section>
@if(session('error'))
<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error text-error translate-y-[150%] session-alert">
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
        <span class="">{{ session('error') }}</span>
    </div>
</div>
@endif
@endsection