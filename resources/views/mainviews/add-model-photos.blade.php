@extends('dashboard')
@section('content')

<div class="max-w-3xl mx-auto">
    <h1 class="text-lg sm:text-3xl font-semibold text-center">Ajoutez des photos pour le mannequin</h1>

    <p class="text-center mt-2">Merci de télécharger les photos du mannequin ci-dessous.</p>

    <form action="{{ route('model.store.photos') }}" method="POST" class="mt-8 space-y-6 required-password" enctype="multipart/form-data">
        @csrf

        <!-- current user id -->
        <input type="hidden" name="model_id" value="{{ $model_id }}">

        <!-- images[] Document Field -->
        <div class="w-full ">
            <x-input-label for="images[]" :value="__('Images')" />
            <div class="hidden bg-secondary/50 aspect-square  w-48 min-w-28 min-h-32"></div>
            <div class="upload-container images-upload-container stroke-primary-light mt-1">
                <div class="flex flex-wrap gap-2">
                    <div class="images-grid flex gap-1 flex-wrap"></div>
                    <label class="upload-widget p-4 bg-primary rounded-xl w-full flex justify-center items-center border border-dashed border-main" for="images">
                        <div class="grid place-items-center no-files-selected-widget">
                            <div class=" place-items-center relative rounded-lg overflow-hidden min-h-40 group w-full">
                                <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 stroke-main">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                                    </svg>
                                </div>
                                <div class="text-center mt-3">
                                    <h4 class="font-medium ">Téléverser les images</h4>
                                    <p class="text-sm text-secondary-light">La taille du fichier doit être inférieure à 1.5 Mo</p>
                                </div>
                            </div>
                            <div class="w-full pt-2 pb-2 px-4 bg-main hover:bg-main-dark text-primary text-sm font-medium rounded-lg text-center cursor-pointer transition-colors">
                                Téléverser des images
                            </div>
                        </div>
                        <div class="files-selected-widget cursor-pointer min-w-20 justify-center items-center hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 stroke-main hover:stroke-main-dark">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                        </div>
                        <input
                            id="images"
                            hidden
                            type="file"
                            name="images[]"
                            accept="image/*"
                            multiple
                            class="images-input" />
                    </label>
                </div>
                <span class="error-message text-red-500 text-sm mt-1 images-error-message"></span>
                <x-input-error :messages="$errors->get('images[]')" class="mt-2" />
            </div>
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

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button>
                Télécharger les photos
            </x-primary-button>
        </div>
    </form>

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