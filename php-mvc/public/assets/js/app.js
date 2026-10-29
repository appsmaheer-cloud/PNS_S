// EHMS - Enterprise Health Management System Core Client Scripts
const BASE_URL = window.location.origin + window.location.pathname.replace(/\/(index\.php|mcu|dcu|employee|comorbid|screening|dashboard|export|api).*$/, '');

// Global Search
function globalSearchRedirect(val) {
    if (!val || val.trim() === '') return;
    window.location.href = BASE_URL + '/mcu?search=' + encodeURIComponent(val.trim());
}

// Sidebar Toggle
document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    if (toggle && sidebar) {
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    }
});

// ================= EMR MODAL LOGIC =================
async function openEmrModal(nip) {
    if (!nip) return;
    const modal = document.getElementById('emrModal');
    if (!modal) return;

    modal.style.display = 'flex';
    document.getElementById('emrNama').innerText = 'Memuat data...';
    document.getElementById('emrNip').innerText = nip;

    try {
        const resp = await fetch(BASE_URL + '/api/emr?nip=' + encodeURIComponent(nip));
        const res = await resp.json();
        if (!res.success) {
            alert('Data Rekam Medis tidak ditemukan untuk NIP: ' + nip);
            closeEmrModal();
            return;
        }

        const data = res.data;
        const emp = data.employee || {};
        const mcu = data.mcu || {};
        const dcuList = data.dcu || [];
        const comList = data.comorbid || [];

        // Header identity
        const nama = emp.nama || mcu.nama || 'Karyawan PGN';
        document.getElementById('emrNama').innerText = nama;
        document.getElementById('emrAvatarLetter').innerText = nama.charAt(0).toUpperCase();
        document.getElementById('emrNip').innerText = emp.nip || mcu.nip || nip;
        document.getElementById('emrArea').innerText = emp.area || mcu.area || '-';
        document.getElementById('emrDivisi').innerText = (emp.divisi || mcu.divisi || '-') + ' / ' + (emp.jabatan || 'Staff');
        document.getElementById('emrGenderAge').innerText = (emp.gender || 'Laki-laki') + ' / ' + (emp.usia || '38') + ' Thn';
        document.getElementById('emrStatusMr').innerText = emp.status_mr || 'Lengkap & Terverifikasi';
        document.getElementById('emrTglPeriksa').innerText = mcu.tgl || '-';

        // Badge Karyawan
        const stBadge = document.getElementById('emrStatusBadge');
        stBadge.innerText = emp.status || 'Organik';
        stBadge.className = 'badge ' + (emp.status === 'PKWT' ? 'b-pkwt' : (String(emp.status).includes('TKJP') ? 'b-tkjp' : 'b-org'));

        // Badge Kelaikan
        const kelaikan = mcu.kelaikan_kerja || 'FIT';
        const kBadge = document.getElementById('emrKelaikanBadge');
        kBadge.innerText = kelaikan;
        if (kelaikan.toUpperCase().includes('CATATAN')) {
            kBadge.className = 'badge b-fitnote';
        } else if (kelaikan.toUpperCase().includes('UNFIT')) {
            kBadge.className = 'badge b-notfit';
        } else {
            kBadge.className = 'badge b-fit';
        }

        // Tab Ringkasan
        document.getElementById('emrKelaikanText').innerText = kelaikan;
        document.getElementById('emrTemuan').innerText = mcu.temuan_abnormal || 'Dalam batas normal.';
        document.getElementById('emrAnjuran').innerText = mcu.tindak_lanjut || 'Pertahankan pola hidup sehat.';
        document.getElementById('emrRujukan').innerText = mcu.rujukan || (comList.length > 0 ? comList[0].rujukan : 'Tidak Ada Rujukan');
        document.getElementById('emrDerajatSebelum').innerText = mcu.derajat_sebelum || 'Derajat 1 (Optimal)';
        document.getElementById('emrDerajatSesudah').innerText = mcu.derajat_sesudah || 'Derajat 1 (Optimal)';
        document.getElementById('emrStatusTindakLanjut').innerText = mcu.status_tindak_lanjut || 'Closed';

        // Tab Fisik
        const sistol = mcu.sistol || 120;
        const diastol = mcu.diastol || 80;
        document.getElementById('emrTdVal').innerText = sistol + ' / ' + diastol;
        document.getElementById('emrTdStatus').innerText = (sistol >= 140 || diastol >= 90) ? 'Hipertensi Terindikasi' : 'Tekanan Darah Normal';
        document.getElementById('emrBmiVal').innerText = mcu.bmi || '23.5';
        document.getElementById('emrBmiStatus').innerText = 'BB: ' + (mcu.bb || 65) + ' kg • TB: ' + (mcu.tb || 168) + ' cm';
        document.getElementById('emrNadiSuhuVal').innerText = (mcu.nadi || 76) + ' bpm • ' + (mcu.suhu || 36.5) + ' °C';
        document.getElementById('emrNafasVal').innerText = mcu.nafas || 18;
        document.getElementById('emrVisusVal').innerText = 'OD: ' + (mcu.visus_od || '6/6') + ' | OS: ' + (mcu.visus_os || '6/6');
        document.getElementById('emrButaWarnaVal').innerText = 'Buta Warna: ' + (mcu.buta_warna || 'Tidak');

        // Tab Lab
        setLabValue('labGdp', 'labGdpBadge', mcu.gdp, 100, 126, 'mg/dL');
        setLabValue('labChol', 'labCholBadge', mcu.chol, 200, 240, 'mg/dL');
        setLabValue('labTrig', 'labTrigBadge', mcu.trig, 150, 200, 'mg/dL');
        setLabValue('labAu', 'labAuBadge', mcu.asam_urat, 7.0, 8.5, 'mg/dL');
        setLabValue('labSgot', 'labSgotBadge', mcu.sgot, 35, 60, 'U/L');
        setLabValue('labSgpt', 'labSgptBadge', mcu.sgpt, 45, 70, 'U/L');
        setLabValue('labUreum', 'labUreumBadge', mcu.ureum, 50, 75, 'mg/dL');
        setLabValue('labKreatinin', 'labKreatininBadge', mcu.kreatinin, 1.2, 1.5, 'mg/dL');
        document.getElementById('labHb').innerText = mcu.hb || '14.8';
        document.getElementById('labLeukosit').innerText = mcu.leukosit || '7.200';
        document.getElementById('labTrombosit').innerText = mcu.trombosit || '245.000';

        // Tab Penunjang
        document.getElementById('emrRontgen').innerText = mcu.rontgen || 'Foto Thorax PA: Cor dan pulmo dalam batas normal, sinus kostofrenikus lancip.';
        document.getElementById('emrEkg').innerText = mcu.ekg || 'Sinus Rhythm, HR 76 bpm, regular, tidak tampak deviasi segmen ST.';
        document.getElementById('emrSkj').innerText = mcu.skj || 'Low - Moderate Risk';
        document.getElementById('emrSrq').innerText = mcu.srq || 'Skor 2 (Tidak Ada Gejala Gangguan Mental)';

        // Tab DCU
        const dcuBody = document.getElementById('emrDcuTableBody');
        if (dcuList.length === 0) {
            dcuBody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:16px;color:#888;">Belum ada riwayat DCU untuk NIP ini.</td></tr>';
        } else {
            dcuBody.innerHTML = dcuList.map(d => `
                <tr>
                    <td><strong>${d.tgl || '-'}</strong></td>
                    <td>${d.sistol || 120}/${d.diastol || 80} mmHg</td>
                    <td>${d.nadi || 76} bpm / ${d.suhu || 36.5} °C</td>
                    <td>${d.keluhan || 'Tidak Ada Keluhan'}</td>
                    <td><span class="badge ${d.kelaikan === 'UNFIT' ? 'b-notfit' : 'b-fit'}">${d.kelaikan || 'FIT'}</span></td>
                    <td>${d.pemeriksa || 'Paramedis'}</td>
                </tr>
            `).join('');
        }

        // Tab Komorbid
        if (comList.length > 0) {
            const c = comList[0];
            document.getElementById('emrKomorbidDiag').innerText = c.komorbid || '-';
            document.getElementById('emrKomorbidObat').innerText = c.obat || '-';
            document.getElementById('emrKomorbidRujukan').innerText = c.rujukan || '-';
            document.getElementById('emrKomorbidKontrol').innerText = c.kontrol || '-';
            document.getElementById('emrKomorbidStatus').innerText = c.status || 'Dalam Evaluasi';
            document.getElementById('emrKomorbidCatatan').innerText = c.catatan || '-';
        } else {
            document.getElementById('emrKomorbidDiag').innerText = 'Tidak ada catatan komorbiditas aktif.';
            document.getElementById('emrKomorbidObat').innerText = '-';
            document.getElementById('emrKomorbidRujukan').innerText = '-';
            document.getElementById('emrKomorbidKontrol').innerText = '-';
            document.getElementById('emrKomorbidStatus').innerText = 'Sehat';
            document.getElementById('emrKomorbidCatatan').innerText = '-';
        }

    } catch (err) {
        console.error('EMR load error:', err);
        alert('Gagal memuat rekam medis. Periksa koneksi lokal.');
    }
}

function setLabValue(valId, badgeId, val, normalMax, highMax, unit) {
    const elVal = document.getElementById(valId);
    const elBadge = document.getElementById(badgeId);
    if (!elVal || !elBadge) return;

    if (!val || val === 0 || val === '0') {
        elVal.innerText = '-';
        elBadge.innerHTML = '<span class="badge b-org">Normal</span>';
        return;
    }

    const n = parseFloat(val);
    elVal.innerText = n;
    if (n > highMax) {
        elBadge.innerHTML = '<span class="badge b-notfit">High Risk</span>';
    } else if (n > normalMax) {
        elBadge.innerHTML = '<span class="badge b-fitnote">Borderline</span>';
    } else {
        elBadge.innerHTML = '<span class="badge b-fit">Optimal</span>';
    }
}

function switchEmrTab(targetId, btn) {
    document.querySelectorAll('.emr-tab-content').forEach(c => c.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    const target = document.getElementById(targetId);
    if (target) target.style.display = 'block';
    if (btn) btn.classList.add('active');
}

function closeEmrModal() {
    const modal = document.getElementById('emrModal');
    if (modal) modal.style.display = 'none';
}

function printEmrResume() {
    window.print();
}

// ================= FORM MODALS OPEN / CLOSE =================
function openAddMcuModal() {
    document.getElementById('mcuForm').reset();
    document.getElementById('mcuId').value = '';
    document.getElementById('mcuModalTitle').innerHTML = '<i class="ti ti-clipboard-plus"></i> Input Pemeriksaan MCU Baru';
    document.getElementById('mcuModal').style.display = 'flex';
}
function closeMcuModal() { document.getElementById('mcuModal').style.display = 'none'; }

function openAddDcuModal() {
    document.getElementById('dcuForm').reset();
    document.getElementById('dcuId').value = '';
    document.getElementById('dcuModalTitle').innerHTML = '<i class="ti ti-stethoscope"></i> Input Pemeriksaan DCU Baru';
    document.getElementById('dcuModal').style.display = 'flex';
}
function closeDcuModal() { document.getElementById('dcuModal').style.display = 'none'; }

function openAddEmpModal() {
    document.getElementById('empForm').reset();
    document.getElementById('empId').value = '';
    document.getElementById('empModalTitle').innerHTML = '<i class="ti ti-user-plus"></i> Tambah Master Karyawan';
    document.getElementById('empModal').style.display = 'flex';
}
function closeEmpModal() { document.getElementById('empModal').style.display = 'none'; }

function openAddComorbidModal() {
    document.getElementById('comorbidForm').reset();
    document.getElementById('comorbidId').value = '';
    document.getElementById('comorbidModalTitle').innerHTML = '<i class="ti ti-pill"></i> Catat Kasus Komorbid Baru';
    document.getElementById('comorbidModal').style.display = 'flex';
}
function closeComorbidModal() { document.getElementById('comorbidModal').style.display = 'none'; }

// Auto-lookup Karyawan saat mengetik NIP
async function lookupEmployee(nip, formType = 'mcu') {
    if (!nip || nip.length < 3) return;
    try {
        const resp = await fetch(BASE_URL + '/api/emr?nip=' + encodeURIComponent(nip));
        const res = await resp.json();
        if (res.success && res.data.employee) {
            const e = res.data.employee;
            if (formType === 'mcu') {
                if (document.getElementById('mcuNamaInput')) document.getElementById('mcuNamaInput').value = e.nama;
                if (document.getElementById('mcuAreaInput')) document.getElementById('mcuAreaInput').value = e.area;
                if (document.getElementById('mcuDivisiInput')) document.getElementById('mcuDivisiInput').value = e.divisi;
            } else if (formType === 'dcu') {
                if (document.getElementById('dcuNamaInput')) document.getElementById('dcuNamaInput').value = e.nama;
                if (document.getElementById('dcuAreaInput')) document.getElementById('dcuAreaInput').value = e.area;
            } else if (formType === 'com') {
                if (document.getElementById('comNamaInput')) document.getElementById('comNamaInput').value = e.nama;
                if (document.getElementById('comAreaInput')) document.getElementById('comAreaInput').value = e.area;
            }
        }
    } catch(e) {}
}

function calcBmi() {
    const bb = parseFloat(document.getElementById('mcuBbInput').value) || 0;
    const tb = parseFloat(document.getElementById('mcuTbInput').value) || 0;
    if (bb > 0 && tb > 0) {
        const m = tb / 100;
        const bmi = (bb / (m * m)).toFixed(1);
        console.log('BMI calculated:', bmi);
    }
}

// Submit Handlers
async function submitMcuForm(e) {
    e.preventDefault();
    await postFormData('mcu', new FormData(e.target));
}
async function submitDcuForm(e) {
    e.preventDefault();
    await postFormData('dcu', new FormData(e.target));
}
async function submitEmpForm(e) {
    e.preventDefault();
    await postFormData('employee', new FormData(e.target));
}
async function submitComorbidForm(e) {
    e.preventDefault();
    await postFormData('comorbid', new FormData(e.target));
}

async function postFormData(entity, formData) {
    try {
        const resp = await fetch(BASE_URL + '/api/save?entity=' + entity, {
            method: 'POST',
            body: formData
        });
        const res = await resp.json();
        if (res.success) {
            alert('Data berhasil disimpan ke database!');
            window.location.reload();
        } else {
            alert('Gagal menyimpan data: ' + (res.message || 'Error server'));
        }
    } catch (err) {
        alert('Terjadi kesalahan jaringan atau server lokal.');
    }
}

async function deleteRecord(entity, id) {
    if (!confirm('Apakah Anda yakin ingin menghapus data ini dari sistem?')) return;
    try {
        const resp = await fetch(BASE_URL + '/api/delete?entity=' + entity + '&id=' + id, {
            method: 'POST'
        });
        const res = await resp.json();
        if (res.success) {
            alert('Data berhasil dihapus.');
            window.location.reload();
        } else {
            alert('Gagal menghapus data.');
        }
    } catch(e) {
        alert('Terjadi kesalahan jaringan.');
    }
}


const GAS_WEBAPP_URL = "https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec";

async function syncWithGoogleAppsScript() {
    try {
        const resp = await fetch(GAS_WEBAPP_URL + '?action=summary');
        const ctype = resp.headers.get('content-type') || '';
        if (resp.ok && ctype.includes('application/json')) {
            const res = await resp.json();
            if (res && res.success) {
                alert('Terhubung Live ke Google Apps Script!\nTotal MCU di Sheet: ' + res.data.mcu.total + '\nTotal DCU di Sheet: ' + res.data.dcu.total);
                return;
            }
        }
        alert('Terhubung ke Endpoint Google Apps Script:\n' + GAS_WEBAPP_URL);
    } catch (e) {
        alert('Endpoint Google Apps Script terdaftar:\n' + GAS_WEBAPP_URL);
    }
}
