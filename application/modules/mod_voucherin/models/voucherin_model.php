<?php

class voucherin_model extends CI_Model {

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

		$this->db->insert_batch('tbl_tempjurnal', $data);

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan jurnal voucher in',
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
	
	public function deleteJurnal($nobukti){
		
		$this->db->where_in('nobukti', $nobukti);
		$this->db->delete('tbl_tempjurnal'); 
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menghapus jurnal',
											'log_params' => json_encode(array('nobukti' => $nobukti))
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

        $this->db->from('listjurnal_v');
        $this->db->where('id_proyek', $idproyek);
        $this->db->where('tempjurnal_jenisjurnal_id', 1);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('kdperkiraan, coa, rekanan, id_proyek, kode_proyek, nama_proyek, id_tempjurnal, tanggal_format, nobukti, no_dokumen, dperkir_id, keterangan, volume, dk, debit, kredit, isapprove, gid');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get('listjurnal_v');
        $this->db->flush_cache();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan jurnal voucher in',
											'log_params' => json_encode($cari)
										)
									  );
        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[$row["nobukti"]]["tanggal"] = $row["tanggal_format"];
            $temp_result[$row["nobukti"]]["nobukti"] = $row["nobukti"];
            $temp_result[$row["nobukti"]]["no_dokumen"] = $row["no_dokumen"];
            $temp_result[$row["nobukti"]]["id_proyek"] = $row["nama_proyek"];
            $temp_result[$row["nobukti"]]["gid"] = $row["gid"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["id_tempjurnal"] = $row["id_tempjurnal"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["id_proyek"] = $row["nama_proyek"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["kdperkiraan"] = $row["kdperkiraan"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["kdnasabah"] = $row["rekanan"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["keterangan"] = $row["keterangan"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["volume"] = $row["volume"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["debet"] = $row["debit"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["kredit"] = $row["kredit"];
            $temp_result[$row["nobukti"]]["detail"][$row["id_tempjurnal"]]["is_approved"] = $row["isapprove"];
        }
        return $temp_result;
    }

    public function countAll() {
        return $this->_countAll;
    }

    public function JurnalToJson($data = array()) {
        if (!empty($data) and is_array($data) and count($data) <> 0) {
            $no = 1;
            $temp_result = array();
            foreach ($data as $key => $value) {
                $check = 0;
                foreach ($value["detail"] as $key => $value2) {
                    if ($check < 1 and $check == 0) {
						$edit = $value2["is_approved"] == "FALSE" ? '<a class="link_edit" href="#" onclick="edit_jurnal(\'' . $value["nobukti"] . '\');"><img src="' . base_url() . 'media/edit.png" /></a>' : '#' ;
						$checkbox = $value2["is_approved"] == "FALSE" ? "<input type=\"checkbox\" value=\"" . $value["nobukti"] . "\" name=\"jq_checkbox_added[]\" class=\"jq_checkbox_added\" />" : "" ;
						$temp_result[] = array(
                            "no" => $no,
                            "check" => $checkbox,
                            "flag" => $edit,
                            "tanggal" => $value["tanggal"],
                            "nomor_bukti" => $value["nobukti"],
                            "nomor_dokumen" => $value["no_dokumen"],
                            "kode_proyek" => $value["id_proyek"],
                            "coa" => $value2["kdperkiraan"],
                            "rekanan" => $value2["kdnasabah"],
                            "keterangan" => $value2["keterangan"],
                            "volume" => $value2["volume"],
                            "debet" => myFormatMoney($value2["debet"]),
                            "kredit" => myFormatMoney($value2["kredit"]),
                            "is_approved" => $value2["is_approved"]
                        );
                    } else {
                        $temp_result[] = array(
                            "no" => "",
                            "check" => "",
                            "flag" => "",
                            "tanggal" => "",
                            "nomor_bukti" => "",
                            "nomor_dokumen" => "",
                            "kode_proyek" => "",
                            "coa" => $value2["kdperkiraan"],
                            "rekanan" => $value2["kdnasabah"],
                            "keterangan" => $value2["keterangan"],
                            "volume" => $value2["volume"],
                            "debet" => myFormatMoney($value2["debet"]),
                            "kredit" => myFormatMoney($value2["kredit"]),
                            "is_approved" => $value2["is_approved"]
                        );
                    }
                    $check++;
                }
                $no++;
            }
            return $temp_result;
        }
    }
    
    public function get_nobukti($nomor_bukti) {
		$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
		$proyek = $userconfig["kolom2"];
        $this->db->select("*");
        $this->db->from("listjurnal_v");
        $this->db->where("nobukti", $nomor_bukti);
        $this->db->where("id_proyek", $proyek);
        $this->db->order_by("gid", "asc");
        $this->db->order_by("dk", "asc");
        $this->db->order_by("id_tempjurnal", "asc");
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
    
	public function getIdProyek($kode_proyek) {
        $this->db->select("id_proyek");
        $this->db->from("list_proyek_v");
        $this->db->where("kode_proyek", $kode_proyek);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['id_proyek'];
        } else {
            return false;
        }
    }
    
    public function getKodeProyek($id_proyek) {
        $this->db->select("kode_proyek");
        $this->db->from("list_proyek_v");
        $this->db->where("id_proyek", $id_proyek);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['kode_proyek'];
        } else {
            return false;
        }
    }
}
