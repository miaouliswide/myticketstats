<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ticket Stats') }} - @yield('title')</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen">
        <!-- Navigation (optional, included if using Breeze) -->
        @if (auth()->check())
            <nav class="bg-white shadow">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-gray-800">{{ config('app.name', 'Ticket Stats') }}</a>
                            </div>
                            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <a href="{{ route('dashboard') }}" class="border-b-2 {{ Route::is('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 text-sm font-medium">Dashboard</a>
                                <a href="{{ route('tickets.index') }}" class="border-b-2 {{ Route::is('tickets.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 text-sm font-medium">Tickets</a>
                                <a href="{{ route('tickets.statistics') }}" class="border-b-2 {{ Route::is('tickets.statistics') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700' }} inline-flex items-center px-1 pt-1 text-sm font-medium">Statistics</a>
                            </div>
                        </div>
                        <div class="hidden sm:ml-6 sm:flex sm:items-center">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="text-gray-500 hover:text-gray-700 text-sm font-medium">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
        @endif

        <!-- Page Content -->
        <main class="py-6">
            @yield('content')
        </main>
    </div>
</body>
</html>
