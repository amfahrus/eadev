<?php

class rpt_bukubesar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            if (IS_AJAX) {
                header('HTTP/1.1 401 Unauthorized');
                exit;
            } else {
                $this->session->set_userdata('redir', current_url());
                redirect('mod_user/user_auth');
            }
        }
        $this->myauth->has_role();
        $this->load->model('dataset_db');
        $this->load->model('bukubesar_model');
        $this->load->library('search_form');
    }

    public function index() {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
        $proyek = $userconfig["kolom2"];
        $data['ptitle'] = "Buku Besar";
        $data['getperiod'] = $this->bukubesar_model->getPeriod($proyek);
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['rpt_bukubesar'] = $this->dataset_db->getModule('rpt_bukubesar');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['rpt_bukubesar']['link'];
        $data['content'] = $this->load->view('bukubesar_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function getjsonperiod() {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
        $proyek = $userconfig["kolom2"];
        $period = $this->bukubesar_model->getperiod($proyek);
        echo json_encode($period);
    }

    public function getDataProyek() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $proyek = $this->dataset_db->getDataProyek($id);
            foreach ($proyek as $key => $value) {
                echo "<option value=\"" . $key . "\">" . $value . "</option>";
            }
        }
    }

    public function jsonBukuBesar() {
        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 50;
        $sidx = !empty($sidx) ? $sidx : "ledger_period_id";
        $sord = !empty($sord) ? $sord : "asc";

        if (strtolower($search) == "true") {
            $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
            $periode_awal = isset($_GET['form_report_bukubesar_periode_awal']) ? $_GET['form_report_bukubesar_periode_awal'] : '';
            $periode_akhir = isset($_GET['form_report_bukubesar_periode_akhir']) ? $_GET['form_report_bukubesar_periode_akhir'] : '';
            $coa_awal = isset($_GET['form_report_bukubesar_coa_awal']) ? $_GET['form_report_bukubesar_coa_awal'] : '11100';
            $coa_akhir = isset($_GET['form_report_bukubesar_coa_akhir']) ? $_GET['form_report_bukubesar_coa_akhir'] : '11100';

            $cari = array();
            $cari["subunit_proyek"] = $userconfig["kolom2"];
            $cari["periode_awal"] = $periode_awal;
            $cari["periode_akhir"] = $periode_akhir;
            $cari["coa_awal"] = $coa_awal;
            $cari["coa_akhir"] = $coa_akhir;
        } else {
            $cari = "";
        }

        $offset = ($page * $limit) - $limit;
        $offset = ($offset < 0) ? 0 : $offset;

        $data = $this->bukubesar_model->getBukuBesar($limit, $offset, $sidx, $sord, $cari, $search);

//        $temp_result = $this->bukubesar_model->arraybukubesar();
//        $count = count($temp_result);

        if ($data["count"] > 0) {
            $total_pages = ceil($data["count"] / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;

        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $data["count"];

        $i = 0;
        foreach ($data["rec"] as $row) {
            $responce['rows'][$i]['id'] = $i;
            $responce['rows'][$i]['cell'] = array(
                $row['gledger_nomor'],
                $row['gledger_tanggal'],
                $row['gledger_no_bukti'],
                $row['gledger_rekanan'],
                $row['gledger_uraian'],
                $row['gledger_coa'],
                $row['gledger_debet'],
                $row['gledger_kredit']
//                ($row['gledger_debet'] != "") ? myFormatMoney((int) $row['gledger_debet']) : "",
//                ($row['gledger_kredit'] != "") ? myFormatMoney((int) $row['gledger_kredit']) : ""
            );
            $i++;
        }
        echo json_encode($responce);
    }

    public function excel_bukubesar() {
        $this->load->library('excel');
        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';

        if (strtolower($search) == "true") {
            $bulan_awal = $this->input->post('bulan_awal');
            $bulan_akhir = $this->input->post('bulan_akhir');
            $kodeperkir_akhir = $this->input->post('kodeperkir_akhir');
            $kodeperkir_awal = $this->input->post('kodeperkir_awal');
            $subunit_proyek = $this->input->post('subunit_proyek');
            $tahun = $this->input->post('tahun');
            $unitkerja = $this->input->post('unitkerja');

            $cari = array();
            $cari["bulan_awal"] = $bulan_awal;
            $cari["bulan_akhir"] = $bulan_akhir;
            $cari["kodeperkir_awal"] = $kodeperkir_awal;
            $cari["kodeperkir_akhir"] = $kodeperkir_akhir;
            $cari["subunit_proyek"] = $subunit_proyek;
            $cari["tahun"] = $tahun;
            $cari["unitkerja"] = $unitkerja;
        } else {
            $cari = "";
        }

        $this->bukubesar_model->getBukuBesar($cari, $search);
        $temp_result = $this->bukubesar_model->arrayToexcel();


        $header = array(
            array('No', 'Tanggal', 'No Bukti', 'Rekanan', 'Uraian', 'Lawan', 'Nilai', ''),
            array('', '', '', '', '', '', 'Debet', 'Kredit'),
        );
        $styleHeader = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            ),
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'E1E0F7'),
            ),
            'font' => array(
                'bold' => true,
            )
        );
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '00000000'),
                ),
            )
        );

        $this->excel->getProperties()->setTitle("Buku Besar");
        $this->excel->getProperties()->setCreator("PT.Brantas Abipraya");
        $this->excel->getProperties()->setSubject("Buku Besar");
        $this->excel->getProperties()->setDescription("Laporan Buku Besar");
        $this->excel->getProperties()->setKeywords("Buku Besar");
        $this->excel->getProperties()->setCategory("Laporan");

        $x = 0;
        foreach ($temp_result as $key => $value) {
            $last_line = count($value["data"]) + 5;
            $this->excel->createSheet();
            $this->excel->setActiveSheetIndex($x);
            $this->excel->getActiveSheet()->setTitle((string) $key);
            $this->excel->getActiveSheet()->getStyle('B5:I6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B5:I6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B5:I6')->applyFromArray($styleHeader);

            $this->excel->getActiveSheet()->getStyle('B7:I' . $last_line)->applyFromArray($styleArray);
            $this->excel->getActiveSheet()->getStyle('H7:I' . $last_line)->getNumberFormat()->setFormatCode("[Black]#,##0.00;[Red](#,##0.00)");
            //$this->excel->getActiveSheet()->getStyle('H7:I' . $last_line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

            $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
            $this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
            $this->excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
            $this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
            $this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
            $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(5);
            $this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
            $this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
            $this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $this->excel->getActiveSheet()->mergeCells('B5:B6');
            $this->excel->getActiveSheet()->mergeCells('C5:C6');
            $this->excel->getActiveSheet()->mergeCells('D5:D6');
            $this->excel->getActiveSheet()->mergeCells('E5:E6');
            $this->excel->getActiveSheet()->mergeCells('F5:F6');
            $this->excel->getActiveSheet()->mergeCells('G5:G6');
            $this->excel->getActiveSheet()->mergeCells('H5:I5');
            $this->excel->getActiveSheet()->freezePane('A7');

            $this->excel->getActiveSheet()->setCellValue('B2', 'Buku Besar ' . $value["nama_proyek"]);
            $this->excel->getActiveSheet()->mergeCells('B2:I2');
            $this->excel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $this->excel->getActiveSheet()->setCellValue('B3', $value["tahun"]);
            $this->excel->getActiveSheet()->mergeCells('B3:I3');
            $this->excel->getActiveSheet()->getStyle('B3')->getFont()->setSize(20);
            $this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $this->excel->getActiveSheet()->setCellValue('B4', $value["kode_perkiraan"]);
            $this->excel->getActiveSheet()->mergeCells('B4:I4');
            $this->excel->getActiveSheet()->getStyle('B4')->getFont()->setSize(18);
            $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            $this->excel->getActiveSheet()->getStyle('B4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

            $this->excel->getActiveSheet()->fromArray($header, null, 'B5');
            $this->excel->getActiveSheet()->fromArray($value["data"], null, 'B7');
            $x++;
        }
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="buku_besar"');
        $objWriter->save("php://output");
        exit;


//        echo "<pre>";
//        print_r($temp_result);
//        echo "<pre>";
    }

    public function genRandomString($length = 100) {
        $characters = "0123456789abcdefghijklmnopqrstuvwxyz _";
        $string = "";
        for ($p = 0; $p < $length; $p++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        return $string;
    }

    public function test() {
        $datestring = "Year: %Y Month: %m Day: %d - %h:%i %a";
        $time = time();

        echo mdate($datestring, $time);
    }

}
