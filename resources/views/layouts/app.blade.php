<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>
        @yield('title', config('app.name', 'Student Management System'))
    </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>

    {{-- Navbar --}}
    @include('partials.navbar')

    <div class="container-fluid">
        <div class="row">

            {{-- Sidebar --}}
            @include('partials.sidebar')

            {{-- Main Content --}}
            <main class="col-lg-10 px-3 px-lg-4 pt-3">

                @yield('content')

            </main>

        </div>
    </div>
@stack('scripts')
</body>

</html>