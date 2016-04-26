<?php

class hubrk_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }
	
	function getAll($konsolidasi,$year,$interval) {
        
		$query = $this->db->query("
		select z.* from (
		select a.id_proyek, a.nama_proyek, coalesce(abs(b.dgs_ending),0) as rk, coalesce(abs(f.saldo_perekanan_ending),0) as kp from tbl_proyek a
		left join tbl_dperkir_group_saldo b on a.id_proyek = b.id_proyek and b.dgs_period_key in (select x.period_key 
							from period x 
							where x.period_number <= $interval
							and extract(year from x.period_start) = $year 
							and extract(year from x.period_end) = $year 
							and x.id_proyek = a.id_proyek
							order by x.period_number desc 
							limit 1
							)
		left join tbl_dperkir_group c on c.dperkir_group_id = b.dgs_dperkir_group_id

		left join tbl_rekanan d on a.kode_proyek = d.kode_rekanan
		left join tbl_bukubantu e on d.kode_rekanan = e.bukubantu_kdrekanan 
		left join saldo_perekanan f on e.bukubantu_dperkir_id = f.saldo_perekanan_dperkir_id
		and f.saldo_perekanan_period_key in (select x.period_key 
							from period x 
							where x.period_number <= $interval
							and extract(year from x.period_start) = $year 
							and extract(year from x.period_end) = $year 
							and x.id_proyek = a.id_proyek
							order by x.period_number desc 
							limit 1
							) and f.saldo_perekanan_kode_rekanan = a.kode_proyek

		where a.id_subunitkerja = $konsolidasi and upper(substring(c.nmlama from 1 for 1)) = upper('R') and a.id_katproyek = 1
		) z
		where z.rk != z.kp
		");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id' 			=> $row['id_proyek'],
						'uraian' 		=> $row['nama_proyek'],
						'rk'			=> myFormatMoney($row['rk']),
						'kp'			=> myFormatMoney($row['kp'])
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
    
    public function getForExcel($konsolidasi,$year,$interval) {
        $query = $this->db->query("
		select z.* from (
		select a.id_proyek, a.nama_proyek, coalesce(abs(b.dgs_ending),0) as rk, coalesce(abs(f.saldo_perekanan_ending),0) as kp from tbl_proyek a
		left join tbl_dperkir_group_saldo b on a.id_proyek = b.id_proyek and b.dgs_period_key in (select x.period_key 
							from period x 
							where x.period_number <= $interval
							and extract(year from x.period_start) = $year 
							and extract(year from x.period_end) = $year 
							and x.id_proyek = a.id_proyek
							order by x.period_number desc 
							limit 1
							)
		left join tbl_dperkir_group c on c.dperkir_group_id = b.dgs_dperkir_group_id

		left join tbl_rekanan d on a.kode_proyek = d.kode_rekanan
		left join tbl_bukubantu e on d.kode_rekanan = e.bukubantu_kdrekanan 
		left join saldo_perekanan f on e.bukubantu_dperkir_id = f.saldo_perekanan_dperkir_id
		and f.saldo_perekanan_period_key in (select x.period_key 
							from period x 
							where x.period_number <= $interval
							and extract(year from x.period_start) = $year 
							and extract(year from x.period_end) = $year 
							and x.id_proyek = a.id_proyek
							order by x.period_number desc 
							limit 1
							) and f.saldo_perekanan_kode_rekanan = a.kode_proyek

		where a.id_subunitkerja = $konsolidasi and upper(substring(c.nmlama from 1 for 1)) = upper('R') and a.id_katproyek = 1
		) z
		where z.rk != z.kp
		");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id' 			=> $row['id_proyek'],
						'uraian' 		=> $row['nama_proyek'],
						'rk'			=> myFormatMoney($row['rk']),
						'kp'			=> myFormatMoney($row['kp'])
					);
		}	
        return $tmp_result;
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
