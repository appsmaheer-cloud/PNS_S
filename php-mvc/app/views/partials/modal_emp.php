<!-- MODAL INPUT / EDIT KARYAWAN -->
<div class="modal-overlay" id="empModal" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title" id="empModalTitle"><i class="ti ti-user-plus"></i> Form Data Karyawan</div>
                <button class="btn-close" onclick="closeEmpModal()">&times;</button>
            </div>
            <form id="empForm" onsubmit="submitEmpForm(event)">
                <div class="modal-body" style="padding:20px;">
                    <input type="hidden" id="empId" name="id">

                    <div style="display:grid;grid-template-columns:1fr 2fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">NIP *</label>
                            <input type="text" class="form-control" id="empNipInput" name="nip" required>
                        </div>
                        <div>
                            <label class="form-label">Nama Lengkap *</label>
                            <input type="text" class="form-control" id="empNamaInput" name="nama" required>
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">Status Karyawan *</label>
                            <select class="form-control" id="empStatusInput" name="status" required>
                                <option value="Organik">Organik</option>
                                <option value="Perbantuan PGN">Perbantuan PGN</option>
                                <option value="PKWT">PKWT</option>
                                <option value="TKJP Perkasa">TKJP Perkasa</option>
                                <option value="TKJP KPU">TKJP KPU</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Area Kerja *</label>
                            <select class="form-control" id="empAreaInput" name="area" required>
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
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">Divisi</label>
                            <input type="text" class="form-control" id="empDivisiInput" name="divisi" value="Operasional">
                        </div>
                        <div>
                            <label class="form-label">Jabatan</label>
                            <input type="text" class="form-control" id="empJabatanInput" name="jabatan" value="Staff">
                        </div>
                    </div>

                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;margin-bottom:12px;">
                        <div>
                            <label class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="empGenderInput" name="gender">
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Usia (Tahun)</label>
                            <input type="number" class="form-control" id="empUsiaInput" name="usia" value="35">
                        </div>
                        <div>
                            <label class="form-label">Status MR</label>
                            <select class="form-control" id="empStatusMrInput" name="status_mr">
                                <option value="Lengkap">Lengkap</option>
                                <option value="Belum Lengkap">Belum Lengkap</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" onclick="closeEmpModal()">Batal</button>
                    <button type="submit" class="btn-primary"><i class="ti ti-device-floppy"></i> Simpan Karyawan</button>
                </div>
            </form>
        </div>
    </div>
</div>
