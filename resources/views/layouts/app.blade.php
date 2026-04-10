<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Shawn Agenc’y management app') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <!-- Icon -->
    <link rel="icon" type="image/png" href="https://shawnagency.fr/wp-content/uploads/2024/03/Fichier-18@2x.png">
    <link rel="shortcut icon" type="image/png" href="https://shawnagency.fr/wp-content/uploads/2024/03/Fichier-18@2x.png">
    <link rel="apple-touch-icon" href="https://shawnagency.fr/wp-content/uploads/2024/03/Fichier-18@2x.png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="font-sans antialiased">
    <div class="min-h-svh bg-primary-light">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
        <header>
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

</body>

</html>
