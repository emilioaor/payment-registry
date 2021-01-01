<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}?version=1.3.0&token=ksiwmxncjdhzgwterafd" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}?version=1.3.0&token=plskfmysatdgxbzfyw" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/cropped-logo-blanco-32x32.png') }}" sizes="32x32">
    <link rel="icon" href="{{ asset('images/cropped-logo-blanco-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('images/cropped-logo-blanco-180x180.png') }}">
</head>
<body>
    <div id="app">
        @guest
            <!-- Navbar -->
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm techland-menu">
                    <div class="container">
                        <a class="navbar-brand" href="{{ Auth::guest() ? route('payment.create') : route('payment.index') }}">
                            <img class="logo" src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'Laravel') }}">
                        </a>
                        {{--  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                              <span class="navbar-toggler-icon"></span>
                          </button>--}}

                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <!-- Left Side Of Navbar -->
                            <ul class="navbar-nav mr-auto">

                            </ul>

                            <!-- Right Side Of Navbar -->
                            <ul class="navbar-nav ml-auto">
                                <!-- Authentication Links -->
                                @guest
                                    <li class="nav-item">
                                        <img class="pointer" src="{{ asset('images/en.png') }}" onclick="changeLanguage('en')">
                                        <img class="pointer ml-2" src="{{ asset('images/es.png') }}" onclick="changeLanguage('es')">
                                    </li>
                                    {{--<li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif--}}
                                @else
                                    {{--<li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }}
                                        </a>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}">
                                                {{ __('Logout') }}
                                            </a>

                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                             document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>


                                        </div>
                                    </li>--}}
                                @endguest
                            </ul>
                        </div>
                    </div>
                </nav>
        @else
            <!-- Menu header -->
            <div class="techland-menu">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    @php($currentController = get_class(Route::current()->getController()))
                    <div id="menu-header" class="menu-header">
                        @include('layouts.menu', [
                            'menu' => [
                                [
                                    'label' => 'menu.payments',
                                    'active' => $currentController === \App\Http\Controllers\PaymentController::class,
                                    'children' => [
                                        [
                                            'route' => route('payment.create'),
                                            'label' => 'menu.add_new'
                                        ],
                                        [
                                            'route' => route('payment.index'),
                                            'label' => 'menu.list'
                                        ],
                                        [
                                            'route' => route('payment.report'),
                                            'label' => 'menu.report'
                                        ]
                                    ]
                                ],
                                [
                                    'label' => 'menu.users',
                                    'active' => $currentController === \App\Http\Controllers\UserController::class,
                                    'show' => Auth::user()->isAdmin(),
                                    'children' => [
                                        [
                                            'route' => route('user.create'),
                                            'label' => 'menu.add_new'
                                        ],
                                        [
                                            'route' => route('user.index'),
                                            'label' => 'menu.list'
                                        ]
                                    ]
                                ]
                            ]
                        ])
                    </div>
                </div>
        @endguest

        <main class="py-4 {{ Auth::check() ? 'main-auth' : '' }}">
            @include('layouts.alert')

            @yield('content')
        </main>
    </div>
</body>
</html>
