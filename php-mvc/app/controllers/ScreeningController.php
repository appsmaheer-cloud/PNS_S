<?php
require_once dirname(__DIR__) . '/core/Controller.php';
require_once dirname(__DIR__) . '/models/McuModel.php';

class ScreeningController extends Controller {
    public function index(): void {
        $db = Database::getInstance();
        
        // Compute 15 rules stats
        $rules = [
            ['id' => 'obesitas', 'name' => 'Obesitas', 'desc' => 'BMI >= 30 kg/m²', 'query' => 'SELECT COUNT(*) FROM mcu WHERE bmi >= 30'],
            ['id' => 'hipertensi', 'name' => 'Hipertensi', 'desc' => 'Sistol > 140 atau Diastol > 90 mmHg', 'query' => 'SELECT COUNT(*) FROM mcu WHERE sistol > 140 OR diastol > 90'],
            ['id' => 'kolesterol', 'name' => 'Hiperkolesterol', 'desc' => 'Kolesterol Total > 200 mg/dL', 'query' => 'SELECT COUNT(*) FROM mcu WHERE chol > 200'],
            ['id' => 'asamurat', 'name' => 'Hiperurisemia', 'desc' => 'Asam Urat > 6.0 mg/dL', 'query' => 'SELECT COUNT(*) FROM mcu WHERE asam_urat > 6.0'],
            ['id' => 'pjk', 'name' => 'Penyakit Jantung Koroner', 'desc' => 'Treadmill selain Negatif Iskemik', 'query' => "SELECT COUNT(*) FROM mcu WHERE LOWER(treadmill) NOT LIKE '%negatif%' AND LOWER(treadmill) NOT LIKE '%normal%' AND LENGTH(treadmill) > 3"],
            ['id' => 'tbc', 'name' => 'Tuberculosis (TBC)', 'desc' => 'Rontgen Thorax suspek TBC Paru', 'query' => "SELECT COUNT(*) FROM mcu WHERE LOWER(rontgen) LIKE '%tbc%'"],
            ['id' => 'ekg', 'name' => 'Abnormal EKG', 'desc' => 'EKG selain Normal', 'query' => "SELECT COUNT(*) FROM mcu WHERE LOWER(ekg) NOT LIKE '%normal%' AND LENGTH(ekg) > 3"],
            ['id' => 'gdp', 'name' => 'Hiperglikemia', 'desc' => 'Gula Darah Puasa > 200 mg/dL', 'query' => 'SELECT COUNT(*) FROM mcu WHERE gdp > 200'],
            ['id' => 'skj', 'name' => 'Abnormal SKJ', 'desc' => 'Skor Kardiovaskular: High Risk', 'query' => "SELECT COUNT(*) FROM mcu WHERE LOWER(skj) LIKE '%high%'"],
            ['id' => 'isk', 'name' => 'Infeksi Saluran Kemih', 'desc' => 'Urinalisa Bakteri Positif', 'query' => "SELECT COUNT(*) FROM mcu WHERE LOWER(urinalisa) LIKE '%positif%' OR LOWER(urinalisa) LIKE '%bakteri%'"],
            ['id' => 'ginjal', 'name' => 'Abnormal Fungsi Ginjal', 'desc' => 'Ureum > 24 mg/dL', 'query' => 'SELECT COUNT(*) FROM mcu WHERE ureum > 24'],
            ['id' => 'hati', 'name' => 'Abnormal Fungsi Hati', 'desc' => 'SGOT > 40 U/L', 'query' => 'SELECT COUNT(*) FROM mcu WHERE sgot > 40'],
            ['id' => 'anemia', 'name' => 'Anemia', 'desc' => 'Hemoglobin (Hb) < 12.0 g/dL', 'query' => 'SELECT COUNT(*) FROM mcu WHERE hb > 0 AND hb < 12'],
            ['id' => 'audiometri', 'name' => 'Abnormal Audiometri', 'desc' => 'Audiometri selain Normal', 'query' => "SELECT COUNT(*) FROM mcu WHERE LOWER(audiometri) NOT LIKE '%normal%' AND LENGTH(audiometri) > 3"],
            ['id' => 'spirometri', 'name' => 'Abnormal Spirometri', 'desc' => 'Spirometri selain Normal', 'query' => "SELECT COUNT(*) FROM mcu WHERE LOWER(spirometri) NOT LIKE '%normal%' AND LENGTH(spirometri) > 3"],
        ];

        foreach ($rules as &$r) {
            $r['count'] = (int) $db->query($r['query'])->fetchColumn();
        }

        // Active filter rule
        $filter = $_GET['filter'] ?? '';
        $screenedQuery = "SELECT * FROM mcu WHERE (bmi >= 30 OR sistol > 140 OR diastol > 90 OR chol > 200 OR asam_urat > 6 OR gdp > 200 OR LOWER(rontgen) LIKE '%tbc%' OR sgot > 40 OR (hb > 0 AND hb < 12))";
        if ($filter) {
            foreach ($rules as $r) {
                if ($r['id'] === $filter) {
                    $screenedQuery = str_replace('SELECT COUNT(*)', 'SELECT *', $r['query']);
                    break;
                }
            }
        }
        $screenedQuery .= " LIMIT 50";
        $screenedList = $db->query($screenedQuery)->fetchAll();

        $this->view('screening/index', [
            'title' => 'Power BI Health Screening (15 Rules)',
            'rules' => $rules,
            'activeFilter' => $filter,
            'screenedList' => $screenedList
        ]);
    }
}
