<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Admin Panel')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .nav-link {
            color: black;
        }

        .active {
            color: #0d6efd;
        }

        #sidebarMenu {
            height: 100%;
            min-height: 100vh;
            overflow: scroll;
        }
        .zoomZone {
            display: none;
            background-color: black;
            width: 100vw;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 9999999999;
        }

        .zoomImageZone {
            position: relative;
            width: 50%;
            left: 25%;
            top: 5%;
        }

        .zoom-image {
            cursor: pointer;
        }
    </style>
</head>

<body>
<header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="#">Admin</a>
    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-nav">
        <div class="nav-item">
            @if(auth()->check())
            <form action="{{route('admin.logout')}}" method="GET">
                <span style="color:white!important"> {{auth()->user()->email}}</span>
                @csrf
                <button style="color:white!important;display: inline" class="nav-link px-3" type="submit">Sign out</button>
            </form>
            @endif
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="row">
        @if(!auth()->guest())

            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse" style="height: 100%!important">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        @if(auth()->user()->role == \App\Models\User::ROLES_SUPER_ADMIN)
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.videos.index') ? "active" : ""}}" aria-current="page" href="{{route('admin.videos.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-camera-reels-fill" viewBox="0 0 16 16">
                                    <path d="M6 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                    <path d="M9 6a3 3 0 1 1 0-6 3 3 0 0 1 0 6"/>
                                    <path
                                        d="M9 6h.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 7.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 16H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2z"/>
                                </svg>
                                Videos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.users.index') ? "active" : ""}}" aria-current="page" href="{{route('admin.users.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-people-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                </svg>
                                Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.products.index') ? "active" : ""}}" aria-current="page" href="{{route('admin.products.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-box-seam-fill" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                          d="M15.528 2.973a.75.75 0 0 1 .472.696v8.662a.75.75 0 0 1-.472.696l-7.25 2.9a.75.75 0 0 1-.557 0l-7.25-2.9A.75.75 0 0 1 0 12.331V3.669a.75.75 0 0 1 .471-.696L7.443.184l.01-.003.268-.108a.75.75 0 0 1 .558 0l.269.108.01.003zM10.404 2 4.25 4.461 1.846 3.5 1 3.839v.4l6.5 2.6v7.922l.5.2.5-.2V6.84l6.5-2.6v-.4l-.846-.339L8 5.961 5.596 5l6.154-2.461z"/>
                                </svg>
                                Products
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.plans.index') ? "active" : ""}}" aria-current="page" href="{{route('admin.plans.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-currency-dollar" viewBox="0 0 16 16">
                                    <path
                                        d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"/>
                                </svg>
                                Plans
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.plan_charge_requests.index') ? "active" : ""}}" aria-current="page"
                               href="{{route('admin.plan_charge_requests.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                     class="bi bi-piggy-bank-fill" viewBox="0 0 16 16">
                                    <path
                                        d="M7.964 1.527c-2.977 0-5.571 1.704-6.32 4.125h-.55A1 1 0 0 0 .11 6.824l.254 1.46a1.5 1.5 0 0 0 1.478 1.243h.263c.3.513.688.978 1.145 1.382l-.729 2.477a.5.5 0 0 0 .48.641h2a.5.5 0 0 0 .471-.332l.482-1.351c.635.173 1.31.267 2.011.267.707 0 1.388-.095 2.028-.272l.543 1.372a.5.5 0 0 0 .465.316h2a.5.5 0 0 0 .478-.645l-.761-2.506C13.81 9.895 14.5 8.559 14.5 7.069q0-.218-.02-.431c.261-.11.508-.266.705-.444.315.306.815.306.815-.417 0 .223-.5.223-.461-.026a1 1 0 0 0 .09-.255.7.7 0 0 0-.202-.645.58.58 0 0 0-.707-.098.74.74 0 0 0-.375.562c-.024.243.082.48.32.654a2 2 0 0 1-.259.153c-.534-2.664-3.284-4.595-6.442-4.595m7.173 3.876a.6.6 0 0 1-.098.21l-.044-.025c-.146-.09-.157-.175-.152-.223a.24.24 0 0 1 .117-.173c.049-.027.08-.021.113.012a.2.2 0 0 1 .064.199m-8.999-.65a.5.5 0 1 1-.276-.96A7.6 7.6 0 0 1 7.964 3.5c.763 0 1.497.11 2.18.315a.5.5 0 1 1-.287.958A6.6 6.6 0 0 0 7.964 4.5c-.64 0-1.255.09-1.826.254ZM5 6.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0"/>
                                </svg>
                                Plan Charge Requests
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.shopify_error_logs.index') ? "active" : ""}}" aria-current="page" href="{{route('admin.shopify_error_logs.index')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#EF4444"
                                     class="bi bi-bug" viewBox="0 0 16 16">
                                    <path
                                        d="M4.355.522a.5.5 0 0 1 .623.333l.291.956A5 5 0 0 1 8 1c1.007 0 1.946.298 2.731.811l.29-.956a.5.5 0 1 1 .957.29l-.41 1.352A5 5 0 0 1 13 6h.5a.5.5 0 0 0 .5-.5V5a.5.5 0 0 1 1 0v.5A1.5 1.5 0 0 1 13.5 7H13v1h1.5a.5.5 0 0 1 0 1H13v1h.5a1.5 1.5 0 0 1 1.5 1.5v.5a.5.5 0 1 1-1 0v-.5a.5.5 0 0 0-.5-.5H13a5 5 0 0 1-10 0h-.5a.5.5 0 0 0-.5.5v.5a.5.5 0 1 1-1 0v-.5A1.5 1.5 0 0 1 2.5 10H3V9H1.5a.5.5 0 0 1 0-1H3V7h-.5A1.5 1.5 0 0 1 1 5.5V5a.5.5 0 0 1 1 0v.5a.5.5 0 0 0 .5.5H3c0-1.364.547-2.601 1.432-3.503l-.41-1.352a.5.5 0 0 1 .333-.623M4 7v4a4 4 0 0 0 3.5 3.97V7zm4.5 0v7.97A4 4 0 0 0 12 11V7zM12 6a4 4 0 0 0-1.334-2.982A3.98 3.98 0 0 0 8 2a3.98 3.98 0 0 0-2.667 1.018A4 4 0 0 0 4 6z"/>
                                </svg>
                                Shopify Error Logs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.tickets.new') ? "active" : ""}}" aria-current="page" href="{{route('admin.tickets.new')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#3B82F6" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                    <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>
                                New Tickets {{cache()->get(\App\Models\Ticket::STATUS_CACHE_COUNT_MAP[\App\Models\Ticket::STATUS_NEW])}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.tickets.read') ? "active" : ""}}" aria-current="page" href="{{route('admin.tickets.read')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#9CA3AF" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                    <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>
                                Read Tickets {{cache()->get(\App\Models\Ticket::STATUS_CACHE_COUNT_MAP[\App\Models\Ticket::STATUS_READ])}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.tickets.in-progress') ? "active" : ""}}" aria-current="page" href="{{route('admin.tickets.in-progress')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#F59E0B" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                    <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>
                                In Progress Tickets {{cache()->get(\App\Models\Ticket::STATUS_CACHE_COUNT_MAP[\App\Models\Ticket::STATUS_IN_PROGRESS])}}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{request()->routeIs('admin.tickets.resolved') ? "active" : ""}}" aria-current="page" href="{{route('admin.tickets.resolved')}}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#10B981" class="bi bi-chat-dots-fill" viewBox="0 0 16 16">
                                    <path d="M16 8c0 3.866-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7M5 8a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                </svg>
                                Resolved Tickets {{cache()->get(\App\Models\Ticket::STATUS_CACHE_COUNT_MAP[\App\Models\Ticket::STATUS_RESOLVED])}}
                            </a>
                        </li>

                    </ul>
                </div>
            </nav>
        @endif

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 pt-5">
            <div class="chartjs-size-monitor">
                <div class="chartjs-size-monitor-expand">
                    <div class=""></div>
                </div>
                <div class="chartjs-size-monitor-shrink">
                    <div class=""></div>
                </div>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @elseif (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>

    </div>
</div>

<footer class="text-center mt-5 text-muted">
    <p>&copy; {{ date('Y') }} My App</p>
</footer>

<div class="zoomZone">
    <img class="zoomImageZone">
</div>

<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>
<script>
    $('.zoom-image').click(function(e) {
        e.preventDefault()
        e.stopPropagation()
        $('.zoomImageZone').attr('src', $(this).attr('src'))
        $('.zoomZone').show()
    })

    $('.zoomZone').click(function () {
        $('.zoomImageZone').attr('src', '')
        $(this).hide()
    })
</script>
</body>
</html>
