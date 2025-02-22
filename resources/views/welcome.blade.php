@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="max-w-2xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md text-center">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Welcome to Ticket Stats</h1>
        <p class="text-gray-600 mb-6">Please log in or register to manage your tickets.</p>

        @if (Route::has('login'))
            <div class="flex justify-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Go to Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Log In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Register</a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
@endsection
