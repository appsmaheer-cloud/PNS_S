<aside class="sidebar" id="sidebar">
    <div class="brand-box">
        <div class="brand-icon"><i class="ti ti-heart-rate-monitor"></i></div>
        <div class="brand-text">
            <span class="brand-title">EHMS</span>
            <span class="brand-sub">PGN Solution Health</span>
        </div>
    </div>
    <nav class="nav-menu">
        <div class="nav-heading">MENU UTAMA</div>
        <a href="<?= BASE_URL ?>/dashboard" class="nav-link <?= (str_contains($_SERVER['REQUEST_URI'] ?? '', 'dashboard') || $_SERVER['REQUEST_URI'] === BASE_URL || $_SERVER['REQUEST_URI'] === BASE_URL . '/') ? 'active' : '' ?>">
            <i class="ti ti-layout-dashboard"></i>
            <span>Wellness Dashboard</span>
        </a>
        <a href="<?= BASE_URL ?>/mcu" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/mcu') && !str_contains($_SERVER['REQUEST_URI'] ?? '', '/dcu') ? 'active' : '' ?>">
            <i class="ti ti-clipboard-list"></i>
            <span>Medical Check Up (MCU)</span>
        </a>
        <a href="<?= BASE_URL ?>/dcu" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/dcu') ? 'active' : '' ?>">
            <i class="ti ti-stethoscope"></i>
            <span>Daily Check Up (DCU)</span>
        </a>
        <a href="<?= BASE_URL ?>/employee" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/employee') ? 'active' : '' ?>">
            <i class="ti ti-users"></i>
            <span>Master Pekerja</span>
        </a>
        
        <div class="nav-heading">KLINIS & SURVEILANS</div>
        <a href="<?= BASE_URL ?>/comorbid" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/comorbid') ? 'active' : '' ?>">
            <i class="ti ti-pill"></i>
            <span>Monitoring Komorbid</span>
        </a>
        <a href="<?= BASE_URL ?>/screening" class="nav-link <?= str_contains($_SERVER['REQUEST_URI'] ?? '', '/screening') ? 'active' : '' ?>">
            <i class="ti ti-alert-triangle"></i>
            <span>15 Aturan Power BI</span>
        </a>

        <div class="nav-heading">SINKRONISASI & EXPORT</div>
        <a href="javascript:void(0)" onclick="syncWithGoogleAppsScript()" class="nav-link">
            <i class="ti ti-cloud-computing" style="color:#38BDF8;"></i>
            <span>Sync Google Apps Script</span>
        </a>
        <a href="<?= defined('GAS_WEBAPP_URL') ? GAS_WEBAPP_URL : 'https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec' ?>" target="_blank" rel="noopener" class="nav-link">
            <i class="ti ti-brand-google-drive" style="color:#10B981;"></i>
            <span>Buka Web App Sheet</span>
        </a>
        <a href="<?= BASE_URL ?>/export/csv?type=mcu" class="nav-link">
            <i class="ti ti-file-spreadsheet"></i>
            <span>Ekspor Data MCU (CSV)</span>
        </a>
        <a href="<?= BASE_URL ?>/export/csv?type=dcu" class="nav-link">
            <i class="ti ti-file-spreadsheet"></i>
            <span>Ekspor Data DCU (CSV)</span>
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="system-status">
            <span class="status-dot online"></span>
            <span>Database: SQLite 9.2MB Ready</span>
        </div>
        <div class="sys-version">Versi 2.5.0-Enterprise</div>
    </div>
</aside>
