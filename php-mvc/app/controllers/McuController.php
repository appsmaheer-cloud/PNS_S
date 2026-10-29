<?php
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/models/McuModel.php';

class McuController extends Controller {
    public function index(): void {
        $model = new McuModel();
        $page = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $area = trim($_GET['area'] ?? '');
        $kelaikan = trim($_GET['kelaikan'] ?? '');
        $tindak = trim($_GET['tindak'] ?? '');

        $result = $model->getList($page, 15, $search, $area, $kelaikan, $tindak);
        $stats = $model->getStats();

        $this->view('mcu/index', [
            'title' => 'Medical Check Up (MCU Workspace)',
            'mcuList' => $result['data'],
            'pagination' => $result,
            'stats' => $stats,
            'search' => $search,
            'area' => $area,
            'kelaikan' => $kelaikan,
            'tindak' => $tindak
        ]);
    }
}
