</div><!-- /content-body -->
        <footer class="main-footer">
            <div>&copy; <?= date('Y') ?> <strong>PT PGAS Solution</strong> - Enterprise Health Management System (EHMS). Sesuai Standar Klinis & K3.</div>
            <div>Migrasi Lengkap &bull; Versi PHP MVC &amp; Google Apps Script Ready</div>
        </footer>
    </main>
</div>

<!-- Modal Partial Components -->
<?php require_once dirname(__DIR__) . '/partials/modal_emr.php'; ?>
<?php require_once dirname(__DIR__) . '/partials/modal_mcu.php'; ?>
<?php require_once dirname(__DIR__) . '/partials/modal_dcu.php'; ?>
<?php require_once dirname(__DIR__) . '/partials/modal_emp.php'; ?>
<?php require_once dirname(__DIR__) . '/partials/modal_comorbid.php'; ?>

<!-- Global Application Script -->
<script src="<?= BASE_URL ?>/public/assets/js/app.js"></script>
</body>
</html>
