
<div class="content form_saldoawal">
    <?= form_open(); ?>
    <input type="hidden" name="form_saldoawal_id" value="" />
        
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Input Saldo Awal</h4></div>
                <div class="basic box_content form-horizontal">

								<div class="row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4">Pilih Periode</label>
											<div class="span8 text">
													<?= form_dropdown('periode_year', $op_yearperiode, set_value('periode_year'), 'class="span8"'); ?>	
											</div>
										</div>
									</div>
								</div>
								
								<div class="row-fluid">
									<div class="span12">
										<div class="row-fluid">
											<label class="form-label span4">Pilih Periode Akunting</label>
											<div class="span8 text">
													<div id="periode"></div>
											</div>
										</div>
									</div>
								</div>
							
					<table id="tabel_uraian" class="table table-bordered">
						<tr>
							<th>KODE</th>
							<th>URAIAN</th>
							<th>DEBET</th>
							<th>KREDIT</th>
						</tr>
						<tr>
							<td>
								<!-- begin blok dperkir -->
								<input type="hidden" name="form_saldoawal_dperkir_id[]" class="form_saldoawal_dperkir_id" id="form_saldoawal_dperkir_id_1" />
								<input type="hidden" name="form_saldoawal_dperkir_kode[]" class="form_saldoawal_dperkir_kode" id="form_saldoawal_dperkir_kode_1" />
								<input type="hidden" name="form_saldoawal_dperkir_kode_bukubantu[]" class="form_saldoawal_dperkir_kode_bukubantu" id="form_saldoawal_dperkir_kode_bukubantu_1" />
								<input type="hidden" name="is_sbdaya[]" class="is_sbdaya" id="is_sbdaya_1" />
								<input type="hidden" name="is_rekanan[]" class="is_rekanan" id="is_rekanan_1" />
								
										<div class="control-group">
											<div class="input-append">
												<input type="text" onkeydown="thisrow=1;getautocomplete();" class="form_saldoawal_dperkir" id="form_saldoawal_dperkir_1" name="form_saldoawal_dperkir[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_saldoawal_dperkir_listcoa" onclick="showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir', 'getpicker_perkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500); thisrow=1;" id="form_saldoawal_dperkir_listcoa_1"><i class="cus-table"></i> List</a></li>
													</ul>
												</div>
											</div>
											<div id="form_saldoawal_dperkir_bukubantu_area_1" class="input-append">
												<input type="text" class="form_saldoawal_dperkir_bukubantu" id="form_saldoawal_dperkir_bukubantu_1" name="form_saldoawal_dperkir_bukubantu[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_saldoawal_dperkir_listbukubantu" onclick="thisrow=1;getListBukuBantu();" id="form_saldoawal_dperkir_listbukubantu_1"><i class="cus-table"></i> List</a></li>
														<li><a href="#" class="form_saldoawal_dperkir_addbukubantu" onclick="thisrow=1;getAddBukuBantu();" id="form_saldoawal_dperkir_addbukubantu_1"><i class="cus-table-add"></i> Add New</a></li>
													</ul>
												</div>
											</div>
										</div>
								<!-- End blok dperkir -->
							</td>
							
							<td>
								<div class="control-group">
									<input type="text" class="form_saldoawal_keterangan" id="form_saldoawal_keterangan_1" name="form_saldoawal_keterangan[]" autocomplete="off">
								</div>
							</td>
							
							<td>
								<div class="control-group">
									<input type="text" id="form_saldoawal_debet_1" name="form_saldoawal_debet[]" class="form_saldoawal_debet" autocomplete="off"/>
								</div>
							</td>
							
							<td>
								<div class="control-group">
									<input type="text" id="form_saldoawal_kredit_1" name="form_saldoawal_kredit[]" class="form_saldoawal_kredit" autocomplete="off"/>
								</div>
							</td>
							<!--<td>
								<button id="add_item" type="button" class="btn btn-primary"><i class="icon-plus icon-white"></I></button>
							</td>-->
						</tr>
					</table>
                    <div class="row-fluid">
                        <div class="control-group">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" name="form_saldoawal_tambah" class="btn btn-success"><i class="cus-table-save"></i>Simpan</button>
                                    <button type="button" name="form_saldoawal_batal" class="btn btn-danger"><i class="cus-cancel"></i>Batal</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <table id="form_saldoawal_list"></table>
            <div id="form_saldoawal_page"></div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
	var thisrow=0;
	var rowCount=1;
	function addItem()
	{
		rowCount++; 
		var tbl = document.getElementById("tabel_uraian");
		var lastRow = tbl.rows.length;
		
		if(rowCount < 101) {
		
		var row = tbl.insertRow(lastRow);
		
		//var newCell = row.insertCell(0);
		//newCell.innerHTML = "<tr><td><span class=\"nomor\" id=\"nomor_"+rowCount+"\">"+rowCount+"</span></td>";

		var newCell = row.insertCell(0);
		newCell.innerHTML = "<td>"
								+"<input type=\"hidden\" name=\"form_saldoawal_dperkir_id[]\" class=\"form_saldoawal_dperkir_id\" id=\"form_saldoawal_dperkir_id_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_saldoawal_dperkir_kode[]\" class=\"form_saldoawal_dperkir_kode\" id=\"form_saldoawal_dperkir_kode_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_saldoawal_dperkir_kode_bukubantu[]\" class=\"form_saldoawal_dperkir_kode_bukubantu\" id=\"form_saldoawal_dperkir_kode_bukubantu_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_sbdaya[]\" class=\"is_sbdaya\" id=\"is_sbdaya_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_rekanan[]\" class=\"is_rekanan\" id=\"is_rekanan_"+rowCount+"\" />"								
										+"<div class=\"control-group\">"
											+"<div class=\"input-append\">"
												+"<input type=\"text\" onkeydown=\"thisrow="+rowCount+";getautocomplete();\" class=\"form_saldoawal_dperkir ui-autocomplete-input\" id=\"form_saldoawal_dperkir_"+rowCount+"\" name=\"form_saldoawal_dperkir[]\"  autocomplete=\"off\" role=\"textbox\" aria-autocomplete=\"list\" aria-haspopup=\"true\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" class=\"form_saldoawal_dperkir_listcoa\" onclick=\"showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir/'+$(this).attr('data-row'), 'getpicker_perkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500);thisrow="+rowCount+";\" id=\"form_saldoawal_dperkir_listcoa_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
											+"<div id=\"form_saldoawal_dperkir_bukubantu_area_"+rowCount+"\" class=\"input-append\">"
												+"<input type=\"text\" class=\"form_saldoawal_dperkir_bukubantu\" id=\"form_saldoawal_dperkir_bukubantu_"+rowCount+"\" name=\"form_saldoawal_dperkir_bukubantu[]\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getListBukuBantu();\" class=\"form_saldoawal_dperkir_listbukubantu\" id=\"form_saldoawal_dperkir_listbukubantu_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getAddBukuBantu();\" class=\"form_saldoawal_dperkir_addbukubantu\" id=\"form_saldoawal_dperkir_addbukubantu_"+rowCount+"\"><i class=\"cus-table-add\"></i> Add New</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
										+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(1);
		newCell.innerHTML = "<tr><td><div class=\"control-group\"><input type=\"text\" class=\"form_saldoawal_keterangan\" id=\"form_saldoawal_keterangan_"+rowCount+"\" name=\"form_saldoawal_keterangan[]\" autocomplete=\"off\"></div></td>";
		
		var newCell = row.insertCell(2);
		newCell.innerHTML = "<td>"
								+"<div class=\"control-group\">"
									+"<input type=\"text\" id=\"form_saldoawal_debet_"+rowCount+"\" name=\"form_saldoawal_debet[]\" class=\"form_saldoawal_debet\" autocomplete=\"off\"/>"
								+"</div>"
							+"</td>";
				
		var newCell = row.insertCell(3);
		newCell.innerHTML = "<td>"
								+"<div class=\"control-group\">"
									+"<input type=\"text\" id=\"form_saldoawal_kredit_"+rowCount+"\" name=\"form_saldoawal_kredit[]\" class=\"form_saldoawal_kredit\" autocomplete=\"off\"/>"
								+"</div>"
							+"</td>";
							
		var newCell = row.insertCell(4);
		newCell.innerHTML ="<td><button type=\"button\" onclick=\"addItem();\" class=\"btn btn-primary add_item\"><i class=\"icon-plus icon-white\"></I></button>"
							+"<button id=\"remove_item_"+rowCount+"\" type=\"button\" class=\"btn btn-danger\" onclick=\"removeItem(" + rowCount + ")\" ><i class=\"icon-remove icon-white\"></I></button><td></tr>"
		
		row.setAttribute("id", "item_" + rowCount);
		$('#form_saldoawal_dperkir_bukubantu_area_'+rowCount).hide();
        $('input[id="form_saldoawal_debet_'+rowCount+'"]').number(true, 2);
        $('input[id="form_saldoawal_kredit_'+rowCount+'"]').number(true, 2);
		} else {
			alert("Maximal 100 Item");
		}
		
	}
	function removeItem(id) {
		var tbl = document.getElementById("tabel_uraian");
		if (tbl.rows.length > 1) {
			var row = document.getElementById('item_'+id);
			var par = row.parentNode;
			par.removeChild(row);
		}rowCount--;
	}
    function saldoawal_clear() {
        $('input[name="form_saldoawal_id"]').val("");
        $('input[class="form_saldoawal_dperkir_kode"]').val("");
        $('input[class="form_saldoawal_dperkir_kode_bukubantu"]').val("");
        $('input[class="is_sbdaya"]').val("");
        $('input[class="is_rekanan"]').val("");
        $('input[class="form_saldoawal_dperkir"]').val("");
        $('input[class="form_saldoawal_dperkir_bukubantu"]').val("");

        $('input[class="form_saldoawal_keterangan"]').val("");
        $('input[class="form_saldoawal_debet"]').val("");
        $('input[class="form_saldoawal_kredit"]').val("");
    }

    function saldoawal_getSession() {
        $.ajax({
            url: root + 'mod_saldo/getSession',
            dataType: 'json',
            success: function(json) {
                $('div.alert').remove();

                if (json['error']) {
                    //$('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                    //$('div.alert').fadeIn('slow');
                    saldoawal_clear();
                }

                if (json['success']) {
                    $('input[name="form_memorial_nobukti"]').val(json["nobukti"]);
                    $('input[name="form_memorial_no_dokumen"]').val(json["no_dokumen"]);
                    $('input[name="form_memorial_tanggal"]').val(json["tanggal"]);
                    $('input[name="form_memorial_jenis"]').val(json["jenis_transaksi"]);
					
					$.each(json["kredit"], function(i, item) {
						$('input[id="form_memorial_debet_id_'+i+'"]').val(json["debet"][i]["dperkir_id"]);
						$('input[id="form_memorial_debet_kode_'+i+'"]').val(json["debet"][i]["kdperkiraan"]);
						if(json["debet"][i]["is_sbdaya"] === "t" || json["debet"][i]["is_rekanan"] === "t"){
							$('#form_memorial_debet_bukubantu_area_'+i+'').show();
						} else {
							$('#form_memorial_debet_bukubantu_area_'+i+'').hide();
						}
						$('input[id="form_memorial_debet_kode_bukubantu_'+i+'"]').val(json["debet"][i]["bukubantu"]);
						$('input[id="is_sbdaya_debet_'+i+'"]').val(json["debet"][i]["is_sbdaya"]);
						$('input[id="is_rekanan_debet_'+i+'"]').val(json["debet"][i]["is_rekanan"]);
						$('input[id="form_memorial_debet_'+i+'"]').val(json["debet"][i]["kdperkiraan"]);
						$('input[id="form_memorial_debet_bukubantu_'+i+'"]').autocomplete().val(json["debet"][i]["bukubantu"]).data('autocomplete')._trigger('select');

						$('input[id="form_memorial_kredit_id_'+i+'"]').val(json["kredit"][i]["dperkir_id"]);
						$('input[id="form_memorial_kredit_kode_'+i+'"]').val(json["kredit"][i]["kdperkiraan"]);
						if(json["kredit"][i]["is_sbdaya"] === "t" || json["kredit"][i]["is_rekanan"] === "t"){
							$('#form_memorial_kredit_bukubantu_area_'+i+'').show();
						} else {
							$('#form_memorial_kredit_bukubantu_area_'+i+'').hide();
						}
						$('input[id="form_memorial_kredit_kode_bukubantu_'+i+'"]').val(json["kredit"][i]["bukubantu"]);
						$('input[id="is_sbdaya_kredit_'+i+'"]').val(json["kredit"][i]["is_sbdaya"]);
						$('input[id="is_rekanan_kredit_'+i+'"]').val(json["kredit"][i]["is_rekanan"]);
						$('input[id="form_memorial_kredit_'+i+'"]').val(json["kredit"][i]["kdperkiraan"]);
						$('input[id="form_memorial_kredit_bukubantu_'+i+'"]').autocomplete().val(json["kredit"][i]["bukubantu"]).data('autocomplete')._trigger('select');

						$('input[id="form_memorial_keterangan_'+i+'"]').val(json["dk"][i]["keterangan"]);
						$('input[id="form_memorial_nilai_'+i+'"]').val(json["dk"][i]["nilai"]);
						if(i < json["kredit_length"]){
							addItem();
						}
					})
					
                    bukubantu();
                }
                scrollup();
                createAutoClosingAlert('div.alert', 3000);

            }
        });
    }

    function bukubantu() {
        var is_rekanan_debet = $('input[id="is_rekanan_'+thisrow+'"]').val();
        var is_sbdaya_debet = $('input[id="is_sbdaya_'+thisrow+'"]').val();
        var coa_debet = $('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val();

        if (is_rekanan_debet === 't') {
            $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_saldoawal_keterangan_'+thisrow+'"]').focus();
                    } else {
                        $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
					if (ui.item === null) {
                        $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    }
                }
            });
        } else if (is_sbdaya_debet === 't') {
            $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_saldoawal_keterangan_'+thisrow+'"]').focus();
                    } else {
                        $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
                        $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val("");
                        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    }
                }
            });
        } else {
            $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').autocomplete("destroy");
        }
    }

    function getpicker_perkiraan(json) {
        $('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val(json.dperkir_id);
        $('input[id="form_saldoawal_dperkir_'+thisrow+'"]').val(json.kdperkiraan);
        $('input[id="form_saldoawal_dperkir_kode_'+thisrow+'"]').val(json.kdperkiraan);
        $('.popup_kodeperkiraan').dialog('close');
        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').focus();
        
        if (json.flag_sumberdaya === 't') {
			$('input[id="is_sbdaya_'+thisrow+'"]').val(json.flag_sumberdaya);			
			$('#form_saldoawal_dperkir_bukubantu_area_'+thisrow+'').show();
		} else {
			$('input[id="is_sbdaya_'+thisrow+'"]').val("");
			$('#form_saldoawal_dperkir_bukubantu_area_'+thisrow+'').hide();
		}

		if (json.flag_nasabah === 't') {
			$('input[id="is_rekanan_'+thisrow+'"]').val(json.flag_nasabah);
			$('#form_saldoawal_dperkir_bukubantu_area_'+thisrow+'').show();
		} else {
			$('input[id="is_rekanan_'+thisrow+'"]').val("");
			$('#form_saldoawal_dperkir_bukubantu_area_'+thisrow+'').hide();
		}
    }

    function getpicker_bukubantu(json) {
        $('input[id="form_saldoawal_dperkir_kode_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
        $('input[id="form_saldoawal_keterangan_'+thisrow+'"]').focus();
    }

	function getautocomplete(){
		/* begin autocomplete debet_kodeperkiraan*/

        $('input[id="form_saldoawal_dperkir_'+thisrow+'"]').autocomplete({
            minLength: 2,
			matchContains:false, 
			minChars:1,  
			autoFill:false, 
			mustMatch:true, 
			cacheLength:20, 
			max:20,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
            create: function(event, ui) {
				$('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val("");
                $('input[id="form_saldoawal_dperkir_kode_'+thisrow+'"]').val("");
                $('input[id="form_saldoawal_dperkir_'+thisrow+'"]').val("");
                return false;
            },
            search: function(event, ui) {
                $('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val("");
                $('input[id="form_saldoawal_dperkir_kode_'+thisrow+'"]').val("");
            },
            select: function(event, ui) {
				//alert(ui.item.flag_nasabah);
                if (ui.item.id != 0) {
                    $('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val(ui.item.id);
                    $('input[id="form_saldoawal_dperkir_kode_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_saldoawal_dperkir_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').focus();

                    if (ui.item.flag_sumberdaya === 't') {
                        $('input[id="is_sbdaya_'+thisrow+'"]').val(ui.item.flag_sumberdaya);
                        $('#form_saldoawal_dperkir_bukubantu_area_'+thisrow).show();
                        $('input[id="is_rekanan_'+thisrow+'"]').val("");
                    }

                    if (ui.item.flag_nasabah === 't') {
                        $('input[id="is_rekanan_'+thisrow+'"]').val(ui.item.flag_nasabah);
                        $('#form_saldoawal_dperkir_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_'+thisrow+'"]').val("");
                    }
                    
                    if (ui.item.flag_sumberdaya === null && ui.item.flag_nasabah === null){
						$('#form_saldoawal_dperkir_bukubantu_area_'+thisrow).hide();
                        $('input[id="is_rekanan_'+thisrow+'"]').val("");
                        $('input[id="is_sbdaya_'+thisrow+'"]').val("");
					}

                } else {
                    $('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_kode_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_'+thisrow+'"]').val("");
                }
                bukubantu();
                return false;
            },
            change: function(event, ui) {
                if (ui.item === null) {
					$('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val("");
					$('input[id="form_saldoawal_dperkir_kode_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_'+thisrow+'"]').val("");
                    $('input[id="form_saldoawal_dperkir_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_'+thisrow+'"]').val("");
				}
            }
        });
	}
	
	function getListBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_'+thisrow+'"]').val();
		var coa_debet = $('input[id="form_saldoawal_dperkir_id_'+thisrow+'"]').val();
		//alert(is_sbdaya+' - '+is_rekanan);
		if (is_rekanan === 't') {
			showUrlInDialog(root + "mod_rekanan/popup_rekanan?coa="+coa_debet, "getpicker_bukubantu", "List Buku Bantu Rekanan", "popup_rekanan", 600, 500);
		}
		else if (is_sbdaya === 't') {
			showUrlInDialog(root + "mod_sbdaya/popup_sbdaya", "getpicker_bukubantu", "List Buku Bantu SB Daya", "popup_rekanan", 600, 500);
		}
		else {
			return false;
		}

	}
	
	function getAddBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_'+thisrow+'"]').val();

		if (is_rekanan === 't') {
			showUrlInDialog(root + "mod_rekanan/popup_add", "", "Tambah Buku Bantu Rekanan", "popup_bukubantu", 600, 500);
		}
		else if (is_sbdaya === 't') {
			showUrlInDialog(root + "mod_sbdaya/popup_add", "", "Add SB Daya", "popup_bukubantu", 600, 500);
		}
		else {
			return false;
		}
	}
	
    function form_saldoawal_clear() {
        $('div.alert').remove();
        $('input[name="form_saldoawal_id"]').val("");
        $('input[class="form_saldoawal_dperkir_kode"]').val("");
        $('input[class="form_saldoawal_dperkir_kode_bukubantu"]').val("");
        $('input[class="is_sbdaya"]').val("");
        $('input[class="is_rekanan"]').val("");
        $('input[class="form_saldoawal_dperkir"]').val("");
        $('input[class="form_saldoawal_dperkir_bukubantu"]').val("");

        $('input[class="form_saldoawal_keterangan"]').val("");
        $('input[class="form_saldoawal_debet"]').val("");
        $('input[class="form_saldoawal_kredit"]').val("");
        while (rowCount > 1) {
			removeItem(rowCount);
		}
    }
	
	function getDataPeriode(id) {
        $.ajax({
            url: root + 'mod_saldo/getDataPeriode',
            type: 'post',
            data: { id: id},
            success: function(data) {
                $('#periode').html(data);
            }
        });	
    }
    
    $('select[name="periode_year"]').change(function() {
        var id =  $('select[name="periode_year"]').val();
        getDataPeriode(id);
    });
    
    $(function() {
        var id_periodeyear = $('select[name="periode_year"]').val();
        getDataPeriode(id_periodeyear);
        
    });
	
    /* begin document ready */
    $(document).ready(function() {
		$('#form_saldoawal_dperkir_bukubantu_area_1').hide();
        $('input[id="form_saldoawal_debet_1"]').number(true, 2);
        $('input[id="form_saldoawal_kredit_1"]').number(true, 2);
		form_saldoawal_clear();
        $('input[name="form_saldoawal_dperkir_id"]').focus();
		$('button[id="add_item"]').bind('click', function(){
			addItem();
		});
		saldoawal_getSession();

        $(".datepicker").datepicker({
            showOn: "button",
            buttonImage: root + "images/calendar.gif",
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true
        });

        $('button[name="form_saldoawal_tambah"]').bind('click', function() {
            var saldoawal_id = $('input[name="form_saldoawal_id"]').val();
            var periode = $('select[name="periode"]').val();
            var saldoawal_dperkir_id = $('input[class="form_saldoawal_dperkir_id"]').serializeArray();
            var saldoawal_dperkir_kode = $('input[class="form_saldoawal_dperkir_kode"]').serializeArray();
            var saldoawal_dperkir_kode_bukubantu = $('input[class="form_saldoawal_dperkir_kode_bukubantu"]').serializeArray();
            var saldoawal_keterangan = $('input[class="form_saldoawal_keterangan"]').serializeArray();
            var saldoawal_debet = $('input[class="form_saldoawal_debet"]').serializeArray();
            var saldoawal_kredit = $('input[class="form_saldoawal_kredit"]').serializeArray();
            var is_rekanan = $('input[class="is_rekanan"]').serializeArray();
            var is_sbdaya = $('input[class="is_sbdaya"]').serializeArray();
            $.ajax({
                url: root + 'mod_saldo/add2db',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    id: saldoawal_id,
                    periode: periode,
                    dperkir_id: saldoawal_dperkir_id,
                    dperkir_kode: saldoawal_dperkir_kode,
                    dperkir_kode_bukubantu: saldoawal_dperkir_kode_bukubantu,
                    keterangan: saldoawal_keterangan,
                    debet: saldoawal_debet,
                    kredit: saldoawal_kredit,
                    is_rekanan: is_rekanan,
                    is_sbdaya: is_sbdaya
                },
                beforeSend: function() {
                    $(this).attr('disabled', true);
                },
                complete: function() {
                    $(this).attr('disabled', false);
                },
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.form_saldoawal').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {

                        form_saldoawal_clear();
                        $('.form_saldoawal').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    //scrollup();
                    //createAutoClosingAlert('div.alert', 3000);
                }
            });
        });

		$('button[name="form_saldoawal_simpan"]').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();
            $.ajax({
                url: root + "mod_saldo/addJurnal",
                dataType: 'json',
                type: 'post',
                data: {id: id},
                beforeSend: function() {
                    $(this).attr('disabled',true);
                },	
                complete: function() {
                    $(this).attr('disabled',false);
                },	
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        form_saldoawal_clear();
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                }
            });
        });
		
		$('button[name="form_saldoawal_batal"]').click(function() {
            $.ajax({
                url: root + "mod_saldo/cleanTransaksi"
            });
            form_saldo_clear();
        });
		
        $('#form_saldoawal_delete').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();


            $.ajax({
                url: root + "mod_saldo/deletejurnal",
                dataType: 'json',
                type: 'post',
                data: {id: id},
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }

                    if (json['success']) {
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                }
            });
        });
    });
</script>
