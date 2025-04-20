<!-- Top Links Bar -->
<div class="top-links">
    <a href="#">Jadi Partner tiket.com</a>
    <a href="#">Blibli Tiket Rewards</a>
    <a href="#">Your Orders</a>
</div>

<!-- Main Navbar -->
<nav class="navbar">
    <div class="logo">
        <img src="{{ asset('user/image/logo.png') }}" alt="tiket.com">
    </div>

    <div class="search-bar">
        <input type="text" placeholder="Event di Jakarta">
    </div>

    <div class="main-menu">
        <a href="{{ url('/') }}">Beranda</a>
        <a href="#">Kategori</a>
        <a href="#">Destinasi</a>
        <a href="#">Event</a>
        <div class="dropdown">
            <a href="#">Lainnya ▼</a>
        </div>
    </div>
    @guest
    <div class="auth-buttons">
        <a href="{{ route('login') }}" class="masuk-btn">Masuk</a>
        <a href="{{ route('register') }}" class="daftar-btn">Daftar</a>
    </div>
    @endguest

    <!-- Profile Menu (menggantikan auth-buttons) -->
    @auth
    <div class="profile-menu">
        <img class="profile-avatar" src="{{ Auth::user()->avatar }}" alt="">
        <div class="profile-name">{{ Auth::user()->username }}</div>
        <div class="dropdown-icon">▼</div>

        <!-- Dropdown Menu -->
        <div class="profile-dropdown">
            <a href="#" class="profile-dropdown-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
                Profil Saya
            </a>
            <a href="#" class="profile-dropdown-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                    <polyline points="10 9 9 9 8 9"></polyline>
                </svg>
                Pesanan Saya
            </a>
            <a href="#" class="profile-dropdown-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                Riwayat Pesanan
            </a>
            <div class="profile-dropdown-divider"></div>
            <a href="#" class="profile-dropdown-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"></circle>
                    <path
                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                    </path>
                </svg>
                Pengaturan
            </a>
            <a href="#" class="profile-dropdown-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
                Bantuan
            </a>
            <div class="profile-dropdown-divider"></div>
            <a href="{{  route('logout') }}" class="profile-dropdown-item logout-item" onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                    <polyline points="16 17 21 12 16 7"></polyline>
                    <line x1="21" y1="12" x2="9" y2="12"></line>
                </svg>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                  </form>
                Keluar
            </a>
        </div>
    </div>
    @endauth
</nav>