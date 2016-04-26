<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_perioda extends CI_Controller {

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
        $this->load->model('perioda_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
    }

}
