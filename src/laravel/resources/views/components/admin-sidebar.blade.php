<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <!-- User Container -->
    <div class="user-container">
        <div class="user-profile">
            <div class="user-avatar">ADM</div>
            <div class="user-info">
                <p class="user-name">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="user-role">Administrator</p>
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
                    'href' => route('admin.dashboard'),
                    'icon' => 'bi-grid-fill',
                    'text' => 'Dashboard',
                    'active' => request()->routeIs('admin.dashboard')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.kelas.index'),
                    'icon' => 'bi-building',
                    'text' => 'Kelas',
                    'active' => request()->routeIs('admin.kelas.*')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.ta.index'),
                    'icon' => 'bi-calendar3',
                    'text' => 'Tahun Akademik',
                    'active' => request()->routeIs('admin.ta.*')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.siswa.index'),
                    'icon' => 'bi-people-fill',
                    'text' => 'Data Siswa',
                    'active' => request()->routeIs('admin.siswa.*')
                ])
            </li>
            <li class="nav-item">
                @include('components.buttons.button-sidebar', [
                    'href' => route('admin.hari-libur.index'),
                    'icon' => 'bi-calendar-x',
                    'text' => 'Hari Libur',
                    'active' => request()->routeIs('admin.hari-libur.*')
                ])
            </li>
        </ul>
    </nav>

    <!-- Logout Section -->
    <div class="logout-section">
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout" style="background: #dc3545; color: white; border: none; width: 100%; text-align: left; display: flex; align-items: center; cursor: pointer; padding: 10px 15px; border-radius: 10px; transition: background 0.3s;">
                <i class="bi bi-box-arrow-right"></i>
                <span style="margin-left: 10px; font-weight: 500;">Keluar</span>
            </button>
        </form>
    </div>
</aside>
