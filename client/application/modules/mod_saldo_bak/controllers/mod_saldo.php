<?php

class mod_saldo extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('saldo_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
        $this->_userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
    }

    public function index() {

        $data['ptitle'] = "Saldo Perkiraan";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
        $data['op_yearperiode'] = $this->dataset_db->getPeriodeYear();
        $tabs['mod_saldo'] = $this->dataset_db->getModule('mod_saldo');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_saldo']['link'];
        $data['content'] = $this->load->view('periode_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

	public function perkiraan_json() {
		$this->form_validation->set_rules("period_key", "Periode", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
			$periodkey = $this->input->post("period_key");
			$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
			$proyek = $userconfig["kolom2"];
			$query = $this->saldo_model->getAll($proyek, $periodkey);
        
			$i = 0;
			foreach ($query as $key => $row) {
				$responce['rows'][$i]['id'] = $row['id'];
				$responce['rows'][$i]['cell'] = array(
													$row['id'],
													$row['perkiraan'], 
													$row['beginning'], 
													$row['debit'], 
													$row['kredit'], 
													$row['ending']
													);
				$i++;
			}
			echo json_encode($responce);
		}
    }
	
	public function edit() {
		$this->form_validation->set_rules("dperkir_id", "ID Perkiraan", "required|xss_clean");
		$this->form_validation->set_rules("period_key", "Periode", "required|xss_clean");

        if ($this->form_validation->run() == TRUE) {
            $dperkir_id = $this->input->post("dperkir_id");
            $period_key = $this->input->post("period_key");
			$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
			$proyek = $userconfig["kolom2"];
            $name = $this->input->post("name");
            switch ($name) {
                case "beginning":
                    $field["trialbal_beginning"] = $this->input->post("val");
                    break;
                case "debit":
                    $field["trialbal_debits"] = $this->input->post("val");
                    $field["trialbal_ending"] = $this->input->post("val");
                    break;
                case "kredit":
                    $field["trialbal_credits"] = $this->input->post("val");
                    $field["trialbal_ending"] = ($this->input->post("val") * -1);
                    break;
                case "ending":
                    $field["trialbal_ending"] = $this->input->post("val");
                    break;
            }
			$exist = $this->saldo_model->cek($proyek, $dperkir_id, $period_key);
			if($exist){
				$exec = $this->saldo_model->update($field, $proyek, $dperkir_id, $period_key);
			} else {
				$field["id_proyek"] = $proyek;
				$field["trialbal_dperkir_id"] = $dperkir_id;
				$field["trialbal_period_key"] = $period_key;
				$exec = $this->saldo_model->insert($field);
			}
            if ($exec) {
                $data['success'] = "<p>Data Berhasil Dimasukan</p>";
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $data['error'] = "<p>Data Gagal Dimasukan</p>";
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
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
