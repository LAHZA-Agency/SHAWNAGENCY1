@extends('dashboard')
@php
$statusMapping = [
'pending' => 'En Attente',
'approved' => 'Approuvé',
'rejected' => 'Rejeté',
];
@endphp
@section('content')
<style>
    .sidebar{
        display: none;;
    }
</style>
<!-- content -->
<main class="-mt-4 md:flex gap-4 sm:-mx-4">

    <div class="md:w-2/3 bg-primary rounded-lg p-6">
        <!-- Model Inof -->
        <div>
            <div class="flex items-center justify-start max-w-full overflow-hidden pb-6">
                <h2 class="text-lg font-semibold whitespace-nowrap">Informations general</h2>
                <div class="w-full bg-main h-[1px] ml-4"></div>
            </div>
            <div class="v-form profile lg:flex 2xl:gap-12 gap-6 pt-8 w-full items-start justify-start">
                <div class="w-fit -mt-6">
                    <!-- Profiel picture -->
                    <div class="lazy-image-container relative aspect-square w-full">
                        <!-- Loading Spinner -->
                        <div class="lazy-image-loader absolute rounded-xl inset-0 flex items-center justify-center bg-primary-light transition-opacity duration-300">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>

                        <!-- Image -->
                        <div class="aspect-square group profile-img relative w-32 m-auto group">
                            <a href="/storage/{{ $model->mannequinCandidate->profile }}" data-fancybox="profile" class="">
                                <img
                                    loading="lazy"
                                    class="profile-p-img w-full h-auto rounded-xl aspect-square object-cover cursor-pointer hover:brightness-75 transition-all duration-100"
                                    src="/storage/{{ $model->mannequinCandidate->profile }}"
                                    alt="{{ $model->name }} profile">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="w-full grid gap-6 !mt-10 lg:!-mt-6 2xl:pl-12 [&>span]:col-span-2 lg:[&>span]:col-span-1">

                    <!-- username -->
                    <span>
                        {{ $model->name }}
                    </span>

                    <!-- email -->
                    <span>
                        {{ $model->email }}
                    </span>

                    <!-- Tel -->
                    <span>
                        {{ $model->mannequinCandidate->tel }}
                    </span>
                    <!-- Languages -->
                    <span>
                        Langues parlées: {{ $model->mannequinCandidate->langues_parlees }}
                    </span>

                    <!-- Physical Characteristics -->
                    <span>
                        Couleur des cheveux: {{ $model->mannequinCandidate->couleur_cheveux }}
                    </span>
                    <span>
                        Couleur des yeux: {{ $model->mannequinCandidate->couleur_yeux }}
                    </span>

                    <span>
                        Piercings: {{ $model->mannequinCandidate->piercings ? 'Oui' : 'Aucun' }}
                    </span>
                    <span>
                        Tatouages: {{ $model->mannequinCandidate->tatouages ? 'Oui' : 'Aucun' }}
                    </span>
                    <span>
                        Sport pratiqué: {{ $model->mannequinCandidate->sport_pratique ? 'Oui' : 'Non' }}
                    </span>
                    <!-- Disponibilité -->
                    <span>
                        Disponibilité :
                        {{ \Carbon\Carbon::parse($model->mannequinCandidate->disponibilite_debut)->format('d/m/Y') ?? '-' }}
                        au
                        {{ \Carbon\Carbon::parse($model->mannequinCandidate->disponibilite_fin)->format('d/m/Y') ?? '-' }}
                    </span>
                    @if(!empty($model->mannequinCandidate->instagram_link))
                    <span>
                        Lien Instagram: <a href="{{$model->mannequinCandidate->instagram_link}}" class="text-sm uppercase flex flex-row items-center gap-4 justify-center rounded text-primary bg-main hover:bg-main-dark px-4 py-2">
                            <span class="text-primary">
                                Profile
                            </span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5 stroke-primary-light">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                            </svg>
                        </a>
                    </span>
                    @endif

                    <!-- Identity document -->
                    <div class="col-span-2 2xl:col-span-3 flex flex-wrap 2xl:flex-nowrap justify-between items-start">
                        <!-- identity document -->
                        <div>
                            <div class="flex items-center gap-4 justify-start 2xl:w-fit w-full p-2 px-4 rounded-xl hover:bg-primary border border-c-border/50">
                                <a target="blank" href="/storage/{{ $model->mannequinCandidate->identity_document }}" class="w-full flex items-center gap-4 justify-start ">
                                    <div class="col-span-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 stroke-secondary">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                    </div>
                                    <div class="gap-4 justify-between w-full grid grid-cols-12 items-center text-secondary hover:underline">
                                        <span class="text-secondary col-span-10">
                                            <p>Pièce d'identité</p>
                                            <p class="text-secondary-light text-sm">Voir le document</p>
                                        </span>
                                        <div class="col-span-2 flex justify-end ">
                                            <svg class=" stroke-secondary size-6 cursor-pointer hover:stroke-secondary-light" data-document-id="id" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Images -->
        <div class="pt-16">
            <div class="flex items-center justify-start max-w-full overflow-hidden pb-6">
                <h2 class="text-lg font-semibold whitespace-nowrap">Photos ({{ $model->mannequinCandidate->images->count() }})</h2>
                <div class="w-full bg-main h-[1px] ml-4"></div>
            </div>

            <div class="grid lg:grid-cols-6 sm:grid-cols-4 grid-cols-3 gap-2 gap-y-4">
                @foreach($model->mannequinCandidate->images as $image)
                <div class="lazy-image-container rounded-xl relative aspect-square bg-primary">
                    <!-- Loading Spinner -->
                    <div class="rounded-xl lazy-image-loader absolute inset-0 flex items-center justify-center bg-primary-light transition-opacity duration-300">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-0" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <!-- Image -->
                    <div class="aspect-square w-full relative group rounded-xl bg-primary">
                        <a href="{{ asset('storage/' . $image->image_url) }}" data-fancybox="gallery" class="">
                            <img loading="lazy" class="lazy-image select-none rounded-xl absolute inset-0 w-11/12 h-full object-cover cursor-pointer hover:brightness-75 transition-all duration-100" src="{{ asset('storage/' . $image->image_url) }}" alt="{{ $model->name }} profile">
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="md:w-1/3 bg-primary rounded-lg p-6">
        <!-- Note -->
        @if($model->mannequinCandidate->ratings->isNotEmpty())
        <div class="mb-16">
            <div class="flex items-center justify-start max-w-full overflow-hidden pb-6">
                <h2 class="text-lg font-semibold whitespace-nowrap">Évaluation</h2>
                <div class="w-full bg-main h-[1px] ml-4"></div>
            </div>

            @php
            $ratingRanges = [
            [
            'min' => 18,
            'max' => 20,
            'mention' => 'Très bien - (félicitations des jury)',
            'comment' => 'Performance exceptionnelle',
            'classes' => 'bg-blue-500/30 border-blue-500'
            ],
            [
            'min' => 16,
            'max' => 17.99,
            'mention' => 'Très bien',
            'comment' => 'Excellente performance',
            'classes' => 'bg-orange-500/30 border-orange-500'
            ],
            [
            'min' => 14,
            'max' => 15.99,
            'mention' => 'Bien',
            'comment' => 'Le niveau de la performance dépasse le niveau attendu mais avec quelques erreurs',
            'classes' => 'bg-red-500/30 border-red-500'
            ],
            [
            'min' => 12,
            'max' => 13.99,
            'mention' => 'Assez bien',
            'comment' => 'Performance correcte mais avec un certain nombre d\'erreurs notables',
            'classes' => 'bg-blue-900/30 border-blue-900'
            ],
            [
            'min' => 10,
            'max' => 11.99,
            'mention' => 'Passable',
            'comment' => 'Le niveau de la performance remplit juste les critères requis',
            'classes' => 'bg-yellow-500/30 border-yellow-500'
            ],
            [
            'min' => 0,
            'max' => 9.99,
            'mention' => 'Faible',
            'comment' => 'Le niveau de la performance ne remplit pas les critères requis',
            'classes' => 'bg-green-500/30 border-green-500'
            ]
            ];

            $getRatingStyle = function($rating) use ($ratingRanges) {
            foreach ($ratingRanges as $range) {
            if ($rating >= $range['min'] && $rating <= $range['max']) {
                return $range;
                }
                }
                return $ratingRanges[count($ratingRanges) - 1];
                };
                @endphp

                <dl class="text-sm">
                @foreach($model->mannequinCandidate->ratings as $rating)
                @php
                $style = $getRatingStyle($rating->rating);
                @endphp
                <div class="min-h-14 overflow-hidden grid grid-cols-1 gap-1 p-4 relative group rounded-xl sm:grid-cols-3 sm:gap-4 border {{ $style['classes'] }}">
                    <dt class="font-medium text-secondary col-span-2 pl-2">
                        Note par {{ $rating->judge->name }} ({{ $style['mention'] }})
                    </dt>
                    <dd class="text-secondary-light text-end text-xl">{{ $rating->rating }}/20</dd>
                    @if($rating->comment)
                    <dd class="col-span-3 text-secondary-light pl-2">
                        {{ $rating->comment }}
                    </dd>
                    @endif
                </div>
                @endforeach
                </dl>
        </div>
        @endif

        <!-- Contract -->
        <div class="">
            <div class="flex items-center justify-start max-w-full overflow-hidden pb-6">
                <h2 class="text-lg font-semibold whitespace-nowrap">Contrats ({{ $model->mannequinCandidate->contracts->count() }})</h2>
                <div class="w-full bg-main h-[1px] ml-4"></div>
            </div>
            <div class="w-full">
                @if($model->mannequinCandidate->contracts && $model->mannequinCandidate->contracts->count() > 0)
                @foreach($model->mannequinCandidate->contracts as $contract)
                <div class="pdf-widgetcursor-default gap-2 p-4 w-full rounded-xl hover:bg-primary mb-4 border border-c-border/50">
                    <div class="flex items-center gap-4 justify-start">
                        <div class="col-span-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 stroke-secondary">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                            </svg>
                        </div>
                        <div class="w-full flex items-center gap-4 justify-between">
                            <a href="{{ asset('storage/' . $contract->contract_url) }}" target="_blank" class="w-full grid grid-cols-12 items-center text-secondary hover:underline">
                                <span class="text-secondary col-span-10">
                                    <p>Contrat #{{$contract->id}}</p>
                                    <p class="text-secondary-light flex items-center text-sm">Voir <span class="hidden 2xl:block text-secondary-light"> le document</span></p>
                                </span>
                                <div class="col-span-2 flex justify-end ">
                                    <svg class=" stroke-secondary size-6 contractDoc cursor-pointer hover:stroke-secondary-light" data-document-id="id" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="mt-6 items-center w-full flex flex-wrap 2xl:flex-nowrap justify-between gap-4">
                        <h4 class='whitespace-nowrap text-sm'>Statut du contrat: </h4>
                        <small class="bg-main/20 px-4 rounded-full py-1 text-center">
                            @switch($contract->status)
                            @case('pending')
                            <span class="text-sm">En attente de signature</span>
                            @break

                            @case('active')
                            <span class="text-sm">Signé et valide</span>
                            @break

                            @case('inactive')
                            <span class="text-sm">Terminé</span>
                            @break

                            @default
                            <span class="text-sm">Statut inconnu</span>
                            @endswitch
                        </small>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>


    <!-- Downlaod PDF -->
    <!-- <div class="pt-16">
        <div class="flex items-center justify-start max-w-full pb-6">
            <h2 class="text-lg font-semibold whitespace-nowrap">La fiche du mannequin</h2>
            <div class="w-full bg-main h-[1px] ml-4"></div>
        </div>

        <a target="_blank" class="py-2 px-4 rounded-lg bg-main hover:bg-main-dark text-primary w-full text-center inline-block" href="{{ route('model.download.pdf', ['id' => $model->id]) }}">Télécharger la fiche du mannequin</a>
    </div> -->
    </div>
</main>
@endsection
