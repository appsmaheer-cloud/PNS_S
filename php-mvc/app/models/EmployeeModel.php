<?php
require_once dirname(__DIR__) . '/core/Model.php';

class EmployeeModel extends Model {
    public function getList(int $page = 1, int $limit = 15, string $search = '', string $area = '', string $status = '', string $mrStatus = ''): array {
        $offset = ($page - 1) * $limit;
        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(nip LIKE ? OR nama LIKE ? OR divisi LIKE ? OR area LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if (!empty($area)) {
            $where[] = "area LIKE ?";
            $params[] = "%{$area}%";
        }
        if (!empty($status)) {
            $where[] = "status_karyawan = ?";
            $params[] = $status;
        }
        if (!empty($mrStatus)) {
            $where[] = "mr_status = ?";
            $params[] = $mrStatus;
        }

        $whereClause = implode(" AND ", $where);
        
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM karyawan WHERE {$whereClause}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM karyawan WHERE {$whereClause} ORDER BY nip ASC LIMIT ? OFFSET ?");
        $stmt->execute(array_merge($params, [$limit, $offset]));
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
        $stmt = $this->db->prepare("SELECT * FROM karyawan WHERE nip = ? LIMIT 1");
        $stmt->execute([$nip]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function save(array $data): bool {
        $sql = "INSERT INTO karyawan (nip, mr_no, nama, gender, tgl_lahir, usia, status_karyawan, area, divisi, unit, mr_status)
                VALUES (:nip, :mr_no, :nama, :gender, :tgl_lahir, :usia, :status_karyawan, :area, :divisi, :unit, :mr_status)
                ON CONFLICT(nip) DO UPDATE SET
                mr_no = excluded.mr_no, nama = excluded.nama, gender = excluded.gender,
                status_karyawan = excluded.status_karyawan, area = excluded.area,
                divisi = excluded.divisi, unit = excluded.unit, mr_status = excluded.mr_status";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nip' => $data['nip'],
            ':mr_no' => $data['mr_no'] ?? ('MR-' . rand(1000, 9999)),
            ':nama' => $data['nama'],
            ':gender' => $data['gender'] ?? 'Laki-laki',
            ':tgl_lahir' => $data['tgl_lahir'] ?? '',
            ':usia' => $data['usia'] ?? 30,
            ':status_karyawan' => $data['status_karyawan'] ?? 'Organik',
            ':area' => $data['area'] ?? 'Jakarta',
            ':divisi' => $data['divisi'] ?? 'Operations',
            ':unit' => $data['unit'] ?? 'Field Ops',
            ':mr_status' => $data['mr_status'] ?? 'Active'
        ]);
    }

    public function delete(string $nip): bool {
        $stmt = $this->db->prepare("DELETE FROM karyawan WHERE nip = ?");
        return $stmt->execute([$nip]);
    }
}
