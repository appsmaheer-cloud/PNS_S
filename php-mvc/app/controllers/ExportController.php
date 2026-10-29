<?php
require_once dirname(__DIR__) . '/core/Controller.php';

class ExportController extends Controller {
    public function exportMcu(): void {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT nip, mr_no, nama, gender, usia, divisi, area, status_karyawan, tahun, tgl_mcu, sistol, diastol, nadi, suhu, bb, tb, bmi, lingkar_pinggang, pem_fisik, rontgen, ekg, skj, srq, tindak_lanjut, status_tindak_lanjut, kelaikan_kerja FROM mcu");
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Rekap_MCU_PGN_Solution_' . date('Ymd_His') . '.csv');
        $out = fopen('php://output', 'w');
        fputs($out, "ï»¿");
        fputcsv($out, ['NIP', 'No MR', 'Nama', 'Gender', 'Usia', 'Divisi', 'Area', 'Status Karyawan', 'Tahun', 'Tgl MCU', 'Sistol', 'Diastol', 'Nadi', 'Suhu', 'BB', 'TB', 'BMI', 'Lingkar Pinggang', 'Pemeriksaan Fisik', 'Rontgen', 'EKG', 'SKJ', 'SRQ', 'Tindak Lanjut', 'Status Tindak Lanjut', 'Kelaikan Kerja']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($out, array_values($row));
        }
        fclose($out);
        exit;
    }

    public function exportDcu(): void {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT dcu_id, nip, nama, area, shift, tgl, sistol, diastol, nadi, suhu, keluhan, pem_fisik, kelaikan, petugas FROM dcu");
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Rekap_DCU_Pemeriksaan_Harian_' . date('Ymd_His') . '.csv');
        $out = fopen('php://output', 'w');
        fputs($out, "ï»¿");
        fputcsv($out, ['DCU ID', 'NIP', 'Nama', 'Area', 'Shift', 'Tanggal & Waktu', 'Sistol', 'Diastol', 'Nadi', 'Suhu', 'Keluhan', 'Pemeriksaan Fisik', 'Kelaikan Kerja (FIT/UNFIT)', 'Petugas']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($out, array_values($row));
        }
        fclose($out);
        exit;
    }

    public function exportEmployee(): void {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT nip, mr_no, nama, gender, tgl_lahir, usia, status_karyawan, area, divisi, unit, mr_status FROM karyawan");
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Data_Karyawan_PGN_' . date('Ymd_His') . '.csv');
        $out = fopen('php://output', 'w');
        fputs($out, "ï»¿");
        fputcsv($out, ['NIP', 'No MR', 'Nama', 'Gender', 'Tgl Lahir', 'Usia', 'Status Karyawan', 'Area Kerja', 'Divisi', 'Unit Kerja', 'Status MR']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($out, array_values($row));
        }
        fclose($out);
        exit;
    }

    public function exportComorbid(): void {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT nip, nama, area, komorbid, obat, rujukan, kontrol, status, catatan FROM komorbid");
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=Monitoring_Komorbid_PGN_' . date('Ymd_His') . '.csv');
        $out = fopen('php://output', 'w');
        fputs($out, "ï»¿");
        fputcsv($out, ['NIP', 'Nama Karyawan', 'Area', 'Riwayat Komorbid', 'Terapi Obat', 'Rujukan Dokter Spesialis', 'Kontrol', 'Status', 'Catatan']);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            fputcsv($out, array_values($row));
        }
        fclose($out);
        exit;
    }
}
