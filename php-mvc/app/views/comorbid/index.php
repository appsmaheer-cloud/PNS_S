<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="kpi-row" style="grid-template-columns:repeat(5, 1fr);">
    <div class="kpi-box red">
        <div class="kpi-label">Total Komorbid</div>
        <div class="kpi-num"><?= number_format($stats['total'] ?? 0) ?></div>
        <div class="kpi-sub">Pasien Dalam Pemantauan</div>
    </div>
    <div class="kpi-box blue">
        <div class="kpi-label">Hipertensi</div>
        <div class="kpi-num"><?= number_format($stats['ht'] ?? 0) ?></div>
        <div class="kpi-sub">Terapi Antihipertensi</div>
    </div>
    <div class="kpi-box orange">
        <div class="kpi-label">Diabetes Melitus</div>
        <div class="kpi-num"><?= number_format($stats['dm'] ?? 0) ?></div>
        <div class="kpi-sub">Terapi OHO / Insulin</div>
    </div>
    <div class="kpi-box purple">
        <div class="kpi-label">Dislipidemia</div>
        <div class="kpi-num"><?= number_format($stats['lipid'] ?? 0) ?></div>
        <div class="kpi-sub">Terapi Statin & Diet</div>
    </div>
    <div class="kpi-box teal">
        <div class="kpi-label">Rujukan Spesialis</div>
        <div class="kpi-num"><?= number_format($stats['rujukan'] ?? 0) ?></div>
        <div class="kpi-sub">Sp.JP / Sp.PD / Sp.P / Sp.OK</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>/comorbid" class="tbar">
            <input type="hidden" name="r" value="comorbid">
            <div class="tbar-left">
                <span style="font-weight:700;">Filter Komorbid:</span>
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Komorbid</option>
                    <option value="Hipertensi" <?= ($type === 'Hipertensi') ? 'selected' : '' ?>>Hipertensi</option>
                    <option value="Diabetes" <?= ($type === 'Diabetes') ? 'selected' : '' ?>>Diabetes Melitus</option>
                    <option value="Dislipidemia" <?= ($type === 'Dislipidemia') ? 'selected' : '' ?>>Dislipidemia</option>
                    <option value="Obesitas" <?= ($type === 'Obesitas') ? 'selected' : '' ?>>Obesitas</option>
                    <option value="Hiperurisemia" <?= ($type === 'Hiperurisemia') ? 'selected' : '' ?>>Hiperurisemia</option>
                </select>
                <select name="area" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Area</option>
                    <option value="Jakarta" <?= ($area === 'Jakarta') ? 'selected' : '' ?>>Jakarta</option>
                    <option value="SOR 1" <?= ($area === 'SOR 1') ? 'selected' : '' ?>>SOR 1</option>
                    <option value="SOR 2" <?= ($area === 'SOR 2') ? 'selected' : '' ?>>SOR 2</option>
                    <option value="SOR 3" <?= ($area === 'SOR 3') ? 'selected' : '' ?>>SOR 3</option>
                    <option value="EPC Daan Mogot" <?= ($area === 'EPC Daan Mogot') ? 'selected' : '' ?>>EPC Daan Mogot</option>
                    <option value="Ketapang" <?= ($area === 'Ketapang') ? 'selected' : '' ?>>Ketapang</option>
                </select>
            </div>
            <div class="tbar-right">
                <input type="text" name="search" class="tbar-search-input" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NIP, Nama, Obat...">
                <button type="submit" class="btn-secondary"><i class="ti ti-search"></i> Cari</button>
                <a href="<?= BASE_URL ?>/export/comorbid" class="btn-secondary"><i class="ti ti-download"></i> Export CSV</a>
                <button type="button" class="btn-primary" onclick="openModalComorbid()"><i class="ti ti-plus"></i> Tambah Pemantauan</button>
            </div>
        </form>

        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama Karyawan</th>
                        <th>Area Kerja</th>
                        <th>Riwayat Komorbid</th>
                        <th>Terapi Obat Rutin</th>
                        <th>Rujukan ke dr. Spesialis</th>
                        <th>Kontrol</th>
                        <th>Status</th>
                        <th>Catatan Klinis</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comorbidList)): ?>
                        <tr><td colspan="10" style="text-align:center;padding:24px;color:#888;">Tidak ada data pemantauan komorbid.</td></tr>
                    <?php else: ?>
                        <?php foreach ($comorbidList as $c): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($c['nip']) ?></strong></td>
                            <td><strong style="color:var(--navy);"><?= htmlspecialchars($c['nama']) ?></strong></td>
                            <td><?= htmlspecialchars($c['area']) ?></td>
                            <td><span class="badge" style="background:#FEE2E2;color:#991B1B;font-weight:700;"><?= htmlspecialchars($c['komorbid']) ?></span></td>
                            <td style="max-width:200px;font-size:12px;color:var(--navy);"><i class="ti ti-pill" style="color:var(--cyan);"></i> <?= htmlspecialchars($c['obat']) ?></td>
                            <td style="max-width:200px;font-size:12px;color:#7F1D1D;"><i class="ti ti-stethoscope" style="color:var(--red);"></i> <?= htmlspecialchars($c['rujukan']) ?></td>
                            <td><?= htmlspecialchars($c['kontrol'] ?? '-') ?></td>
                            <td><span class="badge b-fitnote"><?= htmlspecialchars($c['status']) ?></span></td>
                            <td style="font-size:11.5px;max-width:220px;"><?= htmlspecialchars($c['catatan']) ?></td>
                            <td>
                                <div class="act-btns">
                                    <button class="act-btn" title="Buka Rekam Medis" onclick="openEmrModal('<?= $c['nip'] ?>')"><i class="ti ti-eye"></i></button>
                                    <button class="act-btn del" title="Hapus" onclick="deleteComorbidRecord(<?= $c['id'] ?>)"><i class="ti ti-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (($pagination['total_pages'] ?? 1) > 1): ?>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:16px;">
            <div style="font-size:12.5px;color:var(--text-light);">
                Menampilkan halaman <?= $pagination['page'] ?> dari <?= $pagination['total_pages'] ?> (Total <?= number_format($pagination['total']) ?> pasien komorbid)
            </div>
            <div style="display:flex;gap:4px;">
                <?php if ($pagination['page'] > 1): ?>
                    <a href="<?= BASE_URL ?>/comorbid?page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&type=<?= urlencode($type) ?>" class="btn-secondary" style="padding:4px 10px;">&laquo; Prev</a>
                <?php endif; ?>
                <span class="btn-primary" style="padding:4px 10px;"><?= $pagination['page'] ?></span>
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="<?= BASE_URL ?>/comorbid?page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&type=<?= urlencode($type) ?>" class="btn-secondary" style="padding:4px 10px;">Next &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
