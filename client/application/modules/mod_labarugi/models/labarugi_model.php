<?php

class labarugi_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    function getAll($id_proyek,$periode) {
        
		// modified by camadi on 16/05/2014 09:56
		
		$query = $this->db->query("select * from getlabarugi($id_proyek,$periode)");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id' 			=> $row['labarugi_nomor'],
						'group' 		=> $row['labarugi_group'],
						'uraian' 		=> $row['labarugi_uraian'],
						'total_ini'		=> myFormatMoney($row['labarugi_total_ini']),
						'total_sd'		=> myFormatMoney($row['labarugi_total_sd']),
						'level'			=> $row['labarugi_level'],
						'parent' 		=> $row['labarugi_parent'],
						'isLeaf' 		=> strtolower($row['labarugi_leaf']),
						'expanded' 		=> strtolower('TRUE'),
						'loaded' 		=> strtolower('TRUE')
					);
		}	
			
		//echo "<pre>";
		//print_r($tmp_result);
		//echo "</pre>";	
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan laporan labarugi',
											'log_params' => json_encode(array('id_proyek' => $id_proyek, 'periode' => $periode))
										)
									  );
        return $tmp_result;
		
    }
	
	function getKonsolidasi($konsolidasi,$year,$interval) {
        
		// modified by asep on 22/05/2014 14:56
		if($konsolidasi == 9999){
			$query = $this->db->query("select * from getlabarugikonsolidasiperusahaan($year,$interval)");
		} else {
			$query = $this->db->query("select * from getlabarugikonsolidasi($konsolidasi,$year,$interval)");
		}
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						'id' 			=> $row['labarugi_nomor'],
						'group' 		=> $row['labarugi_group'],
						'uraian' 		=> $row['labarugi_uraian'],
						'total_ini'		=> myFormatMoney($row['labarugi_total_ini']),
						'total_sd'		=> myFormatMoney($row['labarugi_total_sd']),
						'level'			=> $row['labarugi_level'],
						'parent' 		=> $row['labarugi_parent'],
						'isLeaf' 		=> strtolower($row['labarugi_leaf']),
						'expanded' 		=> strtolower('TRUE'),
						'loaded' 		=> strtolower('TRUE')
					);
		}	
			
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan laporan labarugi konsolidasi',
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
	
	public function getAllForExcel($id_proyek,$periode) {
        /*$this->db->select('*');
        //$_table = $proyek > 0 ? $this->_table['view_labarugi'] : $this->_table['view_labarugi_konsolidasi'] ;
        $_table = $this->_table['view_labarugi'];
        $this->db->from( $_table );
        $this->db->where('id_periode', $periode);
        $this->db->where('proyek', $proyek);
        
        $query = $this->db->get();

        $i = 0;
        $j = 0;
        $sum_tot = 0;
		$sum_tot_sd = 0;
        $id_parents = '';
							  
		$ktProject	=	$proyek > 0 && in_array($unitkerja,Array("1","6","7","8","9","10"))? "PUSAT" : "" ;
		$ktProject	=	$proyek > 0 && in_array($unitkerja,Array("5"))?	"PERALATAN"	: $ktProject ;
		$ktProject	=	$proyek > 0 && in_array($unitkerja,Array("2","3"))?	"PRODUKSI" : $ktProject ;
		$ktProject	=	$proyek > 0 && in_array($unitkerja,Array("4"))?	"KHUSUS" : $ktProject ;
		
		switch ($ktProject) {
			
			case	"PUSAT"		:	
					$isi_lap	=	Array("L","N","O","P");
					$tdk_tampil	=	"";
					$kata2		=	Array("","","","","LABA (RUGI) USAHA",
										  "LABA (RUGI) SEBELUM BUNGA DAN PAJAK (EBIT)",
										  "LABA (RUGI) SEBELUM PAJAK (EBT)");
			break;
					
			case	"PERALATAN"		:	
					$isi_lap	=	Array("J","K","N","O","P");
					$tdk_tampil	=	"";
					$kata2		=	Array("HARGA POKOK PRODUKSI","",
										  "LABA (RUGI) KOTOR SEBELUM LABA (RUGI) KSO",
										  "LABA (RUGI) KOTOR SETELAH LABA (RUGI) KSO",
										  "LABA (RUGI) USAHA",
										  "LABA (RUGI) SEBELUM BUNGA DAN PAJAK (EBIT)",
										  "LABA (RUGI) SEBELUM PAJAK (EBT)");
			break;
					
			case	"PRODUKSI"		:	
					$isi_lap	=	Array("J","K","N","O","P");
					$tdk_tampil	=	"";
					$kata2		=	Array("HARGA POKOK PRODUKSI","",
										  "LABA (RUGI) KOTOR SEBELUM LABA (RUGI) KSO",
										  "LABA (RUGI) KOTOR SETELAH LABA (RUGI) KSO",
										  "LABA (RUGI) USAHA",
										  "LABA (RUGI) SEBELUM BUNGA DAN PAJAK (EBIT)",
										  "LABA (RUGI) SEBELUM PAJAK (EBT)");
			break;
					
			case	"KHUSUS"		:	
					$isi_lap	=	Array("J","K","N","O","P");
					$tdk_tampil	=	"";
					$kata2		=	Array("HARGA POKOK PRODUKSI","","",
										  "LABA (RUGI) KOTOR",
										  "LABA (RUGI) USAHA",
										  "LABA (RUGI) SEBELUM BUNGA DAN PAJAK (EBIT)",
										  "LABA (RUGI) SEBELUM PAJAK (EBT)");
			break;	
			
			default	: $isi_lap	=	Array("J","K","L","M","N","O","P");
					  $kata2	=	Array(
										"HARGA POKOK PRODUKSI","",
										"LABA (RUGI) KOTOR SEBELUM LABA (RUGI) KSO",
										"LABA (RUGI) KOTOR SETELAH LABA (RUGI) KSO",
										"LABA (RUGI) USAHA",
										"LABA (RUGI) SEBELUM BUNGA DAN PAJAK (EBIT)",
										"LABA (RUGI) SEBELUM PAJAK (EBT)"
									  );
			
			break;	
		}					  		  
		
		
		foreach($query->result_array() as $row){
			$i++;
			
		if($row['parents'] != $id_parents && $id_parents != '' && in_array($idj,$isi_lap)){
				$tmp_result[] = array(
					'uraian' 		=> $strsum,
					'total_ini'		=> $tot,
					'total_sd'		=> $tot_sd
				);
			}
			
			if($row['parents'] != $id_parents && $id_parents != ''){	
				if($idj == 'J' || $idj == 'N'){
					$sum_tot += $tot;
					$sum_tot_sd += $tot_sd;
				} else {
					$sum_tot -= $tot;
					$sum_tot_sd -= $tot_sd;
				}
				
				if($kata2[$j] != ''){
					$tmp_result[] = array(
						'uraian' 		=> $kata2[$j],
						//'total_ini'		=> $sum_tot,
						'total_ini'		=> '?',
						//'total_sd'		=> $sum_tot_sd,
						'total_sd'		=> '?',
					);
				}
				$j++;
			}
			
			if($row['parents'] == 0){
				$id_parents = $row['id_group'];
				$strsum = 'Jumlah '.$row['uraian'];
				$tot = 0;
				$tot_sd = 0;
				$idj = substr($row['nmlama'],0,1);
			}
			
			if($idj == 'J'){
				$cur = abs($row['periode_ini']);
				$sum = abs($row['sd_periode_ini']);
			} else {
				$cur = $row['periode_ini'];
				$sum = $row['sd_periode_ini'];
			}
			
			if(in_array(substr($row['nmlama'],0,1),$isi_lap)){
				$uraian = $row['levels'] == 0 ? $row['uraian'] : '   '.$row['uraian']; 
				$tmp_result[] = array(
					'uraian' 		=> $uraian,
					'total_ini'		=> $cur,
					'total_sd'		=> $sum
				);
			}
			
			$tot += $cur;
			$tot_sd += $sum;
		}
		$tmp_result[] = array(
					'uraian' 		=> $strsum,
					'total_ini'		=> $tot,
					'total_sd'		=> $tot_sd
				);
				
		$tmp_result[] = array(
					'uraian' 		=> 'LABA (RUGI) BERSIH SETELAH PAJAK (EAT)',
					'total_ini'		=> $sum_tot,
					'total_sd'		=> $sum_tot_sd
				);
		*/		
		
		$query = $this->db->query("select * from getlabarugi($id_proyek,$periode)");
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						//'id' 			=> $row['labarugi_nomor'],
						'uraian' 		=> repeatPrefix($row['labarugi_uraian'],$row['labarugi_level']),
						'total_ini'		=> myFormatMoney($row['labarugi_total_ini'])
						//'total_sd'		=> $row['labarugi_total_sd']
						
					);
		}	
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh laporan labarugi',
											'log_params' => json_encode(array('id_proyek' => $id_proyek, 'periode' => $periode))
										)
									  );
        return $tmp_result;
    }
    
    public function getKonsolidasiForExcel($konsolidasi,$year,$interval) {
        if($konsolidasi == 9999){
			$query = $this->db->query("select * from getlabarugikonsolidasiperusahaan($year,$interval)");
		} else {
			$query = $this->db->query("select * from getlabarugikonsolidasi($konsolidasi,$year,$interval)");
		}
		
        $tmp_result = array();
		
		foreach($query->result_array() as $row){
			$tmp_result[] = array(
						//'id' 			=> $row['labarugi_nomor'],
						'uraian' 		=> repeatPrefix($row['labarugi_uraian'],$row['labarugi_level']),
						'total_ini'		=> myFormatMoney($row['labarugi_total_ini'])
						//'total_sd'		=> $row['labarugi_total_sd']
						
					);
		}	
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh laporan labarugi konsolidasi',
											'log_params' => json_encode(array('konsolidasi' => $konsolidasi, 'year' => $year, 'interval' => $interval))
										)
									  );
        return $tmp_result;
    }
    
     public function getDetail($group, $periode, $unitkerja, $proyek){
        $this->db->select('a.*');
        $this->db->from($this->_table['view_listjurnal_approved'].' a');
        $this->db->join('tbl_group_labarugi b','a.kdperkiraan >= b.bawah AND a.kdperkiraan <= b.atas','left');
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
