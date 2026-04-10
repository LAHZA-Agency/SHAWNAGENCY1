@extends('dashboard')
@php
$roleMapping = [
'accueillant' => 'Accueillant',
'admin' => 'Admin',
'coach' => 'Coach sportif',
'dieteticien' => 'Diététicien',
'jury' => 'Jury',
'styliste' => 'Mensurations/styliste',
'osteopathe' => 'Ostéopathe',
'photographe' => 'Photographe',
'psychologue' => 'Psychologue',
];
@endphp
@section('content')
<section class="">
    <div class="w-full flex flex-wrap items-center justify-between mb-4 gap-4 sm:flex-nowrap">

        <div class="relative dropdown w-full sm:w-fit">
            <a
                href="#"
                class="border-e px-4 py-2 w-full inline-flex items-center gap-2 rounded-md border shadow-sm border-c-border dropdown-trigger">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-4 stroke-main" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                </svg>
                <span class="text-sm text-main">
                    Filtrer
                </span>
            </a>

            <form method="GET" action="{{ route('members.filter') }}" class="absolute start-0 z-10 mt-2 w-56 rounded-md border border-c-border/80 bg-primary-light shadow-lg dropdown-content hidden"
                role="menu">

                <input type="hidden" name="search" value="{{ request('search') }}">

                <div class="p-2 max-h-40 overflow-y-scroll">
                    <strong class="block p-2 text-xs font-medium uppercase opacity-65"> Role </strong>
                    @foreach($roleMapping as $key => $displayName)
                    <label for="{{ $key }}" class="relative my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="checkbox"
                            id="{{ $key }}"
                            name="role[]"
                            value="{{ $key }}"
                            class="hidden peer/draft"
                            {{ in_array($key, request('role', [])) ? 'checked' : '' }} />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/draft:opacity-100 peer-checked/draft:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/draft:-translate-x-1">
                            {{ $displayName }}
                        </span>
                    </label>
                    @endforeach
                </div>

                <hr class="w-11/12 mx-auto border-c-border">
                <div class="p-2">
                    <strong class="block p-2 text-xs font-medium uppercase opacity-65"> Status </strong>
                    <label for="active" class="relative my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="checkbox"
                            id="active"
                            name="status[]"
                            value="active"
                            class="hidden peer/active"
                            {{ in_array('active', request('status', [])) ? 'checked' : '' }} />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/active:opacity-100 peer-checked/active:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/active:-translate-x-1">
                            Actif
                        </span>
                    </label>
                    <label for="inactive" class="relative my-2 flex items-center justify-start gap-2 hover:bg-primary px-2 py-[.35rem] rounded-lg cursor-pointer border-2 border-transparent transition-all duration-300 has-[:checked]:bg-primary-dark has-[:checked]:border-main/50">
                        <input type="checkbox"
                            id="inactive"
                            name="status[]"
                            value="inactive"
                            class="hidden peer/inactive"
                            {{ in_array('inactive', request('status', [])) ? 'checked' : '' }} />
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="stroke-primary-300 w-4 opacity-0 transition-all duration-150 -translate-x-6 peer-checked/inactive:opacity-100 peer-checked/inactive:translate-x-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        <span class="text-sm text-primary-300 select-none transition-all duration-150 delay-[40ms] -translate-x-4 peer-checked/inactive:-translate-x-1">
                            Inactif
                        </span>
                    </label>
                </div>

                <hr class="w-11/12 mx-auto border-c-border">
                <div class="p-2 flex gap-2">
                    <x-primary-button type="submit" class="w-full select-none text-center justify-center">
                        Appliquer
                    </x-primary-button>
                    <div class="relative inline-block group">
                        <a href="{{ route('dashboard.members') }}" class="w-min p-0 hover:bg-main/10 flex items-center justify-center px-4 py-2 rounded-md">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-main-dark">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                            </svg>
                        </a>
                        <span class="absolute left-1/2 transform backdrop-blur-sm -translate-x-1/2 bottom-full mb-2 opacity-0 translate-y-2 transition-all group-hover:opacity-100 group-hover:translate-y-0 bg-main/10 text-main text-sm rounded py-1 px-2 whitespace-nowrap">
                            Réinitialiser
                        </span>
                    </div>


                </div>
            </form>
        </div>


        <form method="GET" action="{{ route('members.search') }}" class="relative w-full sm:w-fit">
            <label for="Search" class="sr-only"> Rechercher </label>
            <input
                type="text"
                id="Search"
                placeholder="rechercher..."
                name="search"
                value="{{ request('search') }}"
                class="w-full rounded-md !border-c-border py-2 sm:py-2.5 pe-10 shadow-sm sm:text-sm focus:!ring-main bg-transparent placeholder:text-main/70" />
            <span class="absolute inset-y-0 end-0 grid w-10 place-content-center">
                <button type="submit" class="!ring-main border-main">
                    <span class="sr-only">Search</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4 stroke-main" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </span>
        </form>

    </div>

    <div class="rounded-lg border border-c-border max-h-full">
        <div class="overflow-x-auto rounded-t-lg min-h-10 flex items-center">
            <table class="min-w-full divide-y-2 divide-c-border text-sm table-sort">
                <thead class="ltr:text-left rtl:text-right">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                #
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Nom d'utilisateur
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Email
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Role
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Status
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium group">
                            <div class="flex w-full justify-between items-center">
                                Action
                                <svg class="size-4 opacity-0 group-hover:opacity-100 transition-opacity duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                        </th>
                    </tr>
                </thead>

                <tbody class=" divide-y divide-c-border">
                    @foreach ($users as $user)
                    <tr class="{{ $user->id == 1 ? 'hidden' : '' }}" data-id="{{ $user->id }}">
                        <td class="whitespace-nowrap px-4 py-2 font-medium ">{{ $user->id }}</td>
                        <td class="whitespace-nowrap px-4 py-2 ">{{ $user->name }}</td>
                        <td class="whitespace-nowrap px-4 py-2 ">{{ $user->email }}</td>
                        <td class="whitespace-nowrap px-4 py-2">
                            {{ $roleMapping[$user->role] ?? $user->role }}
                        </td>
                        <td class="min-w-32">
                        @if($user->id != 1 && $user->email != 'contact@shawnagency.fr')
                            <div class="flex items-center space-x-4 my-1">
                                <label class="relative cursor-pointer flex justify-center items-center gap-2">
                                    <input type="checkbox" value="{{$user->id}}" name="memberStatus" {{ $user->status === 'active' ? 'checked' : '' }} class="hidden peer/draft sr-only" />
                                    <div class="peer h-5 w-9 rounded-full bg-main/30 after:absolute after:top-[2px] after:left-[2px] after:h-4 after:w-4 after:rounded-full after:border after:border-c-border after:bg-primary-light after:transition-all after:content-[''] peer-checked/draft:bg-main peer-checked/draft:after:translate-x-full peer-checked/draft:after:border-primary-dark peer-focus:outline-none"></div>
                                    <span class="peer-checked/draft:hidden">
                                        Inactif
                                    </span>
                                    <span class="hidden peer-checked/draft:block">
                                        Actif
                                    </span>
                                </label>
                            </div>
                            @else
                            <div class="flex items-center space-x-4 my-1">
                                <div class="relative cursor-not-allowed flex justify-center items-center gap-2">
                                    <div class="peer h-5 w-9 rounded-full bg-main/30 after:absolute after:top-[2px] after:left-[2px] after:h-4 after:w-4 after:rounded-full after:border after:border-c-border after:bg-primary-light after:transition-all after:content-[''] {{ $user->status === 'active' ? '!bg-main after:translate-x-full after:border-primary-dark' : '' }}"></div>
                                    <span class="{{ $user->status === 'active' ? 'hidden' : '' }}">
                                        Inactif
                                    </span>
                                    <span class="{{ $user->status === 'active' ? '' : 'hidden' }}">
                                        Actif
                                    </span>
                                    </d>
                                </div>
                                @endif
                        </td>
                        <td>
                            <div class="flex gap-10 justify-center items-center my-2">
                                @if($user->id != 1 && $user->email != 'contact@shawnagency.fr')
                                <button class="delete-member-btn" data-id="{{ $user->id }}" data-type="member">
                                    <svg class="stroke-accent hover:stroke-accent-dark size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                                @endif
                                <a href="{{ route('member.modifier', $user->id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="hover:stroke-main-dark stroke-main size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div>
        {{ $users->links() }}
    </div>
</section>
<div class="z-[9999999] max-w-full ml-6  toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl border p-4 border-success translate-y-[150%] session-alert-success">
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
        <span class="status-message text-secondary"></span>
    </div>
</div>

<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error translate-y-[150%] session-alert-error">
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
        <span class="text-secondary"></span>
    </div>
</div>

<div id="members-delete-modal" class="alert-dialog p-2 fixed inset-0 w-full h-full items-center justify-center bg-black/50 hidden backdrop-blur-sm z-[99999]" data-user-role="{{ auth()->user()->role }}">

    <div role="alert" class="alert-dialog-content rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 max-w-96">
        <div class="flex items-start gap-4">

            <div class="flex-1">
                <strong class="block font-medium ">Êtes-vous sûr ?</strong>

                <p class="mt-1 text-sm ">
                    Cette action est irréversible, vous ne pouvez pas revenir en arrière lorsque vous l'avez supprimée.
                </p>

                <div class="mt-4 flex gap-2">
                    <a href="#" class="inline-flex items-center gap-2 rounded-lg hover:bg-main-dark bg-main px-4 py-1 delete-action-btn">
                        <span class="text-sm text-primary uppercase -mb-[2px] content"> Oui supprimer </span>
                        <svg class=" hover:brightness-200 size-0 trash" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                        </svg>
                        <svg version="1.1" id="L9" class="hover:brightness-200 size-9 -m-2 loading hidden" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#fff" d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                <animateTransform
                                    attributeName="transform"
                                    attributeType="XML"
                                    type="rotate"
                                    dur="1s"
                                    from="0 50 50"
                                    to="360 50 50"
                                    repeatCount="indefinite" />
                            </path>
                        </svg>
                    </a>

                    <button class="block rounded-lg px-4 py-2 transition close-dialog-btn text-main text-sm hover:bg-main/10">
                        Annuler
                    </button>
                </div>
            </div>

            <button class="transition hover:opacity-80 close-dialog-btn">
                <span class="sr-only">Dismiss popup</span>

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- Modal non autorisé pour bookeuse --}}
<div id="unauthorized-modal-member"
    class="fixed inset-0 p-2 w-full h-full items-center justify-center bg-black/50 hidden backdrop-blur-sm z-[999999]">
    <div class="unauthorized-content-member rounded-xl border border-c-border bg-primary p-4 transition-all duration-500 opacity-0 translate-y-20 max-w-96">
        <div class="flex items-start gap-4">
            <div class="flex-1">
                <strong class="block font-medium">Action non autorisée</strong>
                <p class="mt-1 text-sm">Cette action n'est pas autorisée. Vous n'avez pas les permissions nécessaires pour supprimer un membre.</p>
                <div class="mt-4">
                    <button class="close-unauthorized-member block rounded-lg px-4 py-2 transition text-main text-sm hover:bg-main/10 border border-c-border">
                        Fermer
                    </button>
                </div>
            </div>
            <button class="close-unauthorized-member transition hover:opacity-80">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</div>
@endsection
