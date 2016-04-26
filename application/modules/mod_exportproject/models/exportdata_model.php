<?php

class exportdata_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    public function getAll($kode_proyek, $id_proyek, $period_key) {
        
        $tmp = array();
        
        $tmp['kode_proyek'] = $kode_proyek;
        $tmp['yearperiod'] = $this->getYearPeriod($id_proyek, $period_key);
        $tmp['tbl_jurnal'] = $this->getJurnal($id_proyek, $period_key);
        $tmp['tbl_bukubantu'] = $this->getBukuBantu($id_proyek);
        $tmp['saldo_awal_detail'] = $this->getSaldoAwalDetail($id_proyek, $period_key);
        $tmp['saldo_perekanan'] = $this->getSaldoPerekanan($id_proyek, $period_key);
        $tmp['trialbal'] = $this->getTrialbal($id_proyek, $period_key);
        $tmp['tbl_rekanan'] = $this->getRekanan($id_proyek);
        $tmp['tbl_sbdaya'] = $this->getSBDaya($id_proyek);
        //die(print_r($tmp));
        return $tmp;
	}	
    
    private function getPeriod($id_proyek, $period_key){
		$tmp_result = array();
		
		$this->db->select("*");
		$this->db->from("period");
		$this->db->where("id_proyek",$id_proyek);
		$this->db->where("period_key",$period_key);
		$query = $this->db->get();
		
		if ($query->num_rows() > 0){
			$tmp_result = $query->row_array();
		} 
		
		//die(print_r($tmp_result));
		return $tmp_result;
	}
	
	private function getYearPeriod($id_proyek, $period_key){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('yearperiod');
		
		$yearperiod_key = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("yearperiod");
		$this->db->where("id_proyek",$id_proyek);
		$this->db->where("yearperiod_key",$yearperiod_key['period_yearperiod_key']);
		$query = $this->db->get();
		$field_value = array();
		if ($query->num_rows() > 0){
			$row = $query->row_array();
			
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field;
			   $field_value[$field] = $row[$field]; 
			}
			 
			$tmp_result['values'][$row['yearperiod_id']] = $field_value;
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
    
    private function getJurnal($id_proyek, $period_key){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('tbl_jurnal'); 
		
		$period = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("tbl_jurnal");
		$this->db->where("id_proyek",$id_proyek);
		$this->db->where("tanggal >=",$period['period_start']);
		$this->db->where("tanggal <=",$period['period_end']);
		$query = $this->db->get();
		$field_value = array();
		
		if ($query->num_rows() > 0){
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field; 
			}
		
			foreach($query->result_array() as $row){
					foreach ($fields as $fieldname)
					{
						$field_value[$fieldname] = $row[$fieldname];
					}
					$tmp_result['values'][$row['id_jurnal']] = $field_value;
			}
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
    
    private function getBukuBantu($id_proyek){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('tbl_bukubantu'); 
		
		//$period = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("tbl_bukubantu");
		$this->db->where("bukubantu_id_proyek",$id_proyek);
		$query = $this->db->get();
		$field_value = array();
		
		if ($query->num_rows() > 0){
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field; 
			}
		
			foreach($query->result_array() as $row){
					foreach ($fields as $fieldname)
					{
						$field_value[$fieldname] = $row[$fieldname];
					}
					$tmp_result['values'][$row['bukubantu_id']] = $field_value;
			}
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
	
	private function getSaldoAwalDetail($id_proyek, $period_key){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('saldo_awal_detail'); 
		
		//$period = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("saldo_awal_detail");
		$this->db->where("id_proyek",$id_proyek);
		$this->db->where("period_key",$period_key);
		$query = $this->db->get();
		$field_value = array();
		
		if ($query->num_rows() > 0){
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field; 
			}
		
			foreach($query->result_array() as $row){
					foreach ($fields as $fieldname)
					{
						$field_value[$fieldname] = $row[$fieldname];
					}
					$tmp_result['values'][$row['saldo_awal_id']] = $field_value;
			}
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
	
	private function getSaldoPerekanan($id_proyek, $period_key){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('saldo_perekanan'); 
		
		//$period = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("saldo_perekanan");
		$this->db->where("saldo_perekanan_id_proyek",$id_proyek);
		$this->db->where("saldo_perekanan_period_key",$period_key);
		$query = $this->db->get();
		$field_value = array();
		
		if ($query->num_rows() > 0){
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field; 
			}
		
			foreach($query->result_array() as $row){
					foreach ($fields as $fieldname)
					{
						$field_value[$fieldname] = $row[$fieldname];
					}
					$tmp_result['values'][$row['saldo_perekanan_id']] = $field_value;
			}
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
	
	private function getTrialbal($id_proyek, $period_key){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('trialbal'); 
		
		//$period = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("trialbal");
		$this->db->where("id_proyek",$id_proyek);
		$this->db->where("trialbal_period_key",$period_key);
		$query = $this->db->get();
		$field_value = array();
		
		if ($query->num_rows() > 0){
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field; 
			}
		
			foreach($query->result_array() as $row){
					foreach ($fields as $fieldname)
					{
						$field_value[$fieldname] = $row[$fieldname];
					}
					$tmp_result['values'][$row['trialbal_id']] = $field_value;
			}
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
    
    private function getRekanan($id_proyek){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('tbl_rekanan'); 
		
		//$period = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("tbl_rekanan");
		$this->db->where("id_proyek",$id_proyek);
		$query = $this->db->get();
		$field_value = array();
		
		if ($query->num_rows() > 0){
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field; 
			}
		
			foreach($query->result_array() as $row){
					foreach ($fields as $fieldname)
					{
						$field_value[$fieldname] = $row[$fieldname];
					}
					$tmp_result['values'][$row['id_rekanan']] = $field_value;
			}
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
    
    private function getSBDaya($id_proyek){
		$tmp_result = array();
		
		$fields = $this->db->list_fields('tbl_sbdaya'); 
		
		//$period = $this->getPeriod($id_proyek, $period_key);
	
		$this->db->select("*");
		$this->db->from("tbl_sbdaya");
		$this->db->where("id_proyek",$id_proyek);
		$query = $this->db->get();
		$field_value = array();
		
		if ($query->num_rows() > 0){
			foreach ($fields as $field)
			{
			   $tmp_result['coloumn'][] = $field; 
			}
		
			foreach($query->result_array() as $row){
					foreach ($fields as $fieldname)
					{
						$field_value[$fieldname] = $row[$fieldname];
					}
					$tmp_result['values'][$row['id_sbdaya']] = $field_value;
			}
		}
		//die(print_r($tmp_result));
		return $tmp_result;
	}
    
    public function getBulan() {

        $this->db->select('b.id_library, b.nama_library');
        $this->db->from($this->_table['library'] . ' a');
        $this->db->join($this->_table['library'] . ' b', 'b.parent = a.id_library', 'left outer');
        $this->db->where('a.id_library = 15');
        $this->db->order_by('b.id_library', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $i = 1;
        foreach ($query->result_array() as $row) {
            $temp_result[$i++] = $row['nama_library'];
        }
        return $temp_result;
    }

	public function getProyekName($id) {

        $this->db->select('kode_proyek');
        $this->db->from('list_proyek_v');
        $this->db->where('id_proyek', $id);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['kode_proyek'];
        }
        return $result;
    }
	
	public function getPeriodName($id) {

        $this->db->select('period_name');
        $this->db->from('period');
        $this->db->where('period_key', $id);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['period_name'];
        }
        return $result;
    }
	
}
