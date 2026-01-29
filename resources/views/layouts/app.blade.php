<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
    <title>@yield('title')</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Global Style */
        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            background-color: #f8fafc;
            margin: 0;
        }

        #db-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #f8fafc;
        }

        /* Sinkronisasi Header/Topbar agar serasi */
        .navbar {
            background-color: #ffffff !important; /* Mengubah toska menjadi putih bersih */
            border-bottom: 1px solid #e2e8f0 !important; /* Garis pemisah tipis */
            padding: 0.75rem 1.5rem !important;
            box-shadow: none !important;
        }

        /* Mengubah warna teks brand/admin di navbar agar tidak hilang saat bg jadi putih */
        .navbar .navbar-brand, 
        .navbar .nav-link,
        .navbar .dropdown-toggle {
            color: #ffffff !important; 
            font-weight: 600;   
        }

        /* Style agar ikon memiliki ukuran yang konsisten */
        i {
            vertical-align: middle;
        }

        #page-content {
            padding: 2rem;
        }

        /* Transisi halus */
        .fa-chevron-down {
            transition: transform 0.3s ease !important;
        }

        /* Saat menu Manajemen Barang TIDAK collapse (artinya terbuka), putar ikonnya */
        .nav-link:not(.collapsed) .fa-chevron-down {
            transform: rotate(180deg);
        }

        /* State Normal: Menu saat tidak disentuh kursor */
        .navbar-vertical .nav-link {
            color: #94a3b8 !important;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            font-size: 15px;
            /* Weight yang sama agar teks tidak melebar saat hover */
            font-weight: 500; 
            transition: background-color 0.3s ease, color 0.3s ease;
    
            /* Margin dan border transparan sebagai 'cadangan' ruang */
            margin: 4px 15px; 
            border-radius: 8px;
            border: 1px solid transparent; 
    
            /* Lebar elemen tidak berubah */
            width: calc(100% - 30px); 
            box-sizing: border-box;
        }

        /* State Hover: Saat kursor diarahkan */
        .navbar-vertical .nav-link:hover {
            color: #ffffff !important;
            background: #3b82f6 !important;
            /* Jangan ubah margin, padding, atau font-weight di sini */
            border: 1px solid #3b82f6; 
        }

        /* Non-aktifkan Dashboard otomatis jika sedang tidak di halaman dashboard */
        /* Atau jika ingin dashboard tidak biru saat kursor ke menu lain, hapus class 'active' di Blade */
        .navbar-vertical .nav-link.active {
            background: #3b82f6;
            color: #ffffff !important;
        }

/* Memastikan ikon tetap di posisinya */
.navbar-vertical .nav-link i {
    width: 20px;
    text-align: center;
    margin-right: 12px;
}
    </style>
</head>
<body>
<div id="db-wrapper">

    @include('layouts.sidebar')

    <div class="main-wrapper">
        @include('layouts.topbar')
        
        <main id="page-content">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

@include('layouts.scripts')
</body>
</html>