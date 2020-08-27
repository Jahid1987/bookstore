<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/admin.css') }}" type="text/css">
        @yield('css')
        <!-- CDN links -->
        @yield('links')
    </head>
    <body>
        @include('admin.partials.navbar')
        
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            @yield('content')
        </main>
        <!-- Scripts -->
        <script src="{{asset('js/app.js')}}"></script>
        <!-- Icons -->
        <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
        <script>
        feather.replace()
        </script>
        @yield('scripts')
    </body>
</html>