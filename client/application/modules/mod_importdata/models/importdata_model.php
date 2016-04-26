<?php

class importdata_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }
	
	public function upsert($data){
		$sql = '';
		if(is_array($data)){
			//print_r($data);
			// table name
			foreach($data as $k => $v){
				/*if (array_key_exists('coloumn', $v)){
					print_r($v['coloumn']);
				}*/
				
				//die($data['kode_proyek']);
				
				switch ($k) {
					case 'yearperiod':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getYearPeriod($v['values'],$data['kode_proyek']);
						}
						break;
					case 'tbl_proyek':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getProyek($v['values'],$data['kode_proyek']);
						}
						break;
				}
				
			}
		}
		//$this->db->query($sql);
		echo $sql;
	}
	
    private function getYearPeriod($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$id_proyek = $this->getIdProyek($kode_proyek);
				$yearperiod_key = !empty($val['yearperiod_key'])?$val['yearperiod_key']:'NULL';
				$yearperiod_start = !empty($val['yearperiod_start'])?"'".$val['yearperiod_start']."'":'NULL';
				$yearperiod_end = !empty($val['yearperiod_end'])?"'".$val['yearperiod_end']."'":'NULL';
				$yearperiod_closed = !empty($val['yearperiod_closed'])?"'".$val['yearperiod_closed']."'":'NULL';
				$create_id = !empty($val['create_id'])?$val['create_id']:'NULL';
				$create_time = !empty($val['create_time'])?"'".$val['create_time']."'":'NULL';
				$create_ip = !empty($val['create_ip'])?"'".$val['create_ip']."'":'NULL';
				$modify_id = !empty($val['modify_id'])?$val['modify_id']:'NULL';
				$modify_time = !empty($val['modify_time'])?"'".$val['modify_time']."'":'NULL';
				$modify_ip = !empty($val['modify_ip'])?"'".$val['modify_ip']."'":'NULL';
				$tmp .= "UPDATE yearperiod
					SET yearperiod_start=".$yearperiod_start.", yearperiod_end=".$yearperiod_end.", yearperiod_closed=".$yearperiod_closed.", 
						create_id=".$create_id.", create_time=".$create_time.", create_ip=".$create_ip.", 
						modify_id=".$modify_id.", modify_time=".$modify_time.", modify_ip=".$modify_ip."
					WHERE yearperiod_key=".$yearperiod_key." AND id_proyek=".$id_proyek.";
					SELECT yearperiodcreateauto($yearperiod_start, $yearperiod_end, $id_proyek);";
			}
		}
		return $tmp;
	}
	
    private function getProyek($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				//$id_proyek = $this->getIdProyek($kode_proyek);
				$kode_proyek = !empty($val['kode_proyek'])?"'".$val['kode_proyek']."'":'NULL';
				$dperkir_jenis_id = !empty($val['dperkir_jenis_id'])?$val['dperkir_jenis_id']:'NULL';
				$id_katproyek = !empty($val['id_katproyek'])?$val['id_katproyek']:'NULL';
				$id_subunitkerja = !empty($val['id_subunitkerja'])?$val['id_subunitkerja']:'NULL';
				$nama_proyek = !empty($val['nama_proyek'])?"'".$val['nama_proyek']."'":'NULL';
				$proyek_jenisproyek_id = !empty($val['nama_proyek'])?$val['nama_proyek']:'NULL';
				$tmp .= "UPDATE tbl_proyek
						SET kode_proyek=".$kode_proyek.", 
							dperkir_jenis_id=".$dperkir_jenis_id.", 
							id_katproyek=".$id_katproyek.",
							id_subunitkerja=".$id_subunitkerja.",
							nama_proyek=".$nama_proyek.",
							proyek_jenisproyek_id=".$proyek_jenisproyek_id."
						WHERE kode_proyek=".$kode_proyek.";
						INSERT INTO tbl_proyek(
							kode_proyek, dperkir_jenis_id, id_katproyek, 
							id_subunitkerja, nama_proyek, proyek_jenisproyek_id)
						   SELECT 
								".$kode_proyek.", ".$dperkir_jenis_id.", ".$id_katproyek.",
								".$id_subunitkerja.", ".$nama_proyek.", ".$proyek_jenisproyek_id."
						   WHERE NOT EXISTS (SELECT 1 FROM tbl_proyek WHERE kode_proyek=".$kode_proyek.");";
			}
		}
		return $tmp;
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

        $this->db->select('nama_proyek');
        $this->db->from('list_proyek_v');
        $this->db->where('id_proyek', $id);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['nama_proyek'];
        }
        return $result;
    }
	
	public function getIdProyek($kode_proyek) {

        $this->db->select('id_proyek');
        $this->db->from('list_proyek_v');
        $this->db->where('kode_proyek', $kode_proyek);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['id_proyek'];
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
