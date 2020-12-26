@extends('layouts.app')

@section('content')
    @php($currentController = get_class(Route::current()->getController()))
    <div class="container techland-menu">
        @auth
            <div id="menu-header" class="menu-header">
                @include('layouts.menu', [
                    'menu' => [
                        [
                            'label' => __('menu.payments'),
                            'active' => $currentController === \App\Http\Controllers\PaymentController::class,
                            'children' => [
                                [
                                    'route' => route('payment.create'),
                                    'label' => __('menu.add_new')
                                ],
                                [
                                    'route' => route('payment.index'),
                                    'label' => __('menu.list')
                                ],
                                [
                                    'route' => route('payment.index'),
                                    'label' => __('menu.report')
                                ]
                            ]
                        ],
                        [
                            'label' => __('menu.users'),
                            'active' => $currentController === \App\Http\Controllers\UserController::class,
                            'show' => Auth::user()->isAdmin(),
                            'children' => [
                                [
                                    'route' => route('payment.index'),
                                    'label' => __('menu.add_new')
                                ],
                                [
                                    'route' => route('payment.index'),
                                    'label' => __('menu.list')
                                ]
                            ]
                        ]
                    ]
                ])
            </div>
        @endauth
        <div>
            @yield('main')
        </div>
    </div>
@endsection
