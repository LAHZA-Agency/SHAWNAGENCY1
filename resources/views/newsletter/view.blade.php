@extends('dashboard')

@section('content')

<div class="w-full flex flex-wrap items-center justify-between mb-4 gap-4 sm:flex-nowrap">

    {{-- FILTRE DATE --}}
    <form method="GET" action="{{ route('newsletter.index') }}" class="flex items-center gap-2 w-full sm:w-fit">

        <input type="date" name="date" value="{{ request('date') }}"
            class="rounded-md border border-c-border py-2 px-2 text-sm bg-transparent focus:ring-main focus:border-main">

        <button type="submit"
            class="px-3 py-2 rounded-md bg-main text-primary text-sm hover:bg-main-dark">
            Filtrer
        </button>

        <div class="relative inline-block group">
            <a href="{{ route('newsletter.index') }}"
                class="w-min p-0 hover:bg-main/10 flex items-center justify-center px-4 py-2 rounded-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="size-6 stroke-main-dark">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                </svg>
            </a>
            <span
                class="absolute left-1/2 transform backdrop-blur-sm -translate-x-1/2 bottom-full mb-2 opacity-0 translate-y-2 transition-all group-hover:opacity-100 group-hover:translate-y-0 bg-main/10 text-main text-sm rounded py-1 px-2 whitespace-nowrap">
                Réinitialiser
            </span>
        </div>
    </form>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('newsletter.index') }}" class="relative w-full sm:w-fit">

        <input type="text" name="search" placeholder="rechercher..." value="{{ request('search') }}"
            class="w-full rounded-md border-c-border py-2 sm:py-2.5 pe-10 shadow-sm sm:text-sm bg-transparent placeholder:text-main/70" />

        <span class="absolute inset-y-0 end-0 grid w-10 place-content-center">
            <button type="submit">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 stroke-main" fill="none"
                    viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </button>
        </span>
    </form>

</div>

<section class="">

    <div class="rounded-lg border border-c-border max-h-full mt-6">
        <div class="overflow-x-auto rounded-t-lg min-h-10 flex items-center">

            <table class="min-w-full divide-y-2 divide-c-border text-sm table-sort">

                <thead class="ltr:text-left rtl:text-right">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-center">Date</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-center">Prénom</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-center">Email</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-center">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-c-border">

                    @forelse($contacts as $contact)
                        <tr class="hover:bg-primary/10 transition-all duration-150">

                            <td class="whitespace-nowrap px-4 py-2 text-center">
                                {{ isset($contact['createdAt'])
                                    ? \Carbon\Carbon::parse($contact['createdAt'])->format('d/m/Y')
                                    : 'N/A' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-2 font-medium text-center">
                                {{ $contact['attributes']['FIRSTNAME'] ?? 'N/A' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-2 text-center">
                                {{ $contact['email'] ?? 'N/A' }}
                            </td>

                            <td class="whitespace-nowrap px-4 py-2 text-center">

                                @if(isset($contact['email']))
                                    <form method="POST"
                                          action="{{ route('newsletter.delete', $contact['email']) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="delete-newsletter-btn px-4"
                                            data-email="{{ $contact['email'] }}"
                                            data-url="{{ route('newsletter.delete', $contact['email']) }}">

                                            <svg class="stroke-accent hover:stroke-accent-dark size-5"
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5">
                                                <path stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>

                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="4" class="p-4 text-center text-secondary">
                                Aucun contact trouvé
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>
    </div>

</section>


<div id="delete-confirm-modal"
     class="alert-dialog fixed inset-0 w-full h-screen bg-black/50 hidden backdrop-blur-sm z-[99999]"  data-user-role="{{ auth()->user()->role }}">

    <div role="alert"
         class="alert-dialog-content rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0
                max-w-96 flex-shrink-0 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">

        <div class="flex flex-col gap-1">

            <div class="flex items-start justify-between">
                <strong class="block font-medium">Êtes-vous sûr ?</strong>

                <button class="transition hover:opacity-80 close-dialog-btn">
                    <span class="sr-only">Dismiss popup</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="mt-1 text-sm">
                Cette action est irréversible, voulez-vous  vraiment supprimer ce contact ?
            </p>

            <div class="mt-2 flex gap-2">

                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg hover:bg-main-dark bg-main px-4 py-2">
                        <span class="text-sm text-primary uppercase">Oui supprimer</span>
                    </button>
                </form>

                <button class="block rounded-lg px-4 py-2 transition close-dialog-btn text-main text-sm hover:bg-main/10">
                    Annuler
                </button>

            </div>

        </div>
    </div>
</div>

<div id="unauthorized-modal-newsletter"
     class="fixed inset-0 p-2 w-full h-full items-center justify-center bg-black/50 hidden backdrop-blur-sm z-[999999]">

    <div class="unauthorized-content-newsletter rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 max-w-96">

        <div class="flex items-start gap-4">

            <div class="flex-1">
                <strong class="block font-medium">Action non autorisée</strong>

                <p class="mt-1 text-sm">
                    Vous n'avez pas les permissions nécessaires pour supprimer un contact newsletter.
                </p>

                <div class="mt-4">
                    <button class="close-unauthorized-newsletter block rounded-lg px-4 py-2 text-main text-sm hover:bg-main/10 border border-c-border">
                        Fermer
                    </button>
                </div>
            </div>

            <button class="close-unauthorized-newsletter">
                ✕
            </button>

        </div>

    </div>
</div>
@endsection
