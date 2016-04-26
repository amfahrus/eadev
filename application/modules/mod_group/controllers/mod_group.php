<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_group extends CI_Controller {

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
        $this->load->model('group_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_group", "form_group_list", "cus-application", "List Data Group Akses", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_group/form_group_add", "form_group_new", "cus-application-form-add", "Tambah Group Akses", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_group_delete", "cus-application-form-delete", "Delete Group Akses", "tooltip", "right");
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
        $data['ptitle'] = "Manajemen Group";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_group'] = $this->dataset_db->getModule('mod_group');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_group']['link'];
        $data['content'] = $this->load->view('group_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function group_json() {

        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 10;
        $sidx = !empty($sidx) ? $sidx : "nama_group";
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
        $query = $this->group_model->getAll($limit, $offset, $sidx, $sord, $cari, $search);
        $count = $this->group_model->countAll();

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
            $responce['rows'][$i]['id'] = $row['id_group'];
            $responce['rows'][$i]['cell'] = array($row['id_group'], $row['nama_group'], $row['keterangan'], '<a class="link_edit" href="' . base_url() . 'mod_group/group_view/' . $row['id_group'] . '"><img src="' . base_url() . 'media/edit.png" /></a>');
            $i++;
        }
        echo json_encode($responce);
    }

    public function form_group_add() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_group", "form_group_list", "cus-application", "List Data Group Akses", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_group/form_group_add", "form_group_new", "cus-application-form-add", "Tambah Group Akses", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_group_delete", "cus-application-form-delete", "Delete Group Akses", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['ptitle'] = "Manajemen Group / Add Group";
        $data['hak_akses'] = $this->dataset_db->buildCheckTree(0);
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_group'] = $this->dataset_db->getModule('mod_group');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_group']['link'];
        $data['content'] = $this->load->view('group_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function group_add() {

        $this->form_validation->set_rules('nama_group', 'Nama Group', 'required|xss_clean');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $group["nama_group"] = $this->input->post("nama_group");
            $group["keterangan"] = $this->input->post("keterangan");

            if (!empty($_POST['hak_akses'])) {
                $hak_akses = $this->input->post('hak_akses');
                $insert = $this->group_model->insert_group($group);
                if ($insert) {
                    $data_hakakses = array();
                    for ($x = 0; $x < count($hak_akses); $x++) {
                        $data_hakakses[$x]['id_group'] = $insert;
                        $data_hakakses[$x]['id_modules'] = $hak_akses[$x];
                    }
                    $this->group_model->insert_akses($insert, $data_hakakses);
                    $this->session->set_flashdata('messages', 'Data Berhasil Di Simpan ...');

                    $data['success'] = '<p>Berhasil Disimpan</p>';
                    $json['json'] = $data;
                    $this->load->view('template/ajax', $json);
                } else {
                    $data['error'] = '<p>Data Gagal Disimpan</p>';
                    $json['json'] = $data;
                    $this->load->view('template/ajax', $json);
                }
            } else {
                $data['error'] = '<p>Data Gagal Disimpan, Harap Pilih Modulnya</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function group_view($id = false) {
        try {
            if ($id) {
                $check_id = $this->group_model->cekId($id);

                if ($check_id) {
                    $this->toolbar->create_toolbar();
                    $this->toolbar->cGroupButton();
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_group", "form_group_list", "cus-application", "List Data Group Akses", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_group/form_group_add", "form_group_new", "cus-application-form-add", "Tambah Group Akses", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_group_delete", "cus-application-form-delete", "Delete Group Akses", "tooltip", "right");
                    $this->toolbar->eGroupButton();
                    $data['toolbars'] = $this->toolbar->generate();
                    $data['ptitle'] = "Manajemen Group / Edit";
                    $data['detail'] = $this->group_model->get($id);
                    $data['hak_akses'] = $this->group_model->buildCheckTree(0, $id);
                    $data['navs'] = $this->dataset_db->buildNav(0);
                    $tabs = $this->session->userdata('tabs');
                    if (!$tabs)
                        $tabs = array();
                    $tabs['mod_group'] = $this->dataset_db->getModule('mod_group');
                    $this->session->set_userdata('tabs', $tabs);
                    $data['current_tab'] = $tabs['mod_group']['link'];
                    $data['content'] = $this->load->view('group_edit', $data, true);
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

    public function group_edit() {

        $this->form_validation->set_rules('id', 'id', 'required|xss_clean');
        $this->form_validation->set_rules('nama_group', 'Nama Group', 'required|xss_clean');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $group["nama_group"] = $this->input->post("nama_group");
            $group["keterangan"] = $this->input->post("keterangan");

            if (!empty($_POST['hak_akses'])) {
                $hak_akses = $this->input->post('hak_akses');
                $update = $this->group_model->update_group($group, $id);
                if ($update) {
                    $data_hakakses = array();
                    for ($x = 0; $x < count($hak_akses); $x++) {
                        $data_hakakses[$x]['id_group'] = $update;
                        $data_hakakses[$x]['id_modules'] = $hak_akses[$x];
                    }
                    $this->group_model->insert_akses($update, $data_hakakses);


                    $data['redirect'] = base_url() . 'mod_group';
                    $data['success'] = '<p>Data Gagal Disimpan</p>';
                    $json['json'] = $data;
                    $this->load->view('template/ajax', $json);
                } else {
                    $data['error'] = '<p>Data Gagal Disimpan</p>';
                    $json['json'] = $data;
                    $this->load->view('template/ajax', $json);
                }
            } else {
                $data['error'] = '<p>Data Gagal Disimpan, Silahkan Piilh Modulnya</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function group_delete() {
        $id = $this->input->post('id');
        $this->group_model->delete($id);
    }

    public function listgroupaksesjson() {
        if (!$this->session->userdata('ba_username')) {
            header('HTTP/1.1 401 Unauthorized');
        }
        if (IS_AJAX) {
            $term = isset($_GET['term']) ? $_GET['term'] : '';
            $json['json'] = $this->group_model->getListGroupData($term);
            $this->load->view('template/ajax', $json);
        } else {
            echo "You Don't Have Access Here ...";
        }
    }

}