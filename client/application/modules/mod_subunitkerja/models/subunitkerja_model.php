<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class subunitkerja_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    function getAll($limit, $offset, $sidx, $sord, $cari, $search = "false") {

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

        $this->db->from($this->_table['tbl_subunitkerja']);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('*');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['tbl_subunitkerja']);
        $this->db->flush_cache();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan data master subunitkerja',
											'log_params' => json_encode($cari)
										)
									  );
        return $query->result_array();
    }

    public function countAll() {
        return $this->_countAll;
    }

    public function insert($data) {
        $this->db->insert($this->_table['tbl_subunitkerja'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data master subunitkerja',
											'log_params' => json_encode($data)
										)
									  );
        return $this->db->insert_id();
    }

    public function delete($id) {
        if (is_array($id)) {
            $this->db->where_in('id_subunitkerja', $id);
        } else {
            $this->db->where('id_subunitkerja', $id);
        }
        $this->db->delete($this->_table['tbl_subunitkerja']);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menghapus data master subunitkerja',
											'log_params' => json_encode(array('id_subunitkerja' => $id))
										)
									  );
    }

    public function integer($str) {
        return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
    }

    public function cekId($id) {

        if (!empty($id) AND $this->integer($id)) {
            $this->db->select("*");
            $this->db->from($this->_table['tbl_subunitkerja']);
            $this->db->where("id_subunitkerja", $id);
            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function get($id) {
        $this->db->select("*");
        $this->db->from($this->_table['tbl_subunitkerja']);
        $this->db->where("id_subunitkerja", $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return array(
                'id_subunitkerja' => $row['id_subunitkerja'],
                'nama_subunit' => $row['nama_subunit'],
                'is_proyek' => $row['is_proyek'],
                'keterangan' => $row['keterangan']
            );
        } else {
            return array();
        }
    }

    public function update($data, $id) {
        $this->db->where('id_subunitkerja', $id);
        $this->db->update($this->_table['tbl_subunitkerja'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengedit data master subunitkerja',
											'log_params' => json_encode($data)
										)
									  );
        return $id;
    }

}
