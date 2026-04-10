@extends('dashboard')
@section('content')
<main class="max-w-5xl w-full m-auto lg:flex">
    <div class="lg:w-1/3 flex flex-wrap lg:block">

        <div class="w-full lg:w-fit">
            <h3 class="text-lg lg:text-2xl font-semibold text-secondary slugToSpace Model-name">
                {{$model->name}}
            </h3>
            <small class="text-muted mt-4 opacity-50">
                {{ $model->mannequinCandidate->model_type == 'Model' ? 'Modèle' : $model->mannequinCandidate->model_type }}
            </small>
            <hr class="border-c-border mb-2 mt-1 w-1/12">
        </div>
        <div class="w-full lg:w-fit lg:mt-8 flex lg:flex-col items-start gap-4">
            <button class="whitespace-nowrap sm:w-auto text-sm sm:text-base tabTrigger h-min py-1 text-secondary border-b border-secondary/70 font-normal"
                data-target="tab1" id="tab1-btn">
                Profil
            </button>
            <button class="text-sm sm:text-base tabTrigger h-min py-1 text-secondary-light/70 hover:text-secondary-light border-b border-transparent font-normal"
                data-target="tab2" id="tab2-btn">
                Galerie
            </button>

            <button class="text-sm sm:text-base tabTrigger h-min py-1 text-secondary-light/70 hover:text-secondary-light border-b border-transparent font-normal"
                data-target="tab3" id="tab3-btn">
                Contact
            </button>
        </div>

        @if(false)
        <div class="py-3">
            <dt class="font-medium text-secondary col-span-2 text-base">Langues parlées</dt>
            <dd class="text-secondary-light whitespace-nowrap text-sm">{{ $model->mannequinCandidate->langues_parlees }}</dd>
        </div>
        <div class="py-3">
            <dt class="font-medium text-secondary col-span-2 text-base">Piercings</dt>
            <dd class="text-secondary-light whitespace-nowrap text-sm">{{ $model->mannequinCandidate->piercings ? 'Oui' : 'Aucun' }}</dd>
        </div>
        <div class="py-3">
            <dt class="font-medium text-secondary col-span-2 text-base">Tatouages</dt>
            <dd class="text-secondary-light whitespace-nowrap text-sm">{{ $model->mannequinCandidate->tatouages ? 'Oui' : 'Aucun' }}</dd>
        </div>
        <div class="py-3">
            <dt class="font-medium text-secondary col-span-2 text-base">Sport pratiqué</dt>
            <dd class="text-secondary-light whitespace-nowrap text-sm">{{ $model->mannequinCandidate->sport_pratique ? 'Oui' : 'Non' }}</dd>
        </div>

        <!-- Downlaod PDF -->
        <div class="mt-6">
            <div class="flex items-center justify-start max-w-full pb-4">
                <h2 class="text-lg font-semibold whitespace-nowrap">La fiche du mannequin</h2>
                <div class="w-full bg-main h-[1px] ml-4"></div>
            </div>
            <x-primary-button class="w-full downloadPdfBtn" data-id="{{$model->id}}">
                Télécharger la fiche du mannequin
            </x-primary-button>
        </div>
        @endif

    </div>

    <div class="lg:w-2/3 tabs mt-12 lg:mt-0">
        <section class="tab w-full" id="tab1">
            <div class="sm:flex gap-8">
                <!-- first image -->
                @if($images->isNotEmpty())
                <div class="sm:w-1/3">
                    <div class="lazy-image-container rounded-xl relative mb-4 break-inside-avoid">
                        <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center transition-opacity duration-300 aspect-square">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="w-full relative group rounded-xl">
                            <a href="{{ asset('storage/' . $images->sortBy('position')->first()->image_url) }}" data-fancybox="profile" class="">
                                <img loading="lazy"
                                    class="lazy-image select-none rounded-xl w-full h-auto object-cover cursor-pointer hover:brightness-75 transition-all duration-100"
                                    src="{{ asset('storage/' . $images->sortBy('position')->first()->image_url) }}"
                                    alt="{{ $model->name }} profile">
                            </a>
                        </div>
                    </div>
                </div>
                @endif
                <!-- measurements -->
                <div class="sm:w-2/3">
                    <div class="flow-root" data-sexe="{{$model->mannequinCandidate->gender_identity}}">
                        @if($model->mannequinCandidate->measurements->isNotEmpty())
                        @php
                        $measurement = $model->mannequinCandidate->measurements->first();
                        @endphp
                        <div class="grid grid-cols-12 w-fit gap-2 sm:gap-1 [&_*]:text-sm sm:[&_*]:text-base">
                            <span class="col-span-7 sm:col-span-8">Taille / Height</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80 text-sm">
                                <span class="{{ $measurement->total_height ? 'total_height text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $measurement->total_height ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">cm /</span>
                                <span class="total_heightUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>

                            <span class="col-span-7 sm:col-span-8">Poids / Weight</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="{{ $measurement->poids ? 'poids text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $measurement->poids ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">Kg /</span>
                                <span class="poidsUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">lbs</span>
                            </p>

                            @if($model->mannequinCandidate->gender_identity === 'Femme')
                            <span class="col-span-7 sm:col-span-8">Tour de taille / Waist </span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="{{ $measurement->waist_circumference ? 'waist_circumference text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $measurement->waist_circumference ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">cm /</span>
                                <span class="waist_circumferenceUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>
                            @endif

                            <span class="col-span-7 sm:col-span-8">Tour de hanche / Hip</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="{{ $measurement->tour_de_hanches ? 'tour_de_hanches text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $measurement->tour_de_hanches ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">cm /</span>
                                <span class="tour_de_hanchesUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>

                            @if($model->mannequinCandidate->gender_identity === 'Femme')
                            <span class="col-span-7 sm:col-span-8">Tour de poitrine / Bust</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="{{ $measurement->chest_circumference ? 'chest_circumference text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $measurement->chest_circumference ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">cm /</span>
                                <span class="chest_circumferenceUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>
                            @endif

                            <span class="col-span-7 sm:col-span-8">Pointure / Shoe size</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="{{ $measurement->pointure ? 'pointure text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $measurement->pointure ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">/</span>
                                <span class="pointureUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80"></span>
                            </p>

                            <span class="col-span-7 sm:col-span-8">Couleur des yeux / Eye color</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="{{ $model->mannequinCandidate->couleur_yeux ? 'couleur_yeux text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $model->mannequinCandidate->couleur_yeux ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">/</span>
                                <span class="couleur_yeuxUs text-secondary-light/80"></span>
                            </p>

                            <span class="col-span-7 sm:col-span-8">Couleur des cheveux / Hair color</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="{{ $model->mannequinCandidate->couleur_cheveux ? 'couleur_cheveux text-secondary-light/80' : 'text-secondary-light/80' }}">
                                    {{ $model->mannequinCandidate->couleur_cheveux ?? 'N/A' }}
                                </span>
                                <span class="text-secondary-light/80">/</span>
                                <span class="couleur_cheveuxUs text-secondary-light/80"></span>
                            </p>
                        </div>
                        @else
                        <p class="text-secondary-light py-4 text-sm">Aucune mensuration n'a été enregistrée pour ce mannequin. </p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- images -->
            <div class="mt-6 grid grid-cols-4 gap-4 sm:space-y-4 w-full">
                @foreach($images->sortBy('position')->slice(1)->take(4) as $image)
                <div class="lazy-image-container rounded-xl relative mb-4 break-inside-avoid">
                    <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center transition-opacity duration-300 aspect-square">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div class="w-full relative group rounded-xl">
                        <a href="{{ asset('storage/' . $image->image_url) }}" data-fancybox="gallery" class="">
                            <img loading="lazy"
                                class="lazy-image select-none rounded-xl w-full h-auto object-cover cursor-pointer hover:brightness-75 transition-all duration-100"
                                src="{{ asset('storage/' . $image->image_url) }}"
                                alt="{{ $model->name }} profil">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        <section class="hidden tab w-full" id="tab2">
            <div class="mt-6 grid grid-cols-2  md:grid-cols-3 2xl:grid-cols-4 gap-4 space-y-4">
                @foreach($images->sortBy('position') as $image)
                <div class="lazy-image-container rounded-xl relative mb-4 break-inside-avoid">
                    <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center transition-opacity duration-300 aspect-square w-1/3">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div class="w-full relative group rounded-xl">
                        <a href="{{ asset('storage/' . $image->image_url) }}" data-fancybox="gallery_full" class="">
                            <img loading="lazy"
                                class="lazy-image select-none rounded-xl w-full h-auto object-cover cursor-pointer hover:brightness-75 transition-all duration-100"
                                src="{{ asset('storage/' . $image->image_url) }}"
                                alt="{{ $model->name }} profil">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        <section class="hidden tab w-full" id="tab3">
            <form method="POST" action="{{ route('contact.submit', $model->name) }}" class="v-form space-y-6 [&_label]:block [&_label]:text-sm [&_label]:font-medium [&_label]:text-secondary [&_input]:w-full [&_input]:rounded-md [&_input]:border-c-border [&_input]:bg-primary-light [&_input]:text-secondary [&_input]:shadow-sm focus:ring-main focus:border-main">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Votre Nom')" />
                    <x-text-input id="name" name="name" type="text" required class="mt-1 text-sm" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-error" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Votre Email')" />
                    <x-text-input id="email" name="email" type="email" required class="mt-1" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-error" />
                </div>

                <div>
                    <x-input-label for="tel" :value="__('Votre Téléphone')" />
                    <x-text-input id="tel" name="tel" type="tel" required
                        pattern="*[0-9]*"
                        inputmode="numeric"
                        minlength="8"
                        maxlength="15" class="mt-1" />
                    <x-input-error :messages="$errors->get('tel')" class="mt-2 text-error" />
                </div>

                <div>
                    <x-input-label for="message" :value="__('Message')" />
                    <textarea id="message" name="message" rows="4" required class="mt-1 w-full rounded-md border-c-border bg-primary-light text-secondary shadow-sm focus:ring-main focus:border-main"></textarea>
                    <x-input-error :messages="$errors->get('message')" class="mt-2 text-error" />
                </div>

                <div>
                    <x-primary-button type="submit" class="w-full justify-center">
                        Envoyer le message
                    </x-primary-button>
                </div>
            </form>
        </section>
    </div>
</main>

@if ($errors->any())
<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error translate-y-[150%] text-error session-alert">
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
@if (session('success'))
<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl border p-4 border-success text-success translate-y-[150%] session-alert">
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
@endsection
