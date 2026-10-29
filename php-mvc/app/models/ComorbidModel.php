<?php
require_once dirname(__DIR__) . '/core/Model.php';

class ComorbidModel extends Model {
    public function getList(int $page = 1, int $limit = 15, string $search = '', string $area = '', string $type = ''): array {
        $offset = ($page - 1) * $limit;
        $where = ["1=1"];
        $params = [];

        if (!empty($search)) {
            $where[] = "(nip LIKE ? OR nama LIKE ? OR komorbid LIKE ? OR obat LIKE ? OR rujukan LIKE ?)";
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s, $s, $s]);
        }
        if (!empty($area)) {
            $where[] = "area LIKE ?";
            $params[] = "%{$area}%";
        }
        if (!empty($type)) {
            $where[] = "komorbid LIKE ?";
            $params[] = "%{$type}%";
        }

        $whereClause = implode(" AND ", $where);
        
        $countStmt = $this->db->prepare("SELECT COUNT(*) FROM komorbid WHERE {$whereClause}");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM komorbid WHERE {$whereClause} ORDER BY id ASC LIMIT ? OFFSET ?");
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
        $stmt = $this->db->prepare("SELECT * FROM komorbid WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function getByNip(string $nip): array {
        $stmt = $this->db->prepare("SELECT * FROM komorbid WHERE nip = ? ORDER BY id DESC");
        $stmt->execute([$nip]);
        return $stmt->fetchAll();
    }

    public function save(array $data): bool {
        if (!empty($data['id'])) {
            $sql = "UPDATE komorbid SET nip = :nip, nama = :nama, area = :area, komorbid = :komorbid,
                    obat = :obat, rujukan = :rujukan, kontrol = :kontrol, status = :status, catatan = :catatan
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nip' => $data['nip'],
                ':nama' => $data['nama'],
                ':area' => $data['area'],
                ':komorbid' => $data['komorbid'],
                ':obat' => $data['obat'],
                ':rujukan' => $data['rujukan'],
                ':kontrol' => $data['kontrol'],
                ':status' => $data['status'],
                ':catatan' => $data['catatan'],
                ':id' => $data['id']
            ]);
        } else {
            $sql = "INSERT INTO komorbid (nip, nama, area, komorbid, obat, rujukan, kontrol, status, catatan)
                    VALUES (:nip, :nama, :area, :komorbid, :obat, :rujukan, :kontrol, :status, :catatan)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':nip' => $data['nip'],
                ':nama' => $data['nama'],
                ':area' => $data['area'],
                ':komorbid' => $data['komorbid'],
                ':obat' => $data['obat'],
                ':rujukan' => $data['rujukan'],
                ':kontrol' => $data['kontrol'] ?? date('d/m/Y', strtotime('+30 days')),
                ':status' => $data['status'] ?? 'Dalam Evaluasi',
                ':catatan' => $data['catatan'] ?? ''
            ]);
        }
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM komorbid WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getStats(): array {
        $total = (int) $this->db->query("SELECT COUNT(*) FROM komorbid")->fetchColumn();
        $ht = (int) $this->db->query("SELECT COUNT(*) FROM komorbid WHERE komorbid LIKE '%Hipertensi%'")->fetchColumn();
        $dm = (int) $this->db->query("SELECT COUNT(*) FROM komorbid WHERE komorbid LIKE '%Diabetes%'")->fetchColumn();
        $lipid = (int) $this->db->query("SELECT COUNT(*) FROM komorbid WHERE komorbid LIKE '%Dislipidemia%'")->fetchColumn();
        $rujukan = (int) $this->db->query("SELECT COUNT(*) FROM komorbid WHERE rujukan != '-' AND length(rujukan) > 5")->fetchColumn();
        return compact('total', 'ht', 'dm', 'lipid', 'rujukan');
    }
}
