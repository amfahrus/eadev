<?php

class mod_hubrk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('hubrk_model');
        $this->load->model('dataset_db');
        $this->load->library('search_form');
    }

    public function index() {
		$this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_hubrk", "form_hubrk_list", "cus-table", "List Hubungan RK", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "javascript:void(0);", "form_hubrk_excel", "cus-page-excel", "To Excel", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbar'] = $this->toolbar->generate();
        
        $data['ptitle'] = "List Hubungan RK";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['op_interval'] = array(
                  1 => 'Januari',
                  2 => 'Februari',
                  3 => 'Maret',
                  4 => 'April',
                  5 => 'Mei',
                  6 => 'Juni',
                  7 => 'Juli',
                  8 => 'Agustus',
                  9 => 'September',
                  10 => 'Oktober',
                  11 => 'November',
                  12 => 'Desember',
                );
		$data['op_yearperiode'] = $this->dataset_db->getPeriodeYear();
        $tabs['mod_hubrk'] = $this->dataset_db->getModule('mod_hubrk');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_hubrk']['link'];
        $data['content'] = $this->load->view('hubrk_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function hubrk_json($tipe = '') {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$unitkerja = $userconfig["kolom1"];
        $konsolidasi = isset($_GET['konsolidasi']) ? $_GET['konsolidasi'] : '0';
        $periode_konsolidasi = isset($_GET['periode_konsolidasi']) ? $_GET['periode_konsolidasi'] : '0';
        $interval = isset($_GET['interval']) ? $_GET['interval'] : '0';
        if($konsolidasi > 0){
			$query = $this->hubrk_model->getAll($konsolidasi,$periode_konsolidasi,$interval);
		} 
        $count = $this->hubrk_model->countAll();
		
		//echo "<pre>";
		//die(print_r($query));
		//echo "</pre>";
        $i = 0;
        foreach ($query as $row) {
				$responce['rows'][$i]['id'] = $row['id'];
				$responce['rows'][$i]['cell'] = array($row['uraian'], $row['rk'], $row['kp']);
			$i++;
        }
        echo json_encode($responce);
    }
	
    public function to_excel() {
		$this->load->library('export_excel');
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$unitkerja = $userconfig["kolom1"];
        $konsolidasi = $this->input->post('konsolidasi');
        $periode_konsolidasi = $this->input->post('periode_konsolidasi');
        $interval = $this->input->post('interval');
		if($konsolidasi > 0){
			$database = $this->hubrk_model->getForExcel($konsolidasi,$periode_konsolidasi,$interval);
			$namaproyek = $this->hubrk_model->getUnitName($konsolidasi);
			switch ($interval) {
				case 1:
					$namaperiod = "Triwulan - ".$periode_konsolidasi;
					break;
				case 2:
					$namaperiod = "Semester - ".$periode_konsolidasi;
					break;
				case 3:
					$namaperiod = "9 Bulan - ".$periode_konsolidasi;
					break;
				case 4:
					$namaperiod = "Tahun - ".$periode_konsolidasi;
					break;
			}
		} 
		
		if (count($database) > 0) {
			$result = $database;
			$last_line = count($database) + 6;
		}
		
		$title = array(
            array('PT Brantas Abipraya',''),
            array($namaproyek,''),
            array('List Hubungan RK',''),
            array('Periode '.$namaperiod,'')
        );
        
        $header = array(
            array('Uraian', 'Hubungan RK', 'Kantor Pusat'),
            array('', '', '')
        );
        
        $styleArray = array(
            'title' => array(
                'Alignment' => array(
					'Horizontal' => 'Left',
					'Vertical' => 'Center'
                ),
				'Font'	=> array(
					'Bold'	=>	'1',
					'Size'	=>	'14'
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
			)
        );
		
		$excel = new Export_Excel();
		$excel->filename = "Neraca.xls";

		$excel->setStyle($styleArray)->initialize();
		$excel->merge('A1:C1');
		$excel->merge('A2:C2');
		$excel->merge('A3:C3');
		$excel->merge('A4:C4');
		$excel->merge('A5:A6');
		$excel->merge('B5:B6');
		$excel->merge('C5:C6');
		$excel->col('A')->width('250');
		$excel->col('B')->width('100');
		$excel->col('C')->width('100');
		$excel->titleSheet('List Hubungan RK')->startSheet();
		$excel->applyStyle('title')->addRow($title);
		$excel->applyStyle('header')->addRow($header);
		$excel->applyStyle('money')->applyTo('B7:C' .$last_line);
		$excel->applyStyle('data')->addRow($database);
		$excel->freeze('A6')->endSheet();
		
		$excel->finalize();
		
        exit;
    }
    
    public function getDataKonsolidasi() {
		$temp_result = array();
		$res = "<select name=\"konsolidasi\" class=\"span8\">";
		$res .= "<option value=\"0\">Pilih Konsolidasi</option>";
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
		$dataset = $this->dataset_db->getSubUnitKonsolidasi();
		$i = 0;
		foreach ($dataset as $key => $value) {
			$res .= "<option value=\"".$key."\">Konsolidasi ".$value."</option>";
			$i++;
		}
		$res .= "</select>";
        echo $res;
    }
}
