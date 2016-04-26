<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class jenisproyek_model extends CI_Model {

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
                if (!empty($row['cols']) AND ! empty($row['ops'])) {
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

        $this->db->from($this->_table['tbl_jenisproyek']);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('*');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['tbl_jenisproyek']);
        $this->db->flush_cache();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan semua data jenis proyek di modul jenis proyek',
											'log_params' => json_encode($cari)
										)
									  );
        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'jenisproyek_id' => $row['jenisproyek_id'],
                'jenisproyek_name' => $row['jenisproyek_name'],
                'jenisproyek_ket' => $row['jenisproyek_ket']
            );
        }
        return $temp_result;
    }

    public function countAll() {
        return $this->_countAll;
    }

    public function insert($data) {
        $this->db->trans_start();
        $this->db->insert($this->_table['tbl_jenisproyek'], $data);
        $this->db->trans_complete();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data jenis proyek di modul jenis proyek',
											'log_params' => json_encode($data)
										)
									  );
        if ($this->db->trans_status() === TRUE) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }

    public function delete($id) {
        $this->db->trans_start();

        if (is_array($id)) {
            $this->db->where_in('jenisproyek_id', $id);
        } else {
            $this->db->where('jenisproyek_id', $id);
        }
        $this->db->delete($this->_table['tbl_jenisproyek']);

        $this->db->trans_complete();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menghapus data jenis proyek di modul jenis proyek',
											'log_params' => json_encode(array('jenisproyek_id' => $id))
										)
									  );
									  
        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function cekId($id) {

        if (!empty($id) AND isInteger($id)) {
            $this->db->select("jenisproyek_id");
            $this->db->from($this->_table['tbl_jenisproyek']);
            $this->db->where("jenisproyek_id", $id);
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
        $this->db->from($this->_table['tbl_jenisproyek']);
        $this->db->where("jenisproyek_id", $id);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return array(
                'jenisproyek_id' => $row['jenisproyek_id'],
                'jenisproyek_name' => $row['jenisproyek_name'],
                'jenisproyek_ket' => $row['jenisproyek_ket']
            );
        } else {
            return array();
        }
    }

    public function update($data, $id) {
        $this->db->trans_start();
        $this->db->where('jenisproyek_id', $id);
        $this->db->update($this->_table['tbl_jenisproyek'], $data);
        $this->db->trans_complete();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengedit data jenis proyek di modul jenis proyek',
											'log_params' => json_encode($data)
										)
									  );
        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
