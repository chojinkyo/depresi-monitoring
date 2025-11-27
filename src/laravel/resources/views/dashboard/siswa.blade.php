<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Manajemen Siswa</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #F8F9FA;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 256px;
            background: linear-gradient(180deg, #51A2FF 0%, #AD46FF 100%);
            padding: 0;
            transition: all 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }

        /* User Profile Container */
        .user-container {
            background: rgba(255, 255, 255, 0.3);
            padding: 16px;
            border-radius: 0 0 25px 25px;
            margin-bottom: 20px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .user-avatar {
            width: 48px;
            height: 48px;
            background: #FFFFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 18px;
            color: #8200DB;
            flex-shrink: 0;
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 700;
            font-size: 14px;
            color: #FFFFFF;
            margin: 0;
        }

        .user-role {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.8);
            margin: 0;
        }

        .welcome-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            padding-top: 12px;
        }

        .welcome-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.95);
            margin: 0;
        }

        .welcome-emoji {
            margin-left: 4px;
        }

        /* Navigation */
        .nav-menu {
            padding: 0 16px;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            gap: 12px;
            border-radius: 14px;
            color: #FFFFFF;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 15px;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #FFFFFF;
        }

        .nav-link.active {
            background: #FFFFFF;
            color: #8200DB;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .nav-icon {
            font-size: 20px;
        }

        .nav-text {
            flex: 1;
        }

        /* Logout Button */
        .logout-section {
            padding: 16px;
            margin-top: auto;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            gap: 8px;
            width: 100%;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 12px;
            color: #FFFFFF;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Main Content */
        .main-content {
            margin-left: 256px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Header */
        .main-header {
            background: #FFFFFF;
            padding: 20px 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1E2939;
            margin: 0;
        }

        .header-subtitle {
            font-size: 14px;
            color: #6B7280;
            margin: 0;
        }

        .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-settings {
            width: 40px;
            height: 40px;
            background: #F3F4F6;
            border: none;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6B7280;
            transition: all 0.3s ease;
        }

        .btn-settings:hover {
            background: #E5E7EB;
        }

        /* Content Container */
        .content-container {
            padding: 32px;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1E2939;
            margin-bottom: 20px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .stat-card {
            background: #FFFFFF;
            border: 1px solid #F3E8FF;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
        }

        .stat-info {
            flex: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1E2939;
            margin: 0;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .stat-icon.attendance {
            background: linear-gradient(135deg, #05DF72 0%, #00BC7D 100%);
        }

        .stat-icon.grades {
            background: linear-gradient(135deg, #51A2FF 0%, #2B7FFF 100%);
        }

        .stat-icon.mood {
            background: linear-gradient(135deg, #FFE860 0%, #FFDF20 100%);
        }

        .stat-icon.rank {
            background: linear-gradient(135deg, #FB64B6 0%, #FF2056 100%);
        }

        /* Chart Card */
        .chart-card {
            background: #FFFFFF;
            border: 1px solid #F3E8FF;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .chart-header {
            background: linear-gradient(270deg, #F0FDF4 0%, #ECFDF5 100%);
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
        }

        .chart-title {
            font-size: 16px;
            font-weight: 600;
            color: #1E2939;
            margin: 0;
        }

        .chart-subtitle {
            font-size: 13px;
            color: #6B7280;
            margin-top: 4px;
        }

        .chart-content {
            padding: 24px;
        }

        .mood-chart {
            width: 100%;
            height: 300px;
            background: #FAFAFA;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Announcements Card */
        .announcements-card {
            background: #FFFFFF;
            border: 1px solid #F3E8FF;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .announcements-header {
            padding: 20px 24px;
            border-bottom: 1px solid #E5E7EB;
        }

        .announcements-title {
            font-size: 16px;
            font-weight: 600;
            color: #1E2939;
            margin: 0;
        }

        .announcements-content {
            padding: 20px 24px;
        }

        .announcement-item {
            background: #DBEAFE;
            border: 1px solid #93C5FD;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
        }

        .announcement-item:last-child {
            margin-bottom: 0;
        }

        .announcement-item:hover {
            transform: translateX(4px);
        }

        .announcement-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            flex-wrap: wrap;
            gap: 8px;
        }

        .announcement-title-text {
            font-size: 15px;
            font-weight: 600;
            color: #1E2939;
            margin: 0;
        }

        .announcement-badge {
            background: #AD46FF;
            color: #FFFFFF;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 500;
        }

        .announcement-badge.besok {
            background: #8200DB;
        }

        .announcement-description {
            font-size: 13px;
            color: #4B5563;
            line-height: 1.5;
            margin: 0;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background: linear-gradient(135deg, #51A2FF 0%, #AD46FF 100%);
            border: none;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(81, 162, 255, 0.3);
            color: white;
            font-size: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .sidebar {
                left: -256px;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .main-header {
                padding: 16px 20px;
                padding-left: 80px;
            }

            .content-container {
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .header-title h1 {
                font-size: 20px;
            }
        }

        @media (min-width: 769px) and (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle">
        <i class="bi bi-list"></i>
    </button>

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
                <p class="welcome-text">Selamat Datang! <span class="welcome-emoji">👋</span></p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="nav-menu">
            <ul class="list-unstyled">
                <li class="nav-item">
                    <a href="#" class="nav-link active">
                        <i class="bi bi-grid-fill nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-calendar-check nav-icon"></i>
                        <span class="nav-text">Absensi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-calendar-event nav-icon"></i>
                        <span class="nav-text">Jadwal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-file-earmark-text nav-icon"></i>
                        <span class="nav-text">Laporan Nilai</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="bi bi-bar-chart-fill nav-icon"></i>
                        <span class="nav-text">Statistik</span>
                    </a>
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

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header -->
        <header class="main-header">
            <div class="header-title">
                <h1>Dashboard</h1>
                <p class="header-subtitle">Sistem Manajemen Siswa</p>
            </div>
            <div class="header-actions">
                <button class="btn-settings">
                    <i class="bi bi-gear-fill"></i>
                </button>
            </div>
        </header>

        <!-- Content -->
        <div class="content-container">
            <!-- Section Title -->
            <h2 class="section-title">Ringkasan Aktivitas</h2>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <p class="stat-label">Kehadiran Bulan Ini</p>
                            <h3 class="stat-value">92%</h3>
                        </div>
                        <div class="stat-icon attendance">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <p class="stat-label">Rata-rata Nilai</p>
                            <h3 class="stat-value">87.5</h3>
                        </div>
                        <div class="stat-icon grades">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <p class="stat-label">Mood Overall</p>
                            <h3 class="stat-value">Sangat Baik 😊</h3>
                        </div>
                        <div class="stat-icon mood">
                            <i class="bi bi-emoji-smile-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-info">
                            <p class="stat-label">Peringkat</p>
                            <h3 class="stat-value">12 / 30</h3>
                        </div>
                        <div class="stat-icon rank">
                            <i class="bi bi-trophy-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart & Announcements Row -->
            <div class="row">
                <!-- Mood Chart -->
                <div class="col-lg-7 mb-4">
                    <div class="chart-card">
                        <div class="chart-header">
                            <h3 class="chart-title">Mood Overall 14 Hari</h3>
                            <p class="chart-subtitle">Rata-rata mood 14 hari terakhir: 😊 8.2/10</p>
                        </div>
                        <div class="chart-content">
                            <div class="mood-chart">
                                <p style="color: #9CA3AF;">Chart akan ditampilkan di sini</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Announcements -->
                <div class="col-lg-5 mb-4">
                    <div class="announcements-card">
                        <div class="announcements-header">
                            <h3 class="announcements-title">Pengumuman Terbaru</h3>
                        </div>
                        <div class="announcements-content">
                            <div class="announcement-item">
                                <div class="announcement-header">
                                    <h4 class="announcement-title-text">Ujian Matematika</h4>
                                    <span class="announcement-badge">2 jam lagi</span>
                                </div>
                                <p class="announcement-description">Persiapkan diri untuk ujian matematika</p>
                            </div>

                            <div class="announcement-item">
                                <div class="announcement-header">
                                    <h4 class="announcement-title-text">Libur Nasional</h4>
                                    <span class="announcement-badge">3 hari lagi</span>
                                </div>
                                <p class="announcement-description">Sekolah libur pada tanggal 17 Agustus</p>
                            </div>

                            <div class="announcement-item">
                                <div class="announcement-header">
                                    <h4 class="announcement-title-text">Ujian Bahasa Indonesia</h4>
                                    <span class="announcement-badge besok">Besok</span>
                                </div>
                                <p class="announcement-description">Persiapkan diri untuk ujian bahasa indonesia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Mobile Menu Toggle
        const mobileMenuToggle = document.getElementById('mobileMenuToggle');
        const sidebar = document.getElementById('sidebar');

        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const isClickInside = sidebar.contains(event.target) || mobileMenuToggle.contains(event.target);
            
            if (!isClickInside && window.innerWidth <= 768) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
