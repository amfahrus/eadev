<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_jenisproyek extends CI_Controller {

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
        $this->load->model('jenisproyek_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_jenisproyek", "form_jenisproyek_list", "cus-application", "List Jenis Proyek", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_jenisproyek/jenisproyek_form_add", "form_jenisproyek_new", "cus-application-form-add", "Tambah Jenis Proyek", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_jenisproyek_delete", "cus-application-form-delete", "Delete Jenis Proyek", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Jenis Proyek',
                'value' => 'text:LOWER(jenisproyek_name)',
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
        $data['ptitle'] = "Master Jenis Proyek";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_jenisproyek'] = $this->dataset_db->getModule('mod_jenisproyek');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_jenisproyek']['link'];
        $data['content'] = $this->load->view('jenisproyek_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function jenisproyek_json() {

        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 10;
        $sidx = !empty($sidx) ? $sidx : "jenisproyek_name";
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
        $query = $this->jenisproyek_model->getAll($limit, $offset, $sidx, $sord, $cari, $search);
        $count = $this->jenisproyek_model->countAll();

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
            $responce['rows'][$i]['id'] = $row['jenisproyek_id'];
            $responce['rows'][$i]['cell'] = array($row['jenisproyek_id'], $row['jenisproyek_name'], $row['jenisproyek_ket'], '<a class="link_edit" href="' . base_url() . 'mod_jenisproyek/jenisproyek_view/' . $row['jenisproyek_id'] . '"><img src="' . base_url() . 'media/edit.png" /></a>');
            $i++;
        }
        echo json_encode($responce);
    }

    public function jenisproyek_form_add() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_jenisproyek", "form_jenisproyek_list", "cus-application", "List Jenis Proyek", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_jenisproyek/jenisproyek_form_add", "form_jenisproyek_new", "cus-application-form-add", "Tambah Jenis Proyek", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_jenisproyek_delete", "cus-application-form-delete", "Delete Jenis Proyek", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['ptitle'] = "Tambah Jenis Proyek";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_jenisproyek'] = $this->dataset_db->getModule('mod_jenisproyek');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_jenisproyek']['link'];
        $data['content'] = $this->load->view('jenisproyek_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function jenisproyek_add() {
        $this->form_validation->set_rules('jenisproyek_name', 'Jenis Proyek', 'required|xss_clean');
        $this->form_validation->set_rules('jenisproyek_ket', 'Keterangan', 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $field["jenisproyek_name"] = $this->input->post("jenisproyek_name");
            $field["jenisproyek_ket"] = $this->input->post("jenisproyek_ket");

            $insert = $this->jenisproyek_model->insert($field);

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

    public function jenisproyek_view($id = false) {
        try {
            if (!empty($id) AND $this->jenisproyek_model->cekId($id)) {

                $this->toolbar->create_toolbar();
                $this->toolbar->cGroupButton();
                $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_jenisproyek", "form_jenisproyek_list", "cus-application", "List Jenis Proyek", "tooltip", "right");
                $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_jenisproyek/jenisproyek_form_add", "form_jenisproyek_new", "cus-application-form-add", "Tambah Jenis Proyek", "tooltip", "right");
                $this->toolbar->addLink("", "btn tooltips", "#", "form_jenisproyek_delete", "cus-application-form-delete", "Delete Jenis Proyek", "tooltip", "right");
                $this->toolbar->eGroupButton();
                $data['toolbars'] = $this->toolbar->generate();

                $data['ptitle'] = "Edit Jenis Proyek";
                $data['navs'] = $this->dataset_db->buildNav(0);
                $data['detail'] = $this->jenisproyek_model->get($id);
                $tabs = $this->session->userdata('tabs');
                if (!$tabs)
                    $tabs = array();
                $tabs['mod_jenisproyek'] = $this->dataset_db->getModule('mod_jenisproyek');
                $this->session->set_userdata('tabs', $tabs);
                $data['current_tab'] = $tabs['mod_jenisproyek']['link'];
                $data['content'] = $this->load->view('jenisproyek_edit', $data, true);
                $this->load->vars($data);
                $this->load->view('default_view');
            } else {
                throw new Exception('Error');
            }
        } catch (Exception $ex) {
            redirect('forbidden');
        }
    }

    public function jenisproyek_edit() {
        $this->form_validation->set_rules('jenisproyek_id', 'Id', 'required|xss_clean');
        $this->form_validation->set_rules('jenisproyek_name', 'Jenis Proyek', 'required|xss_clean');
        $this->form_validation->set_rules('jenisproyek_ket', 'Keterangan', 'xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("jenisproyek_id");
            $field["jenisproyek_name"] = $this->input->post("jenisproyek_name");
            $field["jenisproyek_ket"] = $this->input->post("jenisproyek_ket");

            $insert = $this->jenisproyek_model->update($field, $id);

            if ($insert) {
                $data['success'] = '<p>Data Berhasil Diedit</p>';
                $data['redirect'] = base_url() . 'mod_jenisproyek';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $data['error'] = '<p>Data Gagal Diedit</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function jenisproyek_delete() {
        $this->form_validation->set_rules('id', 'Id', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");

            $delete = $this->jenisproyek_model->delete($id);

            if ($delete) {
                $data['success'] = '<p>Data Berhasil Dihapus</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $data['error'] = '<p>Data Gagal Dihapus</p>';
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
