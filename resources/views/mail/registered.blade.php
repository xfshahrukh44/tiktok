<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts icons-->

    <!-- bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-white">
<div id="app">
    <main class="py-4 ">
        <div class="container pt-5">

            <div class="row">
                <div class="col-12 col-sm-12 col-md-2 col-lg-3 col-xl-3"></div>
                <div class="col-12 col-sm-12 col-md-8 col-lg-6 col-xl-6">
                    <div class="">
                        <div class="card-body text-center">
                            <div>
                                <img src="{{asset('logo.png')}}" class="w-25 card-img-top" alt="..." style="margin: auto;">
                            </div>
                            <div class="mt-4">
                                <span class="heading_1">Registro exitoso</span>
                            </div>
                        </div>
                        <ul class="mt-5 list-group list-group-flush" style="padding-left: 30px;">
                            <li class="list-group-item border-bottom-0">
                                <p class="text-center" style="font-size: 14px;">
                                    {{$text}}
                                </p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class=" col-3 col-sm-3 col-md-2 col-lg-3 col-xl-3"></div>
            </div>

        </div>
    </main>
</div>
</body>
</html>
