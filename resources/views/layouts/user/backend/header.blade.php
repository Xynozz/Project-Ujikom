<nav class="navbar navbar-expand-lg fixed-top transparent-navbar">
    <div class="container">
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <a class="navbar-brand d-flex align-items-center" href="/">
                    <img src="{{ asset('user/image/logo.png') }}" alt="Logo" class="brand-logo">
                </a>
                <li class="nav-item">
                <form class="search-form ">
                    <div class="input-group">
                        <input type="search" class="form-control search-input" placeholder="Cari destinasi wisata...">
                        <button class="btn btn-search" type="submit">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </form>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#home">
                        <i class='bx bx-home-alt-2'></i>
                        <span>Beranda</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#destinations">
                        <i class='bx bx-map'></i>
                        <span>Destinasi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#categories">
                        <i class='bx bx-category'></i>
                        <span>Kategori</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#promos">
                        <i class='bx bx-gift'></i>
                        <span>Promo</span>
                    </a>
                </li>
                <div class="nav-auth">
                    <a href="#login" class="nav-link-auth">
                        <i class='bx bx-log-in-circle'></i>
                        <span>Masuk</span>
                    </a>
                    <a href="#register" class="btn btn-register">
                        <i class='bx bx-user-plus'></i>
                        <span>Daftar</span>
                    </a>
                </div>
            </ul>
        </div>
    </div>
</nav>