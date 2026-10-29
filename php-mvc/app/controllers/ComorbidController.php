<?php
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/models/ComorbidModel.php';

class ComorbidController extends Controller {
    public function index(): void {
        $model = new ComorbidModel();
        $page = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $area = trim($_GET['area'] ?? '');
        $type = trim($_GET['type'] ?? '');

        $result = $model->getList($page, 15, $search, $area, $type);
        $stats = $model->getStats();

        $this->view('comorbid/index', [
            'title' => 'Monitoring Komorbid & Penyakit Kronis',
            'comorbidList' => $result['data'],
            'pagination' => $result,
            'stats' => $stats,
            'search' => $search,
            'area' => $area,
            'type' => $type
        ]);
    }
}
