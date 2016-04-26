<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class rpt_bukubesar extends CI_Controller {

    private $_userconfig = array();

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
        $this->load->library("searchform");
        $this->_userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", "javascript:void(0);", "bukubesar_export_xls", "cus-page-white-excel", "Export Data Ke Excel", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "javascript:void(0);", "bukubesar_export_pdf", "cus-page-white-acrobat", "Export Data Ke PDF", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Periode Awal',
                'value' => 'text:Periode_Awal',
                'type' => 'custom',
                'callBack' => 'getperiod',
                'ops' => array("=")
            ),
            array(
                'text' => 'Periode Akhir',
                'value' => 'text:Periode_Akhir',
                'type' => 'custom',
                'callBack' => 'getperiod',
                'ops' => array("=")
            ),
            array(
                'text' => 'Kode Perkiraan',
                'value' => 'text:Kode_Perkiraan',
                'type' => 'custom',
                'callBack' => 'getdperkir',
                'ops' => array("=")
            )
        );

        $defaultvalue = array(
            array(
                'text' => 'Periode Awal',
                'value' => 'text:Periode_Awal',
                'type' => 'custom',
                'callBack' => 'getperiod',
                'ops' => array("=")
            ),
            array(
                'text' => 'Periode Akhir',
                'value' => 'text:Periode_Akhir',
                'type' => 'custom',
                'callBack' => 'getperiod',
                'ops' => array("=")
            ),
            array(
                'text' => 'Kode Perkiraan',
                'value' => 'text:Kode_Perkiraan',
                'type' => 'custom',
                'callBack' => 'getdperkir',
                'ops' => array("=")
            )
        );

        $data['searchform'] = $this->searchform->setMultiSearch("false")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();

        $data['ptitle'] = "Buku Besar";
        $data['getperiod'] = $this->bukubesar_model->getPeriod($this->_userconfig["kolom2"]);
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
	
	public function getjsondperkir() {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
        $proyek = $userconfig["kolom2"];
        $dperkir = $this->bukubesar_model->getdperkir($proyek);
        echo json_encode($dperkir);
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
            $cols = isset($_GET['cols']) ? $_GET['cols'] : '';
            $ops = isset($_GET['ops']) ? $_GET['ops'] : '';
            $vals = isset($_GET['vals']) ? $_GET['vals'] : '';
            
            $cari = array();
            $cari["subunit_proyek"] = $this->_userconfig["kolom2"];
            $cari["periode_awal"] = $vals[0];
            $cari["periode_akhir"] = $vals[1];
            $cari["coa"] = $vals[2];
        } else {
            $cari = "";
        }

        $offset = ($page * $limit) - $limit;
        $offset = ($offset < 0) ? 0 : $offset;

        $data = $this->bukubesar_model->getBukuBesar($limit, $offset, $sidx, $sord, $cari, $search);

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
                $row['gledger_no_dokumen'],
                $row['gledger_rekanan'],
                $row['gledger_uraian'],
                $row['gledger_coa'],
                myFormatMoney($row['gledger_debet']),
                myFormatMoney($row['gledger_kredit']),
                myFormatMoney($row['gledger_saldo'])
//                ($row['gledger_debet'] != "") ? myFormatMoney((int) $row['gledger_debet']) : "",
//                ($row['gledger_kredit'] != "") ? myFormatMoney((int) $row['gledger_kredit']) : ""
            );
            $i++;
        }
        echo json_encode($responce);
    }

    public function excel_bukubesar() {
        $data = $this->bukubesar_model->getBukuBesar(0, 100, 1, 1, 1, 1);
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

    public function to_excel() {
        $this->load->library('export_excel');
		
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $this->bukubesar_model->getProyekName($userconfig["kolom2"]);
		
        $param = array();
        $param["subunit_proyek"] = $userconfig["kolom2"];
        
		if ($this->input->post()) {
            $cols = $this->input->post('cols');
            $ops = $this->input->post('ops');
            $vals = $this->input->post('vals');
			
            $cari = array();
            for ($x = 0; $x < count($cols); $x++) {
                $cari[$x]['cols'] = $cols[$x];
                $cari[$x]['ops'] = $ops[$x];
                $cari[$x]['vals'] = $vals[$x];
            }
            
			//die(print_r($cari));
			$filter = array();
			$filter_count = 0;
			foreach ($cari as $row) {
				 if (!empty($row['cols']) AND !empty($row['ops']) AND !empty($row['vals'])) {
					$temp = ''; 
					$filter_count++; 
                    $fields = explode(":", $row['cols']);
                    switch ($fields[1]) {
                        case "Periode_Awal":
							$temp .= 'Periode Awal ';
							$temp .= $row['ops'].' ';
							$temp .= $this->bukubesar_model->getPeriodName($row['vals']);
							$param["periode_awal"] = $row['vals'];
                            break;
                        case "Periode_Akhir":
                            $temp .= 'Periode Akhir ';
							$temp .= $row['ops'].' ';
							$temp .= $this->bukubesar_model->getPeriodName($row['vals']);
							$param["periode_akhir"] = $row['vals'];
                            break;
                        case "Kode_Perkiraan":
                            $temp .= 'Kode Perkiraan';
							$temp .= $row['ops'].' ';
							$temp .= $this->bukubesar_model->getDperkirName($row['vals']);
							$param["coa"] = $row['vals'];
                            break;
                    }
                    $filter[$filter_count] = array($temp,'','');
                }
            }
		}
		
		if ($filter_count > 0) {
			$filtering = $filter_count + 3;
		}
		
        $database = $this->bukubesar_model->getBukuBesarXls($param);
		
        if (count($database) > 0) {
            //$result = $database->result_array();
            //$last_line = $database->num_rows() + $filtering + 5;
            $last_line = count($database) + $filtering + 5;
        }
		
		//die(print_r($result));
		
		$title = array(
            array('PT Brantas Abipraya','',''),
            array($proyek,'',''),
            array('Buku Besar ','','')
        );
		
        $header = array(
            array('No', 'Tanggal', 'No Bukti', 'Rekanan', 'Uraian', 'Lawan', 'Nilai', '','Saldo'),
            array('', '', '', '', '', '', 'Debit', 'Kredit', '')
        );

        $styleArray = array(
            'title' => array(
                'Alignment' => array(
					'Horizontal' => 'Center',
					'Vertical' => 'Center'
                ),
				'Font'	=> array(
					'Bold'	=>	'1',
					'Size'	=>	'14'
				)
			),
			'filter' => array(
                'Alignment' => array(
					'Horizontal' => 'Left',
					'Vertical' => 'Center',
					'WrapText' => '1'
                ),
				'Font'	=> array(
					'Size'	=>	'12'
				)
			),
			'header' => array(
				'Alignment' => array(
					'Horizontal' => 'Center',
					'Vertical' => 'Center'
                ), 	
				'Borders' => array(
					'All' => array(
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					)
				),
				'Font'	=> array(
					'Bold'	=>	'1',
					'Size'	=>	'14'
				),
				'Interior' => array(
					'Color' => '#8DB4E2',
					'Pattern' => 'Solid'
				)
			),
			'data' => array(	
				'Borders' => array(
					'Bottom' => array(
						'Position'	=> 'Bottom',
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					),
					'Left' => array(
						'Position'	=> 'Left',
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					),
					'Right' => array(
						'Position'	=> 'Right',
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					)
				)
			),
			'money' => array(
				'Alignment' => array(
					'Horizontal' => 'Right',
					'Vertical' => 'Center'
                ), 			
				'Borders' => array(
					'All' => array(
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					)
				),
				'NumberFormat' => array(
					'Format'	=>	'#,##0.00_);[Red]\(#,##0.00\)'
				)
			),
			'date' => array(	
				'Borders' => array(
					'All' => array(
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					)
				),
				'NumberFormat' => array(
					'Format'	=>	'dd/mm/yyyy;@'
				)
			)
        );

        //die(print_r($result));

        $excel = new Export_Excel();
        $excel->filename = "BukuBesar.xls";

        $excel->setStyle($styleArray)->initialize();
		$excel->merge('A1:H1');
		$excel->merge('A2:H2');
		$excel->merge('A3:H3');
		for($i=4;$i<=$filtering;$i++){
			$excel->merge('A'.$i.':H'.$i);
		}
		$a=$filtering+1;
		$b=$filtering+2;
		$excel->merge('A'.$a.':A'.$b);
		$excel->merge('B'.$a.':B'.$b);
		$excel->merge('C'.$a.':C'.$b);
		$excel->merge('D'.$a.':D'.$b);
		$excel->merge('E'.$a.':E'.$b);
		$excel->merge('F'.$a.':F'.$b);
		$excel->merge('G'.$a.':H'.$a);
		$excel->merge('I'.$a.':I'.$b);
		$excel->col('A')->width('30');
		$excel->col('B')->width('70');
		$excel->col('C')->width('90');
		$excel->col('D')->width('100');
		$excel->col('E')->width('160');
		$excel->col('F')->width('75');
		$excel->col('G')->width('75');
		$excel->col('H')->width('75');
		$excel->col('I')->width('75');
		$excel->titleSheet('Buku Besar')->startSheet();
		$excel->applyStyle('title')->addRow($title);
		$excel->applyStyle('filter')->addRow($filter);
		$excel->applyStyle('header')->addRow($header);
		$excel->applyStyle('date')->applyTo('B'.$b.':B'.$last_line);
		$excel->applyStyle('money')->applyTo('G'.$b.':I'.$last_line);
		$excel->applyStyle('data')->addRow($database);
		$excel->freeze('A'.$b)->endSheet();
		$excel->finalize();

        exit;
    }

    public function excel_test() {
        $this->load->library("excel");
        $database = $this->bukubesar_model->getBukuBesar(0, 100, 1, 1, 1, 1);
        $this->excel->filename = "test.xls";
        $this->excel->exportTo = "browser";

        /*
          $this->excel->getProperties()->setTitle("Buku Besar");
          $this->excel->getProperties()->setCreator("PT.Brantas Abipraya");
          $this->excel->getProperties()->setSubject("Buku Besar");
          $this->excel->getProperties()->setDescription("Laporan Buku Besar");
          $this->excel->getProperties()->setKeywords("Buku Besar");
          $this->excel->getProperties()->setCategory("Laporan");
         */
//        $this->excel->createSheet();
//        $this->excel->setActiveSheetIndex($x);
//        
//        $this->excel->setTitle("Buku Besar");
//        $this->excel->getColumnDimension("A")->setWidth(5);
//        $this->excel->initialize();
//        foreach ($database["rec"] as $key => $row) {
//            $row = array($row['gledger_nomor'],
//                $row['gledger_tanggal'],
//                $row['gledger_no_bukti'],
//                $row['gledger_rekanan'],
//                $row['gledger_uraian'],
//                $row['gledger_coa'],
//                $row['gledger_debet'],
//                $row['gledger_kredit'],
//            );
//            $this->excel->addRow($row);
//        }
//        $this->excel->finalize();


        for ($x = 0; $x <= 3; $x++) {
            $this->excel->createSheet();
            $this->excel->setActiveSheetIndex($x);
            $this->excel->getActiveSheet()->setTitle("KodePerkiraan" . $x);
            $this->excel->getActiveSheet()->getColumnDimension('1')->setWidth(20);
            $this->excel->getActiveSheet()->getColumnDimension('2')->setWidth(80);
            $this->excel->getActiveSheet()->getColumnDimension('3')->setWidth(100);
            $this->excel->getActiveSheet()->getColumnDimension('4')->setWidth(80);
            $this->excel->getActiveSheet()->getColumnDimension('5')->setWidth(200);
            $this->excel->getActiveSheet()->getColumnDimension('6')->setWidth(80);
            $this->excel->getActiveSheet()->getColumnDimension('7')->setWidth(80);
            $this->excel->getActiveSheet()->getColumnDimension('8')->setWidth(80);
            $this->excel->getActiveSheet()->fromArray($database["rec"]);
//            $this->excel->getActiveSheet()->getStyle("1,1:5,5");
//            $this->excel->getActiveSheet()->cek();
//            $this->excel->getActiveSheet()->cek2();
        }
        $this->excel->initialize();
        $this->excel->finalize();
//        $this->excel->printsheet();
    }

}

