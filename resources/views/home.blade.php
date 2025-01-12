@extends('layouts.viewLayout')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
<style>
        /* Loading Screen */
        #loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #loading img {
            width: 100px;
            height: 100px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Hero Section */
        .hero {
            position: relative;
            text-align: center;
            color: white;
        }

        .hero img {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            filter: brightness(50%);
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .hero-text h1 {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: fadeInDown 1.5s;
        }

        .hero-text p {
            font-size: 1.5rem;
            margin-bottom: 30px;
            animation: fadeInUp 1.5s;
        }

        .btn {
            padding: 10px 20px;
            background-color: #fcb800;
            color: black;
            text-decoration: none;
            font-weight: bold;
            border-radius: 5px;
            animation: fadeIn 2s;
        }

        .btn:hover {
            background-color: #ffa500;
        }
    </style>
@section('content')

    <div id="loading">
        <img src="{{ asset('refresh.png') }}" alt="Loading">
    </div>


    <div class="hero">
        <img src="{{ asset('image.jpg') }}" alt="Background Image">
        <div class="hero-text">
            <h1 class="animate__animated animate__fadeInDown">SIPETARUSA</h1>
            <p class="animate__animated animate__fadeInUp">Sistem Pemetaan Rumah Sakit di Kabupaten Badung dan Kota Denpasar</p>
            <a href="{{ url('/map') }}" class="btn animate__animated animate__fadeIn">Lihat Detail</a>
        </div>
    </div>

    <script>
        // Hide loading animation after page loads
        window.addEventListener('load', function () {
            const loading = document.getElementById('loading');
            loading.style.opacity = '0.5';
            setTimeout(() => {
                loading.style.display = 'none';
            }, 500); // Transition duration
        });
    </script>

@endsection


