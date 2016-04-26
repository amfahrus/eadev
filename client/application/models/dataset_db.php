<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

Class dataset_db extends CI_Model {

    protected $_table;
    protected $_idGroup;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    public function getNavs($parent) {
        $this->db->select('tbl_modules.id_modules,tbl_modules.modules,tbl_modules.link,tbl_modules.icon');
        $this->db->from('tbl_modules');
        $this->db->join('tbl_akses', 'tbl_akses.id_modules = tbl_modules.id_modules', 'left');
        $this->db->join('tbl_groups', 'tbl_groups.id_group = tbl_akses.id_group', 'left');
        $this->db->where('tbl_modules.publish', 'true');
        $this->db->where('tbl_modules.parent', $parent);
        $this->db->where('tbl_groups.id_group', $this->session->userdata('ba_id_group'));
        $this->db->order_by("sort", "asc");
        $query = $this->db->get();
        return $query->result_array();
    }

    public function buildOldNav($p) {
        $rs = $this->getNavs($p);
        $nav = array();
        foreach ($rs as $row) {
            $nav[] = array('row' => $row, 'child' => $this->buildNav($row['id_modules']));
        }
        return $nav;
    }
    
    public function buildNav() {
        $this->db->select("*");
        $this->db->from($this->_table['menu_v']);
        $this->db->where("id_group", $this->session->userdata($this->myauth->getPrefix() . 'id_group'));
        //$this->db->where("parent", $parent);
        $query = $this->db->get();

        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'id_modules' => $row['id_modules'],
                'id_group' => $row['id_group'],
                'modules' => $row['modules'],
                'module_icon' => $row['icon_value'],
                'parent' => $row['parent'],
                'publish' => $row['publish'],
                'link' => $row['link'],
            );
        }
        return $temp_result;
    }
    /*
    public function buildNav() {
		$this->db->select('tbl_groups.id_group, tbl_modules.id_modules,tbl_modules.modules,tbl_modules.link,tbl_modules.icon,tbl_modules.parent,tbl_modules.publish');
        $this->db->from('tbl_modules');
        $this->db->join('tbl_akses', 'tbl_akses.id_modules = tbl_modules.id_modules', 'left');
        $this->db->join('tbl_groups', 'tbl_groups.id_group = tbl_akses.id_group', 'left');
        $this->db->where('tbl_modules.publish', 'true');
        $this->db->where('tbl_groups.id_group', $this->session->userdata('ba_id_group'));
        $this->db->order_by("sort", "asc");
        $query = $this->db->get();

        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'module_id' => $row['id_modules'],
                'group_id' => $row['id_group'],
                'module_name' => $row['modules'],
                'module_icon' => $row['icon'],
                'module_parent' => $row['parent'],
                'module_publish' => $row['publish'],
                'module_link' => $row['link'],
            );
        }
        return $temp_result;
    }
*/
    public function getModule($module) {
        $this->db->select('id_modules,modules,link,icon_value as icon');
        $query = $this->db->get_where($this->_table['menu_v'], array('publish' => 'true', 'link' => $module));
        return $query->row_array();
    }

    public function getHakakses($id_group) {

        $sql = "select 
                a.id_group,
                c.id_modules,
                c.modules,
                c.link
                from 
                tbl_groups a,
                tbl_modules c,
                tbl_akses d
                where 
                c.id_modules = d.id_modules
                and a.id_group = d.id_group
                and a.id_group = 146
                and c.id_modules = 1";

        $query = $this->db->query($sql);
        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[] = array(
                'id_group' => $row['id_group'],
                'id_modules' => $row['id_modules'],
                'modules' => $row['modules'],
                'link' => $row['link']
            );
        }
        return $temp_result;
    }

    public function getGroups() {
        $this->db->select('id_group, nama_group');
        $this->db->from($this->_table['tbl_groups']);
        $this->db->order_by('nama_group', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result[$row['id_group']] = $row['nama_group'];
        }
        return $temp_result;
    }

    public function getIdModul($link) {

        $sql = "select * from tbl_modules where link = '" . $link . "'";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function setIdGroup($id) {
        $this->_idGroup = (int) $id;
    }

    public function getCheck($parent) {
        $sql = "SELECT 
                a.id_modules,a.modules,a.link,a.icon ";
        if (!empty($this->_idGroup)) {
            $sql .= ", (case when COALESCE(z.id_modules,0) = 0 then '' else 'checked' end) as is_checked ";
        }
        $sql .= " FROM tbl_modules a ";
        if (!empty($this->_idGroup)) {
            $sql .= " left join (
                        select 
                        c.id_modules
                        from 
                        tbl_akses a, 
                        tbl_groups b, 
                        tbl_modules c
                        where
                        b.id_group = a.id_group
                        and c.id_modules = a.id_modules
                        and b.id_group = " . $this->_idGroup . "
                ) z on z.id_modules = a.id_modules";
        }
        $sql .= " WHERE 
                a.publish = 'true' AND a.parent = " . $parent . " 
                ORDER BY sort asc";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function buildCheckTree($p) {
        $rs = $this->getCheck($p);
        $nav = array();
        foreach ($rs as $row) {
            $nav[] = array('row' => $row, 'child' => $this->buildCheckTree($row['id_modules']));
        }
        return $nav;
    }

    public function getUnitkerja() {

        $this->db->select('id_unitkerja, nama_unitkerja');
        $this->db->from('tbl_unitkerja');
        $this->db->order_by('nama_unitkerja', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $temp_result[''] = '';
        foreach ($query->result_array() as $row) {
            $temp_result[$row['id_unitkerja']] = $row['nama_unitkerja'];
        }
        return $temp_result;
    }

    public function getProyek() {

        $this->db->select('id_proyek, nama_proyek');
        $this->db->from('list_proyek_v');
        $this->db->order_by('nama_proyek', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $temp_result[''] = '';
        foreach ($query->result_array() as $row) {
            $temp_result[$row['id_proyek']] = $row['nama_proyek'];
        }
        return $temp_result;
    }

    public function getSubUnitkerja() {

        $isproyek = $this->session->userdata("ba_is_proyek");
        if ($isproyek == 'f') {
            $this->db->select('id_subunitkerja, nama_subunit');
            $this->db->from('tbl_subunitkerja');
            $this->db->where_in('id_subunitkerja', json_decode($this->session->userdata('ba_hak_data')));
            $this->db->order_by('nama_subunit', 'asc');
            $query = $this->db->get();

            $temp_result = array();
            foreach ($query->result_array() as $row) {
                $temp_result[$row['id_subunitkerja']] = $row['nama_subunit'];
            }
            return $temp_result;
        } else {
            $this->db->select('id_subunitkerja, nama_subunit');
            $this->db->from('list_proyek_v');
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
            $this->db->order_by('nama_subunit', 'asc');
            $query = $this->db->get();

            $temp_result = array();
            foreach ($query->result_array() as $row) {
                $temp_result[$row['id_subunitkerja']] = $row['nama_subunit'];
            }
            return $temp_result;
        }
    }
	
	public function getSubUnitKonsolidasi() {

        $isproyek = $this->session->userdata("ba_is_proyek");
        if ($isproyek == 'f') {
            $this->db->select('id_subunitkerja, nama_subunit');
            $this->db->from('tbl_subunitkerja');
            $this->db->where_in('id_subunitkerja', json_decode($this->session->userdata('ba_hak_data')));
            $this->db->where('is_proyek', 't');
            $this->db->order_by('nama_subunit', 'asc');
            $query = $this->db->get();

            $temp_result = array();
            foreach ($query->result_array() as $row) {
                $temp_result[$row['id_subunitkerja']] = $row['nama_subunit'];
            }
            return $temp_result;
        } else {
            $this->db->select('id_subunitkerja, nama_subunit');
            $this->db->from('list_proyek_v');
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
            $this->db->order_by('nama_subunit', 'asc');
            $query = $this->db->get();

            $temp_result = array();
            foreach ($query->result_array() as $row) {
                $temp_result[$row['id_subunitkerja']] = $row['nama_subunit'];
            }
            return $temp_result;
        }
    }
	
    public function getDataProyek($id = false) {

        $isproyek = $this->session->userdata("ba_is_proyek");
        if ($isproyek == 'f') {
            $this->db->select('id_proyek, nama_proyek');
            $this->db->from('list_proyek_v');
            $this->db->where('id_subunitkerja', $id);
            $this->db->order_by('nama_proyek', 'asc');
            $query = $this->db->get();

            $temp_result = array();
            foreach ($query->result_array() as $row) {
                $temp_result[$row['id_proyek']] = $row['nama_proyek'];
            }
            return $temp_result;
        } else {
            $this->db->select('id_proyek, nama_proyek');
            $this->db->from('list_proyek_v');
            $this->db->where_in('id_proyek', json_decode($this->session->userdata('ba_hak_data')));
            $this->db->order_by('nama_subunit', 'asc');
            $query = $this->db->get();

            $temp_result = array();
            foreach ($query->result_array() as $row) {
                $temp_result[$row['id_proyek']] = $row['nama_proyek'];
            }
            return $temp_result;
        }
    }

    public function getUserconfig($id_user) {
        $this->db->select('*');
        $this->db->from('tbl_userconfig');
        $this->db->where('user_id', $id_user);
        $query = $this->db->get();

        $temp_result = array();
        foreach ($query->result_array() as $row) {
            $temp_result["id_userconfig"] = $row['id_userconfig'];
            $temp_result["user_id"] = $row['user_id'];
            $temp_result["kolom1"] = $row['kolom1'];
            $temp_result["kolom2"] = $row['kolom2'];
            $temp_result["kolom3"] = $row['kolom3'];
            $temp_result["kolom4"] = $row['kolom4'];
        }
        return $temp_result;
    }
    
	public function getKeyPeriodeYear() {
        $userconfig = $this->getUserconfig($this->session->userdata('ba_user_id'));

        $this->db->select('*');
        $this->db->from('periodayear_v');
        $this->db->where('id_proyek', $userconfig["kolom2"]);
        $query = $this->db->get();
        $temp_result = array();
        if ($query->num_rows() > 0) {
            $temp_result['0'] = 'Pilih Periode';
            foreach ($query->result_array() as $row) {
                $temp_result[$row['yearperiod_key']] = $row['yearperiod_start'] . ' s/d ' . $row['yearperiod_end'];
            }
        } else {
            $temp_result['0'] = 'Tidak Ada Periode';
        }
        return $temp_result;
    }
    
    public function getPeriodeYear() {
        $userconfig = $this->getUserconfig($this->session->userdata('ba_user_id'));

        $this->db->select('*');
        $this->db->from('periodayear_v');
        $this->db->where('id_proyek', $userconfig["kolom2"]);
        $query = $this->db->get();
        $temp_result = array();
        if ($query->num_rows() > 0) {
            $temp_result['0'] = 'Pilih Periode';
            foreach ($query->result_array() as $row) {
                $temp_result[$row['yearperiod_id']] = $row['yearperiod_start'] . ' s/d ' . $row['yearperiod_end'];
            }
        } else {
            $temp_result['0'] = 'Tidak Ada Periode';
        }
        return $temp_result;
    }
    
     public function getPeriodeYearKey() {
        $userconfig = $this->getUserconfig($this->session->userdata('ba_user_id'));

        $this->db->select('*');
        $this->db->from('periodayear_v');
        $this->db->where('id_proyek', $userconfig["kolom2"]);
        $query = $this->db->get();
        $temp_result = array();
        if ($query->num_rows() > 0) {
            $temp_result['0'] = 'Pilih Periode';
            foreach ($query->result_array() as $row) {
                $temp_result[$row['yearperiod_key']] = $row['yearperiod_start'] . ' s/d ' . $row['yearperiod_end'];
            }
        } else {
            $temp_result['0'] = 'Tidak Ada Periode';
        }
        return $temp_result;
    }

    public function getPeriode($id) {
		$userconfig = $this->getUserconfig($this->session->userdata('ba_user_id'));
        $this->db->select('*');
        $this->db->from('perioda_v');
        $this->db->where('yearperiod_id', $id);
        $this->db->where('id_proyek', $userconfig["kolom2"]);
        $query = $this->db->get();
        $temp_result = array();
        if ($query->num_rows() > 0) {
            $temp_result[] = array(
                'id' => '0',
                'desc' => 'Pilih Periode'
            );
            foreach ($query->result_array() as $row) {
                $temp_result[] = array(
                    'id' => $row['period_id'],
                    'desc' => $row['period_name'] . ': ' . $row['period_start'] . ' s/d ' . $row['period_end']
                );
            }
            return $temp_result;
        } else {
            $temp_result['0'] = array(
                'id' => '0',
                'desc' => 'Tidak Ada Periode'
            );
        }
        return $temp_result;
    }
	
	public function getPeriodeKey($id) {
		$userconfig = $this->getUserconfig($this->session->userdata('ba_user_id'));
        $this->db->select('*');
        $this->db->from('perioda_v');
        $this->db->where('yearperiod_id', $id);
        $this->db->where('id_proyek', $userconfig["kolom2"]);
        $query = $this->db->get();
        $temp_result = array();
        if ($query->num_rows() > 0) {
            $temp_result[] = array(
                'id' => '0',
                'desc' => 'Pilih Periode'
            );
            foreach ($query->result_array() as $row) {
                $temp_result[] = array(
                    'id' => $row['period_key'],
                    'desc' => $row['period_name'] . ': ' . $row['period_start'] . ' s/d ' . $row['period_end']
                );
            }
            return $temp_result;
        } else {
            $temp_result['0'] = array(
                'id' => '0',
                'desc' => 'Tidak Ada Periode'
            );
        }
        return $temp_result;
    }
    
	
    public function cekRekanan($idProyek, $kode) {

        $this->db->select('kode_rekanan');
        $this->db->from("rekanan_v");
        $this->db->where('kode_rekanan', $kode);
        $this->db->where('id_proyek', $idProyek);
        $this->db->limit(1);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }

    public function cekSbdaya($kode = 0, $idProyek) {

        $this->db->select('email');
        $this->db->from("rekanan_v");
        $this->db->where('kode_rekanan', $kode);
        $this->db->where('id_proyek', $idProyek);
        $this->db->limit(1);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return TRUE;
        } else {
            return false;
        }
    }
    
    public function insert_logs($data) {
        $this->db->insert('log', $data);
		return $this->db->insert_id();
    }
}
