<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="flex min-h-screen">
            @include('layouts.sidebar')
            
            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden md:ml-64">
                @include('layouts.topbar')
                
                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                    @isset($header)
                        <div class="bg-white shadow mb-6">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </div>
                    @endisset
                    
                    <div class="container mx-auto px-6 py-8">
                        @if(isset($slot))
                            {{ $slot }}
                        @else
                            @yield('content')
                        @endif
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
