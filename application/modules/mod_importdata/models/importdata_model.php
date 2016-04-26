<?php

class importdata_model extends CI_Model {

    protected $_table;
    protected $_countAll;

    public function __construct() {
        parent::__construct();
        $this->config->load('database_tables', TRUE);
        $this->_table = $this->config->item('database_tables');
    }
	
	public function upsert($data){
		$sql = '';
		if(is_array($data)){
			//print_r($data);
			// table name
			foreach($data as $k => $v){
				/*if (array_key_exists('coloumn', $v)){
					print_r($v['coloumn']);
				}*/
				
				//die($data['kode_proyek']);
				
				switch ($k) {
					/*case 'yearperiod':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getYearPeriod($v['values'],$data['kode_proyek']);
						}
						break;*/
					case 'tbl_jurnal':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getJurnal($v['values'],$data['kode_proyek']);
						}
						break;
					case 'tbl_bukubantu':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getBukuBantu($v['values'],$data['kode_proyek']);
						}
						break;
					case 'saldo_awal_detail':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getSaldoAwalDetail($v['values'],$data['kode_proyek']);
						}
						break;
					case 'saldo_perekanan':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getSaldoPerekanan($v['values'],$data['kode_proyek']);
						}
						break;
					case 'tbl_dperkir_group_saldo':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getGroupSaldo($v['values'],$data['kode_proyek']);
						}
						break;
					case 'trialbal':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getTrialbal($v['values'],$data['kode_proyek']);
						}
						break;
					case 'tbl_rekanan':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getRekanan($v['values'],$data['kode_proyek']);
						}
						break;
					case 'tbl_sbdaya':
						if (array_key_exists('values', $v)){
							//print_r($v['values']);
							$sql .= $this->getSBDaya($v['values'],$data['kode_proyek']);
						}
						break;
				}
				
			}
		}
		//$this->db->query($sql);
		echo $sql;
	}
	
    /*private function getYearPeriod($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$id_proyek = $this->getIdProyek($kode_proyek);
				$yearperiod_key = !empty($val['yearperiod_key'])?$val['yearperiod_key']:'NULL';
				$yearperiod_start = !empty($val['yearperiod_start'])?"'".$val['yearperiod_start']."'":'NULL';
				$yearperiod_end = !empty($val['yearperiod_end'])?"'".$val['yearperiod_end']."'":'NULL';
				$yearperiod_closed = !empty($val['yearperiod_closed'])?"'".$val['yearperiod_closed']."'":'NULL';
				$create_id = !empty($val['create_id'])?$val['create_id']:'NULL';
				$create_time = !empty($val['create_time'])?"'".$val['create_time']."'":'NULL';
				$create_ip = !empty($val['create_ip'])?"'".$val['create_ip']."'":'NULL';
				$modify_id = !empty($val['modify_id'])?$val['modify_id']:'NULL';
				$modify_time = !empty($val['modify_time'])?"'".$val['modify_time']."'":'NULL';
				$modify_ip = !empty($val['modify_ip'])?"'".$val['modify_ip']."'":'NULL';
				$tmp .= "UPDATE yearperiod
					SET yearperiod_start=".$yearperiod_start.", yearperiod_end=".$yearperiod_end.", yearperiod_closed=".$yearperiod_closed.", 
						create_id=".$create_id.", create_time=".$create_time.", create_ip=".$create_ip.", 
						modify_id=".$modify_id.", modify_time=".$modify_time.", modify_ip=".$modify_ip."
					WHERE yearperiod_key=".$yearperiod_key." AND id_proyek=".$id_proyek.";
					SELECT yearperiodcreateauto($yearperiod_start, $yearperiod_end, $id_proyek);";
			}
		}
		return $tmp;
	}*/
    
    private function getJurnal($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$tanggal = !empty($val['tanggal'])?"'".$val['tanggal']."'":'NULL';
				$nobukti = !empty($val['nobukti'])?"'".$val['nobukti']."'":'NULL';
				$dperkir_id = !empty($val['dperkir_id'])?$val['dperkir_id']:'NULL';
				$id_proyek = $this->getIdProyek($kode_proyek);
				$kdnasabah = !empty($val['kdnasabah'])?"'".$val['kdnasabah']."'":'NULL';
				$kdsbdaya = !empty($val['kdsbdaya'])?"'".$val['kdsbdaya']."'":'NULL';
				$keterangan = !empty($val['keterangan'])?"'".$val['keterangan']."'":'NULL';
				$volume = !empty($val['volume'])?$val['volume']:'NULL';
				$dk = !empty($val['dk'])?"'".$val['dk']."'":'NULL';
				$rupiah = !empty($val['rupiah'])?$val['rupiah']:'NULL';
				$create_id = !empty($val['create_id'])?$val['create_id']:'NULL';
				$create_time = !empty($val['create_time'])?"'".$val['create_time']."'":'NULL';
				$create_ip = !empty($val['create_ip'])?"'".$val['create_ip']."'":'NULL';
				$modify_id = !empty($val['modify_id'])?$val['modify_id']:'NULL';
				$modify_time = !empty($val['modify_time'])?"'".$val['modify_time']."'":'NULL';
				$modify_ip = !empty($val['modify_ip'])?"'".$val['modify_ip']."'":'NULL';
				$gid = !empty($val['gid'])?$val['gid']:'NULL';
				$is_delete = !empty($val['isdelete'])?"'".$val['isdelete']."'":'NULL';
				$tempjurnal_jenisjurnal_id = !empty($val['jenisjurnal_id'])?$val['jenisjurnal_id']:'NULL';
				$no_dokumen = !empty($val['no_dokumen'])?"'".$val['no_dokumen']."'":'NULL';
				$tmp .= "INSERT INTO tbl_jurnal(
						tanggal, nobukti, dperkir_id, id_proyek, kdnasabah, 
						kdsbdaya, keterangan, volume, dk, rupiah, 
						create_id, create_time, create_ip, modify_id, modify_time, modify_ip, 
						gid, jurnal_jenisjurnal_id, no_dokumen)
						   SELECT 
								".$tanggal.", ".$nobukti.", ".$dperkir_id.", ".$id_proyek.", ".$kdnasabah.",
								".$kdsbdaya.", ".$keterangan.", ".$volume.", ".$dk.", ".$rupiah.",
								".$create_id.", ".$create_time.", ".$create_ip.", ".$modify_id.", ".$modify_time.", ".$modify_ip.",
								".$gid.", ".$tempjurnal_jenisjurnal_id.", ".$no_dokumen."	
						   WHERE NOT EXISTS (SELECT 1 FROM tbl_jurnal WHERE nobukti=".$nobukti." AND id_proyek=".$id_proyek." AND dperkir_id=".$dperkir_id." AND gid=".$gid.");";
			}
		}
		return $tmp;
	}
    
    private function getBukuBantu($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$bukubantu_id_proyek = $this->getIdProyek($kode_proyek);
				$bukubantu_dperkir_id = !empty($val['bukubantu_dperkir_id'])?$val['bukubantu_dperkir_id']:'NULL';
				$bukubantu_kdrekanan = !empty($val['bukubantu_kdrekanan'])?"'".$val['bukubantu_kdrekanan']."'":'NULL';
				$bukubantu_isrekanan = !empty($val['bukubantu_isrekanan'])?"'".$val['bukubantu_isrekanan']."'":'NULL';
				$bukubantu_issbdaya = !empty($val['bukubantu_issbdaya'])?"'".$val['bukubantu_issbdaya']."'":'NULL';
				$tmp .= "UPDATE tbl_bukubantu
						SET bukubantu_id_proyek=".$bukubantu_id_proyek.", bukubantu_dperkir_id=".$bukubantu_dperkir_id.", 
							   bukubantu_kdrekanan=".$bukubantu_kdrekanan.", bukubantu_isrekanan=".$bukubantu_isrekanan.",
							   bukubantu_issbdaya=".$bukubantu_issbdaya."
						WHERE bukubantu_id_proyek=".$bukubantu_id_proyek." AND bukubantu_dperkir_id=".$bukubantu_dperkir_id." AND bukubantu_kdrekanan=".$bukubantu_kdrekanan.";
						INSERT INTO tbl_bukubantu(
							bukubantu_id_proyek, bukubantu_dperkir_id, bukubantu_kdrekanan, 
							bukubantu_isrekanan, bukubantu_issbdaya)
						   SELECT 
								".$bukubantu_id_proyek.", ".$bukubantu_dperkir_id.", ".$bukubantu_kdrekanan.",
								".$bukubantu_isrekanan.", ".$bukubantu_issbdaya."
						   WHERE NOT EXISTS (SELECT 1 FROM tbl_bukubantu WHERE bukubantu_id_proyek=".$bukubantu_id_proyek." AND bukubantu_dperkir_id=".$bukubantu_dperkir_id." AND bukubantu_kdrekanan=".$bukubantu_kdrekanan.");";
			}
		}
		return $tmp;
	}
	
	private function getSaldoAwalDetail($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$id_proyek = $this->getIdProyek($kode_proyek);
				$period_key = !empty($val['period_key'])?$val['period_key']:'NULL';
				$dperkir_id = !empty($val['dperkir_id'])?$val['dperkir_id']:'NULL';
				$kdnasabah = !empty($val['kdnasabah'])?"'".$val['kdnasabah']."'":'NULL';
				$kdsbdaya = !empty($val['kdsbdaya'])?"'".$val['kdsbdaya']."'":'NULL';
				$keterangan = !empty($val['keterangan'])?"'".$val['keterangan']."'":'NULL';
				$debet = !empty($val['debet'])?$val['debet']:'NULL';
				$kredit = !empty($val['kredit'])?$val['kredit']:'NULL';
				$tmp .= "UPDATE saldo_awal_detail
						SET id_proyek=".$id_proyek.", period_key=".$period_key.", 
							   dperkir_id=".$dperkir_id.", kdnasabah=".$kdnasabah.",
							   kdsbdaya=".$kdsbdaya.", keterangan=".$keterangan.",
							   debet=".$debet.", kredit=".$kredit."
						WHERE id_proyek=".$id_proyek." AND 
								dperkir_id=".$dperkir_id." AND 
								period_key=".$period_key." AND
								kdnasabah=".$kdnasabah.";
						INSERT INTO saldo_awal_detail(
							id_proyek, period_key, dperkir_id, 
							kdnasabah, kdsbdaya, keterangan, 
							debet, kredit)
						   SELECT 
								".$id_proyek.", ".$period_key.", ".$dperkir_id.",
								".$kdnasabah.", ".$kdsbdaya.", ".$keterangan.",
								".$debet.", ".$kredit."
						   WHERE NOT EXISTS (SELECT 1 FROM saldo_awal_detail WHERE id_proyek=".$id_proyek." AND dperkir_id=".$dperkir_id." AND period_key=".$period_key.");";
			}
		}
		return $tmp;
	}
    
	private function getSaldoPerekanan($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$saldo_perekanan_id_proyek = $this->getIdProyek($kode_proyek);
				$saldo_perekanan_period_key = !empty($val['saldo_perekanan_period_key'])?$val['saldo_perekanan_period_key']:'NULL';
				$saldo_perekanan_dperkir_id = !empty($val['saldo_perekanan_dperkir_id'])?$val['saldo_perekanan_dperkir_id']:'NULL';
				$saldo_perekanan_kode_rekanan = !empty($val['saldo_perekanan_kode_rekanan'])?"'".$val['saldo_perekanan_kode_rekanan']."'":'NULL';
				$saldo_perekanan_beginning = !empty($val['saldo_perekanan_beginning'])?$val['saldo_perekanan_beginning']:'NULL';
				$saldo_perekanan_ending = !empty($val['saldo_perekanan_ending'])?$val['saldo_perekanan_ending']:'NULL';
				$saldo_perekanan_credits = !empty($val['saldo_perekanan_credits'])?$val['saldo_perekanan_credits']:'NULL';
				$saldo_perekanan_debits = !empty($val['saldo_perekanan_debits'])?$val['saldo_perekanan_debits']:'NULL';
				$saldo_perekanan_dirty = !empty($val['saldo_perekanan_dirty'])?$val['saldo_perekanan_dirty']:'NULL';
				$saldo_perekanan_yearend = !empty($val['saldo_perekanan_yearend'])?$val['saldo_perekanan_yearend']:'NULL';
				$tmp .= "UPDATE saldo_perekanan
						SET saldo_perekanan_id_proyek=".$saldo_perekanan_id_proyek.", 
							saldo_perekanan_period_key=".$saldo_perekanan_period_key.", 
							saldo_perekanan_dperkir_id=".$saldo_perekanan_dperkir_id.", 
							saldo_perekanan_kode_rekanan=".$saldo_perekanan_kode_rekanan.",
							saldo_perekanan_beginning=".$saldo_perekanan_beginning.", 
							saldo_perekanan_ending=".$saldo_perekanan_ending.",
							saldo_perekanan_credits=".$saldo_perekanan_credits.", 
							saldo_perekanan_debits=".$saldo_perekanan_debits.",
							saldo_perekanan_dirty=".$saldo_perekanan_dirty.",
							saldo_perekanan_yearend=".$saldo_perekanan_yearend."
						WHERE saldo_perekanan_id_proyek=".$saldo_perekanan_id_proyek." AND 
								saldo_perekanan_dperkir_id=".$saldo_perekanan_dperkir_id." AND 
								saldo_perekanan_period_key=".$saldo_perekanan_period_key." AND
								saldo_perekanan_kode_rekanan=".$saldo_perekanan_kode_rekanan.";
						INSERT INTO saldo_perekanan(
							saldo_perekanan_id_proyek, saldo_perekanan_period_key, saldo_perekanan_dperkir_id, 
							saldo_perekanan_kode_rekanan, saldo_perekanan_beginning, saldo_perekanan_ending, 
							saldo_perekanan_credits, saldo_perekanan_debits,saldo_perekanan_dirty,saldo_perekanan_yearend)
						   SELECT 
								".$saldo_perekanan_id_proyek.", ".$saldo_perekanan_period_key.", ".$saldo_perekanan_dperkir_id.",
								".$saldo_perekanan_kode_rekanan.", ".$saldo_perekanan_beginning.", ".$saldo_perekanan_ending.",
								".$saldo_perekanan_credits.", ".$saldo_perekanan_debits.", ".$saldo_perekanan_dirty.",
								".$saldo_perekanan_yearend."
						   WHERE NOT EXISTS (SELECT 1 FROM saldo_perekanan WHERE saldo_perekanan_id_proyek=".$saldo_perekanan_id_proyek." AND 
																				saldo_perekanan_dperkir_id=".$saldo_perekanan_dperkir_id." AND 
																				saldo_perekanan_period_key=".$saldo_perekanan_period_key." AND
																				saldo_perekanan_kode_rekanan=".$saldo_perekanan_kode_rekanan.");";
			}
		}
		return $tmp;
	}
	
	private function getGroupSaldo($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$id_proyek = $this->getIdProyek($kode_proyek);
				$dgs_period_key = !empty($val['dgs_period_key'])?$val['dgs_period_key']:'NULL';
				$dgs_dperkir_group_id = !empty($val['dgs_dperkir_group_id'])?$val['dgs_dperkir_group_id']:'NULL';
				$dgs_beginning = !empty($val['saldo_perekanan_beginning'])?$val['saldo_perekanan_beginning']:'NULL';
				$dgs_ending = !empty($val['saldo_perekanan_ending'])?$val['saldo_perekanan_ending']:'NULL';
				$dgs_credits = !empty($val['saldo_perekanan_credits'])?$val['saldo_perekanan_credits']:'NULL';
				$dgs_debits = !empty($val['saldo_perekanan_debits'])?$val['saldo_perekanan_debits']:'NULL';
				$dgs_dirty = !empty($val['saldo_perekanan_dirty'])?$val['saldo_perekanan_dirty']:'NULL';
				$dgs_yearend = !empty($val['saldo_perekanan_yearend'])?$val['saldo_perekanan_yearend']:'NULL';
				$tmp .= "UPDATE tbl_dperkir_group_saldo
						SET id_proyek=".$id_proyek.", 
							dgs_period_key=".$dgs_period_key.", 
							dgs_dperkir_group_id=".$dgs_dperkir_group_id.", 
							dgs_beginning=".$dgs_beginning.",
							dgs_ending=".$dgs_ending.", 
							dgs_credits=".$dgs_credits.",
							dgs_debits=".$dgs_debits.", 
							dgs_dirty=".$dgs_dirty.",
							dgs_yearend=".$dgs_yearend."
						WHERE id_proyek=".$id_proyek." AND 
								dgs_dperkir_group_id=".$dgs_dperkir_group_id." AND 
								dgs_period_key=".$dgs_period_key.";
						INSERT INTO tbl_dperkir_group_saldo(
							id_proyek, dgs_period_key, dgs_dperkir_group_id, 
							dgs_beginning, dgs_ending, dgs_credits, 
							dgs_debits, dgs_dirty,dgs_yearend)
						   SELECT 
								".$id_proyek.", ".$dgs_period_key.", ".$dgs_dperkir_group_id.",
								".$dgs_beginning.", ".$dgs_ending.", ".$dgs_credits.",
								".$dgs_debits.", ".$dgs_dirty.", ".$dgs_yearend."
						   WHERE NOT EXISTS (SELECT 1 FROM tbl_dperkir_group_saldo WHERE id_proyek=".$id_proyek." AND 
																				dgs_dperkir_group_id=".$dgs_dperkir_group_id." AND 
																				dgs_period_key=".$dgs_period_key.");";
			}
		}
		return $tmp;
	}
	
	private function getTrialbal($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$id_proyek = $this->getIdProyek($kode_proyek);
				$trialbal_period_key = !empty($val['trialbal_period_key'])?$val['trialbal_period_key']:'NULL';
				$trialbal_dperkir_id = !empty($val['trialbal_dperkir_id'])?$val['trialbal_dperkir_id']:'NULL';
				$trialbal_beginning = !empty($val['trialbal_beginning'])?$val['trialbal_beginning']:'NULL';
				$trialbal_ending = !empty($val['trialbal_ending'])?$val['trialbal_ending']:'NULL';
				$trialbal_credits = !empty($val['trialbal_credits'])?$val['trialbal_credits']:'NULL';
				$trialbal_debits = !empty($val['trialbal_debits'])?$val['trialbal_debits']:'NULL';
				$trialbal_dirty = !empty($val['trialbal_dirty'])?$val['trialbal_dirty']:'NULL';
				$trialbal_yearend = !empty($val['trialbal_yearend'])?$val['trialbal_yearend']:'NULL';
				$tmp .= "UPDATE trialbal
						SET id_proyek=".$id_proyek.", 
							trialbal_period_key=".$trialbal_period_key.", 
							trialbal_dperkir_id=".$trialbal_dperkir_id.", 
							trialbal_beginning=".$trialbal_beginning.",
							trialbal_ending=".$trialbal_ending.", 
							trialbal_credits=".$trialbal_credits.",
							trialbal_debits=".$trialbal_debits.", 
							trialbal_dirty=".$trialbal_dirty.",
							trialbal_yearend=".$trialbal_yearend."
						WHERE id_proyek=".$id_proyek." AND 
								trialbal_dperkir_id=".$trialbal_dperkir_id." AND 
								trialbal_period_key=".$trialbal_period_key.";
						INSERT INTO trialbal(
							id_proyek, trialbal_period_key, trialbal_dperkir_id, 
							trialbal_beginning, trialbal_ending, trialbal_credits, 
							trialbal_debits, trialbal_dirty,trialbal_yearend)
						   SELECT 
								".$id_proyek.", ".$trialbal_period_key.", ".$trialbal_dperkir_id.",
								".$trialbal_beginning.", ".$trialbal_ending.", ".$trialbal_credits.",
								".$trialbal_debits.", ".$trialbal_dirty.", ".$trialbal_yearend."
						   WHERE NOT EXISTS (SELECT 1 FROM tbl_dperkir_group_saldo WHERE id_proyek=".$id_proyek." AND 
																				trialbal_dperkir_id=".$trialbal_dperkir_id." AND 
																				trialbal_period_key=".$trialbal_period_key.");";
			}
		}
		return $tmp;
	}
	
    private function getRekanan($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$id_proyek = $this->getIdProyek($kode_proyek);
				$kode_rekanan = !empty($val['kode_rekanan'])?"'".$val['kode_rekanan']."'":'NULL';
				$nama_rekanan = !empty($val['nama_rekanan'])?"'".$val['nama_rekanan']."'":'NULL';
				$alamat = !empty($val['alamat'])?"'".$val['alamat']."'":'NULL';
				$kota = !empty($val['kota'])?"'".$val['kota']."'":'NULL';
				$telp_rekanan = !empty($val['telp_rekanan'])?"'".$val['telp_rekanan']."'":'NULL';
				$nama_kontak = !empty($val['nama_kontak'])?"'".$val['nama_kontak']."'":'NULL';
				$telp_kontak = !empty($val['telp_kontak'])?"'".$val['telp_kontak']."'":'NULL';
				$type_rekanan = !empty($val['type_rekanan'])?$val['type_rekanan']:'NULL';
				$create_id = !empty($val['create_id'])?$val['create_id']:'NULL';
				$create_time = !empty($val['create_time'])?"'".$val['create_time']."'":'NULL';
				$modify_id = !empty($val['modify_id'])?$val['modify_id']:'NULL';
				$modify_time = !empty($val['modify_time'])?"'".$val['modify_time']."'":'NULL';
				$tmp .= "UPDATE tbl_rekanan
						SET id_proyek=".$id_proyek.", kode_rekanan=".$kode_rekanan.", nama_rekanan=".$nama_rekanan.", alamat=".$alamat.", 
						   kota=".$kota.", telp_rekanan=".$telp_rekanan.", nama_kontak=".$nama_kontak.", telp_kontak=".$telp_kontak.", 
						   type_rekanan=".$type_rekanan.", create_id=".$create_id.", create_time=".$create_time.", modify_id=".$modify_id.", modify_time=".$modify_time."
						WHERE id_proyek=".$id_proyek." AND kode_rekanan=".$kode_rekanan.";
						INSERT INTO tbl_rekanan(
							id_proyek, kode_rekanan, nama_rekanan, alamat, kota, 
							telp_rekanan, nama_kontak, telp_kontak, type_rekanan, create_id, 
							create_time, modify_id, modify_time)
						   SELECT 
								".$id_proyek.", ".$kode_rekanan.", ".$nama_rekanan.", ".$alamat.", ".$kota.",
								".$telp_rekanan.", ".$nama_kontak.", ".$telp_kontak.", ".$type_rekanan.", ".$create_id.",
								".$create_time.", ".$modify_id.", ".$modify_time."
						   WHERE NOT EXISTS (SELECT 1 FROM tbl_rekanan WHERE id_proyek=".$id_proyek." AND kode_rekanan=".$kode_rekanan.");";
			}
		}
		return $tmp;
	}
    
    private function getSBDaya($data,$kode_proyek){
		$tmp = '';
		if(is_array($data)){
			foreach($data as $key => $val){
				//die(print_r($val));
				
				$id_proyek = $this->getIdProyek($kode_proyek);
				$tipe = !empty($val['tipe'])?$val['tipe']:'NULL';
				$kode_sbdaya = !empty($val['kode_sbdaya'])?"'".$val['kode_sbdaya']."'":'NULL';
				$nama_sbdaya = !empty($val['nama_sbdaya'])?"'".$val['nama_sbdaya']."'":'NULL';
				$satuan = !empty($val['satuan'])?"'".$val['satuan']."'":'NULL';
				$dperkir_id = !empty($val['dperkir_id'])?$val['dperkir_id']:'NULL';
				$create_id = !empty($val['create_id'])?$val['create_id']:'NULL';
				$create_time = !empty($val['create_time'])?"'".$val['create_time']."'":'NULL';
				$modify_id = !empty($val['modify_id'])?$val['modify_id']:'NULL';
				$modify_time = !empty($val['modify_time'])?"'".$val['modify_time']."'":'NULL';
				$tmp .= "UPDATE tbl_sbdaya
						SET id_proyek=".$id_proyek.", tipe=".$tipe.", kode_sbdaya=".$kode_sbdaya.", nama_sbdaya=".$nama_sbdaya.", 
						   satuan=".$satuan.", dperkir_id=".$dperkir_id.", create_id=".$create_id.", create_time=".$create_time.", 
						   modify_id=".$modify_id.", modify_time=".$modify_time."
						WHERE id_proyek=".$id_proyek." AND kode_sbdaya=".$kode_sbdaya." 
							AND dperkir_id=".$dperkir_id.";
						INSERT INTO tbl_sbdaya(
							id_proyek, tipe, kode_sbdaya, nama_sbdaya, satuan, 
							dperkir_id, create_id, create_time, modify_id, modify_time)
						   SELECT 
								".$id_proyek.", ".$tipe.", ".$kode_sbdaya.", ".$nama_sbdaya.", ".$satuan.",
								".$dperkir_id.", ".$create_id.", ".$create_time.",
								".$modify_id.", ".$modify_time."
						   WHERE NOT EXISTS (SELECT 1 FROM tbl_sbdaya WHERE id_proyek=".$id_proyek." AND kode_sbdaya=".$kode_sbdaya." 
							AND dperkir_id=".$dperkir_id.");";
			}
		}
		return $tmp;
	}
    
    public function getBulan() {

        $this->db->select('b.id_library, b.nama_library');
        $this->db->from($this->_table['library'] . ' a');
        $this->db->join($this->_table['library'] . ' b', 'b.parent = a.id_library', 'left outer');
        $this->db->where('a.id_library = 15');
        $this->db->order_by('b.id_library', 'asc');
        $query = $this->db->get();

        $temp_result = array();
        $i = 1;
        foreach ($query->result_array() as $row) {
            $temp_result[$i++] = $row['nama_library'];
        }
        return $temp_result;
    }

	public function getProyekName($id) {

        $this->db->select('nama_proyek');
        $this->db->from('list_proyek_v');
        $this->db->where('id_proyek', $id);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['nama_proyek'];
        }
        return $result;
    }
	
	public function getIdProyek($kode_proyek) {

        $this->db->select('id_proyek');
        $this->db->from('list_proyek_v');
        $this->db->where('kode_proyek', $kode_proyek);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['id_proyek'];
        }
        return $result;
    }
	
	public function getPeriodName($id) {

        $this->db->select('period_name');
        $this->db->from('period');
        $this->db->where('period_key', $id);
        $query = $this->db->get();

        $result = '';
        foreach ($query->result_array() as $row) {
            $result = $row['period_name'];
        }
        return $result;
    }
	
}
