<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- User Container -->
    <div class="user-container">
        <div class="user-profile">
            <div class="user-avatar">FA</div>
            <div class="user-info">
                <p class="user-name">Fariz Hasim A.</p>
                <p class="user-role">Siswa</p>
            </div>
        </div>
        <div class="welcome-divider">
            <p class="welcome-text">Selamat Datang! 👋</p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <ul class="list-unstyled">
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/dashboard',
                    'icon' => 'bi-grid-fill',
                    'text' => 'Dashboard',
                    'active' => request()->is('siswa/dashboard')
                ])
                                    </li>
                                    <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/presensi',
                    'icon' => 'bi-calendar-check',
                    'text' => 'Absensi',
                    'active' => request()->is('siswa/presensi')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '/siswa/jadwal',
                    'icon' => 'bi-calendar-event',
                    'text' => 'Jadwal',
                    'active' => request()->is('siswa/jadwal')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '#',
                    'icon' => 'bi-file-earmark-text',
                    'text' => 'Laporan Nilai',
                    'active' => false
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => '#',
                    'icon' => 'bi-bar-chart-fill',
                    'text' => 'Statistik',
                    'active' => false
                ])
            </li>
        </ul>
    </nav>

    <!-- Logout Section -->
    <div class="logout-section">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout" style="background: none; border: none; width: 100%; text-align: left; display: flex; align-items: center; cursor: pointer;">
                <i class="bi bi-box-arrow-right"></i>
                <span style="margin-left: 10px;">Keluar</span>
            </button>
        </form>
    </div>
</aside>
