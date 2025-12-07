<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Siswa')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Layout CSS -->
    <link rel="stylesheet" href="{{ asset('css/siswa/layout.css') }}">
    
    <!-- Page Specific CSS -->
    @yield('styles')
</head>
<body>
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar Component -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <!-- Header Component (hamburger ada di sini) -->
        @include('components.header', [
            'title' => $pageTitle ?? 'Dashboard',
            'subtitle' => $pageSubtitle ?? 'Sistem Manajemen Siswa'
        ])

        <!-- Content -->
        <div class="content-container">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Layout JS -->
    <script src="{{ asset('js/siswa/layout.js') }}"></script>
    
    <!-- Page Specific JS -->
    @yield('scripts')
</body>
</html>
