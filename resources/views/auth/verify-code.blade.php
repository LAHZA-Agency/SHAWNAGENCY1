<x-guest-layout>
    <h1 class="sm:text-2xl text-lg font-bold text-center my-6">Vérification du Code</h1>

    {{-- Affichage du message de succès --}}
    @if(session('success'))
        <p class="text-green-600 text-center mb-4">{{ session('success') }}</p>
    @endif

    {{-- Affichage du statut de session --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('models.verifyCode') }}">
        @csrf

        <div class="mb-4">
            <x-input-label for="verification_code" :value="__('Code de Vérification')" />
            <x-text-input 
                id="verification_code" 
                class="block mt-1 w-full" 
                type="text" 
                name="verification_code" 
                required 
                autofocus 
            />
            <x-input-error :messages="$errors->get('verification_code')" class="mt-2" />
        </div>

        <div class="flex items-center sm:justify-end mt-4">
            <x-primary-button class="sm:ms-4 w-full sm:w-fit justify-center">
                {{ __('Vérifier') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>


