
<nav x-data="{ open: false }" class="bg-primary border-b border-c-border absolute inset-0 z-[999] h-min">
    <!-- primary-100 Navigation Menu -->
    <div class="w-full px-4">
        <div class="flex justify-between h-16">
            <!-- <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class=" h-10 w-auto fill-current logo-black" />
                        <x-application-logo-light class=" h-10 w-auto fill-current logo-white" />
                    </a>
                </div>
            </div> -->


            <div class="flex justify-end gap-2 items-center whitespace-nowrap max-w-full w-full pl-16">




                <!-- Dark Mode toggle switch -->
                <div class="flex flex-col justify-center items-end ml-3">
                    <input type="checkbox" name="light-switch" id="light-switch" class="light-switch sr-only" />
                    <label class="relative cursor-pointer p-2" for="light-switch" title="basculer entre le mode sombre et le mode clair ">
                        <svg class="dark:block hidden" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                            <path class="fill-main opacity-50" d="M7 0h2v2H7zM12.88 1.637l1.414 1.415-1.415 1.413-1.413-1.414zM14 7h2v2h-2zM12.95 14.433l-1.414-1.413 1.413-1.415 1.415 1.414zM7 14h2v2H7zM2.98 14.364l-1.413-1.415 1.414-1.414 1.414 1.415zM0 7h2v2H0zM3.05 1.706 4.463 3.12 3.05 4.535 1.636 3.12z" />
                            <path class="fill-main" d="M8 4C5.8 4 4 5.8 4 8s1.8 4 4 4 4-1.8 4-4-1.8-4-4-4Z" />
                        </svg>
                        <svg class=" dark:hidden" width="16" height="16" xmlns="http://www.w3.org/2000/svg">
                            <path class="fill-main opacity-50" d="M6.2 1C3.2 1.8 1 4.6 1 7.9 1 11.8 4.2 15 8.1 15c3.3 0 6-2.2 6.9-5.2C9.7 11.2 4.8 6.3 6.2 1Z" />
                            <path class="fill-main" d="M12.5 5a.625.625 0 0 1-.625-.625 1.252 1.252 0 0 0-1.25-1.25.625.625 0 1 1 0-1.25 1.252 1.252 0 0 0 1.25-1.25.625.625 0 1 1 1.25 0c.001.69.56 1.249 1.25 1.25a.625.625 0 1 1 0 1.25c-.69.001-1.249.56-1.25 1.25A.625.625 0 0 1 12.5 5Z" />
                        </svg>
                        <span class="sr-only">Switch to light / dark version</span>
                    </label>
                </div>

                @auth
                @if (Auth::user()->role == "Candidate")
                <div class="">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            title="Déconnexion"
                            class="group w-full relative flex justify-center rounded px-2 py-1.5 text-main/60 hover:bg-primary-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-main">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                            </svg>
                        </button>
                    </form>
                </div>
                @endif
                @endauth

            </div>

        </div>
    </div>
</nav>
