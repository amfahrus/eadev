<?php

class mod_cashflow extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('cashflow_model');
        $this->load->model('dataset_db');
        $this->load->library('search_form');
    }

    public function index() {
		$this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_cashflow", "form_cashflow_list", "cus-table", "Laporan Cashflow", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "javascript:void(0);", "form_cashflow_excel", "cus-page-excel", "To Excel", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbar'] = $this->toolbar->generate();
        
        $data['ptitle'] = "Cashflow";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['op_yearperiode'] = $this->dataset_db->getKeyPeriodeYear();
        $tabs['mod_cashflow'] = $this->dataset_db->getModule('mod_cashflow');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_cashflow']['link'];
        $data['content'] = $this->load->view('cashflow_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function cashflow_json($tipe = '') {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$unitkerja = $userconfig["kolom1"];
        $periode 	= isset($_GET['periode_year']) ? $_GET['periode_year'] : '0';
		
        $query = $this->cashflow_model->getAll($proyek,$periode);
        $count = $this->cashflow_model->countAll();
		
		//echo "<pre>";
		//die(print_r($query));
		//echo "</pre>";
        $i = 0;
        foreach ($query as $row) {
				$responce['rows'][$i]['id'] = $row['id'];
				$responce['rows'][$i]['cell'] = array(
				$row['nomor'], 
				$row['uraian'], 
				$row['akp'], 
				$row['sd_lalu'], 
				$row['ri_1'], 
				$row['ri_2'], 
				$row['ri_3'], 
				$row['ri_4'],
				$row['ri_5'],
				$row['ri_6'],
				$row['ri_s1'],
				$row['ri_7'],
				$row['ri_8'],
				$row['ri_9'],
				$row['ri_10'],
				$row['ri_11'],
				$row['ri_12'],
				$row['ri_s2'],
				$row['ri_total'],
				$row['ri_grandtotal'],
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
	
    public function to_excel() {
		$this->load->library('export_excel');
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$unitkerja = $userconfig["kolom1"];
        $periode 	= $this->input->post('periode_year');

        $database = $this->cashflow_model->getAllForExcel($proyek,$periode);
		
		$namaproyek = $this->cashflow_model->getProyekName($proyek);
		$namaperiod = $this->cashflow_model->getPeriodName($periode);
		
		if (count($database) > 0) {
			$result = $database;
			$last_line = count($database) + 6;
		}
		
		$title = array(
            array('PT Brantas Abipraya','',''),
            array($namaproyek,'',''),
            array('Cashflow','',''),
            array('Periode '.$namaperiod,'','')
        );
        
        $header = array(
            array('NO.', 'URAIAN', 'RBP/AKP', 'Realisasi s/d Des Tahun Lalu', 'REALISASI TAHUN', '', '', '', '', '', '', '', '', '', '', '', '', '', 'TOTAL TAHUN', 'Total Awal Proyek s/d Tahun'),
            array('', '', '', '', 'Semester I', '', '', '', '', '', '', 'Semester II', '', '', '', '', '', '', '', ''),
            array('', '', '', '', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Total', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Total', '', '')
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
					'Format'	=>	'_(* #,##0.00_);_(* \(#,##0.00\);_(* &quot;-&quot;??_);_@_)'
				)
			)
        );
		
		$excel = new Export_Excel();
		$excel->filename = "Cashflow.xls";

		$excel->setStyle($styleArray)->initialize();
		$excel->merge('A1:B1');
		$excel->merge('A2:B2');
		$excel->merge('A3:B3');
		$excel->merge('A4:B4');
		$excel->merge('A5:A7');
		$excel->merge('B5:B7');
		$excel->merge('C5:C7');
		$excel->merge('D5:D7');
		$excel->merge('E5:R5');
		$excel->merge('F5:F6');
		$excel->merge('S5:S7');
		$excel->merge('T5:T7');
		$excel->merge('E6:K6');
		$excel->merge('L6:R6');
		$excel->merge('K5:K6');
		$excel->col('A')->width('50');
		$excel->col('B')->width('250');
		$excel->col('C')->width('100');
		$excel->col('D')->width('100');
		$excel->col('E')->width('100');
		$excel->col('F')->width('100');
		$excel->col('G')->width('100');
		$excel->col('H')->width('100');
		$excel->col('I')->width('100');
		$excel->col('J')->width('100');
		$excel->col('K')->width('100');
		$excel->col('L')->width('100');
		$excel->col('M')->width('100');
		$excel->col('N')->width('100');
		$excel->col('O')->width('100');
		$excel->col('P')->width('100');
		$excel->col('Q')->width('100');
		$excel->col('R')->width('100');
		$excel->col('S')->width('100');
		$excel->col('T')->width('100');
		$excel->titleSheet('Cashflow')->startSheet();
		$excel->applyStyle('title')->addRow($title);
		$excel->applyStyle('header')->addRow($header);
		//$excel->applyStyle('money')->applyTo('B7:B' .$last_line);
		$excel->applyStyle('data')->addRow($database);
		$excel->freeze('A7')->endSheet();
		
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
}
