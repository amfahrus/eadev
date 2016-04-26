<?php

class saldo_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    public function getNobukti($tanggal, $id_proyek, $tipe) {
        $query = $this->db->query("SELECT seq_nobukti_get('" . $tanggal . "'," . $id_proyek . ",'" . $tipe . "') as no_bukti");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['no_bukti'];
        } else {
            return false;
        }
    }

    public function getGid($id_proyek) {
        $query = $this->db->query("select seq_gid_get(" . $id_proyek . ") as gid");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['gid'];
        } else {
            return false;
        }
    }
    
    public function InsertJurnal($data){
		$this->db->trans_begin();

		$this->db->insert_batch('saldo_awal_detail', $data);

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data saldo awal',
											'log_params' => json_encode($data)
										)
									  );
		if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();
			return false;
		}
		else
		{
			$this->db->trans_commit();
			return true;
		}
	}
	
	public function deleteJurnal($id){

		$this->db->where_in('saldo_awal_id', $id);
		$this->db->delete('saldo_awal_detail'); 
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menghapus data saldo awal',
											'log_params' => json_encode(array('saldo_awal_id' => $id))
										)
									  );

	}
	
	public function getJurnal($idproyek, $limit, $offset, $sidx, $sord, $cari, $search = "false") {

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
                        case "not like":
                            $this->db->not_like($fields[1], strtolower($row['vals']));
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
        }

        $this->db->from('saldoawal_v');
        $this->db->where('id_proyek', $idproyek);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('saldo_awal_id, dperkir_id, coa, keterangan, bukubantu, debet, kredit');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get('saldoawal_v');
        $this->db->flush_cache();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan data saldo awal',
											'log_params' => json_encode($cari)
										)
									  );
        $temp_result = $query->result_array();
        return $temp_result;
    }

    public function countAll() {
        return $this->_countAll;
    }

    public function JurnalToJson($data = array()) {
        if (!empty($data) and is_array($data) and count($data) <> 0) {
            $no = 1;
            $temp_result = array();
            foreach ($data as $row) {
                $checkbox = "<input type=\"checkbox\" value=\"" . $row["saldo_awal_id"] . "\" name=\"jq_checkbox_added[]\" class=\"jq_checkbox_added\" />";
				$temp_result[] = array(
					"no" => $no,
					"id" => $row["saldo_awal_id"],
					"check" => $checkbox,
					"coa" => $row["coa"],
					"keterangan" => $row["keterangan"],
					"bukubantu" => $row["bukubantu"],
					"debet" => myFormatMoney($row["debet"]),
					"kredit" => myFormatMoney($row["kredit"])
				);
                $no++;
            }
            return $temp_result;
        }
    }
    
    public function get_saldoawal($saldo_awal_id) {
        $this->db->select("*");
        $this->db->from("saldoawal_v");
        $this->db->where("saldo_awal_id", $saldo_awal_id);
        $this->db->order_by("saldo_awal_id", "asc");
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $temp_result = array();
            foreach ($query->result_array() as $row) {
				$jenis = explode('/',$row["nobukti"]);
                $temp_result["jurnal"]["tanggal"] = $row["tanggal_format"];
                $temp_result["jurnal"]["no_dokumen"] = $row["no_dokumen"];
				$temp_result["jurnal"]["jenis_transaksi"] = $jenis[2];
				$temp_result["jurnal"]["jenis_jurnal"] = $row["tempjurnal_jenisjurnal_id"];
                $temp_result["jurnal"]["nobukti"] = $row["nobukti"];
                $temp_result["jurnal"]["id_proyek"] = $row["id_proyek"];
                $temp_result["jurnal"]["is_approved"] = $row["isapprove"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["gid"] = $row["gid"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["keterangan"] = $row["keterangan"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["nilai"] = $row["nilai"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["id_tempjurnal"] = $row["id_tempjurnal"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["dperkir_id"] = $row["dperkir_id"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["kdperkiraan"] = $row["kdperkiraan"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["kdnasabah"] = $row["kdnasabah"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["kdsbdaya"] = $row["kdsbdaya"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["is_rekanan"] = $row["is_rekanan"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["is_sbdaya"] = $row["is_sbdaya"];

                if (strtolower($row["is_rekanan"]) == "t") {
                    $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["bukubantu"] = $row["kdnasabah"];
                } elseif (strtolower($row["is_sbdaya"]) == "t") {
                    $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["bukubantu"] = $row["kdsbdaya"];
                } else {
                    $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["bukubantu"] = "";
                }

                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["dk"] = $row["dk"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["volume"] = $row["volume"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["debet"] = $row["debit"];
                $temp_result["jurnal"]["detail"][$row["gid"]]["detail"][$row["dk"]]["kredit"] = $row["kredit"];
            }

            $this->_tempResult = $temp_result;
        } else {
            return array();
        }
    }

    public function getArrayNobukti() {
        $temp_result = array();
        $x = 1;
        foreach ($this->_tempResult as $key1 => $value1) {
            $temp_result["jurnal"]["tanggal"] = $value1["tanggal"];
            $temp_result["jurnal"]["no_dokumen"] = $value1["no_dokumen"];
            $temp_result["jurnal"]["jenis_jurnal"] = $value1["jenis_jurnal"];
            $temp_result["jurnal"]["jenis_transaksi"] = $value1["jenis_transaksi"];
            $temp_result["jurnal"]["nobukti"] = $value1["nobukti"];
            $temp_result["jurnal"]["id_proyek"] = $value1["id_proyek"];
            $temp_result["jurnal"]["is_approved"] = $value1["is_approved"];

            foreach ($value1["detail"] as $key2 => $value2) {
                $temp_result["jurnal"]["detail"][$x]["gid"] = $value2["gid"];
                $temp_result["jurnal"]["detail"][$x]["keterangan"] = $value2["keterangan"];
                $temp_result["jurnal"]["detail"][$x]["nilai"] = $value2["nilai"];

                foreach ($value2["detail"] as $key3 => $value3) {
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["id_tempjurnal"] = $value3["id_tempjurnal"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["dperkir_id"] = $value3["dperkir_id"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["kdperkiraan"] = $value3["kdperkiraan"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["kdnasabah"] = $value3["kdnasabah"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["kdsbdaya"] = $value3["kdsbdaya"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["is_rekanan"] = $value3["is_rekanan"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["is_sbdaya"] = $value3["is_sbdaya"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["bukubantu"] = $value3["bukubantu"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["dk"] = $value3["dk"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["volume"] = $value3["volume"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["debet"] = $value3["debet"];
                    $temp_result["jurnal"]["detail"][$x]["detail"][$key3]["kredit"] = $value3["kredit"];
                }
                $x++;
            }
        }
        return $temp_result;
    }

}
