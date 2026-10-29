<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>
<div class="card">
    <div class="card-body">
        <form method="GET" action="<?= BASE_URL ?>/employee" class="tbar">
            <input type="hidden" name="r" value="employee">
            <div class="tbar-left">
                <span style="font-weight:700;">Filter:</span>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status Karyawan</option>
                    <option value="Organik" <?= ($status === 'Organik') ? 'selected' : '' ?>>Organik</option>
                    <option value="Perbantuan PGN" <?= ($status === 'Perbantuan PGN') ? 'selected' : '' ?>>Perbantuan PGN</option>
                    <option value="PKWT" <?= ($status === 'PKWT') ? 'selected' : '' ?>>PKWT</option>
                    <option value="TKJP (Perkasa)" <?= ($status === 'TKJP (Perkasa)') ? 'selected' : '' ?>>TKJP (Perkasa)</option>
                    <option value="TKJP (KPU)" <?= ($status === 'TKJP (KPU)') ? 'selected' : '' ?>>TKJP (KPU)</option>
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
                <select name="mr_status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status MR</option>
                    <option value="Active" <?= ($mrStatus === 'Active') ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= ($mrStatus === 'Inactive') ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="tbar-right">
                <input type="text" name="search" class="tbar-search-input" value="<?= htmlspecialchars($search) ?>" placeholder="Cari NIP, Nama, Divisi...">
                <button type="submit" class="btn-secondary"><i class="ti ti-search"></i> Cari</button>
                <a href="<?= BASE_URL ?>/export/employee" class="btn-secondary"><i class="ti ti-download"></i> Export Data</a>
                <button type="button" class="btn-primary" onclick="openModalEmployee()"><i class="ti ti-user-plus"></i> Tambah Karyawan</button>
            </div>
        </form>

        <div class="tbl-wrap">
            <table class="dtbl">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>No MR</th>
                        <th>Nama Karyawan</th>
                        <th>Status Karyawan</th>
                        <th>Area Kerja</th>
                        <th>Divisi & Unit</th>
                        <th>Gender / Usia</th>
                        <th>Status MR</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($employees)): ?>
                        <tr><td colspan="9" style="text-align:center;padding:24px;color:#888;">Tidak ada data karyawan.</td></tr>
                    <?php else: ?>
                        <?php foreach ($employees as $e): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($e['nip']) ?></strong></td>
                            <td><?= htmlspecialchars($e['mr_no'] ?? '-') ?></td>
                            <td><strong style="color:var(--navy);"><?= htmlspecialchars($e['nama']) ?></strong></td>
                            <td><span class="badge" style="background:#F1F5F9;color:var(--navy);font-weight:700;"><?= htmlspecialchars($e['status_karyawan']) ?></span></td>
                            <td><?= htmlspecialchars($e['area']) ?></td>
                            <td>
                                <div><?= htmlspecialchars($e['divisi']) ?></div>
                                <div style="font-size:11px;color:var(--text-light);"><?= htmlspecialchars($e['unit']) ?></div>
                            </td>
                            <td><?= htmlspecialchars($e['gender']) ?> / <?= $e['usia'] ?> thn</td>
                            <td><span class="badge <?= ($e['mr_status'] === 'Active') ? 'b-active' : 'b-inactive' ?>"><?= htmlspecialchars($e['mr_status']) ?></span></td>
                            <td>
                                <div class="act-btns">
                                    <button class="act-btn" title="Lihat Rekam Medis" onclick="openEmrModal('<?= $e['nip'] ?>')"><i class="ti ti-id-badge-2"></i></button>
                                    <button class="act-btn del" title="Hapus" onclick="deleteEmployeeRecord('<?= $e['nip'] ?>')"><i class="ti ti-trash"></i></button>
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
                Menampilkan halaman <?= $pagination['page'] ?> dari <?= $pagination['total_pages'] ?> (Total <?= number_format($pagination['total']) ?> pekerja)
            </div>
            <div style="display:flex;gap:4px;">
                <?php if ($pagination['page'] > 1): ?>
                    <a href="<?= BASE_URL ?>/employee?page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&status=<?= urlencode($status) ?>&mr_status=<?= urlencode($mrStatus) ?>" class="btn-secondary" style="padding:4px 10px;">&laquo; Prev</a>
                <?php endif; ?>
                <span class="btn-primary" style="padding:4px 10px;"><?= $pagination['page'] ?></span>
                <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                    <a href="<?= BASE_URL ?>/employee?page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>&area=<?= urlencode($area) ?>&status=<?= urlencode($status) ?>&mr_status=<?= urlencode($mrStatus) ?>" class="btn-secondary" style="padding:4px 10px;">Next &raquo;</a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
