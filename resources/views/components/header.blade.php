@push('styles')
<style>
    .site-header {
        position: fixed;
        top: 0;
        right: 0;
        left: 0;
        z-index: 50;
        padding: 1rem;
        transition: all 0.3s ease;
    }

    .header-container {
        max-width: 84rem;
        margin: 0 auto;
    }

    .header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(76, 23, 90, 0.2);
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
    }

    .brand-section {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .brand-logo {
        height: 2rem;
        width: 2rem;
        background: linear-gradient(135deg, #4c175a 0%, #2a0e32 100%);
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        filter: drop-shadow(0 0 4px rgba(76, 23, 90, 0.8));
        transition: filter 0.3s ease;
    }

    .brand-logo:hover {
        filter: drop-shadow(0 0 8px rgba(76, 23, 90, 0.9));
    }

    .brand-text {
        color: white;
        font-weight: 700;
        font-size: 1.125rem;
    }

    @media (max-width: 640px) {
        .brand-text {
            display: none;
        }
    }

    .nav-menu {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.875rem;
    }

    .nav-link {
        color: rgb(209 213 219);
        transition: all 0.3s ease;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .nav-link:hover, .nav-link.active {
        color: #4c175a;
        text-shadow: 0 0 4px rgba(76, 23, 90, 0.4);
    }

    .nav-divider {
        height: 1.25rem;
        width: 1px;
        background-color: #4c175a;
        margin: 0 0.5rem;
    }

    .user-menu {
        position: relative;
    }

    .user-trigger {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.75rem;
        color: rgb(209 213 219);
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border-radius: 0.375rem;
    }

    .user-trigger:hover {
        /* Remove hover effect */
    }

    .user-dropdown {
        position: absolute;
        top: calc(100% + 0.5rem);
        right: 0;
        width: 220px;
        background: rgba(18, 18, 18, 0.95);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(76, 23, 90, 0.2);
        border-radius: 0.5rem;
        padding: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }

    .user-menu:hover .user-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.5rem 0.75rem;
        color: rgb(209 213 219);
        text-decoration: none;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        transition: all 0.3s ease;
    }

    .dropdown-link:hover {
        color: #4c175a;
        text-shadow: 0 0 4px rgba(76, 23, 90, 0.4);
        background: rgba(76, 23, 90, 0.1);
    }

    @media (max-width: 768px) {
        .nav-menu {
            position: fixed;
            top: 5rem;
            left: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.95);
            backdrop-filter: blur(12px);
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid rgba(76, 23, 90, 0.2);
            flex-direction: column;
            gap: 0.5rem;
            transform: translateY(-1rem);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .nav-menu.active {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }

        .nav-link {
            width: 100%;
            justify-content: center;
        }

        .nav-divider {
            width: 100%;
            height: 1px;
            margin: 0.25rem 0;
        }

        .mobile-toggle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            padding: 0;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        .mobile-toggle i {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

<header class="site-header">
    <div class="header-container">
        <div class="header-content">
            <div class="brand-section">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="brand-logo">H</div>
                    <span class="brand-text">Hajusrakendused</span>
                </a>
            </div>

            <button class="mobile-toggle d-lg-none" type="button" onclick="toggleMenu()">
                <i class="bi bi-list"></i>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="{{ route('blogs.index') }}" class="nav-link {{ Request::is('blogs*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text"></i>
                    <span>Blogs</span>
                </a>
                <a href="{{ route('maps.index') }}" class="nav-link {{ Request::is('maps*') ? 'active' : '' }}">
                    <i class="bi bi-geo-alt"></i>
                    <span>Maps</span>
                </a>
                <a href="{{ route('products.index') }}" class="nav-link {{ Request::is('products*') ? 'active' : '' }}">
                    <i class="bi bi-bag"></i>
                    <span>Shop</span>
                </a>
                <a href="{{ route('cart.index') }}" class="nav-link {{ Request::is('cart*') ? 'active' : '' }}">
                    <i class="bi bi-cart2"></i>
                    <span>Cart</span>
                </a>

                @auth
                    <div class="nav-divider"></div>
                    <div class="user-menu">
                        <div class="user-trigger">
                            <i class="bi bi-person"></i>
                            <span>{{ Auth::user()->name }}</span>
                        </div>
                        <div class="user-dropdown">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="dropdown-link">
                                    <i class="bi bi-shield-lock"></i>
                                    <span>Admin Panel</span>
                                </a>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-link w-full text-left">
                                    <i class="bi bi-box-arrow-right"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </nav>
        </div>
    </div>
</header>

@push('scripts')
<script>
function toggleMenu() {
    const menu = document.getElementById('navMenu');
    menu.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    const menu = document.getElementById('navMenu');
    const toggle = document.querySelector('.mobile-toggle');
    
    if (!menu.contains(event.target) && !toggle.contains(event.target)) {
        menu.classList.remove('active');
    }
});
</script>
@endpush 