<?php
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/models/EmployeeModel.php';

class EmployeeController extends Controller {
    public function index(): void {
        $model = new EmployeeModel();
        $page = (int)($_GET['page'] ?? 1);
        $search = trim($_GET['search'] ?? '');
        $area = trim($_GET['area'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $mrStatus = trim($_GET['mr_status'] ?? '');

        $result = $model->getList($page, 15, $search, $area, $status, $mrStatus);

        $this->view('employee/index', [
            'title' => 'Employee Profile (Data Karyawan)',
            'employees' => $result['data'],
            'pagination' => $result,
            'search' => $search,
            'area' => $area,
            'status' => $status,
            'mrStatus' => $mrStatus
        ]);
    }
}
