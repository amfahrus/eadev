<?php

class group_model extends CI_Model {

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

        $this->db->from($this->_table['tbl_groups']);
        $this->_countAll = $this->db->count_all_results();
        $this->db->select('*');
        $this->db->order_by($sidx, $sord);
        $this->db->limit($limit, $offset);
        $query = $this->db->get($this->_table['tbl_groups']);
        $this->db->flush_cache();

        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menampilkan semua data group akses di modul group',
											'log_params' => json_encode($cari)
										)
									  );
        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'id_group' => $row['id_group'],
                'nama_group' => $row['nama_group'],
                'keterangan' => $row['keterangan']
            );
        }
        return $temp_result;
    }

    public function countAll() {
        return $this->_countAll;
    }

    public function insert_group($data) {
        $this->db->insert($this->_table['tbl_groups'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan group di modul group',
											'log_params' => json_encode($data)
										)
									  );
        return $this->db->insert_id();
    }

    public function insert_akses($id, $data) {
        $this->db->where('id_group', $id);
        $this->db->delete($this->_table['tbl_akses']);
        $this->db->insert_batch($this->_table['tbl_akses'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menambahkan group akses di modul group',
											'log_params' => json_encode($data)
										)
									  );
    }

    public function get($id) {
        $this->db->select("*");
        $this->db->from($this->_table['tbl_groups']);
        $this->db->where("id_group", $id);
        $query = $this->db->get();
		$this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User melihat detail group akses di modul group',
											'log_params' => json_encode(array('id_group' => $id))
										)
									  );
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return array(
                'id_group' => $row['id_group'],
                'nama_group' => $row['nama_group'],
                'keterangan' => $row['keterangan']
            );
        } else {
            return array();
        }
    }

    public function getAkses($parent, $idGroup) {
        $this->db->select("*");
        $this->db->from($this->_table['view_hakakses']);
        $this->db->where("id_group", $idGroup);
        $this->db->where("parent", $parent);
        $query = $this->db->get();

        return $query->result_array();
    }

    public function buildCheckTree($p, $idGroup) {
        $rs = $this->getAkses($p, $idGroup);
        $nav = array();
        foreach ($rs as $row) {
            $nav[] = array('row' => $row, 'child' => $this->buildCheckTree($row['id_modules'], $idGroup));
        }
        return $nav;
    }

    public function update_group($data, $id) {
        $this->db->where('id_group', $id);
        $this->db->update($this->_table['tbl_groups'], $data);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User mengganti data group di modul group',
											'log_params' => json_encode($data)
										)
									  );
        return $id;
    }

    public function integer($str) {
        return (bool) preg_match('/^[\-+]?[0-9]+$/', $str);
    }

    public function cekId($id) {

        if (!empty($id) AND $this->integer($id)) {
            $this->db->select("*");
            $this->db->from($this->_table['tbl_groups']);
            $this->db->where("id_group", $id);
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

    public function delete($id) {
        if (is_array($id)) {
            $this->db->where_in('id_group', $id);
        } else {
            $this->db->where('id_group', $id);
        }
        $this->db->delete($this->_table['tbl_groups']);
        $this->dataset_db->insert_logs(
										array(
											'log_username' => $this->session->userdata('ba_username'),
											'log_node' => $_SERVER['REMOTE_ADDR'],
											'log_description' => 'User menghapus group di modul group',
											'log_params' => json_encode(array('id_group' => $id))
										)
									  );
    }

    public function getListGroupData($term) {
        $this->db->select("nama_group");
        $this->db->from($this->_table['tbl_groups']);
        $this->db->like("lower(nama_group)", strtolower($term), 'both');
        $query = $this->db->get();

        $temp_result = array();
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $temp_result[] = array(
                    'id' => $row['nama_group'],
                    'desc' => $row['nama_group'],
                    'label' => $row['nama_group']
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

}
