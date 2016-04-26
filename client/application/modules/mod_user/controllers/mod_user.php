<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_user extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        $method = $this->uri->rsegment(2);
        if (($method != 'user_auth' AND $method != 'user_logout') AND !$this->myauth->logged_in()) {
            if (IS_AJAX) {
                header('HTTP/1.1 401 Unauthorized');
                exit;
            } else {
                $this->session->set_userdata('redir', current_url());
                redirect('mod_user/user_auth');
            }
        }
        $this->load->model('user_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
        $this->load->library('encrypt');
    }

    public function index() {
        $this->myauth->has_role();

        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_user", "form_user_list", "cus-application", "List Data User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_user/form_user_add", "form_user_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_user_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", "#", "form_user_export_xls", "cus-page-white-excel", "Export Data Ke Excel", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_user_export_pdf", "cus-page-white-acrobat", "Export Data Ke PDF", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $DataModel = array(
            array(
                'text' => 'Username',
                'value' => 'text:LOWER(username)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "=", "!=")
            ),
            array(
                'text' => 'Nama Lengkap',
                'value' => 'text:LOWER(fullname)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "=", "!=")
            ),
            array(
                'text' => 'Group Akses',
                'value' => 'text:LOWER(nama_group)',
                'type' => 'custom',
                'callBack' => 'getGroupdata',
                'ops' => array("like", "=", "!=")
            ),
            array(
                'text' => 'Group Data',
                'value' => 'text:LOWER(unit_kerja)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("like", "=", "!=")
            )
        );

        $defaultvalue = array();

        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['ptitle'] = "Manajemen User";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_user'] = $this->dataset_db->getModule('mod_user');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_user']['link'];
        $data['content'] = $this->load->view('user_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function form_user_add() {
        $this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_user", "form_user_list", "cus-application", "List Data User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_user/form_user_add", "form_user_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_user_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", "#", "form_user_export_xls", "cus-page-white-excel", "Export Data Ke Excel", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_user_export_pdf", "cus-page-white-acrobat", "Export Data Ke PDF", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['showerrors'] = validation_errors();
        $data['ptitle'] = "Manajemen User / Add User";
        $data['id_group'] = $this->dataset_db->getGroups();
        $data['active'] = array('t' => "Aktif", 'f' => 'Non Aktif');
        $data['is_proyek'] = array('t' => "Proyek", 'f' => 'Non Proyek');
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
        $tabs['mod_user'] = $this->dataset_db->getModule('mod_user');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_user']['link'];
        $data['content'] = $this->load->view('user_add', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function user_add() {
        $this->myauth->has_role();
        $this->form_validation->set_rules('id_group', 'id_group', 'required|xss_clean');
        $this->form_validation->set_rules('is_proyek', 'is_proyek', 'required|xss_clean');
        $this->form_validation->set_rules('id_relasi', 'id_relasi', 'required|xss_clean');
        $this->form_validation->set_rules('active', 'active', 'required|xss_clean');
        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|callback_check_username|min_length[6]');
        $this->form_validation->set_rules('password', 'password', 'required|xss_clean|matches[repassword]|min_length[6]');
        $this->form_validation->set_rules('repassword', 'repassword', 'required|xss_clean|matches[password]|min_length[6]');
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Alamat Email', 'required|valid_email|callback_check_email');

        if ($this->form_validation->run() == TRUE) {
            $user["id_group"] = $this->input->post("id_group");
            $user["is_proyek"] = $this->input->post("is_proyek");
            $user["id_relasi"] = $this->input->post("id_relasi");
            $user["username"] = $this->input->post("username");
            $user["password"] = $this->encrypt->sha1($this->input->post("password") . $this->config->item('encryption_key'));
            $user['created'] = date("Y-m-d H:i:s");
            $user['active'] = $this->input->post('active');
            $user['enabled'] = $this->input->post('enabled');
            $insert = $this->user_model->insert_users($user);

            $userdata["user_id"] = $insert;
            $userdata["fullname"] = $this->input->post("fullname");
            $userdata["email"] = $this->input->post("email");
            $this->user_model->insert_userdata($userdata);

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

    public function user_view($id = false) {
        $this->myauth->has_role();
        try {
            if ($id) {
                $cek = isInteger($id);
                if ($cek) {
                    $this->toolbar->create_toolbar();
                    $this->toolbar->cGroupButton();
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_user", "form_user_list", "cus-application", "List Data User", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_user/form_user_add", "form_user_new", "cus-application-form-add", "Tambah User", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_user_delete", "cus-application-form-delete", "Delete User", "tooltip", "right");
                    $this->toolbar->eGroupButton();
                    $this->toolbar->cGroupButton();
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_user_export_xls", "cus-page-white-excel", "Export Data Ke Excel", "tooltip", "right");
                    $this->toolbar->addLink("", "btn tooltips", "#", "form_user_export_pdf", "cus-page-white-acrobat", "Export Data Ke PDF", "tooltip", "right");
                    $this->toolbar->eGroupButton();
                    $data['toolbars'] = $this->toolbar->generate();

                    $data['detail'] = $this->user_model->get($id);
                    $data['ptitle'] = "Manajemen User / Edit User";
                    $data['id_group'] = $this->dataset_db->getGroups();
                    $data['active'] = array('t' => "Aktif", 'f' => 'Non Aktif');
                    $data['is_proyek'] = array('t' => "Proyek", 'f' => 'Non Proyek');
                    $data['navs'] = $this->dataset_db->buildNav(0);
                    $tabs = $this->session->userdata('tabs');
                    if (!$tabs)
                        $tabs = array();
                    $tabs['mod_user'] = $this->dataset_db->getModule('mod_user');
                    $this->session->set_userdata('tabs', $tabs);
                    $data['current_tab'] = $tabs['mod_user']['link'];
                    $data['content'] = $this->load->view('user_edit', $data, true);
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

    public function user_edit($id = false) {

        if (!$this->session->userdata('ba_username')) {
            header('HTTP/1.1 401 Unauthorized');
        } else {
            if (IS_AJAX) {
                $this->myauth->has_role();
                $this->form_validation->set_rules('id', 'id', 'required|xss_clean');
                $this->form_validation->set_rules('userdata_id', 'userdata_id', 'required|xss_clean');
                $this->form_validation->set_rules('id_group', 'id_group', 'required|xss_clean');
                $this->form_validation->set_rules('is_proyek', 'is_proyek', 'required|xss_clean');
                $this->form_validation->set_rules('id_relasi', 'id_relasi', 'required|xss_clean');
                $this->form_validation->set_rules('active', 'active', 'required|xss_clean');
                $this->form_validation->set_rules('username', 'Username', 'required|xss_clean');
                $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'required|xss_clean');
                $this->form_validation->set_rules('email', 'Alamat Email', 'required|valid_email');
                if ($this->form_validation->run() == TRUE) {
                    $id = $this->input->post("id");
                    $user["id_group"] = $this->input->post("id_group");
                    $user["is_proyek"] = $this->input->post("is_proyek");
                    $user["id_relasi"] = $this->input->post("id_relasi");
                    $user["username"] = $this->input->post("username");
                    $user['modified'] = date("Y-m-d H:i:s");
                    $user['active'] = $this->input->post('active');
                    $user['enabled'] = $this->input->post('enabled');
                    $update = $this->user_model->update_user($user, $id);
                    $userdata_id = $this->input->post("userdata_id");
                    $userdata["user_id"] = $update;
                    $userdata["fullname"] = $this->input->post("fullname");
                    $userdata["email"] = $this->input->post("email");
                    $this->user_model->update_userdata($userdata, $userdata_id);

                    if ($update) {
                        $data['redirect'] = base_url() . 'mod_user';
                        $data['success'] = '<p>Data Berhasil Diupdate</p>';
                        $json['json'] = $data;
                        $this->load->view('template/ajax', $json);
                    } else {
                        $data['error'] = '<p>Data Gagal Diupdate</p>';
                        $json['json'] = $data;
                        $this->load->view('template/ajax', $json);
                    }
                } else {
                    $data['error'] = validation_errors();
                    $json['json'] = $data;
                    $this->load->view('template/ajax', $json);
                }
            } else {
                redirect('forbidden');
            }
        }
    }

    public function user_json() {

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
                $sidx = !empty($sidx) ? $sidx : "username";
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
                $query = $this->user_model->getAll($limit, $offset, $sidx, $sord, $cari, $search);
                $count = $this->user_model->countAll();

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
                    $responce['rows'][$i]['id'] = $row['user_id'];
                    $responce['rows'][$i]['cell'] = array($row['user_id'], $row['username'], $row['fullname'], $row['nama_group'], $row['unit_kerja'], '<a class="link_edit" href="' . base_url() . 'mod_user/user_view/' . $row['user_id'] . '"><img src="' . base_url() . 'media/edit.png" /></a>');
                    $i++;
                }
                echo json_encode($responce);
            } else {
                redirect('forbidden');
            }
        }
    }

    public function getUnitProyek() {
        if (!$this->session->userdata('ba_username')) {
            header('HTTP/1.1 401 Unauthorized');
        } else {
            if (IS_AJAX) {
                $this->myauth->has_role();
                $id = $this->input->post("id");
                $unitkerja = $this->user_model->getUnitkerja();
                $proyek = $this->user_model->getProyek();

                switch ($id) {
                    default:
                        $data['label'] = 'Group Data';
                        $data['data'] = $unitkerja;
                        echo json_encode($data);
                        break;

                    case "t":
                        $data['label'] = 'List Proyek';
                        $data['data'] = $proyek;
                        echo json_encode($data);
                        break;
                }
            } else {
                redirect('forbidden');
            }
        }
    }

    public function check_username($username) {
        if ($this->user_model->check_username($username)) {
            $this->form_validation->set_message('check_username', "Username " . $username . " Telah Terdaftar, Pilih Yang Lain.");
            return false;
        } else {
            return true;
        }
    }

    public function check_email($email) {
        if ($this->user_model->check_email($email)) {
            $this->form_validation->set_message('check_email', "Email " . $email . " Telah Terdaftar, Pilih Yang Lain.");
            return false;
        } else {
            return true;
        }
    }

    public function login() {
        $data['mod'] = 'sec_users/';
        $data['company'] = "";
        $this->load->view('login', $data);
    }

    public function user_auth() {
        $this->form_validation->set_rules('username', 'Username', 'required|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|alpha_numeric');
        $this->form_validation->set_rules('remember_me', 'Remember Me', '');

        if ($this->form_validation->run() == TRUE) {

            $username = $this->input->post('username');
            $password = $this->input->post('password');

            if ($this->myauth->login($username, $password)) {
                redirect('main');
            } else {
                $data['error'] = $this->myauth->get_error();
            }
        } else {
            $data['error'] = validation_errors();
        }

        $data['ptitle'] = "Login";
        $this->load->view('user_login', $data);
    }

    public function user_delete() {
        $id = $this->input->post('id');
        $this->user_model->delete($id);
    }

    public function user_logout() {
        $this->dataset_db->insert_logs(
			array(
				'log_username' => $this->session->userdata('ba_username'),
				'log_node' => $_SERVER['REMOTE_ADDR'],
				'log_description' => 'User berhasil logout',
				'log_params' => null
			)
		);
        $this->session->sess_destroy();
        redirect('mod_user/user_auth');
    }

    public function user_changepass() {
        $data['id'] = $_GET["id"];
        $data['content'] = $this->load->view('user_changepass', $data, true);
        $this->load->vars($data);
        $this->load->view('default_picker');
    }

    public function act_userchangepass() {
        $this->form_validation->set_rules("password", "Password", "required|xss_clean|matches[repassword]|min_length[6]");
        $this->form_validation->set_rules("repassword", "Re Password", "required|xss_clean|matches[password]|min_length[6]");

        if ($this->form_validation->run() == TRUE) {
            $user_id = $this->input->post("id");
            $user["password"] = $this->encrypt->sha1($this->input->post("password") . $this->config->item('encryption_key'));

            $this->user_model->update_user($user, $user_id);


            $data['success'] = 'Password Berhasil Diganti';
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    /**
     * Mengambil nilai dari domain yang aktif
     *
     * @return JSON
     */
    public function getdomain() {
        $configuser = $this->user_model->getConfigUser($this->session->userdata('ba_user_id'));
        $temp_result = $this->user_model->getDomain($configuser["kolom2"]);

        $data['domain'] = $temp_result;
        $json['json'] = $data;
        $this->load->view('template/ajax', $json);
    }

    public function gantidomain() {
        $data['level'] = $this->session->userdata('ba_unit_kerja');
        $data['unitkerja'] = $this->dataset_db->getSubUnitkerja();
        $data['content'] = $this->load->view('popup_gantidomain', $data, true);
        $this->load->vars($data);
        $this->load->view('default_picker');
    }

    public function act_gantidomain() {
        $this->form_validation->set_rules("unitkerja", "unitkerja", "required");
        $this->form_validation->set_rules("subunit_proyek", "subunit_proyek", "required");

        if ($this->form_validation->run() == TRUE) {

            $unitkerja = $this->input->post("unitkerja");
            $subunit_proyek = $this->input->post("subunit_proyek");

            $field = array();
            $cek = $this->user_model->getConfigUser($this->session->userdata('ba_user_id'));
            if (!empty($cek)) {
                $field["user_id"] = $this->session->userdata('ba_user_id');
                $field["kolom1"] = $unitkerja;
                $field["kolom2"] = $subunit_proyek;
                $field["kolom3"] = "";
                $field["kolom4"] = "";

                $this->user_model->UpdateConfigUser($field, $cek["id_userconfig"]);
                $data['success'] = "<p>Data Berhasil Di Simpan " . $cek["id_userconfig"] . "</p>";
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $field["user_id"] = $this->session->userdata('ba_user_id');
                $field["kolom1"] = $unitkerja;
                $field["kolom2"] = $subunit_proyek;
                $field["kolom3"] = "";
                $field["kolom4"] = "";

                $this->user_model->UpdateConfigUser($field);
                $data['success'] = "<p>Data Berhasil Di Simpan</p>";
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function getDataProyek() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $proyek = $this->dataset_db->getDataProyek($id);
            foreach ($proyek as $key => $value) {
                echo "<option value=\"" . $key . "\">" . $value . "</option>";
            }
        }
    }

}
