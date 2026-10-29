<?php
require_once dirname(__DIR__) . '/core/Model.php';

class McuModel extends Model {
    public function getList(int $page = 1, int $limit = 15, string $search = '', string $area = '', string $kelaikan = '', string $tindak = ''): array {
        $offset = ($page - 1) * $limit;
        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(nip LIKE ? OR nama LIKE ? OR area LIKE ? OR temuan_abnormal LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if (!empty($area)) {
            $where[] = "area LIKE ?";
            $params[] = "%{$area}%";
        }
        if (!empty($kelaikan)) {
            $where[] = "UPPER(kelaikan_kerja) = ?";
            $params[] = strtoupper($kelaikan);
        }
        if (!empty($tindak)) {
            $where[] = "LOWER(status_tindak_lanjut) = ?";
            $params[] = strtolower($tindak);
        }

        $whereClause = implode(" AND ", $where);
        
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM mcu WHERE {$whereClause}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM mcu WHERE {$whereClause} ORDER BY id ASC LIMIT ? OFFSET ?");
        $allParams = array_merge($params, [$limit, $offset]);
        $stmt->execute($allParams);
        $rows = $stmt->fetchAll();

        return [
            'data' => $rows,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($total / $limit)
        ];
    }

    public function getByNip(string $nip): ?array {
        $stmt = $this->db->prepare("SELECT * FROM mcu WHERE nip = ? LIMIT 1");
        $stmt->execute([$nip]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM mcu WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function save(array $data): bool {
        $existing = !empty($data['nip']) ? $this->getByNip($data['nip']) : null;
        if ($existing) {
            $sql = "UPDATE mcu SET mr_no = :mr_no, nama = :nama, area = :area, status_karyawan = :status_karyawan,
                    tgl_mcu = :tgl_mcu, sistol = :sistol, diastol = :diastol, nadi = :nadi, suhu = :suhu, bb = :bb,
                    tb = :tb, bmi = :bmi, pem_fisik = :pem_fisik, gdp = :gdp, chol = :chol, asam_urat = :asam_urat,
                    hb = :hb, ureum = :ureum, sgot = :sgot, rontgen = :rontgen, ekg = :ekg, treadmill = :treadmill,
                    skj = :skj, srq = :srq, temuan_abnormal = :temuan_abnormal, kelaikan_kerja = :kelaikan_kerja,
                    derajat_sebelum = :derajat_sebelum, derajat_sesudah = :derajat_sesudah, tindak_lanjut = :tindak_lanjut,
                    status_tindak_lanjut = :status_tindak_lanjut, saran = :saran WHERE nip = :nip";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':mr_no' => $data['mr_no'] ?? $existing['mr_no'],
                ':nama' => $data['nama'],
                ':area' => $data['area'] ?? $existing['area'],
                ':status_karyawan' => $data['status_karyawan'] ?? 'Organik',
                ':tgl_mcu' => $data['tgl_mcu'] ?? date('d/m/Y'),
                ':sistol' => $data['sistol'] ?? 120,
                ':diastol' => $data['diastol'] ?? 80,
                ':nadi' => $data['nadi'] ?? 78,
                ':suhu' => $data['suhu'] ?? 36.5,
                ':bb' => $data['bb'] ?? 70,
                ':tb' => $data['tb'] ?? 170,
                ':bmi' => $data['bmi'] ?? 24.2,
                ':pem_fisik' => $data['pem_fisik'] ?? '',
                ':gdp' => $data['gdp'] ?? 95,
                ':chol' => $data['chol'] ?? 180,
                ':asam_urat' => $data['asam_urat'] ?? 5.5,
                ':hb' => $data['hb'] ?? 14.5,
                ':ureum' => $data['ureum'] ?? 20,
                ':sgot' => $data['sgot'] ?? 24,
                ':rontgen' => $data['rontgen'] ?? '',
                ':ekg' => $data['ekg'] ?? '',
                ':treadmill' => $data['treadmill'] ?? '',
                ':skj' => $data['skj'] ?? 'Low Risk',
                ':srq' => $data['srq'] ?? 'Dalam Batas Normal',
                ':temuan_abnormal' => $data['temuan_abnormal'] ?? '',
                ':kelaikan_kerja' => $data['kelaikan_kerja'] ?? 'FIT',
                ':derajat_sebelum' => $data['derajat_sebelum'] ?? 'P2',
                ':derajat_sesudah' => $data['derajat_sesudah'] ?? 'P1',
                ':tindak_lanjut' => $data['tindak_lanjut'] ?? 'Resep',
                ':status_tindak_lanjut' => $data['status_tindak_lanjut'] ?? 'Selesai',
                ':saran' => $data['saran'] ?? '',
                ':nip' => $data['nip']
            ]);
        } else {
            $sql = "INSERT INTO mcu (nip, mr_no, nama, area, status_karyawan, tgl_mcu, sistol, diastol, nadi, suhu, bb, tb, bmi,
                    pem_fisik, gdp, chol, asam_urat, hb, ureum, sgot, rontgen, ekg, treadmill, skj, srq, temuan_abnormal,
                    kelaikan_kerja, derajat_sebelum, derajat_sesudah, tindak_lanjut, status_tindak_lanjut, saran, dokter)
                    VALUES (:nip, :mr_no, :nama, :area, :status_karyawan, :tgl_mcu, :sistol, :diastol, :nadi, :suhu, :bb, :tb, :bmi,
                    :pem_fisik, :gdp, :chol, :asam_urat, :hb, :ureum, :sgot, :rontgen, :ekg, :treadmill, :skj, :srq, :temuan_abnormal,
                    :kelaikan_kerja, :derajat_sebelum, :derajat_sesudah, :tindak_lanjut, :status_tindak_lanjut, :saran, 'dr. Hessty, Sp.OK')";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nip' => $data['nip'],
                ':mr_no' => $data['mr_no'] ?? ('MR-' . rand(1000, 9999)),
                ':nama' => $data['nama'],
                ':area' => $data['area'] ?? 'Jakarta',
                ':status_karyawan' => $data['status_karyawan'] ?? 'Organik',
                ':tgl_mcu' => $data['tgl_mcu'] ?? date('d/m/Y'),
                ':sistol' => $data['sistol'] ?? 120,
                ':diastol' => $data['diastol'] ?? 80,
                ':nadi' => $data['nadi'] ?? 78,
                ':suhu' => $data['suhu'] ?? 36.5,
                ':bb' => $data['bb'] ?? 70,
                ':tb' => $data['tb'] ?? 170,
                ':bmi' => $data['bmi'] ?? 24.2,
                ':pem_fisik' => $data['pem_fisik'] ?? '',
                ':gdp' => $data['gdp'] ?? 95,
                ':chol' => $data['chol'] ?? 180,
                ':asam_urat' => $data['asam_urat'] ?? 5.5,
                ':hb' => $data['hb'] ?? 14.5,
                ':ureum' => $data['ureum'] ?? 20,
                ':sgot' => $data['sgot'] ?? 24,
                ':rontgen' => $data['rontgen'] ?? '',
                ':ekg' => $data['ekg'] ?? '',
                ':treadmill' => $data['treadmill'] ?? '',
                ':skj' => $data['skj'] ?? 'Low Risk',
                ':srq' => $data['srq'] ?? 'Dalam Batas Normal',
                ':temuan_abnormal' => $data['temuan_abnormal'] ?? '',
                ':kelaikan_kerja' => $data['kelaikan_kerja'] ?? 'FIT',
                ':derajat_sebelum' => $data['derajat_sebelum'] ?? 'P2',
                ':derajat_sesudah' => $data['derajat_sesudah'] ?? 'P1',
                ':tindak_lanjut' => $data['tindak_lanjut'] ?? 'Resep',
                ':status_tindak_lanjut' => $data['status_tindak_lanjut'] ?? 'Selesai',
                ':saran' => $data['saran'] ?? ''
            ]);
        }
    }

    public function delete(string $nip): bool {
        $stmt = $this->db->prepare("DELETE FROM mcu WHERE nip = ?");
        return $stmt->execute([$nip]);
    }

    public function getStats(): array {
        $total = (int) $this->db->query("SELECT COUNT(*) FROM mcu")->fetchColumn();
        $fit = (int) $this->db->query("SELECT COUNT(*) FROM mcu WHERE UPPER(kelaikan_kerja) = 'FIT'")->fetchColumn();
        $fitnote = (int) $this->db->query("SELECT COUNT(*) FROM mcu WHERE UPPER(kelaikan_kerja) LIKE '%CATATAN%'")->fetchColumn();
        $unfit = (int) $this->db->query("SELECT COUNT(*) FROM mcu WHERE UPPER(kelaikan_kerja) = 'UNFIT'")->fetchColumn();
        return compact('total', 'fit', 'fitnote', 'unfit');
    }
}
