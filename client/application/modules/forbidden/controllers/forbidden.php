<?php

class forbidden extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->load->model('Dataset_db');
    }

    public function index() {
        $data['ptitle'] = "Forbidden Access";
        $data['navs'] = $this->Dataset_db->buildNav(0);
        $data['content'] = $this->load->view('forbidden', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

}
