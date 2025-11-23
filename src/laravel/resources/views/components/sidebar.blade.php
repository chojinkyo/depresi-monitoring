<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- Close Button (hanya tampil di mobile saat sidebar dibuka) -->
    <!-- <button class="sidebar-close" id="sidebarClose">
        <i class="bi bi-arrow-left"></i>
    </button> -->

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
                    'href' => '/siswa',
                    'icon' => 'bi-grid-fill',
                    'text' => 'Dashboard',
                    'active' => request()->is('siswa')
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
                    'href' => '#',
                    'icon' => 'bi-calendar-event',
                    'text' => 'Jadwal',
                    'active' => false
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
        <button class="btn-logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Keluar</span>
        </button>
    </div>
</aside>
