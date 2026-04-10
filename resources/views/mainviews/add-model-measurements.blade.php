@extends('dashboard')
@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-center">
        Ajoutez les mensurations du mannequin
    </h1>

    <p class="text-center mt-2">Merci de télécharger les mensurations du mannequin ci-dessous.</p>

    <form action="{{ route('model.store.measurements', $candidate->id) }}" method="POST" class="mt-8 space-y-6">
        @csrf
        <input type="hidden" name="model_id" value="{{ $candidate->id }}">

        <div class="measurement-from grid grid-cols-2 sm:grid-cols-3 items-end gap-4 w-full [&>div]:mt-0 sm:[&>div]:mt-4">
            <!-- Common measurements for all -->
            <x-measurement-input name="head_circumference" label="Tour de tête" />
            <x-measurement-input name="neck_base_circumference" label="Tour bas encolure" />
            <x-measurement-input name="shoulder_length" label="Longueur de l'épaule" />
            <x-measurement-input name="arm_length" label="Longueur du bras" />
            <x-measurement-input name="front_width" label="Carrure devant" />
            @if($candidate->gender_identity === 'Homme')
            <x-measurement-input name="belt_circumference" label="Tour de ceinture" />
            @endif
            @if($candidate->gender_identity === 'Femme')
            <x-measurement-input name="chest_circumference" label="Tour de poitrine" />
            <x-measurement-input name="waist_circumference" label="Tour de taille" />
            <x-measurement-input name="small_hips_circumference" label="Tour des petites hanches" />
            @endif
            <!-- <x-measurement-input name="hips_circumference" label="Tour de bassin" /> -->
            <x-measurement-input name="thigh_circumference" label="Tour de cuisse" />
            <x-measurement-input name="knee_circumference" label="Tour de genou" />
            <x-measurement-input name="calf_circumference" label="Tour de mollet" />
            <x-measurement-input name="ankle_circumference" label="Tour de cheville" />
            <x-measurement-input name="upper_arm_circumference" label="Tour du bras" />
            <x-measurement-input name="elbow" label="Tour de coude" />
            <x-measurement-input name="forearm_circumference" label="Tour de l'avant-bras" />
            <x-measurement-input name="wrist_size" label="Tour de poignet" />
            <x-measurement-input name="wrist_to_elbow" label="Longueur du poignet au coude" />
            <x-measurement-input name="inseam_length" label="Longueur de l'entrejambe" />
            <x-measurement-input name="knee_height" label="Hauteur du genou" />
            <x-measurement-input name="side_height" label="Hauteur latérale à terre" />
            <x-measurement-input name="total_height" label="Taille" />
            <x-measurement-input name="tour_de_hanches" label="Tour de hanche" />
            <x-measurement-input
                name="pointure"
                label="Pointure" />
            <x-measurement-input
                name="poids"
                label="Poids" />
            <!-- <div class="w-full max-w-sm relative mt-4">
                <label class="block mb-1 text-sm">Confection</label>
                <div class="relative">
                    <input
                        type="text"
                        name="confection"
                        class="w-full bg-transparent placeholder:text-secondary-light/50 text-sm border border-c-border rounded-md pl-3 pr-20 py-2 transition duration-300 ease focus:outline-none focus:border-c-border hover:border-c-border shadow-sm focus:shadow appearance-none [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" />
                </div>
            </div> -->
        </div>

        <div>
            <label for="comment">Ajouter un commentaire (optionnelle)</label>
            <div class="overflow-hidden mt-2 rounded-lg border border-c-border shadow-sm focus-within:border-main focus-within:ring-1 focus-within:ring-main add-cemment space-y-0">
                <textarea
                    class="comment-textarea w-full resize-none border-none align-top focus:ring-0 sm:text-sm bg-primary/40 placeholder:text-secondary-light/70"
                    rows="3"
                    placeholder="Ajouter un commentaire..."
                    name="comment"></textarea>
            </div>
        </div>

        <div class="mt-6">
            <x-primary-button>
                Enregistrer les mensurations
            </x-primary-button>
        </div>
    </form>

    @if (session('success'))
    <div class="z-[9999999] max-w-full ml-6 toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-success/50 backdrop-blur rounded-xl p-4 border-success text-success translate-y-[150%] session-alert">
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
    <div class="toast transition-all duration-700 fixed z-100 bottom-6 right-6 bg-error/50 backdrop-blur rounded-xl border p-4 focus:!ring-error !border-error text-error translate-y-[150%]">
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
            <span>Veuillez corriger les champs surlignés..</span>
        </div>
    </div>

</div>
@endsection