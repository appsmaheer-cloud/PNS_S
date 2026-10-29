<!-- MODAL INPUT / EDIT KOMORBID -->
<div class="modal-overlay" id="comorbidModal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="comorbidModalTitle"><i class="ti ti-pill"></i> Pemantauan Kasus Komorbid</div>
                <button class="btn-close" onclick="closeComorbidModal()">&times;</button>
            </div>
            <form id="comorbidForm" onsubmit="submitComorbidForm(event)">
                <div class="modal-body" style="padding:20px;">
                    <input type="hidden" id="comorbidId" name="id">

                    <div style="display:grid;grid-template-columns:1fr 2fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">NIP Karyawan *</label>
                            <input type="text" class="form-control" id="comNipInput" name="nip" required onblur="lookupEmployee(this.value, 'com')">
                        </div>
                        <div>
                            <label class="form-label">Nama Karyawan *</label>
                            <input type="text" class="form-control" id="comNamaInput" name="nama" required>
                        </div>
                    </div>

                    <div style="margin-bottom:12px;">
                        <label class="form-label">Area Kerja *</label>
                        <input type="text" class="form-control" id="comAreaInput" name="area" value="Area Jakarta" required>
                    </div>

                    <div style="margin-bottom:12px;">
                        <label class="form-label">Diagnosis Komorbiditas *</label>
                        <input type="text" class="form-control" id="comDiagInput" name="komorbid" placeholder="Contoh: Hipertensi Grade 1, Dislipidemia" required>
                    </div>

                    <div style="margin-bottom:12px;">
                        <label class="form-label">Terapi Obat Rutin</label>
                        <input type="text" class="form-control" id="comObatInput" name="obat" placeholder="Contoh: Amlodipine 5mg 1x1, Simvastatin 10mg">
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">Rujukan Dokter Spesialis</label>
                            <input type="text" class="form-control" id="comRujukanInput" name="rujukan" placeholder="Contoh: Sp.JP / Sp.PD">
                        </div>
                        <div>
                            <label class="form-label">Target Kontrol</label>
                            <input type="text" class="form-control" id="comKontrolInput" name="kontrol" value="<?= date('d/m/Y', strtotime('+30 days')) ?>">
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Status Pemantauan</label>
                        <select class="form-control" id="comStatusInput" name="status">
                            <option value="Dalam Evaluasi">Dalam Evaluasi</option>
                            <option value="Terkontrol">Terkontrol</option>
                            <option value="Belum Terkontrol">Belum Terkontrol</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeComorbidModal()">Batal</button>
                    <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Simpan Pemantauan</button>
                </div>
            </form>
        </div>
    </div>
</div>
