<?php

class mod_importdata extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('importdata_model');
        $this->load->model('dataset_db');
        $this->load->library('search_form');
    }

    public function index() {
		/*$this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url() . "mod_neraca", "form_neraca_list", "cus-table", "Laporan Neraca", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "javascript:void(0);", "form_neraca_excel", "cus-page-excel", "To Excel", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbar'] = $this->toolbar->generate();
        */
        $data['ptitle'] = "Import Data";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['op_yearperiode'] = $this->dataset_db->getPeriodeYear();
        $tabs['mod_importdata'] = $this->dataset_db->getModule('mod_importdata');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_importdata']['link'];
        $data['content'] = $this->load->view('importdata_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }
	
	public function getDataImport(){
		
		$config['upload_path'] = 'temp/';
		$config['allowed_types'] = 'zip';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());
			echo $error;
		}
		else
		{
			$upload_data = $this->upload->data();
			
			$string = json_decode(file_get_contents('zip://'.$upload_data["full_path"].'#data.abipraya'), true); 
			//print_r(json_decode($string, true));
			$this->importdata_model->upsert($string);
		}
		
	}

	public function getDataProyek() {
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        $this->form_validation->set_rules("id_proyek", "id_proyek", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $id_proyek = $this->input->post("id_proyek");
            $proyek = $this->dataset_db->getDataProyek($id);
            echo "<option value=\"0\">Konsolidasi</option>";
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
		$temp_result = array();
		$res = "<select name=\"periode\" class=\"span8\">";
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
			$id = $this->input->post("id");
            $periode = $this->dataset_db->getPeriodeKey($id);
            foreach ($periode as $key => $value) {
				$res .= "<option value=\"".$value['id']."\">".$value['desc']."</option>";
				/*$temp_result[] = array(
					'image' => "",
					'description' => $value['desc'],
					'value' => $value['id'],
					'text' => ""
				);*/
            }
        }
        $res .= "</select>";
        //echo json_encode($temp_result);
        echo $res;
    }
}
