<x-app-layout>
    <section class="min-h-svh h-svh max-h-svh overflow-hidden grid-rows-1 grid-cols-12 dashgrid
        @auth
            {{ auth()->user()->can('is-admin') ? 'flex xl:grid' : 'flex' }}
        @else
            flex
        @endauth
    ">
        @include('mainviews.sidebar')
        <article class="otherContent col-span-9 2xl:col-span-10 scrollable min-h-svh h-svh max-h-svh overflow-scroll p-4 sm:p-8 !pt-24 transition-all duration-100 w-full"
        data-user-role="{{ auth()->user()->role ?? '' }}">
            @yield('content')
        </article>

        <div class="toast-load justify-center transition-all border-primary-light shadow-xl duration-700 fixed z-100 bottom-6 right-6 bg-main backdrop-blur rounded-xl border p-4 translate-y-[150%] z-[99999]">
            <div role="alert" class="alert alert-success items-center flex gap-2">
                <span class="text-primary-light">Chargement...</span>
                <svg class="fill-primary-light h-10 w-10" version="1.1" id="L9"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                    y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                    <path
                        d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                        <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                            from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                    </path>
                </svg>
            </div>
        </div>

    </section>
</x-app-layout>
