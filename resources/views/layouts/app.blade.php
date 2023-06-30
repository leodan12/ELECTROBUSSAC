<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ELECTROBUS S.A.C') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- Styles-->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Agregar el link del archivo CSS de select2 -->
    <link href="{{ asset('admin/css/select2.min.css') }}" rel="stylesheet" />
    @livewireStyles
</head>

<body style="background-image: url('/admin/images/auth/lockscreen-bg.jpg'); background-size:cover; background-repeat: no-repeat; margin: 0; height: 100vh; widht:100vh;">
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-light shadow-sm" >
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'ELECTROBUS S.A.C') }}
                </a>
               
 
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
        <!-- Scripts -->
        <script src="{{ asset('assets/js/jquery-3.6.3.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('admin/js/select2.min.js') }}"></script>
        @livewireScripts
</body>
</html>
