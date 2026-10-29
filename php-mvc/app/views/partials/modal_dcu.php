<!-- MODAL INPUT / EDIT DCU -->
<div class="modal-overlay" id="dcuModal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="dcuModalTitle"><i class="ti ti-stethoscope"></i> Pemeriksaan Daily Check Up (DCU)</div>
                <button class="btn-close" onclick="closeDcuModal()">&times;</button>
            </div>
            <form id="dcuForm" onsubmit="submitDcuForm(event)">
                <div class="modal-body" style="padding:20px;">
                    <input type="hidden" id="dcuId" name="id">

                    <div style="display:grid;grid-template-columns:1fr 2fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">NIP Karyawan *</label>
                            <input type="text" class="form-control" id="dcuNipInput" name="nip" required onblur="lookupEmployee(this.value, 'dcu')">
                        </div>
                        <div>
                            <label class="form-label">Nama Karyawan *</label>
                            <input type="text" class="form-control" id="dcuNamaInput" name="nama" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">Area Kerja *</label>
                            <input type="text" class="form-control" id="dcuAreaInput" name="area" value="Area Jakarta" required>
                        </div>
                        <div>
                            <label class="form-label">Tanggal Pemeriksaan *</label>
                            <input type="text" class="form-control" id="dcuTglInput" name="tgl" value="<?= date('d/m/Y') ?>" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">Sistol (mmHg)</label>
                            <input type="number" class="form-control" id="dcuSistolInput" name="sistol" value="120">
                        </div>
                        <div>
                            <label class="form-label">Diastol (mmHg)</label>
                            <input type="number" class="form-control" id="dcuDiastolInput" name="diastol" value="80">
                        </div>
                        <div>
                            <label class="form-label">Nadi (x/menit)</label>
                            <input type="number" class="form-control" id="dcuNadiInput" name="nadi" value="76">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">Suhu (&deg;C)</label>
                            <input type="number" step="0.1" class="form-control" id="dcuSuhuInput" name="suhu" value="36.5">
                        </div>
                        <div>
                            <label class="form-label">Kelaikan DCU * (Murni)</label>
                            <select class="form-control" id="dcuKelaikanInput" name="kelaikan" required>
                                <option value="FIT">FIT</option>
                                <option value="UNFIT">UNFIT</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom:12px;">
                        <label class="form-label">Keluhan Klinis</label>
                        <input type="text" class="form-control" id="dcuKeluhanInput" name="keluhan" value="Tidak Ada Keluhan">
                    </div>

                    <div>
                        <label class="form-label">Petugas Pemeriksa Medis</label>
                        <input type="text" class="form-control" id="dcuPemeriksaInput" name="pemeriksa" value="dr. Paramedis K3">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeDcuModal()">Batal</button>
                    <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Simpan Data DCU</button>
                </div>
            </form>
        </div>
    </div>
</div>
