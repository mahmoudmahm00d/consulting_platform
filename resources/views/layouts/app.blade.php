<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="{{asset('libs/bootstrap/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <!-- Scripts -->
    <script src="{{asset('libs/bootstrap/js/bootstrap.bundle.min.js')}}" defer></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    @role('Admin')
                    <ul class="navbar-nav me-auto">
                        <a class="nav-link" href="{{ url('/categories') }}">{{ __('Categories') }}</a>
                        <a class="nav-link" href="{{ url('/contactInfoTypes') }}">{{ __('Contact Info Types') }}</a>
                        <a class="nav-link" href="{{ url('/users') }}">{{ __('Users') }}</a>
                    </ul>
                    @endrole

                    <!-- Right Side Of Navbar -->
                    <x-auth-menu></x-auth-menu>
                </div>
            </div>
        </nav>

        <main class="container py-4">
            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif
            @yield('content')
        </main>
    </div>
    @yield('scripts')
</body>
</html>
