<?php
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/models/McuModel.php';
require_once dirname(__DIR__) . '/models/DcuModel.php';
require_once dirname(__DIR__) . '/models/ComorbidModel.php';

class DashboardController extends Controller {
    public function index(): void {
        $mcuModel = new McuModel();
        $dcuModel = new DcuModel();
        $comModel = new ComorbidModel();

        $mcuStats = $mcuModel->getStats();
        $dcuStats = $dcuModel->getStats();
        $comStats = $comModel->getStats();

        $this->view('dashboard/index', [
            'title' => 'Wellness Dashboard',
            'mcuStats' => $mcuStats,
            'dcuStats' => $dcuStats,
            'comStats' => $comStats
        ]);
    }
}
