<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_kdperkiraan extends CI_Controller {

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
        $this->load->model('kdperkiraan_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_kdperkiraan", "form_kdperkiraan_list", "cus-application", "List Kode Perkiraan", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_kdperkiraan_new", "cus-application-form-add", "Add Kode Perkiraan", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_kdperkiraan_delete", "cus-application-form-delete", "Delete Kode Perkiraan", "tooltip", "right");
		$this->toolbar->eGroupButton();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", "javascript:void(0);", "form_kode_perkiraan_excel", "cus-page-excel", "To Excel", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Nama Akun',
                'value' => 'text:LOWER(nmperkiraan)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            ),
            array(
                'text' => 'Kode Akun',
                'value' => 'text:LOWER(kdperkiraan)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            )
        );

        $defaultvalue = array();

        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['ptitle'] = "Master Kode Perkiraan";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_kdperkiraan'] = $this->dataset_db->getModule('mod_kdperkiraan');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_kdperkiraan']['link'];
        $data['content'] = $this->load->view('kdperkiraan_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function kdperkiraan_json() {
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		
        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 20;
        $sidx = !empty($sidx) ? $sidx : "kdperkiraan";
        $sord = !empty($sord) ? $sord : "desc";

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
        $query = $this->kdperkiraan_model->getAllAkun($limit, $offset, $sidx, $sord, $cari, $search, $proyek);
        $count = $this->kdperkiraan_model->countAll();

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
        foreach ($query as $row) {
            $responce['rows'][$i]['id'] = $row['kdperkiraan'];
            $responce['rows'][$i]['cell'] = array($row['kdperkiraan'], $row['nmperkiraan'], $row['flag_nasabah'], $row['flag_sumberdaya'], $row['proyek']);
            $i++;
        }
        echo json_encode($responce);
    }

    public function popup_kdperkir() {
        $data['content'] = $this->load->view('popup_kdperkir', '', true);
        $this->load->vars($data);
        $this->load->view('default_picker');
    }

    public function popup_json() {
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		
        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 10;
        $sidx = !empty($sidx) ? $sidx : "kdperkiraan";
        $sord = !empty($sord) ? $sord : "asc";


        if (strtolower($search) == "true") {
            $kdperkiraan = isset($_GET['kdperkiraan']) ? $_GET['kdperkiraan'] : '';
            $nmperkiraan = isset($_GET['nmperkiraan']) ? $_GET['nmperkiraan'] : '';
            $cari = array();
            $cari["kdperkiraan"] = $kdperkiraan;
            $cari["nmperkiraan"] = $nmperkiraan;
        } else {
            $cari = "";
        }

        $offset = ($page * $limit) - $limit;
        $offset = ($offset < 0) ? 0 : $offset;

        if (!$sidx)
            $sidx = 1;

        $query = $this->kdperkiraan_model->getAll($limit, $offset, $sidx, $sord, $cari, $search, $proyek);
        $count = $this->kdperkiraan_model->countAll();

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
        foreach ($query as $row) {
            $responce['rows'][$i]['id'] = $row['dperkir_id'];
            $responce['rows'][$i]['cell'] = array($row['dperkir_id'], $row['kdperkiraan'], $row['nmperkiraan'], $row['flag_sumberdaya'], $row['flag_nasabah'],$row['proyek']);
            $i++;
        }
        echo json_encode($responce);
    }

    public function autocomplete_kodeperkiraan() {
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		
        $search = isset($_GET['_search']) ? $_GET['_search'] : '';
        $cat = isset($_GET['cat']) ? $_GET['cat'] : '';
        $param = isset($_GET['term']) ? $_GET['term'] : '';

        $query = $this->kdperkiraan_model->getPerkiraan($param, $cat, $search, $proyek);
        echo json_encode($query);
    }

    public function get_kodeperkiraan() {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		
        $dt['dperkir_id'] = $this->input->post('item');
        $json['json'] = $this->kdperkiraan_model->getPicker($dt,$proyek);
        $this->load->view('template/ajax', $json);
    }
    
    public function to_excel() {
		$this->load->library('export_excel');
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$unitkerja = $userconfig["kolom1"];

        $database = $this->kdperkiraan_model->getAllExcel($proyek);
		
		//echo ('<pre>');
		//print_r($database);
		//echo count($database);
		//die ('tesss');
		$jumRecord = count($database);
		
		if ($jumRecord > 0) {
			//$result = $database;
			$last_line = $jumRecord + 7;
		}
		
		$title = array(
            array('PT Brantas Abipraya','','','',''),
            array('Master Kode Perkiraan','','','','')
        );
        
        $header = array(
            array('Kode Perkiraan', 'Nama Kode Perkiraan','Buku Bantu','',''),
            array('', '','Rekanan','Sumber Daya','Proyek')
        );
        
        $styleArray = array(
            'title' => array(
                'Alignment' => array(
					'Horizontal' => 'Center',
					'Vertical' => 'Center'
                ),
				'Font'	=> array(
					'Bold'	=>	'1',
					'Size'	=>	'11'
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
					'Size'	=>	'10'
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
		
		//echo $last_line;
		//die ('tesss');
		
		$excel = new Export_Excel();
		$excel->filename = "Master_Kode_Perkiraan.xls";
		
		$excel->setStyle($styleArray)->initialize();
		$excel->merge('A1:E1');
		$excel->merge('A2:E2');
		$excel->merge('C3:E3');
		$excel->merge('A3:A4');
		$excel->merge('B3:B4');
		$excel->col('A')->width('100');
		$excel->col('B')->width('250');
		$excel->col('C')->width('80');
		$excel->col('D')->width('80');
		$excel->col('E')->width('80');
		
		$excel->titleSheet('Master Kode Perkiraan')->startSheet();
		$excel->applyStyle('title')->addRow($title);
		$excel->applyStyle('header')->addRow($header);
		$excel->applyStyle('data')->addRow($database);
		$excel->freeze('A4')->endSheet();
		
		$excel->finalize();
		
        exit;
		
    }

}
