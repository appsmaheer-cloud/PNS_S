<?php
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/models/McuModel.php';
require_once dirname(__DIR__) . '/models/DcuModel.php';
require_once dirname(__DIR__) . '/models/EmployeeModel.php';
require_once dirname(__DIR__) . '/models/ComorbidModel.php';

class ApiController extends Controller {
    /**
     * Endpoint EMR gabungan untuk satu NIP
     */
    public function emr(): void {
        $nip = trim($_GET['nip'] ?? '');
        if (empty($nip)) {
            $this->json(['success' => false, 'message' => 'NIP wajib diisi'], 400);
        }

        $empModel = new EmployeeModel();
        $mcuModel = new McuModel();
        $dcuModel = new DcuModel();
        $comModel = new ComorbidModel();

        $employee = $empModel->getByNip($nip);
        $mcu = $mcuModel->getByNip($nip);
        $dcuList = $dcuModel->getByNip($nip);
        $comorbidList = $comModel->getByNip($nip);

        if (!$employee && !$mcu) {
            $this->json(['success' => false, 'message' => 'Data rekam medis tidak ditemukan untuk NIP ini.'], 404);
        }

        $this->json([
            'success' => true,
            'data' => [
                'employee' => $employee ?: [
                    'nip' => $nip,
                    'nama' => $mcu['nama'] ?? '-',
                    'area' => $mcu['area'] ?? '-',
                    'divisi' => $mcu['divisi'] ?? '-',
                    'jabatan' => 'Staff',
                    'status' => 'Organik',
                    'gender' => 'Laki-laki',
                    'usia' => 38,
                    'status_mr' => 'Lengkap'
                ],
                'mcu' => $mcu,
                'dcu' => $dcuList,
                'comorbid' => $comorbidList
            ]
        ]);
    }

    /**
     * Endpoint Statistik Dashboard
     */
    public function stats(): void {
        $mcuModel = new McuModel();
        $dcuModel = new DcuModel();
        $comModel = new ComorbidModel();

        $this->json([
            'success' => true,
            'data' => [
                'mcu' => $mcuModel->getStats(),
                'dcu' => $dcuModel->getStats(),
                'comorbid' => $comModel->getStats()
            ]
        ]);
    }

    /**
     * Simpan data MCU, DCU, Karyawan, atau Komorbid
     */
    public function save(): void {
        $entity = strtolower(trim($_GET['entity'] ?? 'mcu'));
        $data = $_POST;

        if (empty($data['nip']) || empty($data['nama'])) {
            $this->json(['success' => false, 'message' => 'NIP dan Nama Karyawan wajib diisi'], 400);
        }

        $ok = false;
        if ($entity === 'mcu') {
            $model = new McuModel();
            $ok = $model->save($data);
        } elseif ($entity === 'dcu') {
            $model = new DcuModel();
            $ok = $model->save($data);
        } elseif ($entity === 'employee') {
            $model = new EmployeeModel();
            $ok = $model->save($data);
        } elseif ($entity === 'comorbid') {
            $model = new ComorbidModel();
            $ok = $model->save($data);
        }

        $this->json(['success' => $ok]);
    }

    /**
     * Hapus record
     */
    public function delete(): void {
        $entity = strtolower(trim($_GET['entity'] ?? ''));
        $id = trim($_GET['id'] ?? ($_POST['id'] ?? ''));

        if (empty($id)) {
            $this->json(['success' => false, 'message' => 'ID atau NIP tidak valid'], 400);
        }

        $ok = false;
        if ($entity === 'mcu') {
            $model = new McuModel();
            $ok = $model->delete($id);
        } elseif ($entity === 'dcu') {
            $model = new DcuModel();
            $ok = $model->delete((int)$id);
        } elseif ($entity === 'employee') {
            $model = new EmployeeModel();
            $ok = $model->delete($id);
        } elseif ($entity === 'comorbid') {
            $model = new ComorbidModel();
            $ok = $model->delete((int)$id);
        }

        $this->json(['success' => $ok]);
    }
}
