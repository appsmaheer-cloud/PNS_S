<?php require_once dirname(__DIR__) . '/layouts/header.php'; ?>
<!-- KPI Row -->
<div class="kpi-row">
    <div class="kpi-box blue">
        <div class="kpi-label"><span>Total Pemeriksaan MCU</span><i class="ti ti-clipboard-list"></i></div>
        <div class="kpi-num"><?= number_format($mcuStats['total'] ?? 0) ?></div>
        <div class="kpi-sub">Data Migrasi Lengkap 2026</div>
    </div>
    <div class="kpi-box green">
        <div class="kpi-label"><span>Fit to Work</span><i class="ti ti-circle-check"></i></div>
        <div class="kpi-num"><?= number_format($mcuStats['fit'] ?? 0) ?></div>
        <div class="kpi-sub">Laik bekerja tanpa pembatasan</div>
    </div>
    <div class="kpi-box orange">
        <div class="kpi-label"><span>Fit dengan Catatan</span><i class="ti ti-alert-circle"></i></div>
        <div class="kpi-num"><?= number_format($mcuStats['fitnote'] ?? 0) ?></div>
        <div class="kpi-sub">Dengan kontrol & anjuran medis</div>
    </div>
    <div class="kpi-box red">
        <div class="kpi-label"><span>Unfit to Work</span><i class="ti ti-circle-x"></i></div>
        <div class="kpi-num"><?= number_format($mcuStats['unfit'] ?? 0) ?></div>
        <div class="kpi-sub">Perlu rujukan spesialis segera</div>
    </div>
    <div class="kpi-box teal">
        <div class="kpi-label"><span>DCU Terdata</span><i class="ti ti-stethoscope"></i></div>
        <div class="kpi-num"><?= number_format($dcuStats['total'] ?? 0) ?></div>
        <div class="kpi-sub"><?= number_format($dcuStats['fit'] ?? 0) ?> Fit &bull; <?= number_format($dcuStats['unfit'] ?? 0) ?> Unfit</div>
    </div>
</div>

<!-- 2 Columns Grid: Diseases & Area Index -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ti ti-virus"></i> Top 10 Kondisi & Komorbiditas Karyawan</div>
            <a href="<?= BASE_URL ?>/screening" class="btn-secondary" style="font-size:11px;padding:4px 8px;">Lihat 15 Rules Power BI</a>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:11px;">
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                        <span style="font-weight:600;">Hipertensi (Tekanan Darah > 140/90)</span>
                        <span style="font-weight:700;color:var(--red);"><?= number_format($comStats['ht'] ?? 0) ?> Kasus</span>
                    </div>
                    <div style="height:7px;background:#F1F5F9;border-radius:4px;overflow:hidden;">
                        <div style="width:72%;height:100%;background:linear-gradient(90deg, #3B82F6 0%, #EF4444 100%);"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                        <span style="font-weight:600;">Dislipidemia (Kolesterol Total > 200 / TG > 150)</span>
                        <span style="font-weight:700;color:var(--red);"><?= number_format($comStats['lipid'] ?? 0) ?> Kasus</span>
                    </div>
                    <div style="height:7px;background:#F1F5F9;border-radius:4px;overflow:hidden;">
                        <div style="width:58%;height:100%;background:linear-gradient(90deg, #3B82F6 0%, #F59E0B 100%);"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                        <span style="font-weight:600;">Diabetes Melitus (GDP > 126 mg/dL)</span>
                        <span style="font-weight:700;color:var(--red);"><?= number_format($comStats['dm'] ?? 0) ?> Kasus</span>
                    </div>
                    <div style="height:7px;background:#F1F5F9;border-radius:4px;overflow:hidden;">
                        <div style="width:42%;height:100%;background:linear-gradient(90deg, #3B82F6 0%, #EF4444 100%);"></div>
                    </div>
                </div>
                <div>
                    <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:4px;">
                        <span style="font-weight:600;">Pasien dalam Rujukan Spesialis Aktif</span>
                        <span style="font-weight:700;color:var(--teal);"><?= number_format($comStats['rujukan'] ?? 0) ?> Pasien</span>
                    </div>
                    <div style="height:7px;background:#F1F5F9;border-radius:4px;overflow:hidden;">
                        <div style="width:48%;height:100%;background:#0D9488;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="ti ti-building-community"></i> Indeks Kesehatan per Area Operasi PGN Solution</div>
            <span style="font-size:11px;color:var(--text-light);">Skala Maksimal 5.0</span>
        </div>
        <div class="card-body">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#F8FAFD;border-radius:6px;border:1px solid var(--border-light);">
                    <div><strong>Jakarta (Kantor Pusat)</strong><div style="font-size:11px;color:var(--text-light);">Kelayakan Kerja: 94%</div></div>
                    <div style="font-size:15px;font-weight:800;color:var(--navy);">4.6 <span class="badge b-fit" style="font-size:10px;">Best</span></div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#F8FAFD;border-radius:6px;border:1px solid var(--border-light);">
                    <div><strong>SOR 1 (Jawa Bagian Barat)</strong><div style="font-size:11px;color:var(--text-light);">Kelayakan Kerja: 91%</div></div>
                    <div style="font-size:15px;font-weight:800;color:var(--navy);">4.3 <span class="badge b-fit" style="font-size:10px;">Top</span></div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#F8FAFD;border-radius:6px;border:1px solid var(--border-light);">
                    <div><strong>Ketapang Field Operations</strong><div style="font-size:11px;color:var(--text-light);">Kelayakan Kerja: 88%</div></div>
                    <div style="font-size:15px;font-weight:800;color:var(--navy);">4.1 <span class="badge" style="background:#E0F2FE;color:#0369A1;font-size:10px;">Good</span></div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 12px;background:#F8FAFD;border-radius:6px;border:1px solid var(--border-light);">
                    <div><strong>EPC Daan Mogot & SOR 2</strong><div style="font-size:11px;color:var(--text-light);">Kelayakan Kerja: 79%</div></div>
                    <div style="font-size:15px;font-weight:800;color:var(--red);">3.5 <span class="badge b-notfit" style="font-size:10px;">Risk Focus</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommendations Card -->
<div class="card" style="border-left: 4px solid var(--cyan);">
    <div class="card-header">
        <div class="card-title"><i class="ti ti-bulb"></i> Ringkasan Medis Eksekutif & Rekomendasi Klinis EHMS</div>
    </div>
    <div class="card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div style="background:#F8FAFD;padding:16px;border-radius:var(--radius-sm);border:1px solid var(--border-light);">
            <h4 style="font-size:13.5px;color:var(--navy);margin-bottom:8px;"><i class="ti ti-info-circle" style="color:var(--blue);"></i> Status Kesehatan Pekerja</h4>
            <p style="font-size:12.5px;color:var(--text-med);line-height:1.6;">
                Seluruh <?= number_format($mcuStats['total'] ?? 0) ?> catatan MCU dan <?= number_format($dcuStats['total'] ?? 0) ?> rekap pemeriksaan DCU telah termigrasi ke dalam database lokal EHMS. Proporsi kelaikan kerja menunjukkan stabilitas tinggi, dengan fokus pemantauan ketat pada <?= number_format($mcuStats['fitnote'] ?? 0) ?> pekerja kategori Fit dengan Catatan.
            </p>
        </div>
        <div style="background:#FFFBEB;padding:16px;border-radius:var(--radius-sm);border:1px solid #FDE68A;">
            <h4 style="font-size:13.5px;color:#92400E;margin-bottom:8px;"><i class="ti ti-checkup-list" style="color:#D97706;"></i> Instruksi Tindak Lanjut Medis</h4>
            <ul style="font-size:12.5px;color:#78350F;line-height:1.6;padding-left:18px;">
                <li>Pekerja kategori <strong>Unfit</strong> wajib menjalani konsultasi dengan Dokter Spesialis terkait sebelum kembali bertugas.</li>
                <li>Lakukan evaluasi tekanan darah dan gula darah secara mingguan di unit DCU operasional.</li>
                <li>Gunakan modul <strong>Power BI Screening</strong> untuk deteksi dini 15 indikator penyakit kronis.</li>
            </ul>
        </div>
    </div>
</div>
<?php require_once dirname(__DIR__) . '/layouts/footer.php'; ?>
