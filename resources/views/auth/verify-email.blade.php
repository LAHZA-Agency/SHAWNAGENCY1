<x-guest-layout>
    <div class="mb-4 text-sm">
        {{ __('Merci de vous être inscrit(e) ! Avant de commencer, veuillez vérifier votre adresse email en cliquant sur le lien que nous venons de vous envoyer. Si vous n\'avez pas reçu l\'email, nous pouvons vous en envoyer un autre.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-success">
            {{ __('Un nouveau lien de vérification a été envoyé à l\'adresse email que vous avez fournie.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between gap-4">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="whitespace-nowrap">
                    {{ __('Renvoyer l\'email de vérification') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm hover: rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-100">
                {{ __('Déconnexion') }}
            </button>
        </form>
    </div>
</x-guest-layout>
