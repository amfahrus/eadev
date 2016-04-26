<?php

class neraca_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    function getAll($id_proyek,$periode) {
        
		// modified by asep on 22/05/2014 14:56
		
		$query = $this->db->query("select * from getneraca($id_proyek,$periode)");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id' 			=> $row['neraca_nomor'],
						'group' 		=> $row['neraca_group'],
						'uraian' 		=> $row['neraca_uraian'],
						'total'			=> myFormatMoney($row['neraca_total_ini']),
						'total_sd'		=> myFormatMoney($row['neraca_total_sd']),
						'level'			=> $row['neraca_level'],
						'parent' 		=> $row['neraca_parent'],
						'isLeaf' 		=> strtolower($row['neraca_leaf']),
						'expanded' 		=> strtolower('TRUE'),
						'loaded' 		=> strtolower('TRUE')
					);
		}	
			
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan laporan neraca',
											'log_params' => json_encode(array('id_proyek' => $id_proyek, 'periode' => $periode))
										)
									  );
		//echo "<pre>";
		//print_r($tmp_result);
		//echo "</pre>";	
        return $tmp_result;
	}	
	
	function getKonsolidasi($konsolidasi,$year,$interval) {
        
		// modified by asep on 22/05/2014 14:56
		if($konsolidasi == 9999){
			$query = $this->db->query("select * from getneracakonsolidasiperusahaan($year,$interval)");
		} else {
			$query = $this->db->query("select * from getneracakonsolidasi($konsolidasi,$year,$interval)");
		}
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id' 			=> $row['neraca_nomor'],
						'group' 		=> $row['neraca_group'],
						'uraian' 		=> $row['neraca_uraian'],
						'total'			=> myFormatMoney($row['neraca_total_ini']),
						'total_sd'		=> myFormatMoney($row['neraca_total_sd']),
						'level'			=> $row['neraca_level'],
						'parent' 		=> $row['neraca_parent'],
						'isLeaf' 		=> strtolower($row['neraca_leaf']),
						'expanded' 		=> strtolower('TRUE'),
						'loaded' 		=> strtolower('TRUE')
					);
		}	
			
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan laporan neraca konsolidasi',
											'log_params' => json_encode(array('konsolidasi' => $konsolidasi, 'year' => $year, 'interval' => $interval))
										)
									  );
			
		//echo "<pre>";
		//print_r($tmp_result);
		//echo "</pre>";	
        return $tmp_result;
	}
	
    public function countAll() {
        return $this->_countAll;
    }
	
	public function getAllForExcel($id_proyek, $periode) {
        $query = $this->db->query("select * from getneraca($id_proyek,$periode)");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						//'id' 			=> $row['neraca_nomor'],
						'uraian' 		=> repeatPrefix($row['neraca_uraian'],$row['neraca_level']),
						'total_ini'		=> myFormatMoney($row['neraca_total_ini'])
						//'total_sd'		=> $row['neraca_total_sd']
						
					);
		}	
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh laporan neraca',
											'log_params' => json_encode(array('id_proyek' => $id_proyek, 'periode' => $periode))
										)
									  );
        return $tmp_result;
    }
    
    public function getKonsolidasiForExcel($konsolidasi,$year,$interval) {
        if($konsolidasi == 9999){
			$query = $this->db->query("select * from getneracakonsolidasiperusahaan($year,$interval)");
		} else {
			$query = $this->db->query("select * from getneracakonsolidasi($konsolidasi,$year,$interval)");
		}
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						//'id' 			=> $row['neraca_nomor'],
						'uraian' 		=> repeatPrefix($row['neraca_uraian'],$row['neraca_level']),
						'total_ini'		=> myFormatMoney($row['neraca_total_ini'])
						//'total_sd'		=> $row['neraca_total_sd']
						
					);
		}	
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh laporan neraca konsolidasi',
											'log_params' => json_encode(array('konsolidasi' => $konsolidasi, 'year' => $year, 'interval' => $interval))
										)
									  );
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
    
    public function getDetail($group, $periode, $unitkerja, $proyek){
        $this->db->select('a.*');
        $this->db->from($this->_table['view_listjurnal_approved'].' a');
        $this->db->join('tbl_group_neraca b','a.kdperkiraan >= b.bawah AND a.kdperkiraan <= b.atas','left');
        $this->db->join('period c','a.id_proyek = c.id_proyek','left');
        $this->db->where('a.id_proyek',$proyek);
        $this->db->where('b.nmlama',$group);
        $this->db->where('c.period_id',$periode);
        $query = $this->db->get();
        $this->db->flush_cache();
        
		$tmp = array();
        $tmp_result = array();
        foreach ($query->result_array() as $row) {
				$tmp[$row['nobukti']]['tanggal'] 		= $row['tanggal'];
				$tmp[$row['nobukti']]['nobukti'] 		= $row['nobukti'];
				$tmp[$row['nobukti']]['kode_proyek'] 	= $row['kode_proyek'];
				$tmp[$row['nobukti']]['desc'][$row['id_jurnal']]['id_jurnal'] 	= $row['id_jurnal'];
				$tmp[$row['nobukti']]['desc'][$row['id_jurnal']]['coa'] 		= $row['coa'];
				$tmp[$row['nobukti']]['desc'][$row['id_jurnal']]['rekanan'] 	= $row['rekanan'];
				$tmp[$row['nobukti']]['desc'][$row['id_jurnal']]['keterangan'] 	= $row['keterangan'];
				$tmp[$row['nobukti']]['desc'][$row['id_jurnal']]['debit'] 		= $row['debit'];
				$tmp[$row['nobukti']]['desc'][$row['id_jurnal']]['kredit'] 		= $row['kredit'];
				}
		
        $i = 0;
		foreach($tmp as $row){
			$i++;
			$check = 0;
			foreach($row['desc'] as $val){
				//print_r($val['']);
				//$i++;
				if ($check == 0) {
					$tmp_result[] = array(
						'idnya' 		=> $i,
						'nomor' 		=> $i,
						'tanggal' 		=> $row['tanggal'],
						'nobukti' 		=> $row['nobukti'],
						//'id_jurnal' 	=> "",
						'kode_proyek' 	=> $row['kode_proyek'],
						'coa' 			=> $val["coa"],
						'rekanan' 		=> $val["rekanan"],
						'keterangan' 	=> $val["keterangan"],
						'debit' 		=> myFormatMoney($val["debit"]),
						'kredit' 		=> myFormatMoney($val["kredit"])
					);
				} else {
					$tmp_result[] = array(
						'idnya' 		=> $val["id_jurnal"],
						'nomor' 		=> "",
						'tanggal' 		=> "",
						'nobukti' 		=> "",
						//'id_jurnal' 	=> $val["id_jurnal"],
						'kode_proyek' 	=> "",
						'coa' 			=> $val["coa"],
						'rekanan' 		=> $val["rekanan"],
						'keterangan' 	=> $val["keterangan"],
						'debit' 		=> myFormatMoney($val["debit"]),
						'kredit' 		=> myFormatMoney($val["kredit"])
					);
				}
				$check++;
			}
			$tmp_result[] = array(
					'idnya' 		=> "i".$i,
				    'nomor' 		=> "",
					'tanggal' 		=> "",
					'nobukti' 		=> "",
					//'id_jurnal' 	=> "",
					'kode_proyek' 	=> "",
					'coa' 			=> "",
					'rekanan' 		=> "",
					'keterangan' 	=> "",
					'debit' 		=> "",
					'kredit' 		=> ""
				);
		}
		//print_r($tmp_result);
        return $tmp_result;
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
    
    public function getUnitName($id) {

        $this->db->select('nama_subunit');
        $this->db->from('tbl_subunitkerja');
        $this->db->where('id_subunitkerja', $id);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['nama_subunit'];
        }
        return $result;
    }
	
	public function getPeriodName($id) {

        $this->db->select('period_name');
        $this->db->from('perioda_v');
        $this->db->where('period_id', $id);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['period_name'];
        }
        return $result;
    }
    
    public function getPeriodNameByKey($id,$key) {

        $this->db->select('yearperiod_start, yearperiod_end');
        $this->db->from('periodayear_v');
        $this->db->where('id_proyek', $id);
        $this->db->where('yearperiod_key', $key);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['yearperiod_start'].' s/d '.$row['yearperiod_end'];
        }
        return $result;
    }
	
}
