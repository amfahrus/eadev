<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class mod_saldo extends CI_Controller {

    private $_userconfig = array();

    public function __construct() {
        parent::__construct();
        session_start();
        $this->load->library('myauth');
        if (!$this->myauth->logged_in()) {
            if (IS_AJAX) {
                header('HTTP/1.1 401 Unauthorized');
                exit;
            } else {
                $this->session->set_userdata('redir', current_url());
                redirect('mod_user/user_auth');
            }
        }
        $this->myauth->has_role();
        $this->load->model('saldo_model');
        $this->load->model('dataset_db');
        $this->load->library("searchform");
        $this->_userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
    }

    public function index() {
        $this->list_jurnal();
    }
	
	public function test() {
		$this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url()."mod_saldo", "form_saldoawal_list", "cus-application", "List Jurnal", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url()."mod_saldo/test", "form_saldoawal_add", "cus-application-form-add", "Input Saldo Awal", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_saldoawal_delete", "cus-application-form-delete", "Hapus Saldo Awal", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();

        $data['ptitle'] = "Input Saldo Awal";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $data['tanggal'] = date("Y-m-d");
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		$data['op_yearperiode'] = $this->dataset_db->getPeriodeYear();
        $tabs['mod_saldo'] = $this->dataset_db->getModule('mod_saldo');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_saldo']['link'];
        $data['content'] = $this->load->view('saldo', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }
    
	public function edit_jurnal() {
        $this->form_validation->set_rules("saldo_awal_id", "Nomor Bukti", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {

            $saldo_awal_id = $this->input->post("saldo_awal_id");
            $this->saldo_model->get_saldoawal($saldo_awal_id);
            $jurnal = $this->vouchermem_model->getArrayNobukti();

            $_SESSION["transaksi"] = $jurnal;

            $data['redirect'] = base_url() . 'mod_vouchermem/test';
            
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }
	
    public function sess2json() {
        if (!empty($_SESSION["transaksi"]) AND isset($_SESSION["transaksi"]) AND $_SESSION["transaksi"]["jurnal"]["jenis_jurnal"] == 3) {
            $temp_result = array();
            $no = 1;
            $debet = 0;
            $kredit = 0;

            foreach ($_SESSION["transaksi"] as $keys => $value) {
                foreach ($value["detail"] as $key2 => $value2) {
                    foreach ($value2["detail"] as $key => $value3) {
                        if ($key === "D") {
                            $temp_result[] = array(
                                "act" => "<a href=\"#\" onclick=\"memorial_getSession(" .$key2. ");\" class=\"link_edit\"><img  src=\"".base_url()."media/edit.png\" /></a>",
                                "id" => $key2,
                                "check" => "<input type=\"checkbox\" value=\"" . $key2 . "\" name=\"jq_checkbox_added[]\" class=\"jq_checkbox_added\" />",
                                "no" => $no,
                                "keterangan" => $value2["keterangan"],
                                "dperkir_id" => $value3["dperkir_id"],
                                "kdperkiraan" => $value3["kdperkiraan"],
                                "bukubantu" => $value3["bukubantu"],
                                "debet" => $value3["debet"],
                                "kredit" => $value3["kredit"]
                            );
                        } else {
                            $temp_result[] = array(
                                "act" => "",
                                "id" => $key2,
                                "check" => "",
                                "no" => "",
                                "keterangan" => "",
                                "dperkir_id" => $value3["dperkir_id"],
                                "kdperkiraan" => $value3["kdperkiraan"],
                                "bukubantu" => $value3["bukubantu"],
                                "debet" => $value3["debet"],
                                "kredit" => $value3["kredit"]
                            );
                        }
                        $debet = $debet + $value3["debet"];
                        $kredit = $kredit + $value3["kredit"];
                    }
                    $no++;
                }
            }
			
			//echo "<pre>";
			//print_r($temp_result);
		
            $responce['page'] = 1;
            $responce['total'] = 1;
            $responce['records'] = 1;
            $responce['userdata']['debet'] = $debet;
            $responce['userdata']['kredit'] = $kredit;

            $i = 0;
            foreach ($temp_result as $row) {
                $responce['rows'][$i]['id'] = $row["id"];
                $responce['rows'][$i]['cell'] = array(
                    $row["act"],
                    $row["id"],
                    $row["check"],
                    $row["no"],
                    $row["keterangan"],
                    $row["dperkir_id"],
                    $row["kdperkiraan"],
                    $row["bukubantu"],
                    $row["debet"],
                    $row["kredit"]
                );
                $i++;
            }
            echo json_encode($responce);
        } else {
            $responce['page'] = 1;
            $responce['total'] = 1;
            $responce['records'] = 1;
            $responce['userdata']['debet'] = 0;
            $responce['userdata']['kredit'] = 0;
            echo json_encode($responce);
        }
    }

    public function add2db() {
        $this->form_validation->set_rules("id", "id", "numeric|xss_clean");
        $this->form_validation->set_rules("periode", "periode", "required");
		$j = 0;
		$u = 1;
		foreach($this->input->post("dperkir_id") as $arr){
			$this->form_validation->set_rules("dperkir_id[$j][value]", "ID Perkiraan ke $u", "required");
			$this->form_validation->set_rules("dperkir_kode[$j][value]", "Kode Perkiraan ke $u", "required");
			$this->form_validation->set_rules("keterangan[$j][value]", "Keterangan ke $u", "required");
			$this->form_validation->set_rules("debet[$j][value]", "Debet ke $u", "required");
			$this->form_validation->set_rules("kredit[$j][value]", "Kredit ke $u", "required");
			$j++;
			$u++;
		}
        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post("id");
            $periode = $this->input->post("periode");
            $dperkir_id = $this->input->post("dperkir_id");
            $dperkir_kode = $this->input->post("dperkir_kode");
            $dperkir_kode_bukubantu = $this->input->post("dperkir_kode_bukubantu");
            $keterangan = $this->input->post("keterangan");
            $debet = $this->input->post("debet");
            $kredit = $this->input->post("kredit");
            $is_rekanan = $this->input->post("is_rekanan");
            $is_sbdaya = $this->input->post("is_sbdaya");
            $i=0;
            foreach($dperkir_id as $val){
				$kdnasabah[$i]["value"] = ($is_rekanan[$i]["value"] == 't'? $dperkir_kode_bukubantu[$i]["value"] : '');
				$kdsbdaya[$i]["value"] = ($is_sbdaya[$i]["value"] == 't'? $dperkir_kode_bukubantu[$i]["value"] : '');
				$jurnal[] = array(
								'id_proyek' => $this->_userconfig["kolom2"],
								'period_key' => $periode,
								'dperkir_id' => $dperkir_id[$i]["value"],
								'kdnasabah' => $kdnasabah[$i]["value"],
								'kdsbdaya' => $kdsbdaya[$i]["value"],
								'keterangan' => $keterangan[$i]["value"],
								'debet' => $debet[$i]["value"],
								'kredit' => $kredit[$i]["value"]
								);
				$i++;
            }
			//echo "<pre>";
			//print_r($_SESSION["transaksi"]);
		
            $insert = $this->saldo_model->InsertJurnal($jurnal);
            if($insert){
				$data['success'] = '<p>Data Berhasil Disimpan</p>';
			} else {
				$data['error'] = '<p>Data Gagal Disimpan</p>';
			}
            $json["json"] = $data;
            $this->load->view("template/ajax", $json);
        } else {
            $data['error'] = validation_errors();
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function cek() {
        if (!empty($_SESSION["transaksi"]) AND isset($_SESSION["transaksi"])) {
            echo "<pre><tt>";
            print_r($_SESSION["transaksi"]);
            echo "</tt></pre>";
        }
    }

    public function getSessionId() {
        $this->form_validation->set_rules('id', 'id', 'required');
        if ($this->form_validation->run() == TRUE) {
            if (!empty($_SESSION["transaksi"]) and is_array($_SESSION["transaksi"])) {
                $id = $this->input->post("id");
                $transaksi = $_SESSION["transaksi"];
				//echo "<pre>";
				//print_r($_SESSION["transaksi"]);
			
                $data['success'] = '<p>Data Berhasil Dibaca</p>';
                $data['tanggal'] = $transaksi["jurnal"]["tanggal"];
                $data['nobukti'] = $transaksi["jurnal"]["nobukti"];
                $data['jenis_transaksi'] = $transaksi["jurnal"]["jenis_transaksi"];
                $data['jenis_jurnal'] = $transaksi["jurnal"]["jenis_jurnal"];
                $data['no_dokumen'] = $transaksi["jurnal"]["no_dokumen"];
                $data['id_proyek'] = $transaksi["jurnal"]["id_proyek"];
                $data['is_approved'] = $transaksi["jurnal"]["is_approved"];
                $data['record'] = $transaksi["jurnal"]["detail"][$id];
            
				//echo "<pre>";
				//print_r($data['record']);
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $data['error'] = '<p>Data Gagal Dibaca</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
        } else {
            $data['error'] = '<p>Data Gagal Dibaca</p>';
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }
	
	public function getSession() {
            if (!empty($_SESSION["transaksi"]) AND is_array($_SESSION["transaksi"]) AND $_SESSION["transaksi"]["jurnal"]["jenis_jurnal"] == 3) {
                $transaksi = $_SESSION["transaksi"];
				//echo "<pre>";
				//print_r($_SESSION["transaksi"]);
			
                $data['success'] = '<p>Data Berhasil Dibaca</p>';
                $data['tanggal'] = $transaksi["jurnal"]["tanggal"];
                $data['nobukti'] = $transaksi["jurnal"]["nobukti"];
                $data['jenis_transaksi'] = $transaksi["jurnal"]["jenis_transaksi"];
                $data['jenis_jurnal'] = $transaksi["jurnal"]["jenis_jurnal"];
                $data['no_dokumen'] = $transaksi["jurnal"]["no_dokumen"];
                $data['id_proyek'] = $transaksi["jurnal"]["id_proyek"];
                $data['is_approved'] = $transaksi["jurnal"]["is_approved"];
                $i = 0;
                foreach($transaksi["jurnal"]["detail"] as $key => $val){
					$i++;
					foreach($val["detail"] as $k => $v){
						if($k === "K"){
							$data['kredit'][$i]['dperkir_id'] = $v["dperkir_id"];
							$data['kredit'][$i]['kdperkiraan'] = $v["kdperkiraan"];
							$data['kredit'][$i]['is_rekanan'] = $v["is_rekanan"];
							$data['kredit'][$i]['is_sbdaya'] = $v["is_sbdaya"];
							$data['kredit'][$i]['bukubantu'] = $v["bukubantu"];
						} else {
							$data['debet'][$i]['dperkir_id'] = $v["dperkir_id"];
							$data['debet'][$i]['kdperkiraan'] = $v["kdperkiraan"];
							$data['debet'][$i]['is_rekanan'] = $v["is_rekanan"];
							$data['debet'][$i]['is_sbdaya'] = $v["is_sbdaya"];
							$data['debet'][$i]['bukubantu'] = $v["bukubantu"];							
						}
						$data['dk'][$i]['keterangan'] = $val["keterangan"];
						$data['dk'][$i]['nilai'] = $val["nilai"];	
					}
				}
				$data['kredit_length'] = $i;
				//echo "<pre>";
				//print_r($data);
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            } else {
                $data['error'] = '<p>Data Gagal Dibaca</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
            }
    }
	
    public function deletejurnal() {
		$this->form_validation->set_rules('id', 'id', 'required');
        if ($this->form_validation->run() == TRUE) {
				$id = $this->input->post('id');
				$this->saldo_model->deleteJurnal($id);
				
                unset($_SESSION["transaksi"]);
                /*if (!empty($jurnal) and is_array($jurnal)) {
                    $_SESSION["transaksi"]["jurnal"]["detail"] = $jurnal;
                }*/

                $data['success'] = '<p>Data Berhasil Dihapus</p>';
                $json['json'] = $data;
                $this->load->view('template/ajax', $json);
        } else {
            $data['error'] = '<p>Harap Pilih Data Yang Akan Dihapus ... !</p>';
            $json['json'] = $data;
            $this->load->view('template/ajax', $json);
        }
    }

    public function cekRekanan($username) {
        if ($this->dataset_db->check_username($this->_userconfig["kolom2"], $username)) {
            $this->form_validation->set_message('cekRekanan', "Kode Rekanan " . $username . " Telah Terdaftar, Pilih Yang Lain.");
            return false;
        } else {
            return true;
        }
    }
    
    public function cleanTransaksi() {
        unset($_SESSION["transaksi"]);
    }
    
    public function addJurnal() {
		if (!empty($_SESSION["transaksi"]) and is_array($_SESSION["transaksi"]) AND $_SESSION["transaksi"]["jurnal"]["jenis_jurnal"] == 3) {

			$transaksi = $_SESSION["transaksi"]["jurnal"];
			$userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));
			$transaksi_detail = $transaksi["detail"];
			$jurnal = array();
			$debet = 0;
			$kredit = 0;
			if(!empty($transaksi["nobukti"])){
				$nobukti = $transaksi["nobukti"];
				$this->vouchermem_model->deleteJurnal($nobukti);
			} else {
				$nobukti = $this->vouchermem_model->getNobukti($transaksi["tanggal"], $userconfig["kolom2"], $transaksi["jenis_transaksi"]);
			}
			switch ($nobukti) {
				case 'A':
					$data['error'] = '<p>Periode Accounting Untuk Tanggal Ini Belum Ada</p>';
					break;
				case 'B':
					$data['error'] = '<p>Periode Accounting Untuk Tanggal Ini Telah Dikunci</p>';
					break;

				default:
					foreach ($transaksi_detail as $key => $value) {
						$gid = $this->vouchermem_model->getGid($userconfig["kolom2"]);
							foreach($value["detail"] as $k => $v){
								if($v["dk"] == "D"){
									$jurnal[] = array(
										'tanggal' => $transaksi["tanggal"],
										'nobukti' => $nobukti,
										'dperkir_id' => $v["dperkir_id"],
										'id_proyek' => $userconfig["kolom2"],
										'kdnasabah' => $v["kdnasabah"],
										'keterangan' => $value["keterangan"],
										'dk' => $v["dk"],
										'rupiah' => $value["nilai"],
										'create_id' => $this->session->userdata('ba_user_id'),
										'create_time' => $this->myauth->timestampIndo(),
										'gid' => $gid,
										'tempjurnal_jenisjurnal_id' => 3,
										'no_dokumen' => $transaksi["no_dokumen"]
									);
								$debet = $debet + $value["nilai"];
								} else {
									$jurnal[] = array(
										'tanggal' => $transaksi["tanggal"],
										'nobukti' => $nobukti,
										'dperkir_id' => $v["dperkir_id"],
										'id_proyek' => $userconfig["kolom2"],
										'kdnasabah' => "",
										'keterangan' => $value["keterangan"],
										'dk' => $v["dk"],
										'rupiah' => $value["nilai"] * -1,
										'create_id' => $this->session->userdata('ba_user_id'),
										'create_time' => $this->myauth->timestampIndo(),
										'gid' => $gid,
										'tempjurnal_jenisjurnal_id' => 3,
										'no_dokumen' => $transaksi["no_dokumen"]
									);
								$kredit = $kredit + $value["nilai"];
								}
							}
					}

					if (($kredit === $debet)) {
						$insert = $this->vouchermem_model->InsertJurnal($jurnal);
							if($insert){
								$this->cleanTransaksi();
								$data['success'] = '<p>Jurnal '.$transaksi["no_dokumen"].' berhasil disimpan dengan nomor bukti : '.$nobukti.'</p>';
							} else {
								$data['error'] = '<p>Terjadi kesalahan di database</p>';
							}
					} else {
						$data['error'] = '<p>Debet Dan Kredit Tidak Sama</p>';
					}
					break;
			}

			//$json['json'] = $data;
			//$this->load->view('template/ajax', $json);
		} else {
			$data['error'] = '<p>Tidak Ada Data Yang Disimpan</p>';
			//$json['json'] = $data;
			//$this->load->view('template/ajax', $json);
		}
		return $data;
    }
    
    function tes(){
		echo "<pre>";
		print_r($_SESSION["transaksi"]);
	}
	
	public function list_jurnal() {
		$this->toolbar->create_toolbar();
        $this->toolbar->cGroupButton();
        $this->toolbar->addLink("", "btn tooltips", base_url()."mod_saldo", "form_saldoawal_list", "cus-application", "List Saldo Awal", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", base_url()."mod_saldo/test", "form_saldoawal_add", "cus-application-form-add", "Input Saldo Awal", "tooltip", "right");
        $this->toolbar->addLink("", "btn tooltips", "#", "form_saldoawal_delete", "cus-application-form-delete", "Hapus Saldo Awal", "tooltip", "right");
        $this->toolbar->eGroupButton();
        $data['toolbars'] = $this->toolbar->generate();
        
        $DataModel = array(
            array(
                'text' => 'Kode Perkiraan',
                'value' => 'text:LOWER(perkiraan)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("=", "!=", ">", ">=", "<", "<=")
            ),
            array(
                'text' => 'Keterangan',
                'value' => 'text:LOWER(keterangan)',
                'type' => 'text',
                'callBack' => '',
                'ops' => array("=", "!=", ">", ">=", "<", "<=")
            )
        );

        $defaultvalue = array();

        $data['searchform'] = $this->searchform->setMultiSearch("true")->setDataModel($DataModel)->setDefaultValue($defaultvalue)->genSearchForm();
        $data['ptitle'] = "List Saldo Awal Perkiraan";
        $data['navs'] = $this->dataset_db->buildNav(0);
        $data['tanggal'] = date("Y-m-d");
        $tabs = $this->session->userdata('tabs');
        if (!$tabs)
            $tabs = array();
		
        $tabs['mod_saldo'] = $this->dataset_db->getModule('mod_saldo');
        $this->session->set_userdata('tabs', $tabs);
        $data['current_tab'] = $tabs['mod_saldo']['link'];
        $data['content'] = $this->load->view('list_saldo', $data, true);
        $this->load->vars($data);
        $this->load->view('default_view');
    }

    public function JurnalToJson() {

        $search = isset($_GET['_search']) ? $_GET['_search'] : 'false';
        $page = $this->input->post('page');
        $limit = $this->input->post('rows');
        $sidx = $this->input->post('sidx');
        $sord = $this->input->post('sord');

        $page = !empty($page) ? $page : 1;
        $limit = !empty($limit) ? $limit : 20;
        $sidx = !empty($sidx) ? $sidx : "dperkir_id";
        $sord = !empty($sord) ? $sord : "asc";
        $userconfig = $this->dataset_db->getUserconfig($this->session->userdata('ba_user_id'));

        if (strtolower($search) == "true") {
            $cols = isset($_GET['cols']) ? $_GET['cols'] : '';
            $ops = isset($_GET['ops']) ? $_GET['ops'] : '';
            $vals = isset($_GET['vals']) ? $_GET['vals'] : '';

            $cari = array();
            for ($x = 0; $x < count($cols); $x++) {
                $cari[$x]['cols'] = $cols[$x];
                $cari[$x]['ops'] = $ops[$x];
                $cari[$x]['vals'] = $vals[$x];
            }
        } else {
            $cari = array();
        }

        $offset = ($page * $limit) - $limit;
        $offset = ($offset < 0) ? 0 : $offset;

        if (!$sidx)
            $sidx = 1;

        $data = $this->saldo_model->getJurnal($this->_userconfig["kolom2"], $limit, $offset, $sidx, $sord, $cari, $search);
        $count = $this->saldo_model->countAll();
        $data2 = $this->saldo_model->JurnalToJson($data);
        if ($count > 0) {
            $total_pages = ceil($count / $limit);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages)
            $page = $total_pages;

        if (!empty($data2) and is_array($data2)) {
            $responce['page'] = $page;
            $responce['total'] = $total_pages;
            $responce['records'] = $count;

            $i = 0;
            foreach ($data2 as $row) {
                $responce['rows'][$i]['id'] = $row['id'];
                $responce['rows'][$i]['cell'] = array(
                    $row['no'],
                    $row['check'],
                    $row['coa'],
                    $row['keterangan'],
                    $row['bukubantu'],
                    $row['debet'],
                    $row['kredit']
                );
                $i++;
            }
            echo json_encode($responce);
        } else {
            $responce['page'] = 1;
            $responce['total'] = 1;
            $responce['records'] = 0;
            echo json_encode($responce);
        }
    }
    
    public function getDataPeriode() {
		$temp_result = array();
		$res = "<select name=\"periode\" class=\"span8\">";
        $this->form_validation->set_rules("id", "id", "required|xss_clean");
        if ($this->form_validation->run() == TRUE) {
			$id = $this->input->post("id");
            $periode = $this->dataset_db->getPeriodeKey($id);
            foreach ($periode as $key => $value) {
				$res .= "<option value=\"".$value['id']."\">".$value['desc']."</option>";
				/*$temp_result[] = array(
					'image' => "",
					'description' => $value['desc'],
					'value' => $value['id'],
					'text' => ""
				);*/
            }
        }
        $res .= "</select>";
        //echo json_encode($temp_result);
        echo $res;
    }
}
