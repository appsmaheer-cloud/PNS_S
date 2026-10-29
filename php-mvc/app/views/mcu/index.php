<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>
<!-- Status KPI Row -->
<div class="kpi-row" style="grid-template-columns:repeat(4, 1fr);">
    <div class="kpi-box blue">
        <div class="kpi-label">Total MCU 2026</div>
        <div class="kpi-num"><?= number_format($stats['total'] ?? 0) ?></div>
        <div class="kpi-sub">Pekerja Selesai MCU</div>
    </div>
    <div class="kpi-box green">
        <div class="kpi-label">FIT to Work</div>
        <div class="kpi-num"><?= number_format($stats['fit'] ?? 0) ?></div>
        <div class="kpi-sub">Laik Bekerja Penuh</div>
    </div>
    <div class="kpi-box orange">
        <div class="kpi-label">FIT dg Catatan</div>
        <div class="kpi-num"><?= number_format($stats['fitnote'] ?? 0) ?></div>
        <div class="kpi-sub">Dalam Pemantauan Medis</div>
    </div>
    <div class="kpi-box red">
        <div class="kpi-label">UNFIT to Work</div>
        <div class="kpi-num"><?= number_format($stats['unfit'] ?? 0) ?></div>
        <div class="kpi-sub">Rujukan Spesialis Aktif</div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>/mcu" class="tbar">
            <input type="hidden" name="r" value="mcu">
            <div class="tbar-left">
                <span style="font-weight:700;">Filter:</span>
                <select name="kelaikan" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kelaikan</option>
                    <option value="FIT" <?= ($kelaikan === 'FIT') ? 'selected' : '' ?>>FIT</option>
                    <option value="FIT dengan catatan" <?= ($kelaikan === 'FIT dengan catatan') ? 'selected' : '' ?>>FIT dg Catatan</option>
                    <option value="UNFIT" <?= ($kelaikan === 'UNFIT') ? 'selected' : '' ?>>UNFIT</option>
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
                <select name="tindak" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status Tindak Lanjut</option>
                    <option value="selesai" <?= ($tindak === 'selesai') ? 'selected' : '' ?>>Selesai</option>
                    <option value="dirujuk" <?= ($tindak === 'dirujuk') ? 'selected' : '' ?>>Dirujuk</option>
                    <option value="belum dirujuk" <?= ($tindak === 'belum dirujuk') ? 'selected' : '' ?>>Belum Dirujuk</option>
                    <option value="tidak perlu" <?= ($tindak === 'tidak perlu') ? 'selected' : '' ?>>Tidak Perlu</option>
                </select>
            </div>
            <div class="tbar-right">
                <input type="text" name="search" class="tbar-search-input" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NIP, Nama, Area...">
                <button type="submit" class="btn-secondary"><i class="ti ti-search"></i> Cari</button>
                <a href="<?= BASE_URL ?>/export/mcu" class="btn-secondary"><i class="ti ti-download"></i> Export CSV</a>
                <button type="button" class="btn-primary" onclick="openModalMcu()"><i class="ti ti-plus"></i> Tambah MCU</button>
            </div>
        </form>

        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>No MR</th>
                        <th>Tgl MCU</th>
                        <th>Nama Karyawan</th>
                        <th>Area & Divisi</th>
                        <th>Status</th>
                        <th>Kelaikan Kerja</th>
                        <th>Derajat (Awal &rarr; Akhir)</th>
                        <th>Hasil Kritis Lab</th>
                        <th>Rontgen & EKG</th>
                        <th>Tindak Lanjut</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($mcuList)): ?>
                        <tr><td colspan="12" style="text-align:center;padding:24px;color:#888;">Tidak ada data MCU yang cocok.</td></tr>
                    <?php else: ?>
                        <?php foreach ($mcuList as $m): 
                            $kClass = 'b-fit';
                            if (stripos($m['kelaikan_kerja'], 'catatan') !== false) $kClass = 'b-fitnote';
                            elseif (stripos($m['kelaikan_kerja'], 'unfit') !== false) $kClass = 'b-notfit';
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($m['nip']) ?></strong></td>
                            <td><?= htmlspecialchars($m['mr_no'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($m['tgl_mcu'] ?? '-') ?></td>
                            <td><strong style="color:var(--navy);"><?= htmlspecialchars($m['nama']) ?></strong></td>
                            <td>
                                <div><?= htmlspecialchars($m['area'] ?? '-') ?></div>
                                <div style="font-size:11px;color:var(--text-light);"><?= htmlspecialchars($m['divisi'] ?? '-') ?></div>
                            </td>
                            <td><span class="badge" style="background:#EEF2F6;color:#334155;"><?= htmlspecialchars($m['status_karyawan'] ?? 'Organik') ?></span></td>
                            <td><span class="badge <?= $kClass ?>"><?= htmlspecialchars($m['kelaikan_kerja']) ?></span></td>
                            <td>
                                <span class="badge b-<?= strtolower($m['derajat_sebelum'] ?? 'p2') ?>"><?= htmlspecialchars($m['derajat_sebelum'] ?? 'P2') ?></span>
                                &rarr;
                                <span class="badge b-<?= strtolower($m['derajat_sesudah'] ?? 'p1') ?>"><?= htmlspecialchars($m['derajat_sesudah'] ?? 'P1') ?></span>
                            </td>
                            <td style="font-size:11.5px;">
                                <?php if ($m['chol'] > 200): ?><span class="badge b-notfit" style="font-size:9.5px;">Chol: <?= $m['chol'] ?></span> <?php endif; ?>
                                <?php if ($m['asam_urat'] > 6.0): ?><span class="badge b-notfit" style="font-size:9.5px;">AU: <?= $m['asam_urat'] ?></span> <?php endif; ?>
                                <?php if ($m['sistol'] > 140): ?><span class="badge b-notfit" style="font-size:9.5px;">TD: <?= $m['sistol'] ?>/<?= $m['diastol'] ?></span> <?php endif; ?>
                                <?php if ($m['chol'] <= 200 && $m['asam_urat'] <= 6 && $m['sistol'] <= 140): ?><span style="color:#059669;">Normal</span><?php endif; ?>
                            </td>
                            <td style="font-size:11.5px;max-width:180px;">
                                <div><strong>Thorax:</strong> <?= htmlspecialchars(substr($m['rontgen'] ?? 'Normal', 0, 25)) ?>...</div>
                                <div><strong>EKG:</strong> <?= htmlspecialchars(substr($m['ekg'] ?? 'Normal', 0, 25)) ?></div>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($m['tindak_lanjut'] ?? 'Resep') ?></div>
                                <div style="font-size:11px;color:var(--text-light);"><?= htmlspecialchars($m['status_tindak_lanjut'] ?? 'Selesai') ?></div>
                            </td>
                            <td>
                                <div class="act-btns">
                                    <button class="act-btn" title="Lihat Rekam Medis (EMR)" onclick="openEmrModal('<?= $m['nip'] ?>')"><i class="ti ti-eye"></i></button>
                                    <button class="act-btn" title="Edit Data" onclick="editMcuModal('<?= $m['nip'] ?>')"><i class="ti ti-edit"></i></button>
                                    <button class="act-btn del" title="Hapus" onclick="deleteMcuRecord('<?= $m['nip'] ?>')"><i class="ti ti-trash"></i></button>
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
                    <a href="<?= BASE_URL ?>/mcu?page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&kelaikan=<?= urlencode($kelaikan) ?>&tindak=<?= urlencode($tindak) ?>" class="btn-secondary" style="padding:4px 10px;">&laquo; Prev</a>
                <?php endif; ?>
                <span class="btn-primary" style="padding:4px 10px;"><?= $pagination['page'] ?></span>
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="<?= BASE_URL ?>/mcu?page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&kelaikan=<?= urlencode($kelaikan) ?>&tindak=<?= urlencode($tindak) ?>" class="btn-secondary" style="padding:4px 10px;">Next &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
