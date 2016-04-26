<?php

class mod_listjurnalapp extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('listjurnalapp_model');
        $this->load->model('dataset_db');
        $this->load->library('search_form');
        $this->load->library('searchform');
    }

    public function index() {
        $toolbar_config = array(
						array(
							'list' => array(
								'tag' 	=> 'a',
								'class' => 'btn',
								'link' 	=> base_url().'mod_listjurnalapp',
								'event' => '',
								'icon' 	=> 'cus-table'
							),
							'toexcel' => array(
								'tag' 	=> 'button',
								'class' => 'btn',
								'link' 	=> '#',
								'event' => 'onclick="xsearch.submit();"',
								'icon' 	=> 'cus-printer'
							)
						)
					);

		$DataModel = array(
						array(
							'text' => 'Nomor Bukti',
							'value' => 'text:LOWER(nobukti)',
							'type' => 'text',
							'callBack' => '',
							'ops' => array("like", "not like", "=", "!=")
						),
						array(
							'text' => 'Kode Perkiraan',
							'value' => 'coa',
							'type' => 'custom',
							'callBack' => 'getCOA',
							'ops' => array(">", "<", "=>", "<=", "=", "!=")
						),
						array(
							'text' => 'Keterangan',
							'value' => 'text:LOWER(keterangan)',
							'type' => 'text',
							'callBack' => '',
							'ops' => array("like", "not like", "=", "!=")
						),
						array(
							'text' => 'Tanggal',
							'value' => 'tanggal',
							'type' => 'date',
							'callBack' => '',
							'ops' => array(">", "<", "=>", "<=", "=", "!=")
						)
					);

        $defaultvalue = array();

        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['toolbar'] = $this->search_form->toolbar($toolbar_config);
        $data['ptitle'] = "List Jurnal";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['unitkerja'] = $this->dataset_db->getSubUnitkerja();
		$data['kode_proyek'] = $this->dataset_db->getDataProyek();
        $tabs['mod_listjurnalapp'] = $this->dataset_db->getModule('mod_listjurnalapp');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_listjurnalapp']['link'];
        $data['content'] = $this->load->view('listjurnalapp_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function listjurnal_json() {
        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 10;
        $sidx = !empty($sidx) ? $sidx : "nobukti";
        $sord = !empty($sord) ? $sord : "asc";

        if (strtolower($search) == "true") {
            $cols = isset($_GET['cols']) ? $_GET['cols'] : '';
            $ops = isset($_GET['ops']) ? $_GET['ops'] : '';
            $vals = isset($_GET['vals']) ? $_GET['vals'] : '';

            $cari = array();
            for ($x = 0; $x < count($cols); $x++) {
                $cari[$x]['cols'] = $cols[$x];
                $cari[$x]['ops'] = $ops[$x];
                $cari[$x]['vals'] = $vals[$x];
            }
        } else {
            $cari = "";
        }

        $offset = ($page * $limit) - $limit;
        $offset = ($offset < 0) ? 0 : $offset;

        if (!$sidx)
            $sidx = 1;
        $query = $this->listjurnalapp_model->getAll($limit, $offset, $sidx, $sord, $cari, $search);
        $count = $this->listjurnalapp_model->countAll();

		//echo "<pre>";
		//print_r($query);
		//echo "</pre>";

        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;

        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
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
        $database = $this->listjurnalapp_model->getAllForExcel();
		
		if (count($database) > 0) {
			$result = $database;
			$last_line = count($database) + 5;
		}

        $header = array(
            array('PT Brantas Abipraya','','','','','','','',''),
            array('No', 'Tanggal', 'No Bukti', 'Proyek', 'COA', 'Rekanan', 'Keterangan', 'Nilai',''),
            array('','','','','','','','Debit', 'Kredit')
        );
        
        $styleArray = array(
			'ce1' => array(	
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
					),
					'Top' => array(
						'Position'	=> 'Top',
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					),
				),
				'Font'	=> array(
					'Bold'	=>	'1',
					'Italic'	=>	'1',
					'Size'	=>	'16'
				)
			),
			'ce2' => array(	
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
					)
				),
				'Font'	=> array(
					'Bold'	=>	'1',
					'Italic'	=>	'1',
					'Size'	=>	'8',
				)
			),
			'ce3' => array(	
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
					),
					'Top' => array(
						'Position'	=> 'Top',
						'LineStyle' => 'Continuous',
						'Weight' => '1',
						'Color' => '#000000'
					),
				),
				'Font'	=> array(
					'Bold'	=>	'1',
					'Italic'	=>	'1',
					'Size'	=>	'8',
				),
				'NumberFormat' => array(
					'Format'	=>	'Short Date'
				)
			)/*,
			'ce4' => array(	
				'Borders' => array(
					'Bottom' => array(
						'ss:Position'	=> 'Bottom',
						'ss:LineStyle' => 'Continuous',
						'ss:Weight' => '1',
						'ss:Color' => '#000000'
					),
					'Left' => array(
						'ss:Position'	=> 'Left',
						'ss:LineStyle' => 'Continuous',
						'ss:Weight' => '1',
						'ss:Color' => '#000000'
					),
					'Right' => array(
						'ss:Position'	=> 'Right',
						'ss:LineStyle' => 'Continuous',
						'ss:Weight' => '1',
						'ss:Color' => '#000000'
					),
					'Top' => array(
						'ss:Position'	=> 'Top',
						'ss:LineStyle' => 'Continuous',
						'ss:Weight' => '1',
						'ss:Color' => '#000000'
					),
				),
				'Font'	=> array(
					'ss:Bold'	=>	'1',
					'ss:Italic'	=>	'1',
					'ss:Size'	=>	'8',
				),
				'NumberFormat' => array(
					'ss:Format'	=>	'Short Date'
				)
			)*/
        );
        
        //die(print_r($database));
        //die(key($styleArray[1]));
        
        $excel = new Export_Excel();
		$excel->filename = "test_excel.xls";

		$excel->setStyle($styleArray)->initialize();
		//$excel->initialize();
		//$excel->setStyle($styleArray)->applyStyle();
		$excel->merge('A1:I1');
		$excel->merge('A2:A3');
		$excel->merge('B2:B3');
		$excel->merge('C2:C3');
		$excel->merge('D2:D3');
		$excel->merge('E2:E3');
		$excel->merge('F2:F3');
		$excel->merge('G2:G3');
		$excel->merge('H2:I2');
		//$excel->debug();
		$excel->applyStyle('ce3')->toCell('B2');
		$excel->titleSheet('pertama')->startSheet();
		$excel->applyStyle('ce1')->addRow($header);
		//$excel->addRow($header);
		$excel->applyStyle('ce2')->addRow($database);
		$excel->freeze('A')->endSheet();
		//$excel->endSheet();
		
		$excel->titleSheet('kedua')->startSheet();
		$excel->applyStyle('ce2')->addRow($header);
		//$excel->addRow($header);
		$excel->applyStyle('ce1')->addRow($database);
		$excel->endSheet();
		
		$excel->finalize();
		
        exit;
    }
	
    /*public function to_excel() {
        $this->load->library('excel');
        $database = $this->listjurnalapp_model->getAllForExcel();
		
		if (count($database) > 0) {
			$result = $database;
			$last_line = count($database) + 5;
		}

        $header = array(
            array('No', 'Tanggal', 'No Bukti', 'Proyek', 'COA', 'Rekanan', 'Keterangan', 'Debit', 'Kredit')
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
		
        $this->excel->getProperties()->setTitle("List Jurnal Approved");
        $this->excel->getProperties()->setCreator("PT.Brantas Abipraya");
        $this->excel->getProperties()->setSubject("List Jurnal Approved");
        $this->excel->getProperties()->setDescription("Laporan List Jurnal Approved");
        $this->excel->getProperties()->setKeywords("List Jurnal Approved");
        $this->excel->getProperties()->setCategory("Laporan");

		$this->excel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A5:I6')->applyFromArray($styleHeader);

		$this->excel->getActiveSheet()->getStyle('A7:I' . $last_line)->applyFromArray($styleArray);
		$this->excel->getActiveSheet()->getStyle('H7:I' . $last_line)->getNumberFormat()->setFormatCode("[Black]#,##0.00;[Red](#,##0.00)");
		//$this->excel->getActiveSheet()->getStyle('H7:I' . $last_line)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2);

		$this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT);
		$this->excel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		$this->excel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		$this->excel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		$this->excel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(50);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
		$this->excel->getActiveSheet()->mergeCells('A1:I1');
		$this->excel->getActiveSheet()->mergeCells('A2:I2');
		$this->excel->getActiveSheet()->mergeCells('A3:I3');
		$this->excel->getActiveSheet()->mergeCells('A4:I4');
		$this->excel->getActiveSheet()->mergeCells('A5:A6');
		$this->excel->getActiveSheet()->mergeCells('B5:B6');
		$this->excel->getActiveSheet()->mergeCells('C5:C6');
		$this->excel->getActiveSheet()->mergeCells('D5:D6');
		$this->excel->getActiveSheet()->mergeCells('E5:E6');
		$this->excel->getActiveSheet()->mergeCells('F5:F6');
		$this->excel->getActiveSheet()->mergeCells('G5:G6');
		$this->excel->getActiveSheet()->mergeCells('H5:H6');
		$this->excel->getActiveSheet()->mergeCells('I5:I6');
		$this->excel->getActiveSheet()->freezePane('A7');

		$this->excel->getActiveSheet()->setCellValue('A1', 'PT Brantas Abipraya');
		$this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->setCellValue('A2', 'List Jurnal Approved');
		$this->excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(20);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

		$this->excel->getActiveSheet()->fromArray($header, null, 'A5');
		$this->excel->getActiveSheet()->fromArray($result, null, 'A7');
            
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="List_Jurnal_Approved"');
        $objWriter->save("php://output");
        exit;
    }*/

	public function getDataProyek() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        $this->form_validation->set_rules("id_proyek", "id_proyek", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $id_proyek = $this->input->post("id_proyek");
            $proyek = $this->dataset_db->getDataProyek($id);
            foreach ($proyek as $key => $value) {
                if($id_proyek == $key) {
                    echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                } else {
                    echo "<option value=\"" . $key . "\">" . $value . "</option>";
                }
            }
        }
    }
    
}
