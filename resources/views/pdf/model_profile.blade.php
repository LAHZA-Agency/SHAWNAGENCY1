<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Model Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            border: 1px solid #ccc;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
        }

        .header p {
            margin: 5px 0;
        }

        .profile-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .profile-section img {
            width: 200px;
            height: auto;
            border: 1px solid #ccc;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table th,
        .info-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .info-table th {
            background-color: #f4f4f4;
        }

        .gallery {
            display: flex;
            justify-content: space-between;
        }

        .gallery img {
            width: 100px;
            height: auto;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Naïs Guennegou</h1>
            <p>Shawn Agency</p>
            <p>34 Avenue des Champs-Élysées, 75008 Paris</p>
            <p>contact@shawnagency.fr | +33 6 11 17 43 22</p>
        </div>

        <img src="data:image/png;base64,{{ $logoData }}">

        <img src="{{ asset('storage/logo.png') }}" alt="Logo">
        <img src="{{ url('storage/logo.png') }}" alt="Logo">
        <img src="{{ Storage::url('logo.png') }}" alt="Logo">
        <img src="{{ asset('images/logo.png') }}" alt="Logo">
        <img src="http://127.0.0.1:8000/storage/logo.png" alt="Logo">
        <!-- <img src="{{ public_path('images/logo.png') }}" alt=""> -->
        <!-- <img src="{{ storage_path('app/public/logo.png') }}" alt="Logo"> -->


        <div>
            <p><strong>Languages:</strong> French, English, Spanish</p>
            <p><strong>Piercings:</strong> None</p>
            <p><strong>Tattoos:</strong> None</p>
            <p><strong>Clothing Size:</strong> L-42</p>
            <p><strong>Sports:</strong> Yes</p>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <th>Height</th>
            <th>Weight</th>
            <th>Bust</th>
            <th>Waist</th>
            <th>Hips</th>
            <th>Shoe</th>
            <th>Eyes</th>
            <th>Hair</th>
        </tr>
        <tr>
            <td>177 cm</td>
            <td>69.6 kg</td>
            <td>76 cm</td>
            <td>69 cm</td>
            <td>92 cm</td>
            <td>36.2 EU</td>
            <td>Brown</td>
            <td>Blonde</td>
        </tr>
    </table>

    <div class="gallery">
    </div>

    <main>
        <h1>Name</h1>
        <h3>Shawn Agenc’y</h3>
    </main>
    </div>
</body>

</html>