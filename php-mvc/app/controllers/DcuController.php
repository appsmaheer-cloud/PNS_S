<?php
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/models/DcuModel.php';

class DcuController extends Controller {
    public function index(): void {
        $model = new DcuModel();
        $page = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $area = trim($_GET['area'] ?? '');
        $kelaikan = trim($_GET['kelaikan'] ?? '');
        $shift = trim($_GET['shift'] ?? '');

        $result = $model->getList($page, 15, $search, $area, $kelaikan, $shift);
        $stats = $model->getStats();

        $this->view('dcu/index', [
            'title' => 'Daily Examination (DCU)',
            'dcuList' => $result['data'],
            'pagination' => $result,
            'stats' => $stats,
            'search' => $search,
            'area' => $area,
            'kelaikan' => $kelaikan,
            'shift' => $shift
        ]);
    }
}
