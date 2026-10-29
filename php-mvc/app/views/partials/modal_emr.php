<!-- MODAL ELECTRONIC MEDICAL RECORD (EMR) -->
<div class="modal-overlay" id="emrModal" style="display:none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-navy text-white" style="background:#0F172A;color:#fff;padding:16px 24px;border-bottom:1px solid #334155;">
                <div style="display:flex;align-items:center;gap:14px;">
                    <div style="width:44px;height:44px;border-radius:10px;background:#1E293B;display:flex;align-items:center;justify-content:center;font-size:22px;color:#38BDF8;">
                        <i class="ti ti-id-badge-2"></i>
                    </div>
                    <div>
                        <div style="font-size:17px;font-weight:700;letter-spacing:-0.2px;">Electronic Medical Record (EMR) - Rekam Medis Karyawan</div>
                        <div style="font-size:12px;color:#94A3B8;">Standar K3 & Surveilans Kesehatan PT PGAS Solution</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <button class="btn-primary" onclick="printEmrResume()" style="padding:6px 14px;font-size:12px;background:#0284C7;border:none;">
                        <i class="ti ti-printer"></i> Cetak Resume Medis
                    </button>
                    <button class="btn-close" onclick="closeEmrModal()" style="background:transparent;border:none;color:#94A3B8;font-size:22px;cursor:pointer;">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="modal-body" style="padding:20px 24px;max-height:calc(85vh - 120px);overflow-y:auto;">
                <!-- EMR Header Info Box -->
                <div class="emr-identity-banner" style="background:#F8FAFC;border:1px solid #E2E8F0;border-radius:12px;padding:16px;display:grid;grid-template-columns:auto 1fr auto;gap:20px;align-items:center;margin-bottom:20px;">
                    <div style="width:68px;height:68px;border-radius:12px;background:linear-gradient(135deg, #0284C7 0%, #0369A1 100%);color:#fff;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;">
                        <span id="emrAvatarLetter">K</span>
                    </div>
                    <div>
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                            <h2 style="font-size:18px;font-weight:800;color:#0F172A;margin:0;" id="emrNama">Nama Karyawan</h2>
                            <span class="badge b-org" id="emrStatusBadge">Organik</span>
                            <span class="badge b-fit" id="emrKelaikanBadge">FIT TO WORK</span>
                        </div>
                        <div style="font-size:12.5px;color:#64748B;display:flex;gap:16px;flex-wrap:wrap;">
                            <span><i class="ti ti-id"></i> NIP: <strong id="emrNip" style="color:#0F172A;">-</strong></span>
                            <span><i class="ti ti-map-pin"></i> Area: <strong id="emrArea" style="color:#0F172A;">-</strong></span>
                            <span><i class="ti ti-briefcase"></i> Divisi: <strong id="emrDivisi" style="color:#0F172A;">-</strong></span>
                            <span><i class="ti ti-user"></i> Gender / Usia: <strong id="emrGenderAge" style="color:#0F172A;">-</strong></span>
                        </div>
                    </div>
                    <div style="text-align:right;border-left:1px solid #E2E8F0;padding-left:20px;">
                        <div style="font-size:11px;color:#64748B;text-transform:uppercase;font-weight:600;">Status Rekam Medis</div>
                        <div style="font-size:14px;font-weight:800;color:#0284C7;" id="emrStatusMr">Lengkap &amp; Terverifikasi</div>
                        <div style="font-size:11px;color:#94A3B8;margin-top:2px;">Terakhir Periksa: <span id="emrTglPeriksa">-</span></div>
                    </div>
                </div>

                <!-- Tabs Navigation -->
                <div class="tab-nav-wrapper" style="border-bottom:1px solid #E2E8F0;margin-bottom:18px;display:flex;gap:8px;">
                    <button class="tab-btn active" onclick="switchEmrTab('tab-ringkasan', this)"><i class="ti ti-dashboard"></i> Ringkasan Medis</button>
                    <button class="tab-btn" onclick="switchEmrTab('tab-fisik', this)"><i class="ti ti-heart-rate-monitor"></i> Tanda Vital &amp; Fisik</button>
                    <button class="tab-btn" onclick="switchEmrTab('tab-lab', this)"><i class="ti ti-flask"></i> Laboratorium Lengkap</button>
                    <button class="tab-btn" onclick="switchEmrTab('tab-penunjang', this)"><i class="ti ti-device-heart-monitor"></i> Penunjang &amp; Rontgen</button>
                    <button class="tab-btn" onclick="switchEmrTab('tab-dcu', this)"><i class="ti ti-stethoscope"></i> Riwayat DCU Harian</button>
                    <button class="tab-btn" onclick="switchEmrTab('tab-komorbid', this)"><i class="ti ti-pill"></i> Pemantauan Komorbid</button>
                </div>

                <!-- Tab 1: Ringkasan & Kesimpulan -->
                <div class="emr-tab-content active" id="tab-ringkasan">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px;">
                        <div class="card" style="margin:0;">
                            <div class="card-header" style="background:#F1F5F9;"><div class="card-title" style="font-size:13px;"><i class="ti ti-report-medical"></i> Kesimpulan &amp; Kelaikan Kerja</div></div>
                            <div class="card-body">
                                <div style="margin-bottom:12px;">
                                    <div style="font-size:11.5px;color:#64748B;margin-bottom:2px;">Hasil Kelaikan Kerja (Fit To Work):</div>
                                    <div style="font-size:15px;font-weight:700;" id="emrKelaikanText">FIT TO WORK</div>
                                </div>
                                <div style="margin-bottom:12px;">
                                    <div style="font-size:11.5px;color:#64748B;margin-bottom:2px;">Temuan Medis Abnormal:</div>
                                    <div style="font-size:13px;color:#0F172A;background:#FFF1F2;padding:10px;border-radius:8px;border:1px solid #FECDD3;" id="emrTemuan">-</div>
                                </div>
                                <div>
                                    <div style="font-size:11.5px;color:#64748B;margin-bottom:2px;">Perubahan Derajat Kesehatan:</div>
                                    <div style="font-size:12.5px;">Sebelum: <strong id="emrDerajatSebelum">-</strong> &rarr; Sesudah: <strong id="emrDerajatSesudah">-</strong></div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin:0;">
                            <div class="card-header" style="background:#F1F5F9;"><div class="card-title" style="font-size:13px;"><i class="ti ti-notes"></i> Tindak Lanjut Medis &amp; Saran Dokter</div></div>
                            <div class="card-body">
                                <div style="margin-bottom:12px;">
                                    <div style="font-size:11.5px;color:#64748B;margin-bottom:2px;">Anjuran &amp; Terapi Obat:</div>
                                    <div style="font-size:13px;color:#0F172A;background:#F0FDF4;padding:10px;border-radius:8px;border:1px solid #BBF7D0;" id="emrAnjuran">-</div>
                                </div>
                                <div style="margin-bottom:12px;">
                                    <div style="font-size:11.5px;color:#64748B;margin-bottom:2px;">Rujukan Dokter Spesialis:</div>
                                    <div style="font-size:13px;font-weight:600;color:#0284C7;" id="emrRujukan">-</div>
                                </div>
                                <div>
                                    <div style="font-size:11.5px;color:#64748B;margin-bottom:2px;">Status Tindak Lanjut:</div>
                                    <span class="badge b-fit" id="emrStatusTindakLanjut">Open / Selesai</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Tanda Vital & Fisik -->
                <div class="emr-tab-content" id="tab-fisik" style="display:none;">
                    <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:14px;margin-bottom:18px;">
                        <div class="kpi-box blue">
                            <div class="kpi-label"><span>Tekanan Darah</span><i class="ti ti-heart"></i></div>
                            <div class="kpi-num" style="font-size:20px;" id="emrTdVal">- / -</div>
                            <div class="kpi-sub" id="emrTdStatus">mmHg (Normal < 130/85)</div>
                        </div>
                        <div class="kpi-box teal">
                            <div class="kpi-label"><span>Indeks Massa Tubuh (BMI)</span><i class="ti ti-scale"></i></div>
                            <div class="kpi-num" style="font-size:20px;" id="emrBmiVal">-</div>
                            <div class="kpi-sub" id="emrBmiStatus">BB: - kg &bull; TB: - cm</div>
                        </div>
                        <div class="kpi-box orange">
                            <div class="kpi-label"><span>Detak Nadi &amp; Suhu</span><i class="ti ti-activity"></i></div>
                            <div class="kpi-num" style="font-size:20px;" id="emrNadiSuhuVal">- bpm &bull; - &deg;C</div>
                            <div class="kpi-sub">Pernapasan: <span id="emrNafasVal">-</span> x/mnt</div>
                        </div>
                        <div class="kpi-box green">
                            <div class="kpi-label"><span>Visus &amp; Penglihatan</span><i class="ti ti-eye"></i></div>
                            <div class="kpi-num" style="font-size:18px;" id="emrVisusVal">OD: - | OS: -</div>
                            <div class="kpi-sub" id="emrButaWarnaVal">Buta Warna: Tidak</div>
                        </div>
                    </div>

                    <div class="card" style="margin:0;">
                        <div class="card-header"><div class="card-title">Pemeriksaan Fisik Klinis Lengkap (Head to Toe)</div></div>
                        <div class="card-body">
                            <table class="table" style="font-size:12.5px;">
                                <tbody>
                                    <tr><td style="width:25%;font-weight:600;">Kepala &amp; Leher</td><td id="emrFisikKepala">DBN (Dalam Batas Normal)</td><td style="width:25%;font-weight:600;">Tenggorokan / THT</td><td id="emrFisikTht">DBN</td></tr>
                                    <tr><td style="font-weight:600;">Thorax, Jantung &amp; Paru</td><td id="emrFisikCorPulmo">BJ I-II reguler, murmur (-), gallop (-), wheezing (-), ronki (-)</td><td style="font-weight:600;">Abdomen</td><td id="emrFisikAbdomen">Supel, bising usus normal, nyeri tekan (-)</td></tr>
                                    <tr><td style="font-weight:600;">Ekstremitas Atas &amp; Bawah</td><td id="emrFisikEkstremitas">Oedema (-/-), akral hangat, tremor (-)</td><td style="font-weight:600;">Refleks Fisiologis &amp; Neurologis</td><td id="emrFisikNeurologis">Normal, patologis (-)</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Laboratorium Lengkap -->
                <div class="emr-tab-content" id="tab-lab" style="display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                        <!-- Profil Lipid & Karbohidrat -->
                        <div class="card" style="margin:0;">
                            <div class="card-header"><div class="card-title"><i class="ti ti-droplet"></i> Glukosa &amp; Profil Lipid</div></div>
                            <div class="card-body">
                                <table class="table" style="font-size:12.5px;">
                                    <thead><tr><th>Parameter</th><th>Hasil</th><th>Nilai Normal</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <tr><td>Glukosa Puasa (GDP)</td><td><strong id="labGdp">-</strong> mg/dL</td><td>70 - 100 mg/dL</td><td id="labGdpBadge">-</td></tr>
                                        <tr><td>Kolesterol Total</td><td><strong id="labChol">-</strong> mg/dL</td><td>&lt; 200 mg/dL</td><td id="labCholBadge">-</td></tr>
                                        <tr><td>Trigliserida</td><td><strong id="labTrig">-</strong> mg/dL</td><td>&lt; 150 mg/dL</td><td id="labTrigBadge">-</td></tr>
                                        <tr><td>HDL Kolesterol</td><td><strong id="labHdl">-</strong> mg/dL</td><td>&gt; 40 mg/dL</td><td id="labHdlBadge">-</td></tr>
                                        <tr><td>LDL Kolesterol</td><td><strong id="labLdl">-</strong> mg/dL</td><td>&lt; 100 mg/dL</td><td id="labLdlBadge">-</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Ginjal & Hati -->
                        <div class="card" style="margin:0;">
                            <div class="card-header"><div class="card-title"><i class="ti ti-activity-heartbeat"></i> Fungsi Hati, Ginjal &amp; Asam Urat</div></div>
                            <div class="card-body">
                                <table class="table" style="font-size:12.5px;">
                                    <thead><tr><th>Parameter</th><th>Hasil</th><th>Nilai Normal</th><th>Status</th></tr></thead>
                                    <tbody>
                                        <tr><td>Asam Urat</td><td><strong id="labAu">-</strong> mg/dL</td><td>3.5 - 7.2 mg/dL</td><td id="labAuBadge">-</td></tr>
                                        <tr><td>SGOT / AST</td><td><strong id="labSgot">-</strong> U/L</td><td>&lt; 35 U/L</td><td id="labSgotBadge">-</td></tr>
                                        <tr><td>SGPT / ALT</td><td><strong id="labSgpt">-</strong> U/L</td><td>&lt; 45 U/L</td><td id="labSgptBadge">-</td></tr>
                                        <tr><td>Ureum</td><td><strong id="labUreum">-</strong> mg/dL</td><td>10 - 50 mg/dL</td><td id="labUreumBadge">-</td></tr>
                                        <tr><td>Kreatinin</td><td><strong id="labKreatinin">-</strong> mg/dL</td><td>0.6 - 1.2 mg/dL</td><td id="labKreatininBadge">-</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Hematologi & Urin -->
                    <div class="card" style="margin-top:16px;">
                        <div class="card-header"><div class="card-title"><i class="ti ti-microscope"></i> Hematologi Lengkap &amp; Urin Lengkap</div></div>
                        <div class="card-body">
                            <table class="table" style="font-size:12.5px;">
                                <thead><tr><th>Parameter</th><th>Hasil</th><th>Nilai Normal</th><th>Parameter Urin</th><th>Hasil</th><th>Nilai Normal</th></tr></thead>
                                <tbody>
                                    <tr><td>Hemoglobin (Hb)</td><td><strong id="labHb">-</strong> g/dL</td><td>13.0 - 17.5 g/dL</td><td>Protein Urin</td><td id="labUrinProtein">Negatif</td><td>Negatif</td></tr>
                                    <tr><td>Leukosit</td><td><strong id="labLeukosit">-</strong> /uL</td><td>4.000 - 10.000</td><td>Glukosa Urin</td><td id="labUrinGlukosa">Negatif</td><td>Negatif</td></tr>
                                    <tr><td>Trombosit</td><td><strong id="labTrombosit">-</strong> /uL</td><td>150.000 - 450.000</td><td>Sedimen Leukosit</td><td id="labUrinSedimen">0 - 2 /LPB</td><td>&lt; 5 /LPB</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Penunjang Medis & Radiologi -->
                <div class="emr-tab-content" id="tab-penunjang" style="display:none;">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                        <div class="card" style="margin:0;">
                            <div class="card-header"><div class="card-title"><i class="ti ti-photo"></i> Foto Thorax / Rontgen Dada</div></div>
                            <div class="card-body">
                                <div style="font-size:13px;line-height:1.6;background:#F8FAFC;padding:14px;border-radius:8px;border:1px solid #E2E8F0;" id="emrRontgen">Normal, cor dan pulmo dalam batas normal. Tidak tampak tanda TB paru aktif.</div>
                            </div>
                        </div>

                        <div class="card" style="margin:0;">
                            <div class="card-header"><div class="card-title"><i class="ti ti-heart-rate-monitor"></i> Elektrokardiografi (EKG)</div></div>
                            <div class="card-body">
                                <div style="font-size:13px;line-height:1.6;background:#F8FAFC;padding:14px;border-radius:8px;border:1px solid #E2E8F0;" id="emrEkg">Sinus Rhythm, HR 76x/m, axis normal, tidak ditemukan iskemia atau infark.</div>
                            </div>
                        </div>

                        <div class="card" style="margin:0;">
                            <div class="card-header"><div class="card-title"><i class="ti ti-run"></i> Kebugaran &amp; Treadmill (SKJ / Harvard)</div></div>
                            <div class="card-body">
                                <div style="font-size:13px;margin-bottom:8px;">Kategori Risiko: <strong id="emrSkj">Moderate Risk</strong></div>
                                <div style="font-size:12px;color:#64748B;" id="emrSkjDesc">Dapat melakukan aktivitas operasional dengan pemantauan istirahat berkala.</div>
                            </div>
                        </div>

                        <div class="card" style="margin:0;">
                            <div class="card-header"><div class="card-title"><i class="ti ti-brain"></i> Self-Reporting Questionnaire (SRQ-20)</div></div>
                            <div class="card-body">
                                <div style="font-size:13px;margin-bottom:8px;">Hasil Evaluasi: <strong id="emrSrq">Skor 2 (Normal / Tidak Ada Gejala Neurotik)</strong></div>
                                <div style="font-size:12px;color:#64748B;">Beban kerja mental dalam rentang adaptif normal.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: Riwayat DCU Harian -->
                <div class="emr-tab-content" id="tab-dcu" style="display:none;">
                    <div class="card" style="margin:0;">
                        <div class="card-header">
                            <div class="card-title"><i class="ti ti-history"></i> Rekam Riwayat Daily Check Up (DCU) Pekerja Ini</div>
                            <button class="btn-primary" style="font-size:11.5px;padding:4px 10px;" onclick="openAddDcuModal()"><i class="ti ti-plus"></i> Input DCU Baru</button>
                        </div>
                        <div class="card-body">
                            <table class="table" style="font-size:12px;">
                                <thead>
                                    <tr>
                                        <th>Tanggal DCU</th>
                                        <th>Tensi (TD)</th>
                                        <th>Nadi / Suhu</th>
                                        <th>Keluhan Klinis</th>
                                        <th>Kelaikan DCU</th>
                                        <th>Petugas Medis</th>
                                    </tr>
                                </thead>
                                <tbody id="emrDcuTableBody">
                                    <tr><td colspan="6" style="text-align:center;padding:16px;color:#888;">Memuat riwayat DCU...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tab 6: Pemantauan Komorbid -->
                <div class="emr-tab-content" id="tab-komorbid" style="display:none;">
                    <div class="card" style="margin:0;">
                        <div class="card-header"><div class="card-title"><i class="ti ti-pill"></i> Status Pemantauan Komorbid &amp; Rujukan Dokter</div></div>
                        <div class="card-body">
                            <table class="table" style="font-size:12.5px;">
                                <tbody>
                                    <tr><td style="width:25%;font-weight:600;">Diagnosis Komorbid</td><td id="emrKomorbidDiag">-</td></tr>
                                    <tr><td style="font-weight:600;">Terapi Obat Rutin</td><td id="emrKomorbidObat">-</td></tr>
                                    <tr><td style="font-weight:600;">Rujukan Dokter Spesialis</td><td id="emrKomorbidRujukan">-</td></tr>
                                    <tr><td style="font-weight:600;">Jadwal Kontrol Berikutnya</td><td id="emrKomorbidKontrol">-</td></tr>
                                    <tr><td style="font-weight:600;">Status Pemantauan</td><td><span class="badge b-fit" id="emrKomorbidStatus">-</span></td></tr>
                                    <tr><td style="font-weight:600;">Catatan Tambahan</td><td id="emrKomorbidCatatan">-</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div><!-- /modal-body -->

            <div class="modal-footer" style="padding:12px 24px;border-top:1px solid #E2E8F0;display:flex;justify-content:space-between;background:#F8FAFC;">
                <div style="font-size:12px;color:#64748B;">Data di-sinkronisasi langsung dengan Database EHMS.</div>
                <button class="btn-secondary" onclick="closeEmrModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>
