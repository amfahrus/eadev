<?php

class saldo_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

	public function getAll($id_proyek, $period_key) {
        $jenis = $this->getJenisProyek($id_proyek);
        $this->db->select("a.dperkir_id, a.kdperkiraan||' - '||a.nmperkiraan as perkiraan, b.trialbal_beginning, b.trialbal_debits, b.trialbal_credits, b.trialbal_ending",false);
        $this->db->from("tbl_dperkir a");
        $this->db->join("trialbal b","a.dperkir_id = b.trialbal_dperkir_id AND b.id_proyek = $id_proyek AND b.trialbal_period_key = $period_key","LEFT");
        $this->db->where("a.dperkir_jenis_id",$jenis);
        $this->db->order_by("a.kdperkiraan","ASC");
        $query = $this->db->get();

        //return $query->result_array();
        $tmp_result = array();
							  
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
					'id' 			=> $row['dperkir_id'],
					'perkiraan' 	=> $row['perkiraan'],
					'beginning' 	=> $row['trialbal_beginning'],
					'debit'		 	=> $row['trialbal_debits'],
					'kredit' 		=> $row['trialbal_credits'],
					'ending'	 	=> $row['trialbal_ending']
				);
		}		
		
        return $tmp_result;

    }
	
	public function getPeriodeByKey($key) {
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
		$this->db->from('period');
		$this->db->where('period_key',$key);
		$this->db->where('id_proyek',$proyek);
		return $this->db->get();
	}
	
	public function cek($proyek, $dperkir_id, $period_key) {
        $this->db->select("*");
        $this->db->from("trialbal");
        $this->db->where('id_proyek', $proyek);
        $this->db->where('trialbal_dperkir_id', $dperkir_id);
        $this->db->where('trialbal_period_key', $period_key);
        $sql = $this->db->get();

        if ($sql->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	public function insert($data) {
        $this->db->trans_start();
        $this->db->insert("trialbal", $data); 
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
	
	public function update($data, $proyek, $dperkir_id, $period_key) {
        $this->db->trans_start();
        $this->db->where('id_proyek', $proyek);
        $this->db->where('trialbal_dperkir_id', $dperkir_id);
        $this->db->where('trialbal_period_key', $period_key);
        $this->db->update("trialbal", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    private function getJenisProyek($id) {

        $this->db->select('dperkir_jenis_id');
        $this->db->from('tbl_proyek');
        $this->db->where('id_proyek', $id);
        $query = $this->db->get();

        $result = false;
        if($query->num_rows() > 0){
			$row = $query->row_array();
			$result = $row['dperkir_jenis_id'];
		}
        return $result;
    }
}
