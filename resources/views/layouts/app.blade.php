<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="data:,">
    <title>@yield('title', 'Hajusrakendused')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #00c8e3;
            --primary-hover: #00a7bf;
            --background-color: #121212;
            --secondary-bg: #1a1a1a;
            --text-color: #f5f5f5;
            --border-color: #2a2a2a;
            --header-height: 70px;
            --nav-item-radius: 8px;
        }
        
        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
            padding-top: var(--header-height);
        }
        
        .bg-custom-dark {
            background-color: var(--secondary-bg);
        }
        
        .text-cyan {
            color: var(--primary-color);
        }
        
        .btn-cyan {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #000;
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        
        .btn-cyan:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 200, 227, 0.3);
        }
        
        .btn-outline-cyan {
            border-color: var(--primary-color);
            color: var(--primary-color);
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            transition: all 0.3s ease;
        }
        
        .btn-outline-cyan:hover {
            background-color: var(--primary-color);
            color: #000;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 200, 227, 0.3);
        }
        
        .navbar {
            height: var(--header-height);
            background-color: rgba(26, 26, 26, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            position: fixed;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030;
            padding: 0;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.2);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            color: var(--text-color) !important;
            padding: 0;
            margin-right: 2rem;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .navbar-brand::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 40px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .navbar-brand span {
            color: var(--primary-color);
        }
        
        .nav-link {
            font-size: 0.9rem;
            font-weight: 500;
            color: #bbb !important;
            padding: 8px 16px;
            margin: 0 2px;
            border-radius: var(--nav-item-radius);
            transition: all 0.3s ease;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .nav-link:hover {
            color: var(--text-color) !important;
            background-color: transparent;
        }
        
        .nav-link.active {
            color: var(--primary-color) !important;
            background-color: transparent;
            font-weight: 600;
        }
        
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background-color: var(--primary-color);
            border-radius: 10px;
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            content: none;
        }
        
        .nav-item {
            position: relative;
        }
        
        .nav-item:not(:last-child)::after {
            content: '';
            position: absolute;
            right: -4px;
            top: 50%;
            transform: translateY(-50%);
            height: 20px;
            width: 1px;
            background-color: var(--primary-color);
        }
        
        .navbar-nav {
            gap: 0.5rem;
            align-items: center;
            padding: 6px;
            border-radius: 12px;
        }
        
        .dropdown-menu {
            min-width: 200px;
            margin-top: 0.5rem;
            padding: 0.5rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-item {
            color: #999 !important;
            font-size: 0.9rem;
            padding: 0.5rem 0.75rem;
            border-radius: 4px;
        }
        
        .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: var(--text-color) !important;
        }
        
        .dropdown-item i {
            margin-right: 0.5rem;
        }
        
        .alert {
            border-radius: 0.5rem;
            border: none;
        }
        
        .card {
            background-color: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
        }
        
        .card-header {
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid var(--border-color);
        }
        
        .table {
            color: var(--text-color);
        }
        
        .table th, 
        .table td,
        .table thead th {
            color: var(--text-color) !important;
        }
        
        .table-striped > tbody > tr:nth-of-type(odd),
        .table-striped > tbody > tr:nth-of-type(even) {
            color: var(--text-color);
            background-color: var(--secondary-bg);
        }
        
        .table-striped > tbody > tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.1);
        }
        
        .text-muted, 
        small, 
        .card-body p.text-muted,
        .text-muted small {
            color: var(--text-color) !important;
        }
        
        .leaflet-popup-content {
            color: #000;
        }
        
        .form-control,
        .form-select,
        .input-group-text {
            background-color: var(--secondary-bg);
            color: var(--text-color);
            border-color: var(--border-color);
        }
        
        .form-control:focus,
        .form-select:focus {
            background-color: var(--secondary-bg);
            color: var(--text-color);
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(0, 200, 227, 0.25);
        }
        
        input[type="number"] {
            background-color: var(--secondary-bg);
            color: var(--text-color);
        }
        

        .card-img-top {
            height: 200px;
            object-fit: cover;
            background-color: var(--secondary-bg);
        }
        

        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus,
        .form-select:-webkit-autofill,
        .form-select:-webkit-autofill:hover,
        .form-select:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text-color);
            -webkit-box-shadow: 0 0 0px 1000px var(--secondary-bg) inset;
            transition: background-color 5000s ease-in-out 0s;
        }
        
        @media (max-width: 992px) {
            .navbar-collapse {
                background-color: var(--secondary-bg);
                padding: 1rem;
                border-radius: 12px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
                margin-top: 10px;
            }
            
            .navbar-toggler {
                border: none;
                color: var(--primary-color);
            }
            
            .navbar-toggler:focus {
                box-shadow: none;
            }
            
            .nav-item:not(:last-child)::after {
                display: none;
            }
            
            .navbar-nav {
                background-color: transparent;
                padding: 0;
            }
        }

        .nav-link.dropdown-toggle::after {
            content: none;
        }

        .nav-link.dropdown-toggle:hover::after {
            display: inline-block;
            opacity: 1;
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-left: 0.3em solid transparent;
        }

        .nav-link:hover::after {
            content: none;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}"><span>H</span>ajusrakendused</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('weather*') ? 'active' : '' }}" href="{{ route('weather.index') }}">
                            Weather
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('blogs*') ? 'active' : '' }}" href="{{ route('blogs.index') }}">
                            Blogs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('maps*') ? 'active' : '' }}" href="{{ route('maps.index') }}">
                            Maps
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('monsters*') ? 'active' : '' }}" href="{{ route('monsters.index') }}">
                            Monsters API
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('api-viewer*') ? 'active' : '' }}" href="{{ route('api-viewer.index') }}">
                            API View
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('spotify*') ? 'active' : '' }}" href="{{ route('spotify.index') }}">
                            <i class="bi bi-spotify me-1"></i>Spotify
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            Shop
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('cart*') ? 'active' : '' }}" href="{{ route('cart.index') }}">
                            Cart
                        </a>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end bg-custom-dark">
                                @if(Auth::user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-shield-lock"></i>Admin Panel
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html> 