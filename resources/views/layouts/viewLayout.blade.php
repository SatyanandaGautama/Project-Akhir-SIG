<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPETARUSA</title>
    <style>
        /* Gaya CSS langsung di file HTML */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .navbar {
            display: flex;
            justify-content: flex-end;
            /* Posisi elemen navbar di sebelah kanan */
            align-items: center;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 30px 50px;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
            font-size: 20px;
        }

        .navbar a:hover {
            text-decoration: underline;
        }

        .footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }


    </style>
</head>

<body>
    <div class="navbar">
        <a href="{{ url('/') }}">Home</a>
        <a href="{{ url('/map') }}">Lokasi Rumah Sakit</a>
        <a href="{{ url('/rute') }}">Rute Rumah Sakit</a>
        @if (Route::has('login'))
            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
            @else
                <a href="{{ route('login') }}">Log in</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif
            @endauth
        @endif
    </div>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
                <p>&copy; 2025 Sistem Informasi Rumah Sakit. All Rights Reserved.</p>
    </footer>



</body>

</html>
