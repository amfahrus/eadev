<?php

class mod_neraca extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('neraca_model');
        $this->load->model('dataset_db');
        $this->load->library('search_form');
    }

    public function index() {
		$this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_neraca", "form_neraca_list", "cus-table", "Laporan Neraca", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "javascript:void(0);", "form_neraca_excel", "cus-page-excel", "To Excel", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbar'] = $this->toolbar->generate();
        
        $data['ptitle'] = "Neraca";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['op_interval'] = array(
                  1  => 'Triwulan',
                  2    => 'Semester',
                  3   => '9 Bulan',
                  4 => 'Tahun',
                );
		$data['op_yearperiode'] = $this->dataset_db->getPeriodeYear();
        $tabs['mod_neraca'] = $this->dataset_db->getModule('mod_neraca');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_neraca']['link'];
        $data['content'] = $this->load->view('neraca_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function neraca_json($tipe = '') {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$unitkerja = $userconfig["kolom1"];
        $konsolidasi = isset($_GET['konsolidasi']) ? $_GET['konsolidasi'] : '0';
        $periode_konsolidasi = isset($_GET['periode_konsolidasi']) ? $_GET['periode_konsolidasi'] : '0';
        $interval = isset($_GET['interval']) ? $_GET['interval'] : '0';
        $periode 	= isset($_GET['periode']) ? $_GET['periode'] : '0';
		if($konsolidasi > 0){
			$query = $this->neraca_model->getKonsolidasi($konsolidasi,$periode_konsolidasi,$interval);
		} else {
			$query = $this->neraca_model->getAll($proyek,$periode);
		}
        $count = $this->neraca_model->countAll();
		
		//echo "<pre>";
		//die(print_r($query));
		//echo "</pre>";
        $i = 0;
        foreach ($query as $row) {
				$responce['rows'][$i]['id'] = $row['id'];
				$responce['rows'][$i]['cell'] = array(
													$row['id'],
													$row['uraian'], 
													$row['total'], 
													//$row['total_sd'], 
													$row['level'], 
													$row['parent'], 
													$row['isLeaf'], 
													$row['expanded'], 
													$row['loaded']
													);
			$i++;
        }
        echo json_encode($responce);
    }
    	
	public function getDetail($group = '', $periode = 0) {
		$data['group'] = $group;
		$data['periode'] = $periode;
        $data['content'] = $this->load->view('neraca_detail', $data, true);
        $this->load->vars($data);
        $this->load->view('default_picker');
    }
	
	public function detail_json($group = '', $periode = 0) {
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$unitkerja = $userconfig["kolom1"];
        $query = $this->neraca_model->getDetail($group,$periode,$unitkerja,$proyek);
        $i = 0;
        //print_r($query);
        foreach ($query as $key => $row) {
            $responce['rows'][$i]['id'] = $row['idnya'];
            $responce['rows'][$i]['cell'] = array($row['nomor'],$row['tanggal'], $row['nobukti'], $row['kode_proyek'], $row['coa'], $row['rekanan'], $row['keterangan'], $row['debit'], $row['kredit']);
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
        $periode = $this->input->post('periode');
		if($konsolidasi > 0){
			$database = $this->neraca_model->getKonsolidasiForExcel($konsolidasi,$periode_konsolidasi,$interval);
			$namaproyek = $konsolidasi == 9999 ? 'Perusahaan' : $this->neraca_model->getUnitName($konsolidasi);
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
		} else {
			$database = $this->neraca_model->getAllForExcel($proyek,$periode);
			$namaproyek = $this->neraca_model->getProyekName($proyek);
			$namaperiod = $this->neraca_model->getPeriodName($periode);
		}
		
		if (count($database) > 0) {
			$result = $database;
			$last_line = count($database) + 6;
		}
		
		$title = array(
            array('PT Brantas Abipraya',''),
            array($namaproyek,''),
            array('Neraca',''),
            array('Periode '.$namaperiod,'')
        );
        
        $header = array(
            array('Uraian', 'Nilai'),
            array('', '')
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
			)
        );
		
		$excel = new Export_Excel();
		$excel->filename = "Neraca.xls";

		$excel->setStyle($styleArray)->initialize();
		$excel->merge('A1:B1');
		$excel->merge('A2:B2');
		$excel->merge('A3:B3');
		$excel->merge('A4:B4');
		$excel->merge('A5:A6');
		$excel->merge('B5:B6');
		$excel->col('A')->width('250');
		$excel->col('B')->width('100');
		$excel->titleSheet('Neraca')->startSheet();
		$excel->applyStyle('title')->addRow($title);
		$excel->applyStyle('header')->addRow($header);
		$excel->applyStyle('money')->applyTo('B7:B' .$last_line);
		$excel->applyStyle('data')->addRow($database);
		$excel->freeze('A6')->endSheet();
		
		$excel->finalize();
		
        exit;
		/*
        $header = array(
            array('Uraian', 'Total')
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
		
        $this->excel->getProperties()->setTitle("Neraca");
        $this->excel->getProperties()->setCreator("PT.Brantas Abipraya");
        $this->excel->getProperties()->setSubject("Neraca");
        $this->excel->getProperties()->setDescription("Laporan Neraca");
        $this->excel->getProperties()->setKeywords("Neraca");
        $this->excel->getProperties()->setCategory("Laporan");
        
		$this->excel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5:B5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5:B6')->applyFromArray($styleHeader);

		$this->excel->getActiveSheet()->getStyle('A7:B' . $last_line)->applyFromArray($styleArray);
		$this->excel->getActiveSheet()->getStyle('B7:B' . $last_line)->getNumberFormat()->setFormatCode("[Black]#,##0.00;[Red](#,##0.00)");
		//$this->excel->getActiveSheet()->getStyle('H7:I' . $last_line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

		$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$this->excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(50);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$this->excel->getActiveSheet()->mergeCells('A1:B1');
		$this->excel->getActiveSheet()->mergeCells('A2:B2');
		$this->excel->getActiveSheet()->mergeCells('A3:B3');
		$this->excel->getActiveSheet()->mergeCells('A4:B4');
		$this->excel->getActiveSheet()->mergeCells('A5:A6');
		$this->excel->getActiveSheet()->mergeCells('B5:B6');
		$this->excel->getActiveSheet()->freezePane('A7');

		$this->excel->getActiveSheet()->setCellValue('A1', 'PT Brantas Abipraya');
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('A2', 'Neraca');
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(20);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->fromArray($header, null, 'A5');
		$this->excel->getActiveSheet()->fromArray($result, null, 'A7');
		
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="neraca"');
        $objWriter->save("php://output");
		*/
    }

	public function getDataProyek() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        $this->form_validation->set_rules("id_proyek", "id_proyek", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $id_proyek = $this->input->post("id_proyek");
            $proyek = $this->dataset_db->getDataProyek($id);
            echo "<option value=\"0\">Konsolidasi</option>";
            foreach ($proyek as $key => $value) {
                if($id_proyek == $key) {
                    echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                } else {
                    echo "<option value=\"" . $key . "\">" . $value . "</option>";
                }
            }
        }
    }
    
    public function getDataPeriode() {
		$temp_result = array();
		$res = "<select name=\"periode\" class=\"span8\">";
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
			$id = $this->input->post("id");
            $periode = $this->dataset_db->getPeriode($id);
            foreach ($periode as $key => $value) {
				$res .= "<option value=\"".$value['id']."\">".$value['desc']."</option>";
				/*$temp_result[] = array(
					'image' => "",
					'description' => $value['desc'],
					'value' => $value['id'],
					'text' => ""
				);*/
            }
        }
        $res .= "</select>";
        //echo json_encode($temp_result);
        echo $res;
    }
    
    public function getDataKonsolidasi() {
		$temp_result = array();
		$res = "<select name=\"konsolidasi\" onchange=\"opsikonsolidasi();\" class=\"span8\">";
		$res .= "<option value=\"0\">Non Konsolidasi</option>";
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
		$dataset = $this->dataset_db->getSubUnitKonsolidasi();
		$i = 0;
		foreach ($dataset as $key => $value) {
			$res .= "<option value=\"".$key."\">Konsolidasi ".$value."</option>";
			$i++;
		}
		$res .= "<option value=\"9999\">Konsolidasi Perusahaan</option>";
        $res .= "</select>";
        echo $res;
    }
}
