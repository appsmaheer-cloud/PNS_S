<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="kpi-row" style="grid-template-columns:repeat(3, 1fr);">
    <div class="kpi-box blue">
        <div class="kpi-label">Total Pemeriksaan DCU</div>
        <div class="kpi-num"><?= number_format($stats['total'] ?? 0) ?></div>
        <div class="kpi-sub">Pemeriksaan Harian Seluruh Area</div>
    </div>
    <div class="kpi-box green">
        <div class="kpi-label">FIT to Work</div>
        <div class="kpi-num"><?= number_format($stats['fit'] ?? 0) ?></div>
        <div class="kpi-sub">Laik Bertugas Lapangan & Kantor</div>
    </div>
    <div class="kpi-box red">
        <div class="kpi-label">UNFIT to Work</div>
        <div class="kpi-num"><?= number_format($stats['unfit'] ?? 0) ?></div>
        <div class="kpi-sub">Istirahat Medis & Rujukan</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>/dcu" class="tbar">
            <input type="hidden" name="r" value="dcu">
            <div class="tbar-left">
                <span style="font-weight:700;">Filter:</span>
                <select name="kelaikan" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kelaikan</option>
                    <option value="FIT" <?= ($kelaikan === 'FIT') ? 'selected' : '' ?>>FIT</option>
                    <option value="UNFIT" <?= ($kelaikan === 'UNFIT') ? 'selected' : '' ?>>UNFIT</option>
                </select>
                <select name="area" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Area</option>
                    <option value="Jakarta" <?= ($area === 'Jakarta') ? 'selected' : '' ?>>Jakarta</option>
                    <option value="Ketapang" <?= ($area === 'Ketapang') ? 'selected' : '' ?>>Ketapang</option>
                    <option value="Daan Mogot" <?= ($area === 'Daan Mogot') ? 'selected' : '' ?>>EPC Daan Mogot</option>
                </select>
                <select name="shift" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Shift</option>
                    <option value="Shift 1 (Pagi)" <?= ($shift === 'Shift 1 (Pagi)') ? 'selected' : '' ?>>Shift 1 (Pagi)</option>
                    <option value="Shift 2 (Siang)" <?= ($shift === 'Shift 2 (Siang)') ? 'selected' : '' ?>>Shift 2 (Siang)</option>
                    <option value="Shift 3 (Malam)" <?= ($shift === 'Shift 3 (Malam)') ? 'selected' : '' ?>>Shift 3 (Malam)</option>
                </select>
            </div>
            <div class="tbar-right">
                <input type="text" name="search" class="tbar-search-input" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NIP, Nama, Keluhan...">
                <button type="submit" class="btn-secondary"><i class="ti ti-search"></i> Cari</button>
                <a href="<?= BASE_URL ?>/export/dcu" class="btn-secondary"><i class="ti ti-download"></i> Export CSV</a>
                <button type="button" class="btn-primary" onclick="openModalDcu()"><i class="ti ti-plus"></i> Input DCU</button>
            </div>
        </form>

        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>DCU ID</th>
                        <th>NIP</th>
                        <th>Tanggal & Waktu</th>
                        <th>Nama Pekerja</th>
                        <th>Area & Shift</th>
                        <th>Tanda Vital (TD / Nadi / Suhu)</th>
                        <th>Keluhan</th>
                        <th>Hasil Pemeriksaan Fisik</th>
                        <th>Kelaikan Kerja</th>
                        <th>Petugas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($dcuList)): ?>
                        <tr><td colspan="11" style="text-align:center;padding:24px;color:#888;">Tidak ada data DCU.</td></tr>
                    <?php else: ?>
                        <?php foreach ($dcuList as $d): ?>
                        <tr>
                            <td><?= htmlspecialchars($d['dcu_id']) ?></td>
                            <td><strong><?= htmlspecialchars($d['nip']) ?></strong></td>
                            <td><?= htmlspecialchars($d['tgl']) ?></td>
                            <td><strong style="color:var(--navy);"><?= htmlspecialchars($d['nama']) ?></strong></td>
                            <td>
                                <div><?= htmlspecialchars($d['area']) ?></div>
                                <div style="font-size:11px;color:var(--text-light);"><?= htmlspecialchars($d['shift']) ?></div>
                            </td>
                            <td><strong><?= $d['sistol'] ?>/<?= $d['diastol'] ?></strong> mmHg &bull; <?= $d['nadi'] ?> bpm &bull; <?= $d['suhu'] ?>&deg;C</td>
                            <td style="color:var(--text-med);"><?= htmlspecialchars($d['keluhan']) ?></td>
                            <td style="max-width:240px;line-height:1.4;"><?= htmlspecialchars($d['pem_fisik']) ?></td>
                            <td>
                                <?php if (strtoupper($d['kelaikan']) === 'FIT'): ?>
                                    <span class="badge b-fit"><i class="ti ti-check"></i> FIT</span>
                                <?php else: ?>
                                    <span class="badge b-notfit"><i class="ti ti-x"></i> UNFIT</span>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:11.5px;"><?= htmlspecialchars($d['petugas']) ?></td>
                            <td>
                                <div class="act-btns">
                                    <button class="act-btn del" title="Hapus" onclick="deleteDcuRecord(<?= $d['id'] ?>)"><i class="ti ti-trash"></i></button>
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
                Menampilkan halaman <?= $pagination['page'] ?> dari <?= $pagination['total_pages'] ?> (Total <?= number_format($pagination['total']) ?> data)
            </div>
            <div style="display:flex;gap:4px;">
                <?php if ($pagination['page'] > 1): ?>
                    <a href="<?= BASE_URL ?>/dcu?page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&kelaikan=<?= urlencode($kelaikan) ?>&shift=<?= urlencode($shift) ?>" class="btn-secondary" style="padding:4px 10px;">&laquo; Prev</a>
                <?php endif; ?>
                <span class="btn-primary" style="padding:4px 10px;"><?= $pagination['page'] ?></span>
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="<?= BASE_URL ?>/dcu?page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&kelaikan=<?= urlencode($kelaikan) ?>&shift=<?= urlencode($shift) ?>" class="btn-secondary" style="padding:4px 10px;">Next &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
