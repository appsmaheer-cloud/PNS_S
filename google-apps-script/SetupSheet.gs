/**
 * EHMS - Setup Sheet Automation Script
 * PT PGAS Solution - Divisi K3 & Kesehatan Kerja
 *
 * Jalankan fungsi setupEhmsSpreadsheet() sekali untuk menginisialisasi seluruh tab,
 * warna header korporat, dan validasi data dropdown sesuai standar CSV migrasi.
 */

function setupEhmsSpreadsheet() {
  var ss = SpreadsheetApp.getActiveSpreadsheet();

  // 1. Tab MCU_DATA (Sesuai struktur 49 Kolom MCU_DATA.csv)
  var mcuSheet = getOrCreateSheet(ss, 'MCU_DATA');
  var mcuHeaders = [
    'NIP', 'No MR', 'Nama Karyawan', 'Gender', 'Tgl Lahir', 'Usia', 'Divisi', 'Unit Kerja',
    'Area', 'Status Karyawan', 'Tahun', 'Tgl MCU', 'Sistol', 'Diastol', 'Nadi', 'Suhu',
    'Berat Badan', 'Tinggi Badan', 'BMI', 'Lingkar Pinggang', 'Pemeriksaan Fisik',
    'Rontgen Thorax', 'EKG', 'Treadmill', 'Audiometri', 'Spirometri', 'Urinalisa',
    'Hb', 'GDP', 'HbA1c', 'Kolesterol Total', 'Trigliserida', 'LDL', 'HDL', 'Asam Urat',
    'Ureum', 'Kreatinin', 'SGOT', 'SGPT', 'Temuan Abnormal', 'Saran',
    'Derajat Sebelum', 'Derajat Sesudah', 'SKJ', 'SRQ', 'Tindak Lanjut',
    'Status Tindak Lanjut', 'Kelaikan Kerja', 'Dokter'
  ];
  formatHeader(mcuSheet, mcuHeaders, '#0F172A');

  // Validasi Dropdown Kelaikan Kerja MCU (Kolom AV / ke-48)
  var mcuRule = SpreadsheetApp.newDataValidation()
    .requireValueInList(['FIT', 'FIT dengan catatan', 'FIT DENGAN CATATAN', 'UNFIT'], true)
    .setAllowInvalid(true)
    .build();
  mcuSheet.getRange(2, 48, 3000, 1).setDataValidation(mcuRule);

  // 2. Tab DCU_DATA (Sesuai struktur 14 Kolom DCU_DATA.csv - Murni FIT & UNFIT tanpa Kategori P)
  var dcuSheet = getOrCreateSheet(ss, 'DCU_DATA');
  var dcuHeaders = [
    'DCU ID', 'NIP', 'Nama Karyawan', 'Area', 'Shift', 'Tanggal & Waktu',
    'Sistol', 'Diastol', 'Nadi', 'Suhu', 'Keluhan', 'Pemeriksaan Fisik',
    'Kelaikan Kerja (FIT/UNFIT)', 'Petugas Medis'
  ];
  formatHeader(dcuSheet, dcuHeaders, '#0284C7');

  var dcuRule = SpreadsheetApp.newDataValidation()
    .requireValueInList(['FIT', 'UNFIT'], true)
    .setAllowInvalid(false)
    .build();
  dcuSheet.getRange(2, 13, 3000, 1).setDataValidation(dcuRule);

  // 3. Tab KARYAWAN_DATA (Sesuai struktur 11 Kolom KARYAWAN_DATA.csv)
  var empSheet = getOrCreateSheet(ss, 'KARYAWAN_DATA');
  var empHeaders = [
    'NIP', 'No MR', 'Nama Karyawan', 'Gender', 'Tgl Lahir', 'Usia',
    'Status Karyawan', 'Area Kerja', 'Divisi', 'Unit Kerja', 'Status MR'
  ];
  formatHeader(empSheet, empHeaders, '#10B981');

  var empStatusRule = SpreadsheetApp.newDataValidation()
    .requireValueInList(['Organik', 'Perbantuan PGN', 'PKWT', 'TKJP (Perkasa)', 'TKJP (KPU)', 'TKJP Perkasa', 'TKJP KPU'], true)
    .setAllowInvalid(true)
    .build();
  empSheet.getRange(2, 7, 3000, 1).setDataValidation(empStatusRule);

  // 4. Tab KOMORBID_DATA (Sesuai struktur 9 Kolom KOMORBID_DATA.csv)
  var comSheet = getOrCreateSheet(ss, 'KOMORBID_DATA');
  var comHeaders = [
    'NIP', 'Nama Karyawan', 'Area', 'Riwayat Komorbid', 'Terapi Obat Rutin',
    'Rujukan ke dr Spesialis', 'Jadwal Kontrol', 'Status Kontrol', 'Catatan Klinis'
  ];
  formatHeader(comSheet, comHeaders, '#F59E0B');

  Logger.log('Inisialisasi EHMS Berhasil! Seluruh 4 tab (MCU_DATA, DCU_DATA, KARYAWAN_DATA, KOMORBID_DATA) telah siap.');
  try {
    SpreadsheetApp.getUi().alert('Inisialisasi EHMS Berhasil! Seluruh 4 tab (MCU_DATA, DCU_DATA, KARYAWAN_DATA, KOMORBID_DATA) telah siap.');
  } catch (e) {
    // Aman jika dipanggil langsung dari Apps Script Editor atau Web App tanpa UI spreadsheet terbuka
  }
}

function getOrCreateSheet(ss, sheetName) {
  var sheet = ss.getSheetByName(sheetName);
  if (!sheet) {
    sheet = ss.insertSheet(sheetName);
  }
  return sheet;
}

function formatHeader(sheet, headers, bgColor) {
  sheet.getRange(1, 1, 1, headers.length).setValues([headers]);
  var headerRange = sheet.getRange(1, 1, 1, headers.length);
  headerRange.setBackground(bgColor);
  headerRange.setFontColor('#FFFFFF');
  headerRange.setFontWeight('bold');
  headerRange.setFontFamily('Plus Jakarta Sans');
  headerRange.setFontSize(10);
  headerRange.setHorizontalAlignment('center');
  sheet.setFrozenRows(1);
}
