/**
 * EHMS - Enterprise Health Management System (Google Apps Script Backend & Web App)
 * PT PGAS Solution - Divisi K3 & Kesehatan Kerja
 *
 * URL Deployment Aktif:
 * https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec
 *
 * Mendukung 2 Mode Sekaligus:
 * 1. REST API JSON / JSONP (?action=...) untuk dihubungkan ke Frontend GitHub Pages / Web Eksternal / PHP MVC
 * 2. Native Web App (HtmlService) saat URL dibuka langsung di browser tanpa parameter ?action=
 */

var DEPLOYMENT_URL = 'https://script.google.com/macros/s/AKfycbyElWAEb7e0gO_TL_WBai7GHUKUn9tEAegPXUK6TMmQgDrPHCjoXbwWXlc1549HwTgVag/exec';

/**
 * Handler HTTP GET (REST API & HtmlService)
 */
function doGet(e) {
  var params = (e && e.parameter) ? e.parameter : {};
  var action = String(params.action || '').toLowerCase().trim();

  // Jika dipanggil sebagai REST API oleh Frontend GitHub / Eksternal
  if (action) {
    var result = { success: false, message: 'Unknown action: ' + action };
    try {
      if (action === 'ping' || action === 'status') {
        var stats = apiGetSummaryStats();
        result = {
          success: true,
          status: 'online',
          app: 'EHMS - PT PGAS Solution',
          deployment_url: DEPLOYMENT_URL,
          timestamp: new Date().toISOString(),
          summary: stats.data
        };
      } else if (action === 'summary') {
        result = apiGetSummaryStats();
      } else if (action === 'mcu') {
        result = apiGetMcuData(
          parseInt(params.page || '1', 10),
          parseInt(params.limit || '15', 10),
          params.search || '',
          params.area || '',
          params.kelaikan || '',
          params.tindak || ''
        );
      } else if (action === 'dcu') {
        result = apiGetDcuData(
          parseInt(params.page || '1', 10),
          parseInt(params.limit || '15', 10),
          params.search || '',
          params.area || '',
          params.kelaikan || '',
          params.shift || ''
        );
      } else if (action === 'employees' || action === 'employee') {
        result = apiGetEmployeeData(
          parseInt(params.page || '1', 10),
          parseInt(params.limit || '15', 10),
          params.search || '',
          params.area || '',
          params.status || '',
          params.mr_status || ''
        );
      } else if (action === 'comorbid') {
        result = apiGetComorbidData(
          parseInt(params.page || '1', 10),
          parseInt(params.limit || '15', 10),
          params.search || '',
          params.area || '',
          params.type || ''
        );
      } else if (action === 'screening') {
        result = apiGetScreeningData(params.filter || '');
      } else if (action === 'emr') {
        result = apiGetEmrByNip(params.nip || '');
      } else if (action === 'save') {
        var payload = params.payload ? JSON.parse(params.payload) : params;
        result = apiSaveRecord(params.entity || 'mcu', payload);
      } else if (action === 'delete') {
        result = apiDeleteRecord(params.entity || 'mcu', params.id || params.nip || '');
      }
    } catch (err) {
      result = { success: false, message: err.toString() };
    }

    // Dukungan JSONP jika parameter callback diberikan
    if (params.callback) {
      var jsonp = params.callback + '(' + JSON.stringify(result) + ');';
      return ContentService.createTextOutput(jsonp).setMimeType(ContentService.MimeType.JAVASCRIPT);
    }

    return ContentService.createTextOutput(JSON.stringify(result))
      .setMimeType(ContentService.MimeType.JSON);
  }

  // Jika dibuka langsung di browser -> Render Frontend HTML
  var template = HtmlService.createTemplateFromFile('Index');
  return template.evaluate()
    .setTitle('EHMS - PGN Solution (Enterprise Health Management System)')
    .addMetaTag('viewport', 'width=device-width, initial-scale=1')
    .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL);
}

/**
 * Handler HTTP POST (Menyimpan / Menghapus Data dari Frontend GitHub)
 */
function doPost(e) {
  var result = { success: false, message: 'Invalid POST request' };
  try {
    var body = {};
    if (e && e.postData && e.postData.contents) {
      try {
        body = JSON.parse(e.postData.contents);
      } catch (parseErr) {
        body = e.parameter || {};
      }
    } else if (e && e.parameter) {
      body = e.parameter;
    }

    var action = String(body.action || (e && e.parameter && e.parameter.action) || 'save').toLowerCase();
    var entity = String(body.entity || (e && e.parameter && e.parameter.entity) || 'mcu').toLowerCase();
    var payload = body.payload || body;

    if (action === 'delete') {
      result = apiDeleteRecord(entity, payload.id || payload.nip || '');
    } else {
      result = apiSaveRecord(entity, payload);
    }
  } catch (err) {
    result = { success: false, message: err.toString() };
  }

  return ContentService.createTextOutput(JSON.stringify(result))
    .setMimeType(ContentService.MimeType.JSON);
}

function include(filename) {
  return HtmlService.createHtmlOutputFromFile(filename).getContent();
}

/**
 * Mendapatkan referensi Spreadsheet aktif
 */
function getDb() {
  return SpreadsheetApp.getActiveSpreadsheet();
}

/**
 * Helper membaca baris MCU secara fleksibel (mendukung format 49 kolom CSV penuh maupun 20 kolom ringkas)
 */
function parseMcuRow(row, idx) {
  var isFull = row.length >= 40;
  if (isFull) {
    return {
      id: idx + 1,
      nip: String(row[0] || ''),
      mr_no: String(row[1] || ('MR-' + (idx + 1))),
      nama: String(row[2] || ''),
      gender: String(row[3] || 'Laki-laki'),
      tgl_lahir: String(row[4] || ''),
      usia: row[5] || 30,
      divisi: String(row[6] || ''),
      unit: String(row[7] || ''),
      area: String(row[8] || ''),
      status_karyawan: String(row[9] || 'Organik'),
      tahun: row[10] || 2026,
      tgl: String(row[11] || ''),
      tgl_mcu: String(row[11] || ''),
      sistol: parseFloat(row[12]) || 120,
      diastol: parseFloat(row[13]) || 80,
      nadi: parseFloat(row[14]) || 78,
      suhu: parseFloat(row[15]) || 36.5,
      bb: parseFloat(row[16]) || 68,
      tb: parseFloat(row[17]) || 168,
      bmi: parseFloat(row[18]) || 23.5,
      lingkar_pinggang: parseFloat(row[19]) || 82,
      pem_fisik: String(row[20] || 'Dalam batas normal'),
      rontgen: String(row[21] || 'Normal'),
      ekg: String(row[22] || 'Dalam Batas Normal'),
      treadmill: String(row[23] || '-'),
      audiometri: String(row[24] || 'Normal'),
      spirometri: String(row[25] || 'Normal'),
      urinalisa: String(row[26] || 'Negatif'),
      hb: parseFloat(row[27]) || 14.5,
      gdp: parseFloat(row[28]) || 90,
      hba1c: parseFloat(row[29]) || 5.4,
      chol: parseFloat(row[30]) || 180,
      trig: parseFloat(row[31]) || 110,
      ldl: parseFloat(row[32]) || 100,
      hdl: parseFloat(row[33]) || 50,
      asam_urat: parseFloat(row[34]) || 5.5,
      ureum: parseFloat(row[35]) || 25,
      kreatinin: parseFloat(row[36]) || 0.9,
      sgot: parseFloat(row[37]) || 22,
      sgpt: parseFloat(row[38]) || 25,
      temuan_abnormal: String(row[39] || 'Dalam batas normal'),
      saran: String(row[40] || 'Pertahankan pola hidup sehat'),
      derajat_sebelum: String(row[41] || 'P2'),
      derajat_sesudah: String(row[42] || 'P1'),
      skj: String(row[43] || 'Low Risk'),
      srq: String(row[44] || 'Dalam Batas Normal'),
      tindak_lanjut: String(row[45] || 'Resep'),
      status_tindak_lanjut: String(row[46] || 'Selesai'),
      kelaikan_kerja: String(row[47] || 'FIT'),
      dokter: String(row[48] || 'dr. Hessty')
    };
  }
  return {
    id: idx + 1,
    nip: String(row[0] || ''),
    mr_no: 'MR-' + String(idx + 1).padStart(4, '0'),
    nama: String(row[1] || ''),
    area: String(row[2] || ''),
    divisi: String(row[3] || ''),
    status_karyawan: 'Organik',
    tahun: row[4] || 2026,
    tgl: String(row[5] || ''),
    tgl_mcu: String(row[5] || ''),
    sistol: parseFloat(row[6]) || 120,
    diastol: parseFloat(row[7]) || 80,
    bb: parseFloat(row[8]) || 68,
    tb: parseFloat(row[9]) || 168,
    bmi: parseFloat(row[10]) || 23.5,
    gdp: parseFloat(row[11]) || 90,
    chol: parseFloat(row[12]) || 180,
    asam_urat: parseFloat(row[13]) || 5.5,
    rontgen: String(row[14] || 'Normal'),
    ekg: String(row[15] || 'Normal'),
    kelaikan_kerja: String(row[16] || 'FIT'),
    temuan_abnormal: String(row[17] || 'Dalam batas normal'),
    tindak_lanjut: String(row[18] || 'Kontrol berkala'),
    status_tindak_lanjut: String(row[19] || 'Selesai'),
    derajat_sebelum: 'P2',
    derajat_sesudah: 'P1',
    skj: 'Low Risk',
    srq: 'Normal'
  };
}

function parseDcuRow(row, idx) {
  var hasDcuId = String(row[0] || '').indexOf('DCU-') === 0 || row.length >= 13;
  if (hasDcuId) {
    return {
      id: idx + 1,
      dcu_id: String(row[0] || ('DCU-' + (idx + 1))),
      nip: String(row[1] || ''),
      nama: String(row[2] || ''),
      area: String(row[3] || ''),
      shift: String(row[4] || 'Shift 1 (Pagi)'),
      tgl: String(row[5] || ''),
      sistol: parseFloat(row[6]) || 120,
      diastol: parseFloat(row[7]) || 80,
      nadi: parseFloat(row[8]) || 78,
      suhu: parseFloat(row[9]) || 36.5,
      keluhan: String(row[10] || 'Tidak ada keluhan'),
      pem_fisik: String(row[11] || 'Kesadaran CM, TTV stabil'),
      kelaikan: String(row[12] || 'FIT').toUpperCase().indexOf('UNFIT') !== -1 ? 'UNFIT' : 'FIT',
      petugas: String(row[13] || 'dr. Tim Medis K3')
    };
  }
  return {
    id: idx + 1,
    dcu_id: 'DCU-' + String(idx + 1).padStart(5, '0'),
    nip: String(row[0] || ''),
    nama: String(row[1] || ''),
    area: String(row[2] || ''),
    shift: String(row[3] || 'Shift 1 (Pagi)'),
    tgl: String(row[4] || ''),
    sistol: parseFloat(row[5]) || 120,
    diastol: parseFloat(row[6]) || 80,
    nadi: parseFloat(row[7]) || 78,
    suhu: parseFloat(row[8]) || 36.5,
    keluhan: String(row[9] || 'Tidak ada keluhan'),
    pem_fisik: 'Kesadaran CM, TTV stabil',
    kelaikan: String(row[10] || 'FIT').toUpperCase().indexOf('UNFIT') !== -1 ? 'UNFIT' : 'FIT',
    petugas: String(row[11] || 'dr. Tim Medis K3')
  };
}

function parseEmpRow(row, idx) {
  if (row.length >= 10) {
    return {
      id: idx + 1,
      nip: String(row[0] || ''),
      mr_no: String(row[1] || ('MR-' + (idx + 1))),
      nama: String(row[2] || ''),
      gender: String(row[3] || 'Laki-laki'),
      tgl_lahir: String(row[4] || ''),
      usia: row[5] || 30,
      status_karyawan: String(row[6] || 'Organik'),
      status: String(row[6] || 'Organik'),
      area: String(row[7] || ''),
      divisi: String(row[8] || ''),
      unit: String(row[9] || 'Field Ops'),
      jabatan: String(row[9] || 'Field Ops'),
      mr_status: String(row[10] || 'Active'),
      status_mr: String(row[10] || 'Active')
    };
  }
  return {
    id: idx + 1,
    nip: String(row[0] || ''),
    mr_no: 'MR-' + String(idx + 1).padStart(4, '0'),
    nama: String(row[1] || ''),
    gender: String(row[2] || 'Laki-laki'),
    usia: row[3] || 30,
    status_karyawan: String(row[4] || 'Organik'),
    status: String(row[4] || 'Organik'),
    area: String(row[5] || ''),
    divisi: String(row[6] || ''),
    unit: String(row[7] || 'Field Ops'),
    jabatan: String(row[7] || 'Field Ops'),
    mr_status: String(row[8] || 'Active'),
    status_mr: String(row[8] || 'Active')
  };
}

function parseComRow(row, idx) {
  if (row.length >= 9) {
    return {
      id: idx + 1,
      nip: String(row[0] || ''),
      nama: String(row[1] || ''),
      area: String(row[2] || ''),
      komorbid: String(row[3] || ''),
      obat: String(row[4] || ''),
      rujukan: String(row[5] || ''),
      kontrol: String(row[6] || ''),
      status: String(row[7] || 'Terkontrol Baik'),
      catatan: String(row[8] || '')
    };
  }
  return {
    id: idx + 1,
    nip: String(row[0] || ''),
    nama: String(row[1] || ''),
    area: 'Jakarta',
    komorbid: String(row[2] || ''),
    obat: String(row[3] || ''),
    rujukan: String(row[4] || ''),
    kontrol: String(row[5] || ''),
    status: String(row[6] || 'Terkontrol Baik'),
    catatan: String(row[7] || '')
  };
}

/**
 * KPI & Statistik Keseluruhan Dashboard
 */
function apiGetSummaryStats() {
  var ss = getDb();
  var mcuSheet = ss.getSheetByName('MCU_DATA');
  var dcuSheet = ss.getSheetByName('DCU_DATA');
  var empSheet = ss.getSheetByName('KARYAWAN_DATA');
  var comSheet = ss.getSheetByName('KOMORBID_DATA');

  var mcuTotal = mcuSheet ? Math.max(0, mcuSheet.getLastRow() - 1) : 0;
  var dcuTotal = dcuSheet ? Math.max(0, dcuSheet.getLastRow() - 1) : 0;
  var empTotal = empSheet ? Math.max(0, empSheet.getLastRow() - 1) : 0;
  var comTotal = comSheet ? Math.max(0, comSheet.getLastRow() - 1) : 0;

  var fit = 0, fitNote = 0, unfit = 0;
  var ht = 0, dm = 0, lipid = 0;
  if (mcuSheet && mcuTotal > 0) {
    var mcuRows = mcuSheet.getRange(2, 1, mcuTotal, mcuSheet.getLastColumn()).getValues();
    for (var i = 0; i < mcuRows.length; i++) {
      var m = parseMcuRow(mcuRows[i], i);
      var k = m.kelaikan_kerja.toUpperCase();
      if (k.indexOf('CATATAN') !== -1) fitNote++;
      else if (k.indexOf('UNFIT') !== -1) unfit++;
      else fit++;

      if (m.sistol >= 140 || m.diastol >= 90) ht++;
      if (m.gdp >= 126) dm++;
      if (m.chol >= 200 || (m.trig && m.trig >= 150)) lipid++;
    }
  }

  var dcuFit = 0, dcuUnfit = 0;
  if (dcuSheet && dcuTotal > 0) {
    var dcuRows = dcuSheet.getRange(2, 1, dcuTotal, dcuSheet.getLastColumn()).getValues();
    for (var j = 0; j < dcuRows.length; j++) {
      var d = parseDcuRow(dcuRows[j], j);
      if (d.kelaikan === 'UNFIT') dcuUnfit++;
      else dcuFit++;
    }
  }

  return {
    success: true,
    data: {
      mcu: { total: mcuTotal, fit: fit, fitnote: fitNote, unfit: unfit },
      dcu: { total: dcuTotal, fit: dcuFit, unfit: dcuUnfit },
      employee: { total: empTotal },
      comorbid: { total: comTotal, ht: ht, dm: dm, lipid: lipid, rujukan: comTotal }
    }
  };
}

/**
 * Mengambil data MCU dengan pagination & filter
 */
function apiGetMcuData(page, limit, search, area, kelaikan, tindak) {
  page = page || 1;
  limit = limit || 15;
  search = String(search || '').toLowerCase();
  area = String(area || '').toLowerCase();
  kelaikan = String(kelaikan || '').toUpperCase();
  tindak = String(tindak || '').toLowerCase();

  var sheet = getDb().getSheetByName('MCU_DATA');
  if (!sheet || sheet.getLastRow() <= 1) {
    return { success: true, data: [], total: 0, page: page, total_pages: 1 };
  }

  var data = sheet.getRange(2, 1, sheet.getLastRow() - 1, sheet.getLastColumn()).getValues();
  var filtered = [];

  for (var i = 0; i < data.length; i++) {
    var m = parseMcuRow(data[i], i);
    if (search && m.nip.toLowerCase().indexOf(search) === -1 && m.nama.toLowerCase().indexOf(search) === -1 && m.area.toLowerCase().indexOf(search) === -1) {
      continue;
    }
    if (area && m.area.toLowerCase().indexOf(area) === -1) continue;
    if (kelaikan) {
      var rk = m.kelaikan_kerja.toUpperCase();
      if (kelaikan === 'FIT' && (rk.indexOf('CATATAN') !== -1 || rk.indexOf('UNFIT') !== -1)) continue;
      if (kelaikan !== 'FIT' && rk.indexOf(kelaikan) === -1) continue;
    }
    if (tindak && m.status_tindak_lanjut.toLowerCase().indexOf(tindak) === -1 && m.tindak_lanjut.toLowerCase().indexOf(tindak) === -1) continue;

    filtered.push(m);
  }

  var total = filtered.length;
  var start = (page - 1) * limit;
  var paged = filtered.slice(start, start + limit);

  return {
    success: true,
    data: paged,
    total: total,
    page: page,
    limit: limit,
    total_pages: Math.max(1, Math.ceil(total / limit))
  };
}

/**
 * Mengambil data DCU dengan pagination & filter
 */
function apiGetDcuData(page, limit, search, area, kelaikan, shift) {
  page = page || 1;
  limit = limit || 15;
  search = String(search || '').toLowerCase();
  area = String(area || '').toLowerCase();
  kelaikan = String(kelaikan || '').toUpperCase();
  shift = String(shift || '').toLowerCase();

  var sheet = getDb().getSheetByName('DCU_DATA');
  if (!sheet || sheet.getLastRow() <= 1) {
    return { success: true, data: [], total: 0, page: page, total_pages: 1 };
  }

  var data = sheet.getRange(2, 1, sheet.getLastRow() - 1, sheet.getLastColumn()).getValues();
  var filtered = [];

  for (var i = 0; i < data.length; i++) {
    var d = parseDcuRow(data[i], i);
    if (search && d.nip.toLowerCase().indexOf(search) === -1 && d.nama.toLowerCase().indexOf(search) === -1 && d.area.toLowerCase().indexOf(search) === -1 && d.keluhan.toLowerCase().indexOf(search) === -1) {
      continue;
    }
    if (area && d.area.toLowerCase().indexOf(area) === -1) continue;
    if (kelaikan && d.kelaikan !== kelaikan) continue;
    if (shift && d.shift.toLowerCase().indexOf(shift) === -1) continue;

    filtered.push(d);
  }

  var total = filtered.length;
  var start = (page - 1) * limit;
  var paged = filtered.slice(start, start + limit);

  return {
    success: true,
    data: paged,
    total: total,
    page: page,
    limit: limit,
    total_pages: Math.max(1, Math.ceil(total / limit))
  };
}

/**
 * Mengambil data Master Karyawan dengan pagination & filter
 */
function apiGetEmployeeData(page, limit, search, area, status, mrStatus) {
  page = page || 1;
  limit = limit || 15;
  search = String(search || '').toLowerCase();
  area = String(area || '').toLowerCase();
  status = String(status || '').toLowerCase();
  mrStatus = String(mrStatus || '').toLowerCase();

  var sheet = getDb().getSheetByName('KARYAWAN_DATA');
  if (!sheet || sheet.getLastRow() <= 1) {
    return { success: true, data: [], total: 0, page: page, total_pages: 1 };
  }

  var data = sheet.getRange(2, 1, sheet.getLastRow() - 1, sheet.getLastColumn()).getValues();
  var filtered = [];

  for (var i = 0; i < data.length; i++) {
    var e = parseEmpRow(data[i], i);
    if (search && e.nip.toLowerCase().indexOf(search) === -1 && e.nama.toLowerCase().indexOf(search) === -1 && e.divisi.toLowerCase().indexOf(search) === -1) {
      continue;
    }
    if (area && e.area.toLowerCase().indexOf(area) === -1) continue;
    if (status && e.status_karyawan.toLowerCase().indexOf(status) === -1) continue;
    if (mrStatus && e.mr_status.toLowerCase() !== mrStatus) continue;

    filtered.push(e);
  }

  var total = filtered.length;
  var start = (page - 1) * limit;
  var paged = filtered.slice(start, start + limit);

  return {
    success: true,
    data: paged,
    total: total,
    page: page,
    limit: limit,
    total_pages: Math.max(1, Math.ceil(total / limit))
  };
}

/**
 * Mengambil data Pemantauan Komorbiditas dengan pagination & filter
 */
function apiGetComorbidData(page, limit, search, area, type) {
  page = page || 1;
  limit = limit || 15;
  search = String(search || '').toLowerCase();
  area = String(area || '').toLowerCase();
  type = String(type || '').toLowerCase();

  var sheet = getDb().getSheetByName('KOMORBID_DATA');
  if (!sheet || sheet.getLastRow() <= 1) {
    return { success: true, data: [], total: 0, page: page, total_pages: 1 };
  }

  var data = sheet.getRange(2, 1, sheet.getLastRow() - 1, sheet.getLastColumn()).getValues();
  var filtered = [];

  for (var i = 0; i < data.length; i++) {
    var c = parseComRow(data[i], i);
    if (search && c.nip.toLowerCase().indexOf(search) === -1 && c.nama.toLowerCase().indexOf(search) === -1 && c.obat.toLowerCase().indexOf(search) === -1 && c.komorbid.toLowerCase().indexOf(search) === -1) {
      continue;
    }
    if (area && c.area.toLowerCase().indexOf(area) === -1) continue;
    if (type && c.komorbid.toLowerCase().indexOf(type) === -1) continue;

    filtered.push(c);
  }

  var total = filtered.length;
  var start = (page - 1) * limit;
  var paged = filtered.slice(start, start + limit);

  return {
    success: true,
    data: paged,
    total: total,
    page: page,
    limit: limit,
    total_pages: Math.max(1, Math.ceil(total / limit))
  };
}

/**
 * 15 Indikator Klinis Power BI Screening Engine
 */
function apiGetScreeningData(activeFilter) {
  var sheet = getDb().getSheetByName('MCU_DATA');
  var rows = [];
  if (sheet && sheet.getLastRow() > 1) {
    var raw = sheet.getRange(2, 1, sheet.getLastRow() - 1, sheet.getLastColumn()).getValues();
    for (var i = 0; i < raw.length; i++) {
      rows.push(parseMcuRow(raw[i], i));
    }
  }

  var rulesDef = [
    { id: 'obesitas', name: '1. Obesitas Sentral & BMI >= 25', desc: 'Indeks Massa Tubuh >= 25 kg/m2', match: function(m) { return m.bmi >= 25; } },
    { id: 'hipertensi', name: '2. Hipertensi (TD >= 140/90)', desc: 'Tekanan darah sistolik >= 140 atau diastolik >= 90 mmHg', match: function(m) { return m.sistol >= 140 || m.diastol >= 90; } },
    { id: 'kolesterol', name: '3. Hiperkolesterolemia', desc: 'Kolesterol Total >= 200 mg/dL', match: function(m) { return m.chol >= 200; } },
    { id: 'asam_urat', name: '4. Hiperurisemia (Asam Urat)', desc: 'Kadar Asam Urat > 7.0 mg/dL', match: function(m) { return m.asam_urat > 7.0; } },
    { id: 'trigliserida', name: '5. Dislipidemia Trigliserida', desc: 'Kadar Trigliserida >= 150 mg/dL', match: function(m) { return (m.trig || 0) >= 150; } },
    { id: 'diabetes', name: '6. Hiperglikemia / Suspek DM', desc: 'Glukosa Darah Puasa >= 126 mg/dL', match: function(m) { return m.gdp >= 126; } },
    { id: 'pjk', name: '7. Risiko Kardiovaskular / PJK', desc: 'Treadmill positif iskemia atau TD >= 160', match: function(m) { return m.sistol >= 160 || String(m.treadmill || '').toLowerCase().indexOf('positif') !== -1; } },
    { id: 'ekg', name: '8. Kelainan EKG', desc: 'Hasil rekaman EKG di luar batas normal', match: function(m) { var e = String(m.ekg || '').toLowerCase(); return e !== '' && e.indexOf('normal') === -1 && e !== '-'; } },
    { id: 'thorax', name: '9. Kelainan Radiologi Thorax', desc: 'Temuan fibrosis, kardiomegali, atau infiltrat paru', match: function(m) { var r = String(m.rontgen || '').toLowerCase(); return r !== '' && r.indexOf('tidak tampak kelainan') === -1 && r.indexOf('normal') === -1 && r !== '-'; } },
    { id: 'skj', name: '10. Risiko Kebugaran SKJ', desc: 'Klasifikasi SKJ Moderate / High Risk', match: function(m) { var s = String(m.skj || '').toLowerCase(); return s.indexOf('high') !== -1 || s.indexOf('moderate') !== -1; } },
    { id: 'isk', name: '11. Suspek ISK / Urinalisa', desc: 'Bakteriuria, hematuria, atau proteinuria positif', match: function(m) { var u = String(m.urinalisa || '').toLowerCase(); return u.indexOf('positif') !== -1 || String(m.temuan_abnormal || '').toLowerCase().indexOf('infeksi saluran kemih') !== -1; } },
    { id: 'ginjal', name: '12. Gangguan Fungsi Ginjal', desc: 'Kreatinin > 1.2 mg/dL atau Ureum > 50 mg/dL', match: function(m) { return (m.kreatinin || 0) > 1.2 || (m.ureum || 0) > 50; } },
    { id: 'liver', name: '13. Peningkatan Enzim Hati', desc: 'SGOT > 35 U/L atau SGPT > 45 U/L', match: function(m) { return (m.sgot || 0) > 35 || (m.sgpt || 0) > 45; } },
    { id: 'anemia', name: '14. Anemia Defisiensi (Hb < 12)', desc: 'Kadar Hemoglobin < 12.0 g/dL', match: function(m) { return (m.hb || 14) < 12.0; } },
    { id: 'spirometri', name: '15. Gangguan Fungsi Paru / Pendengaran', desc: 'Restriksi/Obstruksi Spirometri atau Tuli Audiometri', match: function(m) { var sp = String(m.spirometri || '').toLowerCase(); return (sp !== '' && sp !== '-' && sp.indexOf('normal') === -1); } }
  ];

  var rulesOut = [];
  var screened = [];

  for (var rIdx = 0; rIdx < rulesDef.length; rIdx++) {
    var rule = rulesDef[rIdx];
    var count = 0;
    for (var mIdx = 0; mIdx < rows.length; mIdx++) {
      if (rule.match(rows[mIdx])) {
        count++;
        if (activeFilter === rule.id && screened.length < 100) {
          screened.push(rows[mIdx]);
        }
      }
    }
    rulesOut.push({ id: rule.id, name: rule.name, desc: rule.desc, count: count });
  }

  if (!activeFilter) {
    for (var k = 0; k < rows.length && screened.length < 50; k++) {
      if (rows[k].kelaikan_kerja.toUpperCase() !== 'FIT') {
        screened.push(rows[k]);
      }
    }
  }

  return {
    success: true,
    rules: rulesOut,
    activeFilter: activeFilter || '',
    data: screened
  };
}

/**
 * Mengambil rekam medis lengkap pekerja (EMR)
 */
function apiGetEmrByNip(nip) {
  if (!nip) return { success: false, message: 'NIP required' };

  var ss = getDb();
  var mcuSheet = ss.getSheetByName('MCU_DATA');
  var dcuSheet = ss.getSheetByName('DCU_DATA');
  var empSheet = ss.getSheetByName('KARYAWAN_DATA');
  var comSheet = ss.getSheetByName('KOMORBID_DATA');

  var emp = null;
  if (empSheet && empSheet.getLastRow() > 1) {
    var empData = empSheet.getRange(2, 1, empSheet.getLastRow() - 1, empSheet.getLastColumn()).getValues();
    for (var i = 0; i < empData.length; i++) {
      if (String(empData[i][0]).trim() === String(nip).trim()) {
        emp = parseEmpRow(empData[i], i);
        break;
      }
    }
  }

  var mcu = null;
  if (mcuSheet && mcuSheet.getLastRow() > 1) {
    var mcuData = mcuSheet.getRange(2, 1, mcuSheet.getLastRow() - 1, mcuSheet.getLastColumn()).getValues();
    for (var j = 0; j < mcuData.length; j++) {
      if (String(mcuData[j][0]).trim() === String(nip).trim()) {
        mcu = parseMcuRow(mcuData[j], j);
        break;
      }
    }
  }

  var dcuList = [];
  if (dcuSheet && dcuSheet.getLastRow() > 1) {
    var dcuData = dcuSheet.getRange(2, 1, dcuSheet.getLastRow() - 1, dcuSheet.getLastColumn()).getValues();
    for (var k = 0; k < dcuData.length; k++) {
      var d = parseDcuRow(dcuData[k], k);
      if (String(d.nip).trim() === String(nip).trim() || (mcu && d.nama.toLowerCase() === mcu.nama.toLowerCase())) {
        dcuList.push({
          tgl: d.tgl,
          sistol: d.sistol,
          diastol: d.diastol,
          nadi: d.nadi,
          suhu: d.suhu,
          keluhan: d.keluhan,
          kelaikan: d.kelaikan,
          pemeriksa: d.petugas
        });
      }
    }
  }

  var comList = [];
  if (comSheet && comSheet.getLastRow() > 1) {
    var comData = comSheet.getRange(2, 1, comSheet.getLastRow() - 1, comSheet.getLastColumn()).getValues();
    for (var m = 0; m < comData.length; m++) {
      if (String(comData[m][0]).trim() === String(nip).trim()) {
        comList.push(parseComRow(comData[m], m));
      }
    }
  }

  return {
    success: true,
    data: {
      employee: emp || {
        nip: nip,
        nama: mcu ? mcu.nama : '-',
        area: mcu ? mcu.area : '-',
        divisi: mcu ? mcu.divisi : '-',
        gender: mcu ? mcu.gender : 'Laki-laki',
        usia: mcu ? mcu.usia : 30,
        status: mcu ? mcu.status_karyawan : 'Organik',
        status_mr: 'Lengkap & Terverifikasi'
      },
      mcu: mcu,
      dcu: dcuList,
      comorbid: comList
    }
  };
}

/**
 * Menyimpan data record ke Sheet (Mendukung MCU, DCU, Karyawan, Komorbid)
 */
function apiSaveRecord(entity, payload) {
  var ss = getDb();
  entity = String(entity || 'mcu').toLowerCase();
  var sheetName = entity === 'mcu' ? 'MCU_DATA' : (entity === 'dcu' ? 'DCU_DATA' : (entity === 'employee' ? 'KARYAWAN_DATA' : 'KOMORBID_DATA'));
  var sheet = ss.getSheetByName(sheetName);
  if (!sheet) return { success: false, message: 'Sheet ' + sheetName + ' tidak ditemukan' };

  var lastCol = sheet.getLastColumn();
  var nowDate = Utilities.formatDate(new Date(), 'GMT+7', 'dd/MM/yyyy');
  var nowDateTime = Utilities.formatDate(new Date(), 'GMT+7', 'dd/MM/yyyy HH:mm');

  if (entity === 'mcu') {
    var bb = parseFloat(payload.bb) || 68;
    var tb = parseFloat(payload.tb) || 168;
    var bmi = payload.bmi || (tb > 0 ? (bb / ((tb / 100) * (tb / 100))).toFixed(2) : 23.5);
    if (lastCol >= 40) {
      sheet.appendRow([
        payload.nip || '',
        payload.mr_no || ('MR-' + sheet.getLastRow()),
        payload.nama || '',
        payload.gender || 'Laki-laki',
        payload.tgl_lahir || '01/01/1990',
        payload.usia || 35,
        payload.divisi || 'Operasional',
        payload.unit || 'Field Ops',
        payload.area || 'Jakarta (Kantor Pusat)',
        payload.status_karyawan || 'Organik',
        new Date().getFullYear(),
        payload.tgl_mcu || payload.tgl || nowDate,
        payload.sistol || 120,
        payload.diastol || 80,
        payload.nadi || 78,
        payload.suhu || 36.5,
        bb,
        tb,
        bmi,
        payload.lingkar_pinggang || 82,
        payload.pem_fisik || 'Dalam batas normal',
        payload.rontgen || 'Tidak tampak kelainan radiologi jantung dan paru.',
        payload.ekg || 'Dalam Batas Normal',
        payload.treadmill || '-',
        payload.audiometri || 'Normal',
        payload.spirometri || 'Normal',
        payload.urinalisa || 'Negatif',
        payload.hb || 14.5,
        payload.gdp || 92,
        payload.hba1c || 5.4,
        payload.chol || 185,
        payload.trig || 110,
        payload.ldl || 105,
        payload.hdl || 52,
        payload.asam_urat || 5.6,
        payload.ureum || 24,
        payload.kreatinin || 0.9,
        payload.sgot || 21,
        payload.sgpt || 24,
        payload.temuan_abnormal || 'Dalam batas normal',
        payload.saran || 'Pertahankan pola hidup sehat',
        payload.derajat_sebelum || 'P2',
        payload.derajat_sesudah || 'P1',
        payload.skj || 'Low Risk',
        payload.srq || 'Dalam Batas Normal',
        payload.tindak_lanjut || 'Resep',
        payload.status_tindak_lanjut || 'Selesai',
        payload.kelaikan_kerja || 'FIT',
        payload.dokter || 'dr. Tim Medis K3'
      ]);
    } else {
      sheet.appendRow([
        payload.nip || '', payload.nama || '', payload.area || 'Jakarta', payload.divisi || 'Operasional',
        new Date().getFullYear(), payload.tgl || nowDate,
        payload.sistol || 120, payload.diastol || 80, bb, tb,
        bmi, payload.gdp || 95, payload.chol || 180, payload.asam_urat || 5.5,
        payload.rontgen || 'Normal', payload.ekg || 'Normal', payload.kelaikan_kerja || 'FIT',
        payload.temuan_abnormal || 'Dalam batas normal', payload.tindak_lanjut || 'Kontrol berkala',
        payload.status_tindak_lanjut || 'Selesai'
      ]);
    }
  } else if (entity === 'dcu') {
    var kelaikanDcu = String(payload.kelaikan || 'FIT').toUpperCase().indexOf('UNFIT') !== -1 ? 'UNFIT' : 'FIT';
    if (lastCol >= 13) {
      sheet.appendRow([
        'DCU-' + String(sheet.getLastRow()).padStart(5, '0'),
        payload.nip || '',
        payload.nama || '',
        payload.area || 'Jakarta (Kantor Pusat)',
        payload.shift || 'Shift 1 (Pagi)',
        payload.tgl || nowDateTime,
        payload.sistol || 120,
        payload.diastol || 80,
        payload.nadi || 78,
        payload.suhu || 36.5,
        payload.keluhan || 'Tidak ada keluhan',
        payload.pem_fisik || 'Kesadaran CM, TTV stabil',
        kelaikanDcu,
        payload.petugas || 'dr. Tim Medis K3'
      ]);
    } else {
      sheet.appendRow([
        payload.nip || '', payload.nama || '', payload.area || 'Jakarta', payload.shift || 'Shift 1 (Pagi)',
        payload.tgl || nowDateTime, payload.sistol || 120, payload.diastol || 80, payload.nadi || 76, payload.suhu || 36.5,
        payload.keluhan || 'Tidak ada keluhan', kelaikanDcu, payload.petugas || 'Paramedis K3'
      ]);
    }
  } else if (entity === 'employee') {
    if (lastCol >= 10) {
      sheet.appendRow([
        payload.nip || '',
        payload.mr_no || ('MR-' + String(sheet.getLastRow()).padStart(4, '0')),
        payload.nama || '',
        payload.gender || 'Laki-laki',
        payload.tgl_lahir || '01/01/1990',
        payload.usia || 32,
        payload.status_karyawan || payload.status || 'Organik',
        payload.area || 'Jakarta (Kantor Pusat)',
        payload.divisi || 'Operasional',
        payload.unit || payload.jabatan || 'Field Ops',
        payload.mr_status || payload.status_mr || 'Active'
      ]);
    } else {
      sheet.appendRow([
        payload.nip || '', payload.nama || '', payload.gender || 'Laki-laki', payload.usia || 32,
        payload.status_karyawan || payload.status || 'Organik', payload.area || 'Jakarta',
        payload.divisi || 'Operasional', payload.jabatan || 'Staff', payload.status_mr || 'Lengkap'
      ]);
    }
  } else if (entity === 'comorbid') {
    sheet.appendRow([
      payload.nip || '',
      payload.nama || '',
      payload.area || 'Jakarta (Kantor Pusat)',
      payload.komorbid || 'Hipertensi',
      payload.obat || 'Amlodipine 5mg 1x1',
      payload.rujukan || 'dr. Sp.PD (Penyakit Dalam)',
      payload.kontrol || nowDate,
      payload.status || 'Terkontrol Baik',
      payload.catatan || 'Pemantauan berkala K3'
    ]);
  }

  return { success: true, message: 'Data ' + entity.toUpperCase() + ' berhasil disimpan ke Google Sheets' };
}

/**
 * Menghapus record dari Sheet berdasarkan NIP atau nomor baris
 */
function apiDeleteRecord(entity, idOrNip) {
  if (!idOrNip) return { success: false, message: 'ID atau NIP diperlukan' };
  var ss = getDb();
  entity = String(entity || 'mcu').toLowerCase();
  var sheetName = entity === 'mcu' ? 'MCU_DATA' : (entity === 'dcu' ? 'DCU_DATA' : (entity === 'employee' ? 'KARYAWAN_DATA' : 'KOMORBID_DATA'));
  var sheet = ss.getSheetByName(sheetName);
  if (!sheet || sheet.getLastRow() <= 1) return { success: false, message: 'Sheet kosong atau tidak ditemukan' };

  var data = sheet.getRange(2, 1, sheet.getLastRow() - 1, 2).getValues();
  for (var i = data.length - 1; i >= 0; i--) {
    if (String(data[i][0]) === String(idOrNip) || String(data[i][1]) === String(idOrNip) || String(i + 1) === String(idOrNip)) {
      sheet.deleteRow(i + 2);
      return { success: true, message: 'Data berhasil dihapus dari Google Sheets' };
    }
  }
  return { success: false, message: 'Data tidak ditemukan di Google Sheets' };
}
