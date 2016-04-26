<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_subunitkerja extends CI_Controller {

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
        $this->load->model('subunitkerja_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_subunitkerja", "form_subunitkerja_list", "cus-application", "List Sub Group Data", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_subunitkerja/form_subunitkerja_add", "form_subunitkerja_new", "cus-application-form-add", "Tambah Sub Group Data", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_subunitkerja_delete", "cus-application-form-delete", "Delete Sub Group Data", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Sub Group Data',
                'value' => 'text:LOWER(nama_subunit)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            )
        );

        $defaultvalue = array();
        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['ptitle'] = "Manajemen SubUnit Kerja";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_subunitkerja'] = $this->dataset_db->getModule('mod_subunitkerja');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_subunitkerja']['link'];
        $data['content'] = $this->load->view('subunitkerja_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function subunitkerja_json() {

        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 10;
        $sidx = !empty($sidx) ? $sidx : "nama_subunit";
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
        $query = $this->subunitkerja_model->getAll($limit, $offset, $sidx, $sord, $cari, $search);
        $count = $this->subunitkerja_model->countAll();

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
            $responce['rows'][$i]['id'] = $row['id_subunitkerja'];
            $responce['rows'][$i]['cell'] = array($row['id_subunitkerja'], $row['nama_subunit'], $row['is_proyek'], $row['keterangan'], '<a class="link_edit" href="' . base_url() . 'mod_subunitkerja/subunitkerja_view/' . $row['id_subunitkerja'] . '"><img src="' . base_url() . 'media/edit.png" /></a>');
            $i++;
        }
        echo json_encode($responce);
    }

    public function form_subunitkerja_add() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_subunitkerja", "form_subunitkerja_list", "cus-application", "List Sub Group Data", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_subunitkerja/form_subunitkerja_add", "form_subunitkerja_new", "cus-application-form-add", "Tambah Sub Group Data", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_subunitkerja_delete", "cus-application-form-delete", "Delete Sub Group Data", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['ptitle'] = "Manajemen Subunit / Add Subunit";
        $data['is_proyek'] = array('t' => "TRUE", 'f' => 'FALSE');
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_subunitkerja'] = $this->dataset_db->getModule('mod_subunitkerja');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_subunitkerja']['link'];
        $data['content'] = $this->load->view('subunitkerja_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function subunitkerja_add() {

        $this->form_validation->set_rules('nama_subunit', 'Nama Sub Group Data', 'required|xss_clean');
        $this->form_validation->set_rules('is_proyek', 'isProyek', 'required|xss_clean');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $field["nama_subunit"] = $this->input->post("nama_subunit");
            $field["is_proyek"] = $this->input->post("is_proyek");
            $field["keterangan"] = $this->input->post("keterangan");

            $insert = $this->subunitkerja_model->insert($field);

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

    public function subunitkerja_delete() {
        $id = $this->input->post('id');
        $this->subunitkerja_model->delete($id);
    }

    public function subunitkerja_view($id = false) {
        try {
            if ($id) {
                $check_id = $this->subunitkerja_model->cekId($id);
                if ($check_id) {
                    $this->toolbar->create_toolbar();
                    $this->toolbar->cGroupButton();
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_subunitkerja", "form_subunitkerja_list", "cus-application", "List Sub Group Data", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_subunitkerja/form_subunitkerja_add", "form_subunitkerja_new", "cus-application-form-add", "Tambah Sub Group Data", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_subunitkerja_delete", "cus-application-form-delete", "Delete Sub Group Data", "tooltip", "right");
                    $this->toolbar->eGroupButton();
                    $data['toolbars'] = $this->toolbar->generate();
                    
                    $data['ptitle'] = "Manajemen Subunit / Edit Subunit";
                    $data['detail'] = $this->subunitkerja_model->get($id);
                    $data['is_proyek'] = array('t' => "TRUE", 'f' => 'FALSE');
                    $data['navs'] = $this->dataset_db->buildNav(0);
                    $tabs = $this->session->userdata('tabs');
                    if (!$tabs)
                        $tabs = array();
                    $tabs['mod_subunitkerja'] = $this->dataset_db->getModule('mod_subunitkerja');
                    $this->session->set_userdata('tabs', $tabs);
                    $data['current_tab'] = $tabs['mod_subunitkerja']['link'];
                    $data['content'] = $this->load->view('subunitkerja_edit', $data, true);
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

    public function subunitkerja_edit() {

        $this->form_validation->set_rules('id', 'id', 'required|xss_clean');
        $this->form_validation->set_rules('nama_subunit', 'Nama Sub Group Data', 'required|xss_clean');
        $this->form_validation->set_rules('is_proyek', 'isProyek', 'required|xss_clean');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $field["nama_subunit"] = $this->input->post("nama_subunit");
            $field["is_proyek"] = $this->input->post("is_proyek");
            $field["keterangan"] = $this->input->post("keterangan");

            $update = $this->subunitkerja_model->update($field, $id);

            if ($update) {
                $data['success'] = '<p>Data Berhasil Disimpan</p>';
                $data['redirect'] = base_url() . 'mod_subunitkerja';
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

}