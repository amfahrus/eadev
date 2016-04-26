<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_katproyek extends CI_Controller {

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
        $this->load->model('katproyek_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_katproyek", "form_katproyek_list", "cus-application", "List Kategori Proyek", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_katproyek/form_katproyek_add", "form_katproyek_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_katproyek_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Kategori Proyek',
                'value' => 'text:LOWER(nama_kategoriproyek)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            )
        );

        $defaultvalue = array();

        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['ptitle'] = "Manajemen Kategori Proyek";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_katproyek'] = $this->dataset_db->getModule('mod_katproyek');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_katproyek']['link'];
        $data['content'] = $this->load->view('katproyek_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function katproyek_json() {

        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 10;
        $sidx = !empty($sidx) ? $sidx : "nama_kategoriproyek";
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
        $query = $this->katproyek_model->getAll($limit, $offset, $sidx, $sord, $cari, $search);
        $count = $this->katproyek_model->countAll();

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
            $responce['rows'][$i]['id'] = $row['id_katproyek'];
            $responce['rows'][$i]['cell'] = array($row['id_katproyek'], $row['nama_kategoriproyek'], $row['keterangan'], $row['is_active'], '<a class="link_edit" href="' . base_url() . 'mod_katproyek/katproyek_view/' . $row['id_katproyek'] . '"><img src="' . base_url() . 'media/edit.png" /></a>');
            $i++;
        }
        echo json_encode($responce);
    }

    public function form_katproyek_add() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_katproyek", "form_katproyek_list", "cus-application", "List Kategori Proyek", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_katproyek/form_katproyek_add", "form_katproyek_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_katproyek_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['ptitle'] = "Manajemen Kategori Proyek/ Add Kategori";
        $data['is_active'] = array('t' => "Aktif", 'f' => 'Non Aktif');
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_katproyek'] = $this->dataset_db->getModule('mod_katproyek');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_katproyek']['link'];
        $data['content'] = $this->load->view('katproyek_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function katproyek_add() {

        $this->form_validation->set_rules('nama_kategoriproyek', 'Kategori Proyek', 'required|xss_clean');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'xss_clean');
        $this->form_validation->set_rules('is_active', 'IsAktif', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $field["nama_kategoriproyek"] = $this->input->post("nama_kategoriproyek");
            $field["keterangan"] = $this->input->post("keterangan");
            $field["is_active"] = $this->input->post("is_active");
            $insert = $this->katproyek_model->insert($field);

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

    public function katproyek_delete() {
        $id = $this->input->post('id');
        $this->katproyek_model->delete($id);
    }

    public function katproyek_view($id = false) {
        try {
            if ($id) {
                $check_id = $this->katproyek_model->cekId($id);
                if ($check_id) {
                    $this->toolbar->create_toolbar();
                    $this->toolbar->cGroupButton();
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_katproyek", "form_katproyek_list", "cus-application", "List Kategori Proyek", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_katproyek/form_katproyek_add", "form_katproyek_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_katproyek_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
                    $this->toolbar->eGroupButton();
                    $data['toolbars'] = $this->toolbar->generate();
                    
                    $data['ptitle'] = "Manajemen Kategori Proyek/ Add Kategori";
                    $data['detail'] = $this->katproyek_model->get($id);
                    $data['is_active'] = array('t' => "Aktif", 'f' => 'Non Aktif');
                    $data['navs'] = $this->dataset_db->buildNav(0);
                    $tabs = $this->session->userdata('tabs');
                    if (!$tabs)
                        $tabs = array();
                    $tabs['mod_katproyek'] = $this->dataset_db->getModule('mod_katproyek');
                    $this->session->set_userdata('tabs', $tabs);
                    $data['current_tab'] = $tabs['mod_katproyek']['link'];
                    $data['content'] = $this->load->view('katproyek_edit', $data, true);
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

    public function katproyek_edit() {
        $this->form_validation->set_rules('id', 'id', 'required|xss_clean');
        $this->form_validation->set_rules('nama_kategoriproyek', 'nama_kategoriproyek', 'required|xss_clean');
        $this->form_validation->set_rules('keterangan', 'keterangan', 'xss_clean');
        $this->form_validation->set_rules('is_active', 'is_active', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $field["nama_kategoriproyek"] = $this->input->post("nama_kategoriproyek");
            $field["keterangan"] = $this->input->post("keterangan");
            $field["is_active"] = $this->input->post("is_active");
            $update = $this->katproyek_model->update($field, $id);

            if ($update) {
                $data['success'] = '<p>Data Berhasil Disimpan</p>';
                $data['redirect'] = base_url() . 'mod_katproyek';
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
