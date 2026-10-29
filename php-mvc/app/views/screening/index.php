<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <div class="card-title"><i class="ti ti-cpu"></i> Clinical Screening Engine — 15 Indikator Medis (data power BI.docx)</div>
        <a href="<?= BASE_URL ?>/screening" class="btn-secondary"><i class="ti ti-filter-off"></i> Hapus Filter</a>
    </div>
    <div class="card-body">
        <p style="font-size:12.5px;color:var(--text-med);margin-bottom:16px;">
            Sistem secara otomatis mengkalkulasi 15 indikator klinis risiko tinggi dari seluruh database MCU. Klik salah satu kartu indikator di bawah untuk menyaring data:
        </p>
        <div class="pbi-grid">
            <?php foreach ($rules as $r): ?>
            <a href="<?= BASE_URL ?>/screening?filter=<?= $r['id'] ?>" class="pbi-card <?= ($activeFilter === $r['id']) ? 'active-filter' : '' ?>" style="text-decoration:none;">
                <div class="pbi-header">
                    <span class="pbi-name"><?= htmlspecialchars($r['name']) ?></span>
                    <span class="pbi-count"><?= number_format($r['count']) ?></span>
                </div>
                <div class="pbi-desc"><?= htmlspecialchars($r['desc']) ?></div>
                <span class="pbi-tag"><?= $r['count'] > 0 ? number_format($r['count']) . ' Karyawan Terindikasi' : 'Nol Kasus' ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="ti ti-list-details"></i> Daftar Karyawan Terindikasi Klinis</div>
        <span class="badge b-fit"><?= count($screenedList) ?> Pekerja Ditampilkan</span>
    </div>
    <div class="card-body">
        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama Karyawan</th>
                        <th>Area & Divisi</th>
                        <th>Kelaikan Kerja</th>
                        <th>Parameter Kritis</th>
                        <th>Rontgen & EKG</th>
                        <th>Tindakan / Rujukan Disarankan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($screenedList)): ?>
                        <tr><td colspan="8" style="text-align:center;padding:24px;color:#888;">Tidak ada data pekerja yang terindikasi pada filter ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($screenedList as $m): 
                            $kClass = 'b-fit';
                            if (stripos($m['kelaikan_kerja'], 'catatan') !== false) $kClass = 'b-fitnote';
                            elseif (stripos($m['kelaikan_kerja'], 'unfit') !== false) $kClass = 'b-notfit';
                        ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($m['nip']) ?></strong></td>
                            <td><strong style="color:var(--navy);"><?= htmlspecialchars($m['nama']) ?></strong></td>
                            <td><?= htmlspecialchars($m['area']) ?> / <?= htmlspecialchars($m['divisi']) ?></td>
                            <td><span class="badge <?= $kClass ?>"><?= htmlspecialchars($m['kelaikan_kerja']) ?></span></td>
                            <td style="font-size:11.5px;">
                                TD: <strong><?= $m['sistol'] ?>/<?= $m['diastol'] ?></strong> &bull; BMI: <strong><?= $m['bmi'] ?></strong><br>
                                Chol: <strong><?= $m['chol'] ?></strong> &bull; AU: <strong><?= $m['asam_urat'] ?></strong> &bull; GDP: <strong><?= $m['gdp'] ?></strong>
                            </td>
                            <td style="font-size:11px;max-width:180px;">
                                <div><strong>Thorax:</strong> <?= htmlspecialchars(substr($m['rontgen'] ?? 'Normal', 0, 30)) ?>...</div>
                                <div><strong>EKG:</strong> <?= htmlspecialchars($m['ekg'] ?? 'Normal') ?></div>
                            </td>
                            <td style="font-size:12px;color:var(--navy);"><?= htmlspecialchars($m['tindak_lanjut'] ?? 'Resep Terapi & Konsultasi') ?></td>
                            <td>
                                <button class="btn-secondary" style="font-size:11px;padding:4px 8px;" onclick="openEmrModal('<?= $m['nip'] ?>')">
                                    <i class="ti ti-id-badge-2"></i> Buka EMR
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
