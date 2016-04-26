<?php

class transaksi_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }

    public function insertJornal() {
        $this->db->trans_begin();
        $this->db->query('AN SQL QUERY...');
        $this->db->query('ANOTHER QUERY...');
        $this->db->query('AND YET ANOTHER QUERY...');
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    public function getNobukti($tanggal, $kode_proyek, $tipe) {
        $query = $this->db->query("select get_nobukti('" . $tanggal . "', '" . $kode_proyek . "', '" . $tipe . "') as no_bukti");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['no_bukti'];
        } else {
            return false;
        }
    }

    public function getGid() {
        $query = $this->db->query("select getgid() as gid");
        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['gid'];
        } else {
            return false;
        }
    }

}