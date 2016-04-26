<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_module extends CI_Controller {

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
        $this->load->model('module_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
    }

    public function index() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_module", "form_module_list", "cus-application", "List Data User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_module/form_module_add", "form_module_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_module_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Module',
                'value' => 'text:LOWER(modules)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            ),
            array(
                'text' => 'Link',
                'value' => 'text:LOWER(link)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "not like", "=", "!=")
            )
        );

        $defaultvalue = array();
        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['ptitle'] = "Manajemen Module";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_module'] = $this->dataset_db->getModule('mod_module');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_module']['link'];
        $data['content'] = $this->load->view('module_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function module_json() {
        if (!$this->session->userdata('ba_username')) {
            header('HTTP/1.1 401 Unauthorized');
        } else {
            if (IS_AJAX) {
                $this->myauth->has_role();

                $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
                $page = $this->input->post('page');
                $limit = $this->input->post('rows');
                $sidx = $this->input->post('sidx');
                $sord = $this->input->post('sord');

                $page = !empty($page) ? $page : 1;
                $limit = !empty($limit) ? $limit : 10;
                $sidx = !empty($sidx) ? $sidx : "modules";
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
                $query = $this->module_model->getAll($limit, $offset, $sidx, $sord, $cari, $search);
                $count = $this->module_model->countAll();

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
                    $responce['rows'][$i]['id'] = $row['id_modules'];
                    $responce['rows'][$i]['cell'] = array($row['id_modules'], $row['modules'], $row['icon'], $row['parent'], $row['publish'], $row['link'], $row['sort'], '<a class="link_edit" href="' . base_url() . 'mod_module/module_view/' . $row['id_modules'] . '"><img src="' . base_url() . 'media/edit.png" /></a>');
                    $i++;
                }
                echo json_encode($responce);
            } else {
                redirect('forbidden');
            }
        }
    }

    public function form_module_add() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_module", "form_module_list", "cus-application", "List Data User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_module/form_module_add", "form_module_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_module_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['ptitle'] = "Manajemen Module / Add Module";
        $data['parent'] = $this->module_model->getModule();
        $data['publish'] = array('t' => "Aktif", 'f' => 'Non Aktif');
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_module'] = $this->dataset_db->getModule('mod_module');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_module']['link'];
        $data['content'] = $this->load->view('module_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function module_add() {
        $this->form_validation->set_rules('modules', 'modules', 'required|xss_clean');
        $this->form_validation->set_rules('icon', 'icon', 'required|xss_clean');
        $this->form_validation->set_rules('parent', 'parent', 'required|xss_clean');
        $this->form_validation->set_rules('publish', 'publish', 'required|xss_clean');
        $this->form_validation->set_rules('link', 'link', 'required|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $field["modules"] = $this->input->post("modules");
            $field["icon"] = $this->input->post("icon");
            $field["parent"] = $this->input->post("parent");
            $field["publish"] = $this->input->post("publish");
            $field["link"] = $this->input->post("link");
            $field["sort"] = $this->input->post("sort");
            $insert = $this->module_model->insert($field);

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

    public function module_delete() {
        $id = $this->input->post('id');
        $this->module_model->delete($id);
    }

    public function module_view($id = false) {
        try {
            if ($id) {
                $check_id = $this->module_model->cekId($id);
                if ($check_id) {
                    $this->toolbar->create_toolbar();
                    $this->toolbar->cGroupButton();
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_module", "form_module_list", "cus-application", "List Data User", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_module/form_module_add", "form_module_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_module_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
                    $this->toolbar->eGroupButton();
                    $data['toolbars'] = $this->toolbar->generate();
                    
                    $data['ptitle'] = "Manajemen Module / Edit Module";
                    $data['detail'] = $this->module_model->get($id);
                    $data['parent'] = $this->module_model->getModule();
                    $data['publish'] = array('t' => "Aktif", 'f' => 'Non Aktif');
                    $data['navs'] = $this->dataset_db->buildNav(0);
                    $tabs = $this->session->userdata('tabs');
                    if (!$tabs)
                        $tabs = array();
                    $tabs['mod_module'] = $this->dataset_db->getModule('mod_module');
                    $this->session->set_userdata('tabs', $tabs);
                    $data['current_tab'] = $tabs['mod_module']['link'];
                    $data['content'] = $this->load->view('module_edit', $data, true);
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

    public function module_edit() {
        $this->myauth->has_role();

        $this->form_validation->set_rules('id', 'id', 'required|xss_clean');
        $this->form_validation->set_rules('modules', 'modules', 'required|xss_clean');
        $this->form_validation->set_rules('icon', 'icon', 'required|xss_clean');
        $this->form_validation->set_rules('parent', 'parent', 'required|xss_clean');
        $this->form_validation->set_rules('publish', 'publish', 'required|xss_clean');
        $this->form_validation->set_rules('link', 'link', 'required|xss_clean');
        $this->form_validation->set_rules('sort', 'sort', 'required|xss_clean');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $field["modules"] = $this->input->post("modules");
            $field["module_icon"] = $this->input->post("icon");
            $field["parent"] = $this->input->post("parent");
            $field["publish"] = $this->input->post("publish");
            $field["link"] = $this->input->post("link");
            $field["sort"] = $this->input->post("sort");
            $update = $this->module_model->update($field, $id);

            if ($update) {
                $data['success'] = '<p>Data Berhasil Disimpan</p>';
                $data['redirect'] = base_url() . 'mod_module';
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
    
    public function module_icon() {
        if (IS_AJAX) {
            $temp_result = array();
            foreach ($this->site_library->getIcons() as $row) {
                $temp_result[] = array(
                    "image" => "",
                    "description" => "",
                    "value" => $row["icon_id"],
                    "text" => $row["icon_value"] . " " . $row["icon_name"]
                );
            }
            echo json_encode($temp_result);
        } else {
            redirect('forbidden');
        }
    }
}
