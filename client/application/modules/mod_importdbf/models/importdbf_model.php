<?php

class importdbf_model extends CI_Model {

    protected $_table;
    protected $_countAll;
    protected $_tempResult = array();

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    private function _insert_id() {
        $sql = "SELECT CURRVAL('tbl_importdata_importdata_id_seq') AS ins_id";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row["ins_id"];
        } else {
            return false;
        }
    }

    /*
     * @param array
     * @access public
     * @return integer
     */

    public function insert_import($data) {
        $this->db->trans_start();
        if ($this->db->insert("tbl_importdata", $data))
            $id = $this->_insert_id();
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return $id;
        } else {
            return FALSE;
        }
    }

    /*
     * insert data ke table import data
     * @param array an array of bind data
     * @access public
     * @return bool
     */

    public function insert($data) {
        $this->db->trans_start();
        $this->db->insert_batch('tbl_importdatadetail', $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }

    function getAll($idproyek, $limit, $offset, $sidx, $sord, $cari, $search = "false") {

        if (!is_array($cari))
            $cari = array();

        $this->db->start_cache();
        if ($search == "true") {
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

        $this->db->from("listimport_v");
        $this->db->where("id_proyek", $idproyek);
        $this->_countAll = $this->db->count_all_results();

        $this->db->select('importdata_id, kode_proyek, nama_proyek, period_name, period_start, period_end, period_flag');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get("listimport_v");
        $this->db->flush_cache();

        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'importdata_id' => $row['importdata_id'],
                'proyek' => $row['kode_proyek'] . " - " . $row['nama_proyek'],
                'period_name' => $row['period_name'],
                'period_start' => $row['period_start'],
                'period_end' => $row['period_end'],
                'period_flag' => $row['period_flag']
            );
        }
        return $temp_result;
    }

    public function countAll() {
        return $this->_countAll;
    }

    /*
     * @return array
     */

    public function getAllData($idproyek, $id, $limit, $offset, $sidx, $sord) {
        $this->db->start_cache();
        $this->db->select("*");
        $this->db->from("tbl_importdata");
        $this->db->join("tbl_importdatadetail", "importdatadetail_importdata_id = importdata_id");
        $this->db->where("importdata_id_proyek", $idproyek);
        $this->db->where("importdata_id", $id);
        $this->_countAll = $this->db->count_all_results();
        $this->db->limit($limit, $offset);
        $this->db->order_by($sidx, $sord);
        $query = $this->db->get();
        $this->db->flush_cache();

        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $this->_tempResult[$row["importdatadetail_txno"]]["txno"] = $row["importdatadetail_txno"];
                $this->_tempResult[$row["importdatadetail_txno"]]["date"] = $row["importdatadetail_date"];
                $this->_tempResult[$row["importdatadetail_txno"]]["txcode"] = $row["importdatadetail_txcode"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["id"] = $row["importdatadetail_id"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["itno"] = $row["importdatadetail_itno"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["desc"] = $row["importdatadetail_desc"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["amount"] = $row["importdatadetail_amount"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["dk"] = $row["importdatadetail_dk"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["glcode"] = $row["importdatadetail_glcode"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["dperkir_id"] = $row["importdatadetail_dperkir_id"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["kdperkiraan"] = $row["importdatadetail_kdperkiraan"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["kdnasabah"] = $row["importdatadetail_kdnasabah"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["kdsbdaya"] = $row["importdatadetail_kdsbdaya"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["issaved"] = $row["importdatadetail_issaved"];
            }
            return $this->_tempResult;
        } else {
            return false;
        }
    }

    public function formaterArray() {
        if (!empty($this->_tempResult) AND is_array($this->_tempResult)) {
            $temp_result = array();
            foreach ($this->_tempResult as $row) {
                $no = 0;
                foreach ($row["detail"] as $row1) {
                    foreach ($row1 as $row2) {
                        if ($no < 1 AND $no == 0) {
                            if ($row2["dk"] == "D") {
                                $checkbox = $row2["issaved"] == "f" ? "<input type=\"checkbox\" value=\"" . $row["txno"] . "\" name=\"jq_checkbox_added[]\" class=\"jq_checkbox_added\" />" : "" ;
								$temp_result[] = array(
                                    "id" => $row2["id"],
                                    "check" => $checkbox,
                                    "txno" => $row["txno"],
                                    "date" => $row["date"],
                                    "txcode" => $row["txcode"],
                                    "itno" => $row2["itno"],
                                    "desc" => $row2["desc"],
                                    "glcode" => $row2["glcode"],
                                    "dperkir_id" => $row2["dperkir_id"],
                                    "kode" => $row2["kdperkiraan"],
                                    "kdperkiraan" => $row2["kdperkiraan"],
                                    "kdnasabah" => $row2["kdnasabah"],
                                    "kdsbdaya" => $row2["kdsbdaya"],
                                    "debet" => $row2["amount"],
                                    "kredit" => 0
                                );
                            } else {
                                $checkbox = $row2["issaved"] == "f" ? "<input type=\"checkbox\" value=\"" . $row["txno"] . "\" name=\"jq_checkbox_added[]\" class=\"jq_checkbox_added\" />" : "" ;
								$temp_result[] = array(
                                    "id" => $row2["id"],
                                    "check" => $checkbox,
                                    "txno" => $row["txno"],
                                    "date" => $row["date"],
                                    "txcode" => $row["txcode"],
                                    "itno" => $row2["itno"],
                                    "desc" => $row2["desc"],
                                    "glcode" => $row2["glcode"],
                                    "dperkir_id" => $row2["dperkir_id"],
                                    "kode" => $row2["kdperkiraan"],
                                    "kdperkiraan" => $row2["kdperkiraan"],
                                    "kdnasabah" => $row2["kdnasabah"],
                                    "kdsbdaya" => $row2["kdsbdaya"],
                                    "debet" => 0,
                                    "kredit" => $row2["amount"]
                                );
                            }
                        } else {
                            if ($row2["dk"] == "D") {
                                $temp_result[] = array(
                                    "id" => $row2["id"],
                                    "check" => "",
                                    "txno" => "",
                                    "date" => "",
                                    "txcode" => $row["txcode"],
                                    "itno" => $row2["itno"],
                                    "desc" => $row2["desc"],
                                    "glcode" => $row2["glcode"],
                                    "dperkir_id" => $row2["dperkir_id"],
                                    "kode" => $row2["kdperkiraan"],
                                    "kdperkiraan" => $row2["kdperkiraan"],
                                    "kdnasabah" => $row2["kdnasabah"],
                                    "kdsbdaya" => $row2["kdsbdaya"],
                                    "debet" => $row2["amount"],
                                    "kredit" => 0
                                );
                            } else {
                                $temp_result[] = array(
                                    "id" => $row2["id"],
                                    "check" => "",
                                    "txno" => "",
                                    "date" => "",
                                    "txcode" => $row["txcode"],
                                    "itno" => "",
                                    "desc" => $row2["desc"],
                                    "glcode" => $row2["glcode"],
                                    "dperkir_id" => $row2["dperkir_id"],
                                    "kode" => $row2["kdperkiraan"],
                                    "kdperkiraan" => $row2["kdperkiraan"],
                                    "kdnasabah" => $row2["kdnasabah"],
                                    "kdsbdaya" => $row2["kdsbdaya"],
                                    "debet" => 0,
                                    "kredit" => $row2["amount"]
                                );
                            }
                        }
                        $no++;
                    }
                }
            }
            return $temp_result;
        }
    }

    public function update($data, $id) {
        $this->db->trans_start();
        $this->db->where('importdatadetail_id', $id);
        $this->db->update("tbl_importdatadetail", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function cek_kodeperkiraan($kodeperkiraan) {
        $this->db->select('kdperkiraan');
        $this->db->from("tbl_dperkir");
        $this->db->where('kdperkiraan', $kodeperkiraan);
        $this->db->limit(1);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
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

    public function getsavejurnal($idproyek, $iddata, $data = array()) {
        if (!is_array($data))
            $data = array();

        $this->db->select("*");
        $this->db->from("tbl_importdata");
        $this->db->join("tbl_importdatadetail", "importdatadetail_importdata_id = importdata_id");
        $this->db->where("importdata_id_proyek", $idproyek);
        $this->db->where("importdata_id", $iddata);
        $this->db->where_in("importdatadetail_txno", $data);
        $query = $this->db->get();
		$jenis = false;
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $this->_tempResult[$row["importdatadetail_txno"]]["txno"] = $row["importdatadetail_txno"];
                $this->_tempResult[$row["importdatadetail_txno"]]["date"] = $row["importdatadetail_date"];
                $this->_tempResult[$row["importdatadetail_txno"]]["txcode"] = $row["importdatadetail_txcode"];

                switch ($row["importdatadetail_txcode"]) {
                    case 2:
                        if ($row["importdatadetail_dk"] == "K") {
                            if (($row["importdatadetail_dperkir_id"] > 8) && ($row["importdatadetail_dperkir_id"] < 69)) {
                                $jenis = "B";
                            }
                            if ($row["importdatadetail_dperkir_id"] < 9) {
                                $jenis = "K";
                            }
                        }
                        break;

                    case 3:
                        if ($row["importdatadetail_dk"] == "D") {
                            if (($row["importdatadetail_dperkir_id"] > 8) && ($row["importdatadetail_dperkir_id"] < 69)) {
                                $jenis = "B";
                            }
                            if ($row["importdatadetail_dperkir_id"] < 9) {
                                $jenis = "K";
                            }
                        }
                        break;

                    default:
                        $jenis = "M";
                        break;
                }

                $this->_tempResult[$row["importdatadetail_txno"]]["jenis"] = $jenis;
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["id"] = $row["importdatadetail_id"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["itno"] = $row["importdatadetail_itno"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["desc"] = $row["importdatadetail_desc"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["amount"] = $row["importdatadetail_amount"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["dk"] = $row["importdatadetail_dk"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["glcode"] = $row["importdatadetail_glcode"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["dperkir_id"] = $row["importdatadetail_dperkir_id"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["kdperkiraan"] = $row["importdatadetail_kdperkiraan"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["kdnasabah"] = $row["importdatadetail_kdnasabah"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["kdsbdaya"] = $row["importdatadetail_kdsbdaya"];
                $this->_tempResult[$row["importdatadetail_txno"]]["detail"][$row["importdatadetail_itno"]][$row["importdatadetail_dk"]]["issaved"] = $row["importdatadetail_issaved"];
            }
            return $this->_tempResult;
        } else {
            return false;
        }
    }

    public function savetotempjurnal($data = array()) {
        if (!is_array($data))
            $data = array();

        $this->db->trans_start();
        $this->db->insert_batch("tbl_tempjurnal", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    
    public function updateSaved($nobukti = array()) {
		$data = array(
					   'importdatadetail_issaved' => 't'
					);
        $this->db->trans_start();
        $this->db->where_in("importdatadetail_txno", $nobukti);
        $this->db->update("tbl_importdatadetail", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
