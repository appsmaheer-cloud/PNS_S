<?php
require_once dirname(__DIR__) . '/core/Model.php';

class DcuModel extends Model {
    public function getList(int $page = 1, int $limit = 15, string $search = '', string $area = '', string $kelaikan = '', string $shift = ''): array {
        $offset = ($page - 1) * $limit;
        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(nip LIKE ? OR nama LIKE ? OR keluhan LIKE ? OR pem_fisik LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s]);
        }
        if (!empty($area)) {
            $where[] = "area LIKE ?";
            $params[] = "%{$area}%";
        }
        if (!empty($kelaikan)) {
            $where[] = "UPPER(kelaikan) = ?";
            $params[] = strtoupper($kelaikan);
        }
        if (!empty($shift)) {
            $where[] = "shift = ?";
            $params[] = $shift;
        }

        $whereClause = implode(" AND ", $where);
        
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM dcu WHERE {$whereClause}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM dcu WHERE {$whereClause} ORDER BY id ASC LIMIT ? OFFSET ?");
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

    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM dcu WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByNip(string $nip): array {
        $stmt = $this->db->prepare("SELECT * FROM dcu WHERE nip = ? ORDER BY id DESC");
        $stmt->execute([$nip]);
        return $stmt->fetchAll();
    }

    public function save(array $data): bool {
        if (!empty($data['id'])) {
            $sql = "UPDATE dcu SET nip = :nip, nama = :nama, area = :area, shift = :shift, sistol = :sistol,
                    diastol = :diastol, nadi = :nadi, suhu = :suhu, keluhan = :keluhan, pem_fisik = :pem_fisik,
                    kelaikan = :kelaikan, petugas = :petugas WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nip' => $data['nip'],
                ':nama' => $data['nama'],
                ':area' => $data['area'],
                ':shift' => $data['shift'],
                ':sistol' => $data['sistol'],
                ':diastol' => $data['diastol'],
                ':nadi' => $data['nadi'],
                ':suhu' => $data['suhu'],
                ':keluhan' => $data['keluhan'],
                ':pem_fisik' => $data['pem_fisik'],
                ':kelaikan' => $data['kelaikan'],
                ':petugas' => $data['petugas'],
                ':id' => $data['id']
            ]);
        } else {
            $sql = "INSERT INTO dcu (dcu_id, nip, nama, area, shift, tgl, sistol, diastol, nadi, suhu, keluhan, pem_fisik, kelaikan, petugas)
                    VALUES (:dcu_id, :nip, :nama, :area, :shift, :tgl, :sistol, :diastol, :nadi, :suhu, :keluhan, :pem_fisik, :kelaikan, :petugas)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':dcu_id' => 'DCU-' . rand(10000, 99999),
                ':nip' => $data['nip'],
                ':nama' => $data['nama'],
                ':area' => $data['area'],
                ':shift' => $data['shift'],
                ':tgl' => date('d/m/Y H:i'),
                ':sistol' => $data['sistol'],
                ':diastol' => $data['diastol'],
                ':nadi' => $data['nadi'],
                ':suhu' => $data['suhu'],
                ':keluhan' => $data['keluhan'],
                ':pem_fisik' => $data['pem_fisik'],
                ':kelaikan' => $data['kelaikan'],
                ':petugas' => $data['petugas'] ?? 'dr. Andi Prasetyo'
            ]);
        }
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM dcu WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getStats(): array {
        $total = (int) $this->db->query("SELECT COUNT(*) FROM dcu")->fetchColumn();
        $fit = (int) $this->db->query("SELECT COUNT(*) FROM dcu WHERE UPPER(kelaikan) = 'FIT'")->fetchColumn();
        $unfit = (int) $this->db->query("SELECT COUNT(*) FROM dcu WHERE UPPER(kelaikan) = 'UNFIT'")->fetchColumn();
        return compact('total', 'fit', 'unfit');
    }
}
