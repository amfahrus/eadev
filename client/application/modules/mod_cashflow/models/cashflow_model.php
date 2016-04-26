<?php

class cashflow_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    function getAll($id_proyek,$yearperiod_key) {
		
		$query = $this->db->query("select * from getcashflow($id_proyek,$yearperiod_key)");
		
        $tmp_result = array();
		$i = 0;
		foreach($query->result_array() as $row){
			$i++;
			$tmp_result[] = array(
						'id' 			=> $i,
						'nomor' 		=> $row['cashflow_nomor'],
						'uraian' 		=> $row['cashflow_uraian'],
						'akp'			=> $row['cashflow_akp'],
						'sd_lalu'		=> $row['cashflow_ri_sd_lalu'],
						'ri_1'			=> $row['cashflow_ri_1'],
						'ri_2'			=> $row['cashflow_ri_2'],
						'ri_3'			=> $row['cashflow_ri_3'],
						'ri_4'			=> $row['cashflow_ri_4'],
						'ri_5'			=> $row['cashflow_ri_5'],
						'ri_6'			=> $row['cashflow_ri_6'],
						'ri_s1'			=> $row['cashflow_ri_s1'],
						'ri_7'			=> $row['cashflow_ri_7'],
						'ri_8'			=> $row['cashflow_ri_8'],
						'ri_9'			=> $row['cashflow_ri_9'],
						'ri_10'			=> $row['cashflow_ri_10'],
						'ri_11'			=> $row['cashflow_ri_11'],
						'ri_12'			=> $row['cashflow_ri_12'],
						'ri_s2'			=> $row['cashflow_ri_s2'],
						'ri_total'			=> $row['cashflow_ri_total'],
						'ri_grandtotal'			=> $row['cashflow_ri_grandtotal'],
						'level'			=> $row['cashflow_levels'],
						'parent' 		=> $row['cashflow_parents'],
						'isLeaf' 		=> TRUE,
						'expanded' 		=> TRUE,
						'loaded' 		=> TRUE
					);
		}	
			
		//echo "<pre>";
		//print_r($tmp_result);
		//echo "</pre>";	
        return $tmp_result;
	}	

    public function countAll() {
        return $this->_countAll;
    }
	
	public function getAllForExcel($id_proyek, $periode) {
       $query = $this->db->query("select * from getcashflow($id_proyek,$periode)");
		
        $tmp_result = array();
		$i = 0;
		foreach($query->result_array() as $row){
			$i++;
			$tmp_result[] = array(
						//'id' 			=> $i,
						'nomor' 		=> $row['cashflow_nomor'],
						'uraian' 		=> $row['cashflow_uraian'],
						'akp'			=> $row['cashflow_akp'],
						'sd_lalu'		=> $row['cashflow_ri_sd_lalu'],
						'ri_1'			=> $row['cashflow_ri_1'],
						'ri_2'			=> $row['cashflow_ri_2'],
						'ri_3'			=> $row['cashflow_ri_3'],
						'ri_4'			=> $row['cashflow_ri_4'],
						'ri_5'			=> $row['cashflow_ri_5'],
						'ri_6'			=> $row['cashflow_ri_6'],
						'ri_s1'			=> $row['cashflow_ri_s1'],
						'ri_7'			=> $row['cashflow_ri_7'],
						'ri_8'			=> $row['cashflow_ri_8'],
						'ri_9'			=> $row['cashflow_ri_9'],
						'ri_10'			=> $row['cashflow_ri_10'],
						'ri_11'			=> $row['cashflow_ri_11'],
						'ri_12'			=> $row['cashflow_ri_12'],
						'ri_s2'			=> $row['cashflow_ri_s2'],
						'ri_total'			=> $row['cashflow_ri_total'],
						'ri_grandtotal'			=> $row['cashflow_ri_grandtotal']
					);
		}	
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
	
}
