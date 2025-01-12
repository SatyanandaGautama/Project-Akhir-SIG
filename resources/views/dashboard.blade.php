@extends('layouts.app')
@section('content')
    <div class="container mx-auto py-12 flex justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div
            class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 max-w-sm w-full flex flex-col items-center animate-fade-in">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-2">
                Success!
            </h2>
            <p class="text-gray-600 dark:text-gray-400 text-center mb-4">
                You have logged in successfully.
            </p>
        </div>
    </div>

    <style>
        /* Animation for fade-in effect */
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.5s ease-out;
        }
    </style>
@endsection
