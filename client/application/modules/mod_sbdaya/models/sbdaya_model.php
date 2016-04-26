<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class sbdaya_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    public function autocomplete($param, $id_proyek, $kode_perkiraan) {

        $sql = "select kode_sbdaya,
                    nama_sbdaya
                from tbl_sbdaya
                where ((lower(kode_sbdaya) like '%" . strtolower($param) . "%' OR lower(nama_sbdaya) like '%" . strtolower($param) . "%') AND
                    (id_proyek = " . $id_proyek . "))";

        $query = $this->db->query($sql);

        $temp_result = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $temp_result[] = array(
                    'id' => $row['kode_sbdaya'],
                    'desc' => $row['nama_sbdaya'],
                    'label' => $row['kode_sbdaya'] . " - " . $row['nama_sbdaya']
                );
            }
        } else {
            $temp_result[] = array(
                'id' => 0,
                'desc' => "Not Found",
                'label' => "Record Not Found"
            );
        }
        return $temp_result;
    }

    function getAll($limit, $offset, $sidx, $sord, $cari, $search = "false", $idproyek) {

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
        $this->db->from($this->_table['view_sbdaya']);
        $this->db->where("id_proyek", $idproyek);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('*');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['view_sbdaya']);
        $this->db->flush_cache();
        $temp_result = array();
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan data master sumberdaya',
											'log_params' => json_encode($cari)
										)
									  );
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'id_sbdaya' => $row['id_sbdaya'],
                'id_subunitkerja' => $row['id_subunitkerja'],
                'id_proyek' => $row['id_proyek'],
                'nama_subunit' => $row['nama_subunit'],
                'proyek' => $row['proyek'],
                'tipe' => $row['tipe'],
                'nama_library' => $row['nama_library'],
                'kode_sbdaya' => $row['kode_sbdaya'],
                'sbdaya' => $row['sbdaya'],
                'satuan' => $row['satuan']
            );
        }
        return $temp_result;
    }

    public function countAll() {
        return $this->_countAll;
    }

    public function getAllForExcel() {
        $this->db->select('*');
        $this->db->from($this->_table['view_sbdaya']);
        if ($this->session->userdata('ba_is_proyek') == "f") {
            $this->db->where_in('id_subunitkerja', json_decode($this->session->userdata('ba_hak_data')));
        } else {
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
        }
        $this->db->order_by('sbdaya', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $i = 0;
        foreach ($query->result_array() as $row) {
            $i++;
            $temp_result[] = array(
                'nomor' => $i,
                'kode_sbdaya' => $row['kode_sbdaya'],
                'sbdaya' => $row['sbdaya'],
                'satuan' => $row['satuan'],
                'tipe' => $row['nama_library']
            );
        }
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh data master sumberdaya',
											'log_params' => json_encode(json_decode($this->session->userdata('ba_hak_data')))
										)
									  );
        return $temp_result;
    }

    function PopupGetAll($limit, $offset, $sidx, $sord, $cari, $search, $id) {

        if (!is_array($cari))
            $cari = array();

        $this->db->start_cache();
        if ($search == "true") {
            foreach ($cari as $key => $value) {
                if (!empty($value)) {
                    $this->db->like("lower(" . $key . ")", strtolower($value), 'both');
                }
            }
        }
        $this->db->from($this->_table['view_sbdaya']);
        if ($this->session->userdata('ba_is_proyek') == "f") {
            $this->db->where_in('id_subunitkerja', json_decode($this->session->userdata('ba_hak_data')));
        } else {
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
        }
        $this->db->where('id_proyek', $id);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('id_sbdaya, kode_sbdaya, sbdaya');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['view_sbdaya']);
        $this->db->flush_cache();

        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'id_sbdaya' => $row['id_sbdaya'],
                'kode_sbdaya' => $row['kode_sbdaya'],
                'sbdaya' => $row['sbdaya']
            );
        }
        return $temp_result;
    }

    public function PopupCountAll() {
        return $this->_countAll;
    }

    public function getpicker($cond) {
        $this->db->select('kode_sbdaya as kode_rekanan, sbdaya');
        $this->db->from($this->_table['view_sbdaya']);
        $this->db->where($cond);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function getTypeSbdaya() {

        $this->db->select('b.id_library, b.nama_library');
        $this->db->from($this->_table['library'] . ' a');
        $this->db->join($this->_table['library'] . ' b', 'b.parent = a.id_library', 'left outer');
        $this->db->where('a.id_library = 12');
        $this->db->order_by('b.nama_library', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $temp_result[''] = '';
        foreach ($query->result_array() as $row) {
            $temp_result[$row['id_library']] = $row['nama_library'];
        }
        return $temp_result;
    }

    public function getIdLibrary($nama) {

        $this->db->select('b.id_library, b.nama_library');
        $this->db->from($this->_table['library'] . ' a');
        $this->db->join($this->_table['library'] . ' b', 'b.parent = a.id_library', 'left outer');
        $this->db->where('a.id_library = 12');
        $this->db->where("upper(b.nama_library) = '$nama'");
        $this->db->order_by('b.nama_library', 'asc');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['id_library'];
        } else {
            return false;
        }
    }

    public function insert($data) {
        $this->db->insert($this->_table['tbl_sbdaya'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data master sumberdaya',
											'log_params' => json_encode($data)
										)
									  );
        return $this->db->insert_id();
    }

    public function insertExport($data) {
        $this->db->insert_batch($this->_table['tbl_sbdaya'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data master sumberdaya',
											'log_params' => json_encode($data)
										)
									  );
    }

    public function checkKodeSbdaya($kode_sbdaya, $id_proyek) {

        $this->db->select('kode_sbdaya');
        $this->db->from($this->_table['view_sbdaya']);
        $this->db->where('kode_sbdaya', $kode_sbdaya);
        $this->db->where('id_proyek', $id_proyek);
        $this->db->limit(1);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }

    public function delete($id) {
        if (is_array($id)) {
            $this->db->where_in('id_sbdaya', $id);
        } else {
            $this->db->where('id_sbdaya', $id);
        }
        $this->db->delete($this->_table['tbl_sbdaya']);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menghapus data master sumberdaya',
											'log_params' => json_encode(array('id_sbdaya' => $id))
										)
									  );
    }

    public function integer($str) {
        return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
    }

    public function cekId($id) {

        if (!empty($id) AND $this->integer($id)) {
            $this->db->select("*");
            $this->db->from($this->_table['view_sbdaya']);
            $this->db->where("id_sbdaya", $id);
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
        $this->db->from($this->_table['view_sbdaya']);
        $this->db->where("id_sbdaya", $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return array(
                'id_sbdaya' => $row['id_sbdaya'],
                'id_subunitkerja' => $row['id_subunitkerja'],
                'id_proyek' => $row['id_proyek'],
                'kode_sbdaya' => $row['kode_sbdaya'],
                'sbdaya' => $row['sbdaya'],
                'satuan' => $row['satuan'],
                'tipe' => $row['tipe']
            );
        } else {
            return array();
        }
    }

    public function update($data, $id) {
        $this->db->where('id_sbdaya', $id);
        $this->db->update($this->_table['tbl_sbdaya'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengedit data master sumberdaya',
											'log_params' => json_encode($data)
										)
									  );
        return $id;
    }

}
