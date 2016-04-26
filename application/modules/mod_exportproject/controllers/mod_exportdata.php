<?php

class mod_exportdata extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('exportdata_model');
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
        $data['ptitle'] = "Export Data";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['op_yearperiode'] = $this->dataset_db->getPeriodeYear();
        $tabs['mod_exportdata'] = $this->dataset_db->getModule('mod_exportdata');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_exportdata']['link'];
        $data['content'] = $this->load->view('exportdata_list', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }
	
	public function getDataExport(){
		$this->form_validation->set_rules("periode", "Periode", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
            $periodkey = $this->input->post("periode");
			$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
			$proyek = $userconfig["kolom2"];
			$proyekname = $this->exportdata_model->getProyekName($proyek);
			$periodname = $this->exportdata_model->getPeriodName($periodkey);
			$this->load->library('zip');
			$dataex = $this->exportdata_model->getAll($proyekname,$proyek,$periodkey);
			$string = json_encode($dataex);
			//die(var_dump(json_decode($string,true)));
			/*
			# --- ENCRYPTION ---

			# the key should be random binary, use scrypt, bcrypt or PBKDF2 to
			# convert a string into a key
			# key is specified using hexadecimal
			$key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
			
			# show key size use either 16, 24 or 32 byte keys for AES-128, 192
			# and 256 respectively
			$key_size =  strlen($key);
			//echo "Key size: " . $key_size . "\n";

			# create a random IV to use with CBC encoding
			$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
			$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
			
			# creates a cipher text compatible with AES (Rijndael block size = 128)
			# to keep the text confidential 
			# only suitable for encoded input that never ends with value 00h
			# (because of default zero padding)
			$ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
										 $string, MCRYPT_MODE_CBC, $iv);

			# prepend the IV for it to be available for decryption
			$ciphertext = $iv . $ciphertext;
			
			# encode the resulting cipher text so it can be represented by a string
			$ciphertext_base64 = base64_encode($ciphertext);

			//echo  $ciphertext_base64 . "\n";
			*/
			$name = 'data.abipraya';

			$this->zip->add_data($name, $string);

			// Download the file to your desktop. Name it "my_backup.zip"
			$this->zip->download(url_title($proyekname,'underscore').'_'.url_title($periodname,'underscore').'_backup.zip');  
			
			/*
			# --- DECRYPTION ---
		
			$ciphertext_dec = base64_decode($ciphertext_base64);
			
			# retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
			$iv_dec = substr($ciphertext_dec, 0, $iv_size);
			
			# retrieves the cipher text (everything except the $iv_size in the front)
			$ciphertext_dec = substr($ciphertext_dec, $iv_size);

			# may remove 00h valued characters from end of plain text
			$plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
											$ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
			
			echo  $plaintext_dec . "\n";
			*/
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
