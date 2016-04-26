<?php

class mod_restore extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            $this->session->set_userdata('redir', current_url());
            redirect('mod_user/user_auth');
        }
        $this->myauth->has_role();
        $this->load->model('restore_model');
        $this->load->model('dataset_db');
        $this->load->library('search_form');
    }

    public function index() {
        $toolbar_config = array(
						array(
							'list' => array(
								'tag' 	=> 'a',
								'class' => 'btn',
								'link' 	=> base_url().'mod_restore',
								'event' => '',
								'icon' 	=> 'cus-table'
							)
						)
					);

        $data['toolbar'] = $this->search_form->toolbar($toolbar_config);
        $data['ptitle'] = "Restore";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
		$data['unitkerja'] = $this->dataset_db->getSubUnitkerja();
		$data['kode_proyek'] = $this->dataset_db->getDataProyek();
        $tabs['mod_restore'] = $this->dataset_db->getModule('mod_restore');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_restore']['link'];
        $data['content'] = $this->load->view('restore_list', $data, true);
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
    
    public function hashing(){
		$this->load->library('encrypt');
		$key = $this->config->item('encryption_key');
		
		$msg = 'My secret message';
		$encrypted_string = $this->encrypt->encode($msg, $key);
		echo $encrypted_string.'<br>';
		
		$decrypted_string = $this->encrypt->decode($encrypted_string, $key);
		echo $decrypted_string;
	}
	
	public function restore(){
		$config['upload_path'] = './files/';
		$config['allowed_types'] = 'zip';
		$config['max_size']	= '10000000';
		
		$this->load->library('upload', $config);

		if ($this->upload->do_upload('file'))
		{
			$files = array('upload_data' => $this->upload->data());
            $file = $files['upload_data']['file_name'];
            $nama = $files['upload_data']['raw_name'];
            $type = $files['upload_data']['file_type'];
			$this->session->set_flashdata('messages', $file);
			//$string = read_file('./files/'.$file);
			$zip = zip_open('./files/'.$file);
			$this->load->library('encrypt');
			$key = $this->config->item('encryption_key');
			$data = array();
			//$i = 0;
			while($zipFile = zip_read($zip))
			{
			//$data[$i]['table'] = zip_entry_name($zipFile);
			//$data = unserialize(bzdecompress(zip_entry_read($zipFile, zip_entry_filesize($zipFile))));
			//$i++;
			$this->restore_model->InsertData(zip_entry_name($zipFile), unserialize(bzdecompress(zip_entry_read($zipFile, zip_entry_filesize($zipFile)))));
			
			zip_entry_close($zipFile);
			}
			/*
			$this->load->library('encrypt');
			$key = $this->config->item('encryption_key');	
			$decode = json_decode($this->encrypt->decode($string, $key));
			//echo $decode;
			$data = array();
			foreach($decode as $key => $value){
				/*foreach($value as $keys => $values){
					foreach($values as $keyss => $valuess){
						//$data[$key][$keys][$keyss] = $valuess;
						$data[$keys][$keyss] = $valuess;
					}
				}*/
				//$this->restore_model->InsertData($key, $value);
			//}
			/*$data = array(
			   array(
				  'title' => 'My title' ,
				  'name' => 'My Name' ,
				  'date' => 'My date'
			   ),
			   array(
				  'title' => 'Another title' ,
				  'name' => 'Another Name' ,
				  'date' => 'Another date'
			   )
			);

			$this->db->insert_batch('mytable', $data);*/
			 /*$data = array(
				   array(
					  'title' => 'My title' ,
					  'name' => 'My Name' ,
					  'date' => 'My date'
				   ),
				   array(
					  'title' => 'Another title' ,
					  'name' => 'Another Name' ,
					  'date' => 'Another date'
				   )
				);*/

			die(var_dump($data));
		}
		else
		{
			$this->session->set_flashdata('messages', $this->upload->display_errors());
		}
		redirect('mod_restore');
	}
}
