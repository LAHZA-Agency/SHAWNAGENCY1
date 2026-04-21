@extends('dashboard')

@section('content')
<section class="">

    <div class="w-full flex flex-wrap items-center justify-between mb-4 gap-4 sm:flex-nowrap">

        <form method="GET" action="{{ route('demandes.view') }}" class="flex items-center gap-2 w-full sm:w-fit">
            <input type="hidden" name="search" value="{{ request('search') }}">

            <input type="date" name="date" value="{{ request('date') }}"
                class="rounded-md border border-c-border py-2 px-2 text-sm bg-transparent focus:ring-main focus:border-main">

            <div class="p-2 flex gap-2">
                <button type="submit"
                    class="px-3 py-2 rounded-md bg-main text-primary text-sm hover:bg-main-dark">
                    Filtrer
                </button>

                <div class="relative inline-block group">
                    <a href="{{ route('demandes.view') }}"
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
            </div>
        </form>

        <form method="GET" action="{{ route('demandes.view') }}" class="relative w-full sm:w-fit">
            <input type="text" name="search" placeholder="rechercher..." value="{{ request('search') }}"
                class="w-full rounded-md !border-c-border py-2 sm:py-2.5 pe-10 shadow-sm sm:text-sm focus:!ring-main bg-transparent placeholder:text-main/70" />

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

    <div class="rounded-lg border border-c-border max-h-full mt-6">
        <div class="overflow-x-auto rounded-t-lg min-h-10 flex items-center">
            <table class="min-w-full divide-y-2 divide-c-border text-sm table-sort">
                <thead class="ltr:text-left rtl:text-right">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium">Date</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium">Nom</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium">Email</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium">Téléphone</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium">Modèle</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium">Message</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-c-border">
                    @forelse($demandes as $demande)
                    <tr class="hover:bg-primary/10 transition-all duration-150">
                        <td class="whitespace-nowrap px-4 py-2 text-center" >{{ $demande->created_at->format('d/m/Y') }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-center font-medium">{{ $demande->name }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-center">{{ $demande->email }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-center">{{ $demande->tel }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-center">{{ $demande->mannequinCandidate->user->name ?? 'N/A' }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-center">{{ $demande->message }}</td>
                       <td class="whitespace-nowrap px-4 py-2 text-center">
                      <button type="button"
                            class="delete-demande-btn px-4"
                            data-id="{{ $demande->id }}"
                            data-url="{{ route('demandes.delete', $demande->id) }}">
                        <svg class="stroke-accent hover:stroke-accent-dark size-5"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                    </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-secondary">Aucune demande trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $demandes->links() }}
    </div>

<div id="delete-confirm-modal"
     class="alert-dialog fixed inset-0 w-full h-screen bg-black/50 hidden backdrop-blur-sm z-[99999]"
     data-user-role="{{ auth()->user()->role }}">

    <div role="alert"
         class="alert-dialog-content rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0
                max-w-96 flex-shrink-0 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
        <div class="flex flex-col gap-1">

            <div class="flex items-start justify-between">
                <strong class="block font-medium">Êtes-vous sûr ?</strong>
                <button class="transition hover:opacity-80 close-dialog-btn">
                    <span class="sr-only">Dismiss popup</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <p class="mt-1 text-sm ">
                Cette action est irréversible, vous ne pouvez pas <br> revenir en arrière lorsque vous l'avez <br> supprimez.
            </p>

            <div class="mt-2 flex gap-2">
                <form id="delete-form" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg hover:bg-main-dark bg-main px-4 py-2">
                        <span class="text-sm text-primary uppercase -mb-[2px]">Oui supprimer</span>
                        <!-- Icône corbeille -->
                        <svg class="hover:brightness-200 size-0 trash" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                    </button>
                </form>
                <button
                    class="block rounded-lg px-4 py-2 transition close-dialog-btn text-main text-sm hover:bg-main/10">
                    Annuler
                </button>
            </div>

        </div>
    </div>
</div>
</section>

<!-- Success Toast -->
@if(session('success'))
<div
    class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl border p-4 border-success translate-y-[150%] session-alert">
    <div class="flex gap-2 items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-current text-success" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-success">{{ session('success') }}</span>
    </div>
</div>
{{ session()->forget('success') }}
@endif

<!-- Error Toast -->
@if ($errors->any())
<div
    class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error translate-y-[150%] session-alert">
    <div class="flex gap-2 items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 stroke-current text-error" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-error">Erreur :</span>
    </div>
    <ul class="pl-4 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


{{-- Modal non autorisé pour bookeuse (Demandes) --}}
<div id="unauthorized-modal-demande"
     class="fixed inset-0 p-2 w-full h-full items-center justify-center bg-black/50 hidden backdrop-blur-sm z-[999999]">

    <div class="unauthorized-content-demande rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 max-w-96">
        <div class="flex items-start gap-4">
            <div class="flex-1">
                <strong class="block font-medium">Action non autorisée</strong>
                <p class="mt-1 text-sm">
                    Cette action n'est pas autorisée. Vous n'avez pas les permissions nécessaires pour supprimer une demande.
                </p>
                <div class="mt-4">
                    <button class="close-unauthorized-demande block rounded-lg px-4 py-2 transition text-main text-sm hover:bg-main/10 border border-c-border">
                        Fermer
                    </button>
                </div>
            </div>
            <button class="close-unauthorized-demande transition hover:opacity-80">
                <span class="sr-only">Dismiss popup</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endsection
