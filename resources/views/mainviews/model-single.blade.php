@extends('dashboard')
@section('content')
<main class="max-w-5xl w-full m-auto lg:flex px-4 py-6">
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

    </div>

    <div class="lg:w-2/3 tabs mt-6 lg:mt-0">
        <section class="tab w-full" id="tab1">
            <div class="sm:flex gap-8">

                <!-- Photo principale -->
               @if($profilePhoto)
                <div class="sm:w-1/3">
                    <div class="lazy-image-container rounded-xl relative mb-4 break-inside-avoid">
                        <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center transition-opacity duration-300 aspect-square">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <div class="w-full relative group rounded-xl">
                            <a href="{{ $profilePhoto }}" data-fancybox="profile">
                                <img loading="lazy"
                                    class="lazy-image select-none rounded-xl w-full h-auto object-cover cursor-pointer hover:brightness-75 transition-all duration-100"
                                    src="{{ $profilePhoto }}"
                                    alt="{{ $model->name }} profile">
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Contenu principal : Mensurations + Informations générales -->
                <div class="sm:w-2/3">
                    <div class="flow-root" data-sexe="{{$model->mannequinCandidate->gender_identity}}">

                      <!-- ====================== MENSURATIONS ====================== -->
                <div class="mb-6">
                    <div class="flex items-center justify-start pb-3">
                        <h2 class="text-lg font-semibold text-secondary">Mensurations (cm)</h2>
                        <div class="flex-1 h-px bg-main ml-4"></div>
                    </div>

                    @if($model->mannequinCandidate->measurements->isNotEmpty())
                        @php
                            $measurement = $model->mannequinCandidate->measurements->first();
                        @endphp

                        <div class="grid grid-cols-12 w-fit gap-2 sm:gap-1 [&_*]:text-sm sm:[&_*]:text-base">

                            <!-- Mensurations existantes avec traduction -->
                            @if($measurement->total_height)
                            <span class="col-span-7 sm:col-span-8">Taille / Height</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80 text-sm">
                                <span class="total_height text-secondary-light/80">{{ $measurement->total_height }}</span>
                                <span class="text-secondary-light/80"> cm / </span>
                                <span class="total_heightUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>
                            @endif

                            @if($measurement->poids)
                            <span class="col-span-7 sm:col-span-8">Poids / Weight</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="poids text-secondary-light/80">{{ $measurement->poids }}</span>
                                <span class="text-secondary-light/80"> kg / </span>
                                <span class="poidsUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80"> lbs</span>
                            </p>
                            @endif

                            @if($model->mannequinCandidate->gender_identity === 'Femme' && $measurement->waist_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de taille / Waist</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="waist_circumference text-secondary-light/80">{{ $measurement->waist_circumference }}</span>
                                <span class="text-secondary-light/80"> cm / </span>
                                <span class="waist_circumferenceUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>
                            @endif

                            @if($model->mannequinCandidate->gender_identity === 'Femme' && $measurement->chest_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de poitrine / Bust</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="chest_circumference text-secondary-light/80">{{ $measurement->chest_circumference }}</span>
                                <span class="text-secondary-light/80"> cm / </span>
                                <span class="chest_circumferenceUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>
                            @endif

                            @if($measurement->tour_de_hanches)
                            <span class="col-span-7 sm:col-span-8">Tour de hanche / Hip</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="tour_de_hanches text-secondary-light/80">{{ $measurement->tour_de_hanches }}</span>
                                <span class="text-secondary-light/80"> cm / </span>
                                <span class="tour_de_hanchesUs text-secondary-light/80"></span>
                                <span class="text-secondary-light/80">"</span>
                            </p>
                            @endif

                            @if($measurement->pointure)
                            <span class="col-span-7 sm:col-span-8">Pointure / Shoe size</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="pointure text-secondary-light/80">{{ $measurement->pointure }}</span>
                                <span class="text-secondary-light/80"> / </span>
                                <span class="pointureUs text-secondary-light/80"></span>
                            </p>
                            @endif

                            <!-- Nouvelles mensurations avec traduction et affichage conditionnel -->
                            @if($measurement->head_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de tête / Head circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="head_circumference text-secondary-light/80">{{ $measurement->head_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->neck_base_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour bas encolure / Neck base circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="neck_base_circumference text-secondary-light/80">{{ $measurement->neck_base_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->shoulder_length)
                            <span class="col-span-7 sm:col-span-8">Longueur de l'épaule / Shoulder length</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="shoulder_length text-secondary-light/80">{{ $measurement->shoulder_length }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->arm_length)
                            <span class="col-span-7 sm:col-span-8">Longueur du bras / Arm length</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="arm_length text-secondary-light/80">{{ $measurement->arm_length }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->front_width)
                            <span class="col-span-7 sm:col-span-8">Carrure devant / Front width</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="front_width text-secondary-light/80">{{ $measurement->front_width }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->small_hips_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour des petites hanches / Lower hip</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="small_hips_circumference text-secondary-light/80">{{ $measurement->small_hips_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->thigh_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de cuisse / Thigh circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="thigh_circumference text-secondary-light/80">{{ $measurement->thigh_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->knee_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de genou / Knee circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="knee_circumference text-secondary-light/80">{{ $measurement->knee_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->calf_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de mollet / Calf circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="calf_circumference text-secondary-light/80">{{ $measurement->calf_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->ankle_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de cheville / Ankle circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="ankle_circumference text-secondary-light/80">{{ $measurement->ankle_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->upper_arm_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour du bras / Upper arm circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="upper_arm_circumference text-secondary-light/80">{{ $measurement->upper_arm_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->elbow)
                            <span class="col-span-7 sm:col-span-8">Tour de coude / Elbow circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="elbow text-secondary-light/80">{{ $measurement->elbow }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->forearm_circumference)
                            <span class="col-span-7 sm:col-span-8">Tour de l'avant-bras / Forearm circumference</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="forearm_circumference text-secondary-light/80">{{ $measurement->forearm_circumference }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->wrist_size)
                            <span class="col-span-7 sm:col-span-8">Tour de poignet / Wrist size</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="wrist_size text-secondary-light/80">{{ $measurement->wrist_size }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->wrist_to_elbow)
                            <span class="col-span-7 sm:col-span-8">Longueur du poignet au coude / Wrist to elbow</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="wrist_to_elbow text-secondary-light/80">{{ $measurement->wrist_to_elbow }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->inseam_length)
                            <span class="col-span-7 sm:col-span-8">Longueur de l'entrejambe / Inseam length</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="inseam_length text-secondary-light/80">{{ $measurement->inseam_length }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->knee_height)
                            <span class="col-span-7 sm:col-span-8">Hauteur du genou / Knee height</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="knee_height text-secondary-light/80">{{ $measurement->knee_height }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                            @if($measurement->side_height)
                            <span class="col-span-7 sm:col-span-8">Hauteur latérale à terre / Side height</span>
                            <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                                <span class="side_height text-secondary-light/80">{{ $measurement->side_height }}</span>
                                <span class="text-secondary-light/80"> cm</span>
                            </p>
                            @endif

                        </div>
                    @else
                        <p class="text-secondary-light py-4 text-sm">
                            Aucune mensuration n'a été enregistrée pour ce mannequin.
                        </p>
                    @endif
                </div>

                       <!-- ====================== INFORMATIONS GÉNÉRALES (juste en bas des mensurations) ====================== -->
            <div>
                <div class="flex items-center justify-start pb-4">
                    <h2 class="text-lg font-semibold text-secondary">Informations du mannequin</h2>
                    <div class="flex-1 h-px bg-main ml-4"></div>
                </div>

                <div class="grid grid-cols-12 gap-2 sm:gap-1 [&_*]:text-sm sm:[&_*]:text-base">
                    @if($model->mannequinCandidate->couleur_cheveux)
                    <span class="col-span-7 sm:col-span-8">Couleur des cheveux / Hair color</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->couleur_cheveux ? 'couleur_cheveux text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->couleur_cheveux ?? 'N/A' }}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->couleur_yeux)
                    <span class="col-span-7 sm:col-span-8">Couleur des yeux / Eye color</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->couleur_yeux ? 'couleur_yeux text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->couleur_yeux  }}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->sport_pratique)
                    <span class="col-span-7 sm:col-span-8">Sport pratiqué / Sport practiced</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->sport_pratique ? 'sport_pratique text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->sport_pratique  }}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->piercings)
                    <span class="col-span-7 sm:col-span-8">Piercings</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->piercings ? 'piercings text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->piercings}}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->tatouages)
                    <span class="col-span-7 sm:col-span-8">Tatouages / Tattoos</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->tatouages ? 'tatouages text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->tatouages }}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->finition_peau)
                    <span class="col-span-7 sm:col-span-8">Finition de peau / Skin finish</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->finition_peau ? 'finition_peau text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->finition_peau  }}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->sous_ton)
                    <span class="col-span-7 sm:col-span-8">Sous-ton / Undertone</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->sous_ton ? 'sous_ton text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->sous_ton }}
                        </span>
                    </p>
                    @endif

                    @if( $model->mannequinCandidate->niveau)
                    <span class="col-span-7 sm:col-span-8">Niveau / Level</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->niveau ? 'niveau text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->niveau }}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->emotions)
                    <span class="col-span-7 sm:col-span-8">Émotions</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->emotions ? 'emotions text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->emotions }}
                        </span>
                    </p>
                    @endif

                    @if($model->mannequinCandidate->categorie)
                    <span class="col-span-7 sm:col-span-8">Catégorie / Category</span>
                    <p class="whitespace-nowrap col-span-5 sm:col-span-4 text-secondary/80">
                        <span class="{{ $model->mannequinCandidate->categorie ? 'categorie text-secondary-light/80' : 'text-secondary-light/80' }}">
                            {{ $model->mannequinCandidate->categorie}}
                        </span>
                    </p>

                    @endif
                </div>
            </div>

            <!-- Galerie supplémentaire (4 images) -->
            <div class="mt-6 grid grid-cols-4 gap-4 sm:space-y-4 w-full">
                @foreach($images->sortBy('position')->slice(1)->take(4) as $image)
                <div class="lazy-image-container rounded-xl relative mb-4 break-inside-avoid">
                    <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center transition-opacity duration-300 aspect-square">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

        <!-- Onglet Galerie complète -->
        <section class="hidden tab w-full" id="tab2">
            <div class="mt-6 grid grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-4 space-y-4">
                @foreach($images->sortBy('position') as $image)
                <div class="lazy-image-container rounded-xl relative mb-4 break-inside-avoid">
                    <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center transition-opacity duration-300 aspect-square">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
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

        <!-- Onglet Contact -->
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
                    <x-text-input id="tel" name="tel" type="tel" required pattern="*[0-9]*" inputmode="numeric" minlength="8" maxlength="15" class="mt-1" />
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

<!-- Toasts -->
@if ($errors->any())
<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 border-error translate-y-[150%] text-error session-alert">
    <div role="alert" class="alert alert-error flex gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>Erreur</span>
    </div>
    <ul class="pl-8">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

@if (session('success'))
<div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl border p-4 border-success text-success translate-y-[150%] session-alert">
    <div role="alert" class="alert alert-success flex gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 shrink-0 stroke-current" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
{{ session()->forget('success') }}
@endif
@endsection