<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_sbdaya extends CI_Controller {

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
        $this->load->model('sbdaya_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
        $this->_userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_sbdaya", "form_sbdaya_list", "cus-application", "List Sumber Daya", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_sbdaya/form_sbdaya_add", "form_sbdaya_new", "cus-application-form-add", "Tambah Sumber Daya", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_sbdaya_delete", "cus-application-form-delete", "Delete Sumber Daya", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Nama Group',
                'value' => 'text:LOWER(nama_group)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            ),
            array(
                'text' => 'Keterangan',
                'value' => 'text:LOWER(keterangan)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            )
        );
        $defaultvalue = array();

        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['ptitle'] = "Master Sumber Daya";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_sbdaya'] = $this->dataset_db->getModule('mod_sbdaya');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_sbdaya']['link'];
        $data['content'] = $this->load->view('sbdaya_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function sbdaya_json() {

        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 20;
        $sidx = !empty($sidx) ? $sidx : "sbdaya";
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
        $query = $this->sbdaya_model->getAll($limit, $offset, $sidx, $sord, $cari, $search, $this->_userconfig["kolom2"]);
        $count = $this->sbdaya_model->countAll();

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
            $responce['rows'][$i]['id'] = $row['id_sbdaya'];
            $responce['rows'][$i]['cell'] = array($row['id_sbdaya'], $row['nama_subunit'], $row['proyek'], $row['nama_library'], $row['sbdaya'], '<a class="link_edit" href="' . base_url() . 'mod_sbdaya/sbdaya_view/' . $row['id_sbdaya'] . '"><img src="' . base_url() . 'media/edit.png" /></a>');
            $i++;
        }
        echo json_encode($responce);
    }

    public function autocomplete_sbdaya() {
        $param = !empty($_GET['term']) ? $_GET['term'] : '';
        $coa = !empty($_GET['coa']) ? $_GET['coa'] : '';
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
        $query = $this->sbdaya_model->autocomplete($param, $userconfig["kolom2"], $coa);
        echo json_encode($query);
    }

    public function form_sbdaya_add() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_sbdaya", "form_sbdaya_list", "cus-application", "List Sumber Daya", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_sbdaya/form_sbdaya_add", "form_sbdaya_new", "cus-application-form-add", "Tambah Sumber Daya", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_sbdaya_delete", "cus-application-form-delete", "Delete Sumber Daya", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['ptitle'] = "Master Sumber Daya";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_sbdaya'] = $this->dataset_db->getModule('mod_sbdaya');
        $this->session->set_userdata('tabs', $tabs);
        $data['unitkerja'] = $this->dataset_db->getSubUnitkerja();
        $data['kode_proyek'] = $this->dataset_db->getDataProyek();
        $data['tipe'] = $this->sbdaya_model->getTypeSbdaya();
        $data['current_tab'] = $tabs['mod_sbdaya']['link'];
        $data['content'] = $this->load->view('sbdaya_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function sbdaya_add() {
        $this->form_validation->set_rules("tipe", "Tipe Sumber Daya", "required|xss_clean");
        $this->form_validation->set_rules("kode_sbdaya", "Kode Sumber Daya", "required|xss_clean|callback_checkKodeSbdaya");
        $this->form_validation->set_rules("nama_sbdaya", "Nama Sumber Daya", "required|xss_clean");
        $this->form_validation->set_rules("satuan", "Satuan", "required|xss_clean");

        if ($this->form_validation->run() == TRUE) {
            $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
            $field["tipe"] = $this->input->post("tipe");
            $field["kode_sbdaya"] = $this->input->post("kode_sbdaya");
            $field["nama_sbdaya"] = $this->input->post("nama_sbdaya");
            $field["satuan"] = $this->input->post("satuan");
            $field["id_proyek"] = $userconfig["kolom2"];
            $field["create_id"] = $this->session->userdata('ba_user_id');
            $field["create_time"] = $this->myauth->timestampIndo();

            $insert = $this->sbdaya_model->insert($field);

            if ($insert) {
                $data['success'] = '<p>Data Berhasil Disimpan</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $data['error'] = '<p>Data Gagal Disimpan</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function checkKodeSbdaya() {
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
        $kode_sbdaya = $this->input->post("kode_sbdaya");
        $kode_proyek = $userconfig["kolom2"];
        if ($this->sbdaya_model->checkKodeSbdaya($kode_sbdaya, $kode_proyek)) {
            $this->form_validation->set_message('checkKodeSbdaya', "Kode Sumber Daya " . $kode_sbdaya . " Telah Terdaftar, Pilih Yang Lain.");
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function sbdaya_delete() {
        $id = $this->input->post('id');
        $this->sbdaya_model->delete($id);
    }

    public function sbdaya_view($id = false) {
        try {
            if ($id) {
                $check_id = $this->sbdaya_model->cekId($id);
                if ($check_id) {
                    $this->toolbar->create_toolbar();
                    $this->toolbar->cGroupButton();
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_sbdaya", "form_sbdaya_list", "cus-application", "List Sumber Daya", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_sbdaya/form_sbdaya_add", "form_sbdaya_new", "cus-application-form-add", "Tambah Sumber Daya", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_sbdaya_delete", "cus-application-form-delete", "Delete Sumber Daya", "tooltip", "right");
                    $this->toolbar->eGroupButton();
                    $data['toolbars'] = $this->toolbar->generate();
                    
                    $data['ptitle'] = "Master Sumber Daya";
                    $data['navs'] = $this->dataset_db->buildNav(0);
                    $tabs = $this->session->userdata('tabs');
                    if (!$tabs)
                        $tabs = array();
                    $tabs['mod_sbdaya'] = $this->dataset_db->getModule('mod_sbdaya');
                    $this->session->set_userdata('tabs', $tabs);
                    $data['detail'] = $this->sbdaya_model->get($id);
                    $data['unitkerja'] = $this->dataset_db->getSubUnitkerja();
                    $data['kode_proyek'] = $this->dataset_db->getDataProyek();
                    $data['tipe'] = $this->sbdaya_model->getTypeSbdaya();
                    $data['current_tab'] = $tabs['mod_sbdaya']['link'];
                    $data['content'] = $this->load->view('sbdaya_edit', $data, true);
                    $this->load->vars($data);
                    $this->load->view('default_view');
                } else {
                    throw new Exception('Error');
                }
            } else {
                throw new Exception('Error');
            }
        } catch (Exception $ex) {
            redirect('forbidden');
        }
    }

    public function sbdaya_edit() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        $this->form_validation->set_rules("tipe", "tipe", "required|xss_clean");
        $this->form_validation->set_rules("kode_sbdaya", "kode_sbdaya", "required|xss_clean");
        $this->form_validation->set_rules("nama_sbdaya", "nama_sbdaya", "required|xss_clean");
        $this->form_validation->set_rules("satuan", "satuan", "required|xss_clean");

        if ($this->form_validation->run() == TRUE) {
            $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
            $id = $this->input->post("id");
            $field["tipe"] = $this->input->post("tipe");
            $field["kode_sbdaya"] = $this->input->post("kode_sbdaya");
            $field["nama_sbdaya"] = $this->input->post("nama_sbdaya");
            $field["satuan"] = $this->input->post("satuan");
            $field["id_proyek"] = $userconfig["kolom2"];
            $field["modify_id"] = $this->session->userdata('ba_user_id');
            $field["modify_time"] = $this->myauth->timestampIndo();

            $update = $this->sbdaya_model->update($field, $id);

            if ($update) {
                $data['success'] = '<p>Data Berhasil Disimpan</p>';
                $data['redirect'] = base_url() . 'mod_sbdaya';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $data['error'] = '<p>Data Gagal Disimpan</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function popup_sbdaya() {
        $data['content'] = $this->load->view('popup_sbdaya', "", true);
        $this->load->vars($data);
        $this->load->view('default_picker');
    }

    public function popup_add() {
        $data['tipe'] = $this->sbdaya_model->getTypeSbdaya();
        $data['content'] = $this->load->view('popup_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_picker');
    }

    public function popup_view($id = false) {
        try {
            if ($id) {
                if (isInteger($id)) {
                    $data["detail"] = $this->sbdaya_model->get($id);
                    $data["tipe"] = $this->sbdaya_model->getTypeSbdaya();
                    $data["content"] = $this->load->view('popup_edit', $data, true);
                    $this->load->vars($data);
                    $this->load->view('default_picker');
                } else {
                    throw new Exception('Error');
                }
            } else {
                throw new Exception('Error');
            }
        } catch (Exception $ex) {
            redirect('forbidden');
        }
    }

    public function popup_json() {
        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 10;
        $sidx = !empty($sidx) ? $sidx : "sbdaya";
        $sord = !empty($sord) ? $sord : "asc";


        if (strtolower($search) == "true") {
            $kode_sbdaya = isset($_GET['kodesbdaya']) ? $_GET['kodesbdaya'] : '';
            $sbdaya = isset($_GET['namasbdaya']) ? $_GET['namasbdaya'] : '';
            $cari = array();
            $cari["kode_sbdaya"] = $kode_sbdaya;
            $cari["sbdaya"] = $sbdaya;
        } else {
            $cari = "";
        }

        $offset = ($page * $limit) - $limit;
        $offset = ($offset < 0) ? 0 : $offset;

        if (!$sidx)
            $sidx = 1;

        $query = $this->sbdaya_model->PopupGetAll($limit, $offset, $sidx, $sord, $cari, $search, $this->_userconfig["kolom2"]);
        $count = $this->sbdaya_model->PopupCountAll();

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
            $responce['rows'][$i]['id'] = $row['id_sbdaya'];
            $responce['rows'][$i]['cell'] = array('', $row['id_sbdaya'], $row['kode_sbdaya'], $row['sbdaya']);
            $i++;
        }
        echo json_encode($responce);
    }

    public function getsbdaya() {

        $dt['id_sbdaya'] = $this->input->post('item');
        $json['json'] = $this->sbdaya_model->getPicker($dt);
        $this->load->view('template/ajax', $json);
    }

    public function upload_form() {
        if ($this->input->post("submit")) {
            $kode_proyek = $this->input->post('kode_proyek');
            $config['file_name'] = 'form_upload_master_sumberdaya';
            $config['upload_path'] = './files/';
            $config['allowed_types'] = 'xl|xls|xlsx';
            $config['overwrite'] = TRUE;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('master_sbdaya')) {
                $this->session->set_flashdata('messages', 'File yang anda upload bukan file excel ...');
                redirect('mod_sbdaya/upload_form');
                //echo "<script>alert('Data gagal disimpan!". $this->upload->display_errors()."');window.location = '".base_url()."/mod_rekanan/upload_form'</script>";
            } else {
                $files = array('upload_data' => $this->upload->data());
                $file = $files['upload_data']['full_path'];
            }
            //print_r($this->upload->data());
            //echo $file;
            $this->load->library('excel');
            $inputFileType = 'Excel2007';
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($file);
            $objPHPExcel->setActiveSheetIndex(0);
            $objWorksheet = $objPHPExcel->getActiveSheet();
            $highestRow = $objWorksheet->getHighestRow();
            $temp_result = array();
            $log_error = '';
            if (file_exists($file)) {
                for ($i = 2; $i <= $highestRow; $i++) {
                    if ($this->sbdaya_model->checkKodeSbdaya($objPHPExcel->getActiveSheet()->getCell('B' . $i)->getValue(), $kode_proyek))
                        $log_error .= 'Error : Kode sumberdaya sudah ada<br>';
                    if ($this->sbdaya_model->getIdLibrary(strtoupper($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getValue())) == false)
                        $log_error .= 'Error : Tipe sumberdaya tidak ada dalam database<br>';
                    $temp_result[] = array(
                        'id_proyek' => $kode_proyek,
                        'kode_sbdaya' => $objPHPExcel->getActiveSheet()->getCell('B' . $i)->getValue(),
                        'sbdaya' => $objPHPExcel->getActiveSheet()->getCell('C' . $i)->getValue(),
                        'satuan' => $objPHPExcel->getActiveSheet()->getCell('D' . $i)->getValue(),
                        'tipe' => $this->sbdaya_model->getIdLibrary(strtoupper($objPHPExcel->getActiveSheet()->getCell('E' . $i)->getValue())),
                        'status' => ($log_error == '' ? 'OK' : $log_error)
                    );
                    $log_error = '';
                }
            }
            $data['tmp'] = $temp_result;
            $data['mydata'] = json_encode($temp_result);
            //$this->upload_excel($temp_result);
            $tmp = array();
            foreach ($temp_result as $key => $row) {
                if ($row['status'] == 'OK') {
                    $tmp[] = array(
                        'id_proyek' => $row['id_proyek'],
                        'kode_sbdaya' => $row['kode_sbdaya'],
                        'nama_sbdaya' => $row['sbdaya'],
                        'satuan' => $row['satuan'],
                        'tipe' => $row['tipe']
                    );
                }
            }
            //print_r($tmp);
            if (count($tmp) > 0) {
                $this->sbdaya_model->insertExport($tmp);
            }
        } else {
            $data['tmp'] = false;
            $data['mydata'] = '[]';
        }
        $toolbar_config = array(
            'item_new' => base_url() . 'mod_sbdaya/sbdaya_add',
//                'item_delete' => '#',
            'table' => base_url() . 'mod_sbdaya'
        );

        $data['toolbar'] = $this->search_form->toolbars($toolbar_config);
        $data['ptitle'] = "Master Sumberdaya";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_sbdaya'] = $this->dataset_db->getModule('mod_sbdaya');
        $this->session->set_userdata('tabs', $tabs);
        $data['unitkerja'] = $this->dataset_db->getSubUnitkerja();
        $data['kode_proyek'] = $this->dataset_db->getDataProyek();

//            $data['kode_proyek'] = $this->rekanan_model->getKodeProyek();
        $data['tipe'] = $this->sbdaya_model->getTypeSbdaya();
        $data['current_tab'] = $tabs['mod_sbdaya']['link'];
        $data['content'] = $this->load->view('sbdaya_upload', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function to_excel() {
        $this->load->library('excel');
        $header = array('No', 'Kode Sumberdaya', 'Nama Sumberdaya', 'Satuan', 'Tipe');
        $result = array();
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_discISAM;
        $cacheSettings = array('dir' => '/var/www/tmp');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        //$inputFileType = 'Excel2007';
        //$objReader = PHPExcel_IOFactory::createReader($inputFileType);
        //$objReader->setReadDataOnly(true);
        $database = $this->sbdaya_model->getAllForExcel();
        if (count($database) > 0) {
            $result = $database;
        }
        $this->excel->getProperties()->setTitle("Master_Sumberdaya");
        $this->excel->getProperties()->setSubject("Master_Sumberdaya");
        $this->excel->getProperties()->setDescription("Master_Sumberdaya");
        $this->excel->getProperties()->setKeywords("Master_Sumberdaya");
        $this->excel->getProperties()->setCategory("Master_Sumberdaya");

        $this->excel->getActiveSheet()->fromArray($header, null, 'A1');
        $this->excel->getActiveSheet()->fromArray($result, null, 'A2');
        $this->excel->setActiveSheetIndex(0);
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');

        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="Master_Sumberdaya"');
        $objWriter->save("php://output");
    }

}
