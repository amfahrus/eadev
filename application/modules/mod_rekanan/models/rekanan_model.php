<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class rekanan_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    function getAll($limit, $offset, $sidx, $sord, $cari, $search = "false", $idProyek) {

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
      
        $this->db->from($this->_table['view_rekanan']);
        //$this->db->where('id_proyek', $idProyek);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('*');
        $this->db->where('id_proyek', $idProyek);
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['view_rekanan']);
        $this->db->flush_cache();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan data master rekanan',
											'log_params' => json_encode($cari)
										)
									  );
        return $query->result_array();
    }

    public function countAll() {
        return $this->_countAll;
    }

    public function getAllForExcel() {
        $this->db->select('*');
        $this->db->from($this->_table['view_rekanan']);
        if ($this->session->userdata('ba_is_proyek') == "f") {
            $this->db->where_in('id_subunitkerja', json_decode($this->session->userdata('ba_hak_data')));
        } else {
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
        }
        $this->db->order_by('nama_rekanan', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $i = 0;
        foreach ($query->result_array() as $row) {
            $i++;
            $temp_result[] = array(
                'nomor' => $i,
                'kode_rekanan' => $row['kode_rekanan'],
                'nama_rekanan' => $row['nama_rekanan'],
                'telp_rekanan' => $row['telp_rekanan'],
                'nama_kontak' => $row['nama_kontak'],
                'alamat' => $row['alamat'],
                'telp_kontak' => $row['telp_kontak'],
                'kota' => $row['kota'],
                'type_rekanan' => $row['nama_library']
            );
        }
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengunduh data master rekanan',
											'log_params' => json_encode(json_decode($this->session->userdata('ba_hak_data')))
										)
									  );
        return $temp_result;
    }

    public function getTypeRekanan() {

        $this->db->select('b.id_library, b.nama_library');
        $this->db->from($this->_table['library'] . ' a');
        $this->db->join($this->_table['library'] . ' b', 'b.parent = a.id_library', 'left outer');
        $this->db->where('a.id_library = 1');
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
        $this->db->where('a.id_library = 1');
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

    public function getKodeProyek() {
        $this->db->select('id_proyek, nama_proyek');
        $this->db->from($this->_table['tbl_proyek']);
        $this->db->order_by('nama_proyek', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $temp_result[''] = '';
        foreach ($query->result_array() as $row) {
            $temp_result[$row['id_proyek']] = $row['nama_proyek'];
        }
        return $temp_result;
    }

    private function _insert_id() {
        $sql = "SELECT CURRVAL('tbl_rekanan_id_rekanan_seq') AS ins_id";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row["ins_id"];
        } else {
            return false;
        }
    }

    public function insert($data) {
        $this->db->trans_start();
        $this->db->insert($this->_table['tbl_rekanan'], $data);
        $id = $this->_insert_id();
        $this->db->trans_complete();
		
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data master rekanan',
											'log_params' => json_encode($data)
										)
									  );
									  
        if ($this->db->trans_status() === TRUE) {
            $this->db->select('kode_rekanan');
            $this->db->from($this->_table['tbl_rekanan']);
            $this->db->where('id_rekanan', $id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                return $row['kode_rekanan'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function insert_bukubantu($data) {
        $this->db->trans_start();
        $this->db->insert_batch('tbl_bukubantu', $data);
        $this->db->trans_complete();
		
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data master buku bantu',
											'log_params' => json_encode($data)
										)
									  );
        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function insertImport($data) {
        $this->db->insert_batch($this->_table['tbl_rekanan'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan data master rekanan',
											'log_params' => json_encode($data)
										)
									  );
        //return $this->db->insert_id();
    }

    function PopupGetAll($limit, $offset, $sidx, $sord, $cari, $search, $id, $coa) {

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
        if ($coa) {
			$this->db->where("bukubantu_dperkir_id",$coa);
		}
        $this->db->from($this->_table['view_rekanan']);
        if ($this->session->userdata('ba_is_proyek') == "f") {
            $this->db->where_in('id_subunitkerja', json_decode($this->session->userdata('ba_hak_data')));
        } else {
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
        }
        $this->db->where('id_proyek', $id);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('id_rekanan, kode_rekanan, nama_rekanan');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['view_rekanan']);
        $this->db->flush_cache();

        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'id_rekanan' => $row['id_rekanan'],
                'kode_rekanan' => $row['kode_rekanan'],
                'nama_rekanan' => $row['nama_rekanan']
            );
        }
        return $temp_result;
    }

    public function PopupCountAll() {
        return $this->_countAll;
    }

    public function getpicker($cond) {
        $this->db->select('kode_rekanan, nama_rekanan');
        $this->db->from($this->_table['view_rekanan']);
        $this->db->where($cond);
        $query = $this->db->get();

        return $query->row_array();
    }

    public function checkKodeRekanan($kode_rekanan, $id_proyek) {

        $this->db->select('kode_rekanan');
        $this->db->from($this->_table['view_rekanan']);
        $this->db->where('kode_rekanan', $kode_rekanan);
        $this->db->where('id_proyek', $id_proyek);
        $this->db->limit(1);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function delete($id) {
        $this->db->trans_start();
        if (is_array($id)) {
            $this->db->where_in('id_rekanan', $id);
        } else {
            $this->db->where('id_rekanan', $id);
        }
        $this->db->delete($this->_table['tbl_rekanan']);
        $this->db->trans_complete();
		
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menghapus data master rekanan',
											'log_params' => json_encode(array('id_rekanan' => $id))
										)
									  );
        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function integer($str) {
        return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
    }

    public function cekId($id) {

        if (!empty($id) AND $this->integer($id)) {
            $this->db->select("*");
            $this->db->from($this->_table['tbl_rekanan']);
            $this->db->where("id_rekanan", $id);
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
        $this->db->from($this->_table['view_rekanan']);
        $this->db->where("id_rekanan", $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return array(
                'id_subunitkerja' => $row['id_subunitkerja'],
                'id_rekanan' => $row['id_rekanan'],
                'id_proyek' => $row['id_proyek'],
                'kode_rekanan' => $row['kode_rekanan'],
                'nama_rekanan' => $row['nama_rekanan'],
                'alamat' => $row['alamat'],
                'kota' => $row['kota'],
                'telp_rekanan' => $row['telp_rekanan'],
                'nama_kontak' => $row['nama_kontak'],
                'telp_kontak' => $row['telp_kontak'],
                'type_rekanan' => $row['type_rekanan']
            );
        } else {
            return array();
        }
    }

    public function update($data, $id) {
        $this->db->trans_start();
        $this->db->where('id_rekanan', $id);
        $this->db->update($this->_table['tbl_rekanan'], $data);
        $this->db->trans_complete();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengedit data master rekanan',
											'log_params' => json_encode($data)
										)
									  );
        if ($this->db->trans_status() === TRUE) {
            $this->db->select('kode_rekanan');
            $this->db->from($this->_table['tbl_rekanan']);
            $this->db->where('id_rekanan', $id);
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $row = $query->row_array();
                return $row['kode_rekanan'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function update_bukubantu($data, $kode_rekanan) {
        $this->db->trans_start();
        $this->db->delete('tbl_bukubantu', array('bukubantu_kdrekanan' => $kode_rekanan));
        $this->db->insert_batch('tbl_bukubantu', $data);
        $this->db->trans_complete();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengedit data master buku bantu',
											'log_params' => json_encode($data)
										)
									  );
        if ($this->db->trans_status() === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function autocomplete($param, $id_proyek, $id_perkiraan) {

        $sql = "SELECT kode_rekanan,
                    nama_rekanan
                FROM tbl_rekanan a
                    left join tbl_bukubantu b on (b.bukubantu_kdrekanan = a.kode_rekanan and b.bukubantu_id_proyek = a.id_proyek)
                where ((lower(a.kode_rekanan) like '%" . strtolower($param) . "%' OR lower(a.nama_rekanan) like '%" . strtolower($param) . "%') AND
                    (b.bukubantu_dperkir_id = '" . $id_perkiraan . "') AND
                    (a.id_proyek = " . $id_proyek . "))";

        $query = $this->db->query($sql);

        $temp_result = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $temp_result[] = array(
                    'id' => $row['kode_rekanan'],
                    'desc' => $row['nama_rekanan'],
                    'label' => $row['kode_rekanan'] . " - " . $row['nama_rekanan']
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

    public function getConfigUser($id) {

        $this->db->select('tbl_userconfig.*');
        $this->db->from($this->_table['tbl_user']);
        $this->db->join($this->_table['tbl_userconfig'], 'tbl_user.user_id = tbl_userconfig.user_id');
        $this->db->where("tbl_user.user_id", $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return array(
                'id_userconfig' => $row['id_userconfig'],
                'user_id' => $row['user_id'],
                'kolom1' => $row['kolom1'],
                'kolom2' => $row['kolom2'],
                'kolom3' => $row['kolom3'],
                'kolom4' => $row['kolom4']
            );
        } else {
            return array();
        }
    }

    public function getDomain($id) {
        $this->db->select('*');
        $this->db->from($this->_table['tbl_proyek']);
        $this->db->where("id_proyek", $id);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return array(
                'id_proyek' => $row['id_proyek'],
                'kode_proyek' => $row['kode_proyek'],
                'id_katproyek' => $row['id_katproyek'],
                'id_subunitkerja' => $row['id_subunitkerja'],
                'nama_proyek' => $row['nama_proyek']
            );
        } else {
            return array();
        }
    }

}
