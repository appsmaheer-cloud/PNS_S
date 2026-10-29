<!-- MODAL INPUT / EDIT MCU -->
<div class="modal-overlay" id="mcuModal" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="mcuModalTitle"><i class="ti ti-clipboard-plus"></i> Form Pemeriksaan MCU Karyawan</div>
                <button class="btn-close" onclick="closeMcuModal()">&times;</button>
            </div>
            <form id="mcuForm" onsubmit="submitMcuForm(event)">
                <div class="modal-body" style="max-height:75vh;overflow-y:auto;padding:20px;">
                    <input type="hidden" id="mcuId" name="id">
                    
                    <div style="display:grid;grid-template-columns:1fr 2fr;gap:14px;margin-bottom:14px;">
                        <div>
                            <label class="form-label">NIP Karyawan *</label>
                            <input type="text" class="form-control" id="mcuNipInput" name="nip" required onblur="lookupEmployee(this.value)">
                        </div>
                        <div>
                            <label class="form-label">Nama Karyawan *</label>
                            <input type="text" class="form-control" id="mcuNamaInput" name="nama" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px;margin-bottom:14px;">
                        <div>
                            <label class="form-label">Area Kerja *</label>
                            <select class="form-control" id="mcuAreaInput" name="area" required>
                                <option value="Kantor Pusat">Kantor Pusat</option>
                                <option value="Area Dumai">Area Dumai</option>
                                <option value="Area Palembang">Area Palembang</option>
                                <option value="Area Cilegon">Area Cilegon</option>
                                <option value="Area Jakarta">Area Jakarta</option>
                                <option value="Area Bekasi">Area Bekasi</option>
                                <option value="Area Bogor">Area Bogor</option>
                                <option value="Area Cirebon">Area Cirebon</option>
                                <option value="Area Semarang">Area Semarang</option>
                                <option value="Area Surabaya">Area Surabaya</option>
                                <option value="Area Sorong">Area Sorong</option>
                                <option value="Area Batam">Area Batam</option>
                                <option value="Area Lampung">Area Lampung</option>
                                <option value="Ketapang">Ketapang</option>
                                <option value="Area Pagardewa">Area Pagardewa</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Divisi</label>
                            <input type="text" class="form-control" id="mcuDivisiInput" name="divisi" value="Operasional">
                        </div>
                        <div>
                            <label class="form-label">Tanggal MCU *</label>
                            <input type="text" class="form-control" id="mcuTglInput" name="tgl" value="<?= date('d/m/Y') ?>" required>
                        </div>
                    </div>

                    <div style="font-weight:700;font-size:13px;color:#0F172A;margin:16px 0 8px;padding-bottom:4px;border-bottom:1px solid #E2E8F0;">
                        TANDA VITAL &amp; KLINIS
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:12px;margin-bottom:14px;">
                        <div>
                            <label class="form-label">Sistol (mmHg)</label>
                            <input type="number" class="form-control" id="mcuSistolInput" name="sistol" value="120">
                        </div>
                        <div>
                            <label class="form-label">Diastol (mmHg)</label>
                            <input type="number" class="form-control" id="mcuDiastolInput" name="diastol" value="80">
                        </div>
                        <div>
                            <label class="form-label">Berat Badan (kg)</label>
                            <input type="number" step="0.1" class="form-control" id="mcuBbInput" name="bb" value="65" onchange="calcBmi()">
                        </div>
                        <div>
                            <label class="form-label">Tinggi Badan (cm)</label>
                            <input type="number" class="form-control" id="mcuTbInput" name="tb" value="168" onchange="calcBmi()">
                        </div>
                    </div>

                    <div style="font-weight:700;font-size:13px;color:#0F172A;margin:16px 0 8px;padding-bottom:4px;border-bottom:1px solid #E2E8F0;">
                        LABORATORIUM DARAH &amp; URIN
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(4, 1fr);gap:12px;margin-bottom:14px;">
                        <div>
                            <label class="form-label">GDP (mg/dL)</label>
                            <input type="number" class="form-control" id="mcuGdpInput" name="gdp" value="95">
                        </div>
                        <div>
                            <label class="form-label">Kolesterol Total</label>
                            <input type="number" class="form-control" id="mcuCholInput" name="chol" value="185">
                        </div>
                        <div>
                            <label class="form-label">Asam Urat</label>
                            <input type="number" step="0.1" class="form-control" id="mcuAuInput" name="asam_urat" value="5.5">
                        </div>
                        <div>
                            <label class="form-label">SGOT (U/L)</label>
                            <input type="number" class="form-control" id="mcuSgotInput" name="sgot" value="24">
                        </div>
                    </div>

                    <div style="font-weight:700;font-size:13px;color:#0F172A;margin:16px 0 8px;padding-bottom:4px;border-bottom:1px solid #E2E8F0;">
                        KESIMPULAN &amp; KELAIKAN KERJA
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                        <div>
                            <label class="form-label">Kelaikan Kerja *</label>
                            <select class="form-control" id="mcuKelaikanInput" name="kelaikan_kerja" required>
                                <option value="FIT">FIT</option>
                                <option value="FIT DENGAN CATATAN">FIT DENGAN CATATAN</option>
                                <option value="UNFIT">UNFIT</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Status Tindak Lanjut</label>
                            <select class="form-control" id="mcuStatusTindakInput" name="status_tindak_lanjut">
                                <option value="Open">Open</option>
                                <option value="Closed">Closed</option>
                                <option value="Rujukan Spesialis">Rujukan Spesialis</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom:14px;">
                        <label class="form-label">Temuan Abnormal Klinis</label>
                        <input type="text" class="form-control" id="mcuTemuanInput" name="temuan_abnormal" value="Dalam Batas Normal">
                    </div>

                    <div>
                        <label class="form-label">Anjuran &amp; Terapi Obat</label>
                        <textarea class="form-control" id="mcuAnjuranInput" name="tindak_lanjut" rows="2">Pola hidup sehat, kurangi konsumsi garam dan lemak jenuh.</textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeMcuModal()">Batal</button>
                    <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Simpan Data MCU</button>
                </div>
            </form>
        </div>
    </div>
</div>
