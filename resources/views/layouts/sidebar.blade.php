<nav class="navbar-vertical navbar bg-dark navbar-expand-lg">
    <div class="nav-scroller">
        <!-- Brand -->
        <a class="navbar-brand text-white fw-bold px-4 py-3" href="{{ url('dashboard') }}">
          JC
        </a>

        <!-- Navbar -->
        <ul class="navbar-nav flex-column" id="sideNavbar">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->is('dashboard') ? 'active' : '' }}"
                   href="{{ url('/dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i><i class="bi bi-house"></i>
                    Dashboard
                </a>
            </li>

            <!-- Components -->
            <li class="nav-item">
                <a class="nav-link text-white"
                   data-bs-toggle="collapse"
                   href="#componentsMenu"
                   role="button">
                    <i class="bi bi-box-seam me-2"></i>
                    Barang
                    <span class="ms-auto">
                        <i class="bi bi-chevron-down"></i>
                    </span>
                </a>

                <div class="collapse" id="componentsMenu">
                    <ul class="nav flex-column ms-3">
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="/barang">
                                Barang Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white-50" href="/produk">
                                Barang Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link text-white {{ request()->is('dashboard') ? 'active' : '' }}"
                   href="{{ url('/dashboard') }}">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Laporan
                </a>
            </li>

            <!-- Divider -->
            <li class="nav-item my-4">
                <hr class="border-secondary">
            </li>

            <!-- Logout -->
            <li class="nav-item px-3">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        onclick="return confirm('Yakin ingin logout?')"
                        class="btn btn-danger w-100">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Logout
                    </button>
                </form>
            </li>

        </ul>
    </div>
</nav>
<style>
.navbar-vertical {
  width: 260px;
  min-height: 100vh;
  position: fixed;
  background-color: #212529;
  overflow-y: auto;
  margin-left: 0;
  transition: margin 0.3s ease;
}
#page-content {
  margin-left: 260px;
  transition: margin 0.3s ease;
}
body.sidebar-collapsed .navbar-vertical {
  margin-left: -260px;
}
body.sidebar-collapsed #page-content {
  margin-left: 0;
}
</style>
