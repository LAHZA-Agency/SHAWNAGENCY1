<body data-authenticated="{{ auth()->check() ? '1' : '0' }}">
    <div class="sidebar flex xl:col-span-3 2xl:col-span-2 bg-primary sideTextParent transition-transform duration-200
    @auth
        {{ auth()->user()->can('is-admin') ? '' : 'xl:w-24' }}
    @else
        hidden
    @endauth
">
    <div class="flex h-screen
        @auth
            {{ auth()->user()->can('is-admin') ? 'xl:w-16' : 'xl:w-24' }}
        @else
            w-24
        @endauth
    flex-col justify-between border-e border-c-border bg-primary-dark/40 z-[999]">
        <div class="">
            <div class="px-2">

                <div class="flex mt-3 justify-center items-center">
                    <!-- Logo -->
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}">
                            <x-application-logo class=" w-11 h-auto fill-current logo-black" />
                            <x-application-logo-light class=" w-10 h-auto fill-current logo-white" />
                        </a>
                    </div>
                </div>

                @can('is-active')
                @can('is-admin')
                <div class="pb-4 pt-8">
                    <a
                        href="{{ route('tableau-de-bord') }}"
                        class="group relative z-50 flex justify-center rounded py-1.5 hover:bg-primary-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>

                        <div
                            class="invisible z-[999] absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                            Tableau de bord
                        </div>
                    </a>
                </div>
                @endcan
                @cannot('is-admin')
                <div class="pb-4 pt-6">
                    <a
                        href="{{ route('dashboard') }}"
                        class="group relative z-50 flex justify-center rounded py-1.5 hover:bg-primary-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>

                        <div
                            class="invisible z-[999] absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                            Tableau de bord
                        </div>
                    </a>
                </div>
                @endcannot

                @can('is-admin')
                <ul class="space-y-1 border-t border-c-border pt-4">
                    <li>
                        <a
                            href="{{ route('dashboard') }}"
                            class="group relative flex justify-center rounded px-2 py-1.5 text-main/60 hover:bg-primary-dark hover:text-main">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>

                            <span
                                class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                                Liste des mannequins
                            </span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('dashboard.add-model') }}"
                            class="group relative flex justify-center rounded px-2 py-1.5 text-main/60 hover:bg-primary-dark hover:text-main">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0ZM3 19.235v-.11a6.375 6.375 0 0 1 12.75 0v.109A12.318 12.318 0 0 1 9.374 21c-2.331 0-4.512-.645-6.374-1.766Z" />
                            </svg>

                            <span
                                class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                                Ajouter un mannequin
                            </span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('dashboard.members') }}"
                            class="group relative flex justify-center rounded px-2 py-1.5 text-main/60 hover:bg-primary-dark hover:text-main">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>

                            <span
                                class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                                Liste des membres
                            </span>
                        </a>
                    </li>

                    <li>
                        <a
                            href="{{ route('dashboard.add-member') }}"
                            class="group relative flex justify-center rounded px-2 py-1.5 text-main/60 hover:bg-primary-dark hover:text-main">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>

                            <span
                                class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                                Ajouter un membre
                            </span>
                        </a>
                    </li>
                </ul>
                @endcan
                @endcan


            </div>
        </div>
        @can('is-admin')
        <div class="sticky inset-x-0 bottom-0 border-t border-c-border p-2 hidden xl:block">
            <button
                id="expandMenu"
                type="submit"
                class=" hidden group relative text-center w-full justify-center rounded-lg px-2 py-1.5 text-sm text-main/60 hover:bg-primary-dark hover:text-main">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                </svg>
                <span
                    class="w-fit invisible absolute start-full inset-0 rounded bg-secondary px-2 py-1.5 h-min text-xs font-medium text-primary group-hover:visible  whitespace-nowrap">
                    élargir le menu
                </span>
            </button>
            <button
                type="submit"
                id="collapseMenu"
                class="group relative flex w-full justify-center rounded-lg px-2 py-1.5 text-sm text-main/60 hover:bg-primary-dark hover:text-main">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-[1.35rem] stroke-main">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 9V4.5M9 9H4.5M9 9 3.75 3.75M9 15v4.5M9 15H4.5M9 15l-5.25 5.25M15 9h4.5M15 9V4.5M15 9l5.25-5.25M15 15h4.5M15 15v4.5m0-4.5 5.25 5.25" />
                </svg>

                <span
                    class="w-fit invisible absolute start-full inset-0 rounded bg-secondary px-2 py-1.5 h-min text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                    réduire le menu
                </span>
            </button>
            <div class="hidden -translate-x-[150%] col-span-11 2xl:col-span-11 w-[3.2rem] fill-primary-light -my-8 ml-6"></div>
        </div>

        <div class="sticky inset-x-0 bottom-0 border-t border-c-border p-2 xl:hidden block">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="group w-full relative flex justify-center rounded px-2 py-1.5 text-main/60 hover:bg-primary-dark hover:text-main">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>

                    <span
                        class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                        Se déconnecter
                    </span>
                </button>
            </form>
        </div>
        @endcan

        @cannot('is-admin')
        <div class="sticky inset-x-0 bottom-0 border-t border-c-border p-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="group w-full relative flex justify-center rounded px-2 py-1.5 text-main/60 hover:bg-primary-dark hover:text-main">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>

                    <span
                        class="invisible absolute start-full top-1/2 ms-4 -translate-y-1/2 rounded bg-secondary px-2 py-1.5 text-xs font-medium text-primary group-hover:visible whitespace-nowrap">
                        Se déconnecter
                    </span>
                </button>
            </form>
        </div>
        @endcannot

    </div>

    @can('is-admin')
    <div class="hidden xl:flex h-screen flex-1 flex-col justify-between border-e border-c-border sideText transform transition-transform duration-300 ease-in-out">
        <div class="px-4 py-6">
            <ul class="mt-14 space-y-4">
                <li class='w-full'>
                    <a href="{{ route('tableau-de-bord') }}" class="w-full">
                        <span class="text-sm w-full inline-block font-medium text-main hover:bg-primary-dark px-4 py-2 cursor-pointer rounded-lg"> Tableau de bord </span>
                    </a>
                </li>
                <li>
                    <summary class="px-4 py-2 text-main cursor-default">
                        <span class="text-sm font-medium text-main"> Gérer les mannequins </span>
                    </summary>

                    <ul class="mt-2 space-y-1 px-4">
                        <li>
                            <a
                                href="{{ route('dashboard') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-main/60 hover:bg-primary-dark/70 hover:text-main">
                                Liste des mannequins
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('dashboard.add-model') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-main/60 hover:bg-primary-dark/70 hover:text-main">
                                Ajouter un mannequin
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <summary class="px-4 py-2 text-main cursor-default">
                        <span class="text-sm font-medium text-main"> Gérer les membres</span>

                        <span class="shrink-0 transition duration-300 group-open:-rotate-180">
                        </span>
                    </summary>

                    <ul class="mt-2 space-y-1 px-4">
                        <li>
                            <a
                                href="{{ route('dashboard.members') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-main/60 hover:bg-primary-dark/70 hover:text-main">
                                Liste des membres
                            </a>
                        </li>

                        <li>
                            <a
                                href="{{ route('dashboard.add-member') }}"
                                class="block rounded-lg px-4 py-2 text-sm font-medium text-main/60 hover:bg-primary-dark/70 hover:text-main">
                                Ajouter un membre
                            </a>
                        </li>

                        <li>
                      <li>
                        <a
                            href="{{ route('demandes.view') }}"
                            class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-main/60 hover:bg-primary-dark/70 hover:text-main">
                            Demandes
                            <span id="demandes-badge"
                                class="bg-black text-white text-xs px-2 py-0.5 rounded-full"
                                style="{{ $unseenDemandesCount > 0 ? '' : 'display:none;' }}">
                                {{ $unseenDemandesCount }}
                            </span>
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('calendar.view') }}"
                            class="flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-main/60 hover:bg-primary-dark/70 hover:text-main">
                            Calendrier
                        </a>
                    </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="div">
            <input type="checkbox" id="toggle-logout" class="hidden peer">
            <div class="w-full p-4 pb-0 hidden peer-checked:block nav-account-menu">
                <div class="space-y-2 p-2 mb-2 bg-primary-light rounded-lg shadow-lg border border-c-border/50">
                    <form method="POST" class="rounded-tl-lg rounded-tr-lg box-border px-4 py-3 hover:bg-primary-dark/70 hover:rounded-lg hover:text-main" action="{{ route('logout') }}">
                        @csrf
                        <a
                            href="route('logout')"
                            onclick="event.preventDefault();this.closest('form').submit();"
                            class="flex justify-start items-center gap-4">
                            <svg class="size-5 scale-x-[-1] stroke-main" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                            </svg>
                            <span class='text-main text-base'>
                                Se déconnecter
                            </span>
                        </a>
                    </form>
                    <a href="https://shawnagency.fr/wp-login.php" class="flex items-center  gap-4 px-4 py-3 rounded-bl-lg rounded-br-lg hover:bg-primary-dark/70 hover:rounded-lg hover:text-main">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                        <span class='text-main text-base'>
                            Admin Wordpress
                        </span>
                    </a>
                </div>
            </div>
            <label for="toggle-logout" class="nav-account-menu-trigger select-none border border-c-border mx-4 mb-2 rounded-lg px-4 py-2 text-sm font-medium hover:bg-primary-dark/70 hover:text-main flex justify-between items-center">
                <span class="cursor-default text-main">
                    @auth
                    {{ Auth::user()->name }}
                    @else
                    Guest
                    @endauth
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 stroke-main">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                </svg>
            </label>
        </div>
    </div>
    @endcan
</div>
</body>
