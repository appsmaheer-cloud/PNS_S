<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'EHMS - Enterprise Health Management System') ?> | PT PGAS Solution</title>
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- EHMS Custom Styling -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/assets/css/style.css">
</head>
<body>
<div class="app-layout">
    <!-- Sidebar Navigation -->
    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <!-- Main Content Area -->
    <main class="main-wrapper">
        <!-- Top Navigation Bar -->
        <header class="topbar">
            <div class="topbar-left">
                <button class="btn-menu" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="ti ti-menu-2"></i>
                </button>
                <div class="topbar-title-block">
                    <h1 class="page-title"><?= htmlspecialchars($title ?? 'Wellness Dashboard') ?></h1>
                    <span class="badge b-org" style="font-size:11px;">PT PGAS Solution &bull; Divisi K3 & Kesehatan Kerja</span>
                </div>
            </div>
            <div class="topbar-right">
                <div class="search-box">
                    <i class="ti ti-search search-icon"></i>
                    <input type="text" id="globalSearch" placeholder="Cari NIP, Nama, Area, Diagnosis..." onkeydown="if(event.key==='Enter') globalSearchRedirect(this.value)">
                </div>
                <a href="<?= BASE_URL ?>/export/csv?type=all" class="btn-secondary" title="Export Seluruh Database">
                    <i class="ti ti-download"></i> Backup CSV
                </a>
                <div class="user-profile">
                    <div class="avatar"><i class="ti ti-user-check"></i></div>
                    <div class="user-info">
                        <div class="user-name">dr. Tim Medis K3</div>
                        <div class="user-role">Occupational Health Officer</div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Body -->
        <div class="content-body">
