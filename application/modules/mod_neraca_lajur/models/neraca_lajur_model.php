<?php

class neraca_lajur_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    function getAll($id_proyek,$periode) {
        
		// modified by asep on 22/05/2014 14:56 getneracalajurdetail / getneracalajur
		
		$query = $this->db->query("select * from getneracalajur($id_proyek,$periode) where 
									nl_debet_lalu::numeric != 0
									or nl_kredit_lalu::numeric != 0
									or nl_debet_skrg::numeric != 0
									or nl_kredit_skrg::numeric != 0
									or nl_debet_akhir::numeric != 0
									or nl_kredit_akhir::numeric != 0
									or nl_debet_lr::numeric != 0
									or nl_kredit_lr::numeric != 0
									or nl_debet_nrc::numeric != 0
									or nl_kredit_nrc::numeric != 0");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id'	 		=> $row['nl_nomor'],
						'kode'	 		=> $row['nl_kode'],
						'uraian' 		=> $row['nl_uraian'],
						'debet_lalu'	=> myFormatMoney($row['nl_debet_lalu']),
						'kredit_lalu'	=> myFormatMoney($row['nl_kredit_lalu']),
						'debet_skrg'	=> myFormatMoney($row['nl_debet_skrg']),
						'kredit_skrg'	=> myFormatMoney($row['nl_kredit_skrg']),
						'debet_akhir'	=> myFormatMoney($row['nl_debet_akhir']),
						'kredit_akhir'	=> myFormatMoney($row['nl_kredit_akhir']),
						'debet_lr'		=> myFormatMoney($row['nl_debet_lr']),
						'kredit_lr'		=> myFormatMoney($row['nl_kredit_lr']),
						'debet_nrc'		=> myFormatMoney($row['nl_debet_nrc']),
						'kredit_nrc'	=> myFormatMoney($row['nl_kredit_nrc']),
						'level'	 		=> $row['nl_level'],
						'parent'	 	=> $row['nl_parent'],
						'isLeaf' 		=> $row['nl_leaf'],
						'expanded' 		=> strtolower('TRUE'),
						'loaded' 		=> strtolower('TRUE')
					);
		}	
			
		//echo "<pre>";
		//print_r($tmp_result);
		//echo "</pre>";	
        return $tmp_result;
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan laporan neraca lajur',
											'log_params' => json_encode(array('id_proyek' => $id_proyek, 'periode' => $periode))
										)
									  );
	}	
	
	function getKonsolidasi($konsolidasi,$year,$interval) {
        
		// modified by asep getneracalajurdetail / getneracalajur konsolidasi
		if($konsolidasi == 9999){
			$query = $this->db->query("select * from getneracalajurkonsolidasiperusahaan($year,$interval) where 
									nl_nomor::numeric = 0 
									or nl_debet_lalu::numeric != 0
									or nl_kredit_lalu::numeric != 0
									or nl_debet_skrg::numeric != 0
									or nl_kredit_skrg::numeric != 0
									or nl_debet_akhir::numeric != 0
									or nl_kredit_akhir::numeric != 0
									or nl_debet_lr::numeric != 0
									or nl_kredit_lr::numeric != 0
									or nl_debet_nrc::numeric != 0
									or nl_kredit_nrc::numeric != 0");
		} else {
			$query = $this->db->query("select * from getneracalajurkonsolidasi($konsolidasi,$year,$interval) where 
									nl_nomor::numeric = 0 
									or nl_debet_lalu::numeric != 0
									or nl_kredit_lalu::numeric != 0
									or nl_debet_skrg::numeric != 0
									or nl_kredit_skrg::numeric != 0
									or nl_debet_akhir::numeric != 0
									or nl_kredit_akhir::numeric != 0
									or nl_debet_lr::numeric != 0
									or nl_kredit_lr::numeric != 0
									or nl_debet_nrc::numeric != 0
									or nl_kredit_nrc::numeric != 0");
		}
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id'	 		=> $row['nl_nomor'],
						'kode'	 		=> $row['nl_kode'],
						'uraian' 		=> $row['nl_uraian'],
						'debet_lalu'	=> myFormatMoney($row['nl_debet_lalu']),
						'kredit_lalu'	=> myFormatMoney($row['nl_kredit_lalu']),
						'debet_skrg'	=> myFormatMoney($row['nl_debet_skrg']),
						'kredit_skrg'	=> myFormatMoney($row['nl_kredit_skrg']),
						'debet_akhir'	=> myFormatMoney($row['nl_debet_akhir']),
						'kredit_akhir'	=> myFormatMoney($row['nl_kredit_akhir']),
						'debet_lr'		=> myFormatMoney($row['nl_debet_lr']),
						'kredit_lr'		=> myFormatMoney($row['nl_kredit_lr']),
						'debet_nrc'		=> myFormatMoney($row['nl_debet_nrc']),
						'kredit_nrc'	=> myFormatMoney($row['nl_kredit_nrc']),
						'level'	 		=> $row['nl_level'],
						'parent'	 	=> $row['nl_parent'],
						'isLeaf' 		=> $row['nl_leaf'],
						'expanded' 		=> strtolower('TRUE'),
						'loaded' 		=> strtolower('TRUE')
					);
		}	
			
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan laporan neraca lajur konsolidasi',
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
	
	public function getAllExcel($id_proyek,$periode) {
        // modified by asep on 22/05/2014 14:56
		
		$query = $this->db->query("select * from getneracalajur($id_proyek,$periode) where 
								nl_debet_lalu::numeric != 0
								or nl_kredit_lalu::numeric != 0
								or nl_debet_skrg::numeric != 0
								or nl_kredit_skrg::numeric != 0
								or nl_debet_akhir::numeric != 0
								or nl_kredit_akhir::numeric != 0
								or nl_debet_lr::numeric != 0
								or nl_kredit_lr::numeric != 0
								or nl_debet_nrc::numeric != 0
								or nl_kredit_nrc::numeric != 0");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'kode'	 		=> $row['nl_kode'],
						'uraian' 		=> $row['nl_uraian'],
						'debet_lalu'	=> myFormatMoney($row['nl_debet_lalu']),
						'kredit_lalu'	=> myFormatMoney($row['nl_kredit_lalu']),
						'debet_skrg'	=> myFormatMoney($row['nl_debet_skrg']),
						'kredit_skrg'	=> myFormatMoney($row['nl_kredit_skrg']),
						'debet_akhir'	=> myFormatMoney($row['nl_debet_akhir']),
						'kredit_akhir'	=> myFormatMoney($row['nl_kredit_akhir']),
						'debet_lr'		=> myFormatMoney($row['nl_debet_lr']),
						'kredit_lr'		=> myFormatMoney($row['nl_kredit_lr']),
						'debet_nrc'		=> myFormatMoney($row['nl_debet_nrc']),
						'kredit_nrc'	=> myFormatMoney($row['nl_kredit_nrc'])
					);
		}	
			
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh laporan neraca lajur',
											'log_params' => json_encode(array('id_proyek' => $id_proyek, 'periode' => $periode))
										)
									  );
		//echo "<pre>";
		//print_r($tmp_result);
		//echo "</pre>";	
        return $tmp_result;
    }
    
    public function getKonsolidasiForExcel($konsolidasi,$year,$interval) {
        // modified by asep on 22/05/2014 14:56
		
		// modified by asep getneracalajurdetail / getneracalajur konsolidasi
		if($konsolidasi == 9999){
			$query = $this->db->query("select * from getneracalajurkonsolidasiperusahaan($year,$interval) where 
									nl_nomor::numeric = 0 
									or nl_debet_lalu::numeric != 0
									or nl_kredit_lalu::numeric != 0
									or nl_debet_skrg::numeric != 0
									or nl_kredit_skrg::numeric != 0
									or nl_debet_akhir::numeric != 0
									or nl_kredit_akhir::numeric != 0
									or nl_debet_lr::numeric != 0
									or nl_kredit_lr::numeric != 0
									or nl_debet_nrc::numeric != 0
									or nl_kredit_nrc::numeric != 0");
		} else {
			$query = $this->db->query("select * from getneracalajurkonsolidasi($konsolidasi,$year,$interval) where 
									nl_nomor::numeric = 0 
									or nl_debet_lalu::numeric != 0
									or nl_kredit_lalu::numeric != 0
									or nl_debet_skrg::numeric != 0
									or nl_kredit_skrg::numeric != 0
									or nl_debet_akhir::numeric != 0
									or nl_kredit_akhir::numeric != 0
									or nl_debet_lr::numeric != 0
									or nl_kredit_lr::numeric != 0
									or nl_debet_nrc::numeric != 0
									or nl_kredit_nrc::numeric != 0");
		}
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'kode'	 		=> $row['nl_kode'],
						'uraian' 		=> $row['nl_uraian'],
						'debet_lalu'	=> myFormatMoney($row['nl_debet_lalu']),
						'kredit_lalu'	=> myFormatMoney($row['nl_kredit_lalu']),
						'debet_skrg'	=> myFormatMoney($row['nl_debet_skrg']),
						'kredit_skrg'	=> myFormatMoney($row['nl_kredit_skrg']),
						'debet_akhir'	=> myFormatMoney($row['nl_debet_akhir']),
						'kredit_akhir'	=> myFormatMoney($row['nl_kredit_akhir']),
						'debet_lr'		=> myFormatMoney($row['nl_debet_lr']),
						'kredit_lr'		=> myFormatMoney($row['nl_kredit_lr']),
						'debet_nrc'		=> myFormatMoney($row['nl_debet_nrc']),
						'kredit_nrc'	=> myFormatMoney($row['nl_kredit_nrc'])
					);
		}	
			
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh laporan neraca lajur konsolidasi',
											'log_params' => json_encode(array('konsolidasi' => $konsolidasi, 'year' => $year, 'interval' => $interval))
										)
									  );
		//echo "<pre>";
		//print_r($tmp_result);
		//echo "</pre>";	
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
}
