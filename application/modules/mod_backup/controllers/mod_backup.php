<?php

class mod_backup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('backup_model');
        $this->load->model('dataset_db');
        $this->load->library('search_form');
    }

    public function index() {
        $toolbar_config = array(
						array(
							'list' => array(
								'tag' 	=> 'a',
								'class' => 'btn',
								'link' 	=> base_url().'mod_backup',
								'event' => '',
								'icon' 	=> 'cus-table'
							)
						)
					);

        $data['toolbar'] = $this->search_form->toolbar($toolbar_config);
        $data['tables'] = $this->backup_model->getTables();
        $data['op_yearperiode'] = $this->dataset_db->getPeriodeYear();
        $data['ptitle'] = "Backup";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['unitkerja'] = $this->dataset_db->getSubUnitkerja();
		$data['kode_proyek'] = $this->dataset_db->getDataProyek();
        $tabs['mod_backup'] = $this->dataset_db->getModule('mod_backup');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_backup']['link'];
        $data['content'] = $this->load->view('backup_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

	public function getDataProyek() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        $this->form_validation->set_rules("id_proyek", "id_proyek", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $id_proyek = $this->input->post("id_proyek");
            $proyek = $this->dataset_db->getDataProyek($id);
            foreach ($proyek as $key => $value) {
                if($id_proyek == $key) {
                    echo "<option value=\"" . $key . "\" selected>" . $value . "</option>";
                } else {
                    echo "<option value=\"" . $key . "\">" . $value . "</option>";
                }
            }
        }
    }
    
    public function getDataPeriode() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $periode = $this->dataset_db->getPeriode($id);
            foreach ($periode as $key => $value) {
                echo "<option value=\"" . $key . "\">" . $value . "</option>";
            }
        }
    }
    
    public function hashing(){
		$this->load->library('encrypt');
		$key = $this->config->item('encryption_key');
		
		$msg = 'My secret message';
		$encrypted_string = $this->encrypt->encode($msg, $key);
		echo $encrypted_string.'<br>';
		
		$decrypted_string = $this->encrypt->decode($encrypted_string, $key);
		echo $decrypted_string;
	}
	
	public function backup(){
		$this->load->library('zip');
		$this->load->library('crypt');
		$key = $this->config->item('encryption_key');	
		$table = $this->input->post('table', TRUE);
		foreach ($table as $row) {
			$sql = $this->db->query("select * from ".$row);
			$tmp[$row]	= bzcompress(serialize($sql->result_array()));
		}

		$this->zip->add_data($tmp);
		$this->zip->download('brantas'); 
		$this->zip->clear_data(); 
		
	}
	
	public function tes(){
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		echo $userconfig["kolom2"];
	}
}
