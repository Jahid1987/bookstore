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
        @yield('admin_css')
    </head>
    <body>
        <div id="app">
            @include('layouts.partials.navbar')

            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3">
                        @include('layouts.partials.sidebar')
                    </div>
                    <div class="col-md-9">
                       @if(session()->has('message'))
                            <div class="alert alert-success">
                                <p>
                                    {{ session()->get('message') }}
                                </p>
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
            <main class="py-4">
            </main>
        </div>
        <!-- Scripts -->
        <script src="{{asset('js/app.js')}}"></script>
        @yield('scripts')
    </body>
</html>