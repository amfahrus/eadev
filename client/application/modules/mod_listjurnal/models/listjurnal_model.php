<?php

class listjurnal_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    function getAll($limit, $offset, $sidx, $sord, $cari, $search = "false") {
		
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
        $this->db->flush_cache();
		
        if (!is_array($cari))
            $cari = array();

        $this->db->start_cache();
        if (!empty($cari) and is_array($cari)) {
            foreach ($cari as $row) {
                if (!empty($row['cols']) AND !empty($row['ops'])) {
                    $fields = explode(":", $row['cols']);
                    switch ($row['ops']) {
                        case "=":
                            $this->db->where($fields[1], strtolower($row['vals']));
                            break;
                        case "!=":
                            $this->db->where($fields[1] . " !=", strtolower($row['vals']));
                            break;
                        case "like":
                            $this->db->like($fields[1], strtolower($row['vals']), 'both');
                            break;
                        case ">":
                            $this->db->where($fields[1] . " >", strtolower($row['vals']));
                            break;
                        case "<":
                            $this->db->where($fields[1] . " <", strtolower($row['vals']));
                            break;
                        case ">=":
                            $this->db->where($fields[1] . " >=", strtolower($row['vals']));
                            break;
                        case "<=":
                            $this->db->where($fields[1] . " <=", strtolower($row['vals']));
                            break;
                    }
                }
            }
            /*if (empty($_GET['kode_proyek'])) {
				$this->db->where_in('id_subunitkerja', $_GET['unitkerja']);
			} else {
				$this->db->where_in('id_proyek', $_GET['kode_proyek']);
			}*/
        }
        
        /*if ($this->session->userdata('ba_is_proyek') == "f") {
            $this->db->where_in('id_subunitkerja', $this->session->userdata('ba_hak_data'));
        } else {
            $this->db->where_in('id_proyek', $this->session->userdata('ba_hak_data'));
        }*/
        
        $this->db->select('*');
        $this->db->from($this->_table['view_listjurnal']);
        $this->db->where_in('id_proyek', $userconfig["kolom2"]);
        $this->_countAll = $this->db->count_all_results();
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['view_listjurnal']);
        $this->db->flush_cache();

        //return $query->result_array();
        $tmp = array();
        $tmp_result = array();
        foreach ($query->result_array() as $row) {
				$tmp[$row['nobukti']]['tanggal'] 		= $row['tanggal'];
				$tmp[$row['nobukti']]['nobukti'] 		= $row['nobukti'];
				$tmp[$row['nobukti']]['no_dokumen'] 		= $row['no_dokumen'];
				$tmp[$row['nobukti']]['nama_proyek'] 		= $row['nama_proyek'] ;
				//$tmp[$row['nobukti']]['keterangan'] 	= $row['keterangan'] ;
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['keterangan'] 		= $row['keterangan'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['id_tempjurnal'] 	= $row['id_tempjurnal'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['coa'] 			= $row['coa'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['rekanan'] 		= $row['rekanan'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['debit'] 			= $row['debit'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['kredit'] 			= $row['kredit'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['isapprove'] 		= $row['isapprove'];
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
						'no_dokumen' 	=> $row['no_dokumen'],
						'nama_proyek' 	=> $row['nama_proyek'],
						'keterangan' 	=> $val['keterangan'],
						'coa' 			=> $val["coa"],
						'rekanan' 		=> $val["rekanan"],
						'debit' 		=> myFormatMoney($val["debit"]),
						'kredit' 		=> myFormatMoney($val["kredit"]),
						'isapprove' 	=> $val["isapprove"]
					);
				} else {
					$tmp_result[] = array(
						'idnya' 		=> $val["id_tempjurnal"],
						'nomor' 		=> "",
						'tanggal' 		=> "",
						'nobukti' 	=> "",
						'no_dokumen' 	=> "",
						'nama_proyek' 	=> "",
						'keterangan' 	=> $val['keterangan'],
						'coa' 			=> $val["coa"],
						'rekanan' 		=> $val["rekanan"],
						'debit' 		=> myFormatMoney($val["debit"]),
						'kredit' 		=> myFormatMoney($val["kredit"]),
						'isapprove' 	=> $val["isapprove"]
					);
				}
				$check++;
			}
			$tmp_result[] = array(
					'idnya' 		=> "i".$i,
				    'nomor' 		=> "",
					'tanggal' 		=> "",
					'nobukti' 	=> "",
					'no_dokumen' 	=> "",
					'nama_proyek' 	=> "",
					'keterangan' 	=> "",
					'coa' 			=> "",
					'rekanan' 		=> "",
					'debit' 		=> "",
					'kredit' 		=> "",
					'isapprove' 	=> "",
				);
		}
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan laporan listjurnal',
											'log_params' => json_encode($cari)
										)
									  );
		//print_r($tmp_result);
        return $tmp_result;

    }

    public function countAll() {
        return $this->_countAll;
    }
	
	public function getAllForExcel() {
        $this->db->select('*');
        $this->db->from($this->_table['view_listjurnal']);
        if ($this->input->post()) {
            $cols = $this->input->post('cols');
            $ops = $this->input->post('ops');
            $vals = $this->input->post('vals');
			
			//print_r($cols);
			
            $cari = array();
            for ($x = 0; $x < count($cols); $x++) {
                $cari[$x]['cols'] = $cols[$x];
                $cari[$x]['ops'] = $ops[$x];
                $cari[$x]['vals'] = $vals[$x];
            }
            foreach ($cari as $row) {
                if (!empty($row['cols']) AND !empty($row['ops'])) {
                    $fields = explode(":", $row['cols']);
                    switch ($row['ops']) {
                        case "=":
                            $this->db->where($fields[1], strtolower($row['vals']));
                            break;
                        case "!=":
                            $this->db->where($fields[1] . " !=", strtolower($row['vals']));
                            break;
                        case "like":
                            $this->db->like($fields[1], strtolower($row['vals']), 'both');
                            break;
                        case ">":
                            $this->db->where($fields[1] . " >", strtolower($row['vals']));
                            break;
                        case "<":
                            $this->db->where($fields[1] . " <", strtolower($row['vals']));
                            break;
                        case ">=":
                            $this->db->where($fields[1] . " >=", strtolower($row['vals']));
                            break;
                        case "<=":
                            $this->db->where($fields[1] . " <=", strtolower($row['vals']));
                            break;
                    }
                }
            }
            /*if ($this->input->post('kode_proyek')) {
				$this->db->where_in('id_proyek', $this->input->post('kode_proyek'));
			} else {
				$this->db->where_in('id_subunitkerja', $this->input->post('unitkerja'));
			}*/
        }
        if ($this->session->userdata('ba_is_proyek') == "f") {
            $this->db->where_in('id_subunitkerja', json_decode($this->session->userdata('ba_hak_data')));
        } else {
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
        }
        $this->db->order_by('nobukti', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $i = 0;
        $tmp = array();
        $tmp_result = array();
        foreach ($query->result_array() as $row) {
				$tmp[$row['nobukti']]['tanggal'] 		= $row['tanggal'];
				$tmp[$row['nobukti']]['nobukti'] 		= $row['nobukti'];
				$tmp[$row['nobukti']]['no_dokumen'] 	= $row['no_dokumen'];
				//$tmp[$row['nobukti']]['keterangan'] 	= $row['keterangan'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['keterangan'] 		= $row['keterangan'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['id_tempjurnal'] 	= $row['id_tempjurnal'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['coa'] 			= $row['coa'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['rekanan'] 		= $row['rekanan'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['debit'] 			= $row['debit'];
				$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['kredit'] 			= $row['kredit'];
				//$tmp[$row['nobukti']]['desc'][$row['id_tempjurnal']]['isapprove'] 	= $row['isapprove'];
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
						'nomor' 		=> $i,
						'tanggal' 		=> $row['tanggal'],
						'nobukti' 		=> $row['no_dokumen'],
						'keterangan' 	=> $val['keterangan'],
						'coa' 			=> $val["coa"],
						'rekanan' 		=> $val["rekanan"],
						'debit' 		=> myFormatMoney($val["debit"]),
						'kredit' 		=> myFormatMoney($val["kredit"]),
						//'isapprove' 	=> $val["isapprove"]
					);
				} else {
					$tmp_result[] = array(
						'nomor' 		=> "",
						'tanggal' 		=> "",
						'nobukti' 		=> "",
						'keterangan' 	=> $val['keterangan'],
						'coa' 			=> $val["coa"],
						'rekanan' 		=> $val["rekanan"],
						'debit' 		=> myFormatMoney($val["debit"]),
						'kredit' 		=> myFormatMoney($val["kredit"]),
						//'isapprove' 	=> $val["isapprove"]
					);
				}
				$check++;
			}
			$tmp_result[] = array(
				    'nomor' 		=> "",
					'tanggal' 		=> "",
					'nobukti' 		=> "",
					'keterangan' 	=> "",
					'coa' 			=> "",
					'rekanan' 		=> "",
					'debit' 		=> "",
					'kredit' 		=> "",
					//'isapprove' 	=> ""
				);
		}
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh laporan listjurnal',
											'log_params' => json_encode($cari)
										)
									  );
        return $tmp_result;
    }
    
    public function getProyekName($id) {

        $this->db->select('nama_proyek');
        $this->db->from('list_proyek_v');
        $this->db->where('id_proyek', $id);
        $query = $this->db->get();

        $proyek = '';
        foreach ($query->result_array() as $row) {
            $proyek = $row['nama_proyek'];
        }
        return $proyek;
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
