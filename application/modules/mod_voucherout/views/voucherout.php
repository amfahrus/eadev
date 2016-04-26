
<div class="content form_voucherout">
    <?= form_open(); ?>
    <input type="hidden" name="form_voucherout_id" value="" />
        
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="red box_title"><h4><span>#</span> Voucher Out</h4></div>
                <div class="red box_content form-horizontal">

								<div class="row-fluid">
									<div class="control-group error">
										<label class="control-label" for="form_voucherout_nobukti">Nomor Bukti</label>
										<div class="controls">
											<input type="text" id="form_voucherout_nobukti" name="form_voucherout_nobukti" readonly value="AUTO"/>
										</div>
									</div>
								</div>

								<div class="row-fluid">
									<div class="control-group error">
										<label class="control-label" for="form_voucherout_tanggal">Tanggal Transaksi</label>
										<div class="controls">
											<input type="text" id="form_voucherout_tanggal" name="form_voucherout_tanggal" class="datepicker" value="<?= $tanggal; ?>" autocomplete="off"/>
										</div>
									</div>
								</div>
							
								<div class="row-fluid">
									<div class="control-group error">
										<label class="control-label" for="form_voucherout_jenis">Jenis Transaksi</label>
										<div class="controls">
											<select name="form_voucherout_jenis" id="form_voucherout_jenis">
												<option value="K">Kas</option>
												<option value="B">Bank</option>
											</select>
										</div>
									</div>
								</div>
							
								 <!--  begin blok kredit -->
								<input type="hidden" name="form_voucherout_kredit_id" id="form_voucherout_kredit_id"/>
								<input type="hidden" name="form_voucherout_kredit_kode" id="form_voucherout_kredit_kode"/>
								<input type="hidden" name="form_voucherout_kredit_kode_bukubantu" id="form_voucherout_kredit_kode_bukubantu"/>
								<input type="hidden" name="is_sbdaya_kredit" id="is_sbdaya_kredit"/>
								<input type="hidden" name="is_rekanan_kredit" id="is_rekanan_kredit"/>
								<div class="row-fluid">
									<div class="control-group error">
										<label class="control-label" for="form_voucherout_kredit">Kredit</label>
										<div class="controls">
											<div class="input-append">
												<input type="text" id="form_voucherout_kredit" name="form_voucherout_kredit"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" id="form_voucherout_kredit_listcoa"><i class="cus-table"></i> List</a></li>
													</ul>
												</div>
											</div>
											<div id="form_voucherout_kredit_bukubantu_area" class="input-append">
												<input type="text" id="form_voucherout_kredit_bukubantu" name="form_voucherout_kredit_bukubantu"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" id="form_voucherout_kredit_listbukubantu"><i class="cus-table"></i> List</a></li>
														<li><a href="#" id="form_voucherout_kredit_addbukubantu"><i class="cus-table-add"></i> Add New</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--  End blok kredit -->
							
								<div class="row-fluid">
									<div class="control-group error">
										<label class="control-label" for="form_voucherout_no_dokumen">Nomor Dokumen</label>
										<div class="controls">
											<input type="text" id="form_voucherout_no_dokumen" name="form_voucherout_no_dokumen" autocomplete="off"/>
										</div>
									</div>
								</div>
							
					<table id="tabel_uraian" class="table table-bordered">
						<tr>
							<th>NOMOR</th>
							<th>URAIAN</th>
							<th>DEBET</th>
							<th>JUMLAH</th>
						</tr>
						<tr>
							<td>
								<span class="nomor" id="nomor_1">1</span>
							</td>
							<td>
								<div class="control-group error">
									<input type="text" class="form_voucherout_keterangan" id="form_voucherout_keterangan_1" name="form_voucherout_keterangan[]" autocomplete="off">
								</div>
							</td>
							<td>
								<!-- begin blok debet -->
								<input type="hidden" name="form_voucherout_debet_id[]" class="form_voucherout_debet_id" id="form_voucherout_debet_id_1" />
								<input type="hidden" name="form_voucherout_debet_kode[]" class="form_voucherout_debet_kode" id="form_voucherout_debet_kode_1" />
								<input type="hidden" name="form_voucherout_debet_kode_bukubantu[]" class="form_voucherout_debet_kode_bukubantu" id="form_voucherout_debet_kode_bukubantu_1" />
								<input type="hidden" name="is_sbdaya_debet[]" class="is_sbdaya_debet" id="is_sbdaya_debet_1" />
								<input type="hidden" name="is_rekanan_debet[]" class="is_rekanan_debet" id="is_rekanan_debet_1" />
								
										<div class="control-group error">
											<div class="input-append">
												<input type="text" onkeydown="thisrow=1;getautocomplete();" class="form_voucherout_debet" id="form_voucherout_debet_1" name="form_voucherout_debet[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_voucherout_debet_listcoa" onclick="showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir', 'getpicker_debetperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500); thisrow=1;" id="form_voucherout_debet_listcoa_1"><i class="cus-table"></i> List</a></li>
													</ul>
												</div>
											</div>
											<div id="form_voucherout_debet_bukubantu_area_1" class="input-append">
												<input type="text" class="form_voucherout_debet_bukubantu" id="form_voucherout_debet_bukubantu_1" name="form_voucherout_debet_bukubantu[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_voucherout_debet_listbukubantu" onclick="thisrow=1;getDebetListBukuBantu();" id="form_voucherout_debet_listbukubantu_1"><i class="cus-table"></i> List</a></li>
														<li><a href="#" class="form_voucherout_debet_addbukubantu" onclick="thisrow=1;getDebetAddBukuBantu();" id="form_voucherout_debet_addbukubantu_1"><i class="cus-table-add"></i> Add New</a></li>
													</ul>
												</div>
											</div>
										</div>
								<!-- End blok debet -->
							</td>
							<td>
								<div class="control-group error">
									<input type="text" id="form_voucherout_nilai_1" name="form_voucherout_nilai[]" class="form_voucherout_nilai" autocomplete="off"/>
								</div>
							</td>
							<td>
								<button id="add_item" type="button" class="btn btn-primary"><i class="icon-plus icon-white"></I></button>
							</td>
						</tr>
					</table>
                    
                    <div class="row-fluid">
                        <div class="control-group error">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" name="form_voucherout_tambah" class="btn btn-danger"><i class="cus-table-add"></i>Tambah</button>
                                    <button type="button" name="form_voucherout_batal" class="btn btn-danger"><i class="cus-cancel"></i>Batal</button>
                                    <button type="button" name="form_voucherout_simpan" class="btn btn-danger"><i class="cus-table-save"></i>Simpan</button>
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
            <table id="form_voucherout_list"></table>
            <div id="form_voucherout_page"></div>
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
		
		if(rowCount < 11) {
		
		var row = tbl.insertRow(lastRow);
		
		var newCell = row.insertCell(0);
		newCell.innerHTML = "<tr><td><span class=\"nomor\" id=\"nomor_"+rowCount+"\">"+rowCount+"</span></td>";

		var newCell = row.insertCell(1);
		newCell.innerHTML = "<td><div class=\"control-group error\"><input type=\"text\" class=\"form_voucherout_keterangan\" id=\"form_voucherout_keterangan_"+rowCount+"\" name=\"form_voucherout_keterangan[]\" autocomplete=\"off\"></div></td>";

		var newCell = row.insertCell(2);
		newCell.innerHTML = "<td>"
								+"<input type=\"hidden\" name=\"form_voucherout_debet_id[]\" class=\"form_voucherout_debet_id\" id=\"form_voucherout_debet_id_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_voucherout_debet_kode[]\" class=\"form_voucherout_debet_kode\" id=\"form_voucherout_debet_kode_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_voucherout_debet_kode_bukubantu[]\" class=\"form_voucherout_debet_kode_bukubantu\" id=\"form_voucherout_debet_kode_bukubantu_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_sbdaya_debet[]\" class=\"is_sbdaya_debet\" id=\"is_sbdaya_debet_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_rekanan_debet[]\" class=\"is_rekanan_debet\" id=\"is_rekanan_debet_"+rowCount+"\" />"								
										+"<div class=\"control-group error\">"
											+"<div class=\"input-append\">"
												+"<input type=\"text\" onkeydown=\"thisrow="+rowCount+";getautocomplete();\" class=\"form_voucherout_debet ui-autocomplete-input\" id=\"form_voucherout_debet_"+rowCount+"\" name=\"form_voucherout_debet[]\"  autocomplete=\"off\" role=\"textbox\" aria-autocomplete=\"list\" aria-haspopup=\"true\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" class=\"form_voucherout_debet_listcoa\" onclick=\"showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir/'+$(this).attr('data-row'), 'getpicker_debetperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500);thisrow="+rowCount+";\" id=\"form_voucherout_debet_listcoa_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
											+"<div id=\"form_voucherout_debet_bukubantu_area_"+rowCount+"\" class=\"input-append\">"
												+"<input type=\"text\" class=\"form_voucherout_debet_bukubantu\" id=\"form_voucherout_debet_bukubantu_"+rowCount+"\" name=\"form_voucherout_debet_bukubantu[]\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getDebetListBukuBantu();\" class=\"form_voucherout_debet_listbukubantu\" id=\"form_voucherout_debet_listbukubantu_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getDebetAddBukuBantu();\" class=\"form_voucherout_debet_addbukubantu\" id=\"form_voucherout_debet_addbukubantu_"+rowCount+"\"><i class=\"cus-table-add\"></i> Add New</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
										+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(3);
		newCell.innerHTML = "<td>"
								+"<div class=\"control-group error\">"
									+"<input type=\"text\" id=\"form_voucherout_nilai_"+rowCount+"\" name=\"form_voucherout_nilai[]\" class=\"form_voucherout_nilai\" autocomplete=\"off\"/>"
								+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(4);
		newCell.innerHTML ="<td><button id=\"remove_item_"+rowCount+"\" type=\"button\" class=\"btn btn-danger\" onclick=\"removeItem(" + rowCount + ")\" ><i class=\"icon-remove icon-white\"></I></button>"
							+" <button type=\"button\" onclick=\"addItem();\" class=\"btn btn-primary add_item\"><i class=\"icon-plus icon-white\"></I></button><td></tr>"
		
		row.setAttribute("id", "item_" + rowCount);
		$('#form_voucherout_debet_bukubantu_area_'+rowCount).hide();
        $('input[id="form_voucherout_nilai_'+rowCount+'"]').number(true, 2);
		} else {
			alert("Maximal 10 Item");
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
    function voucherout_clear() {
        $('input[name="form_voucherout_id"]').val("");
        $('input[name="form_voucherout_tanggal"]').val("");
        $('input[name="form_voucherout_jenis"]').val("");
        $('input[name="form_voucherout_kredit_kode"]').val("");
        $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
        $('input[name="is_sbdaya_kredit"]').val("");
        $('input[name="is_rekanan_kredit"]').val("");
        $('input[name="form_voucherout_kredit"]').val("");
        $('input[name="form_voucherout_kredit_bukubantu"]').val("");

        $('input[class="form_voucherout_debet_kode"]').val("");
        $('input[class="form_voucherout_debet_kode_bukubantu"]').val("");
        $('input[class="is_sbdaya_debet"]').val("");
        $('input[class="is_rekanan_debet"]').val("");
        $('input[class="form_voucherout_debet"]').val("");
        $('input[class="form_voucherout_debet_bukubantu"]').val("");

        $('input[class="form_voucherout_keterangan"]').val("");
        $('input[class="form_voucherout_nilai"]').val("");
    }

    function voucherout_getSession(id) {
        $.ajax({
            url: root + 'mod_voucherout/getSessionId',
            dataType: 'json',
            type: 'post',
            data: {id: id},
            success: function(json) {
                $('div.alert').remove();

                if (json['error']) {
                    $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                    $('div.alert').fadeIn('slow');
                    voucherout_clear();
                }

                if (json['success']) {
					//alert(json["record"]["detail"]["D"]["bukubantu"]);
                    $('input[name="form_voucherout_id"]').val(json["record"]["id"]);
                    $('input[name="form_voucherout_nobukti"]').val(json["nobukti"]);
                    $('input[name="form_voucherout_no_dokumen"]').val(json["no_dokumen"]);
                    $('input[name="form_voucherout_tanggal"]').val(json["tanggal"]);
                    $('select[name="form_voucherout_jenis"]').val(json["jenis_transaksi"]);

                    $('input[name="form_voucherout_kredit_id"]').val(json["record"]["detail"]["K"]["dperkir_id"]);
                    $('input[name="form_voucherout_kredit_kode"]').val(json["record"]["detail"]["K"]["kdperkiraan"]);
					if(json["record"]["detail"]["K"]["is_sbdaya"] === "t" || json["record"]["detail"]["K"]["is_rekanan"] === "t"){
						$('#form_voucherout_kredit_bukubantu_area').show();
					} else {
						$('#form_voucherout_kredit_bukubantu_area').hide();
					}
                    $('input[name="form_voucherout_kredit_kode_bukubantu"]').val(json["record"]["detail"]["K"]["bukubantu"]);
                    $('input[name="is_sbdaya_kredit"]').val(json["record"]["detail"]["K"]["is_sbdaya"]);
                    $('input[name="is_rekanan_kredit"]').val(json["record"]["detail"]["K"]["is_rekanan"]);
                    $('input[name="form_voucherout_kredit"]').val(json["record"]["detail"]["K"]["kdperkiraan"]);
                    $('input[name="form_voucherout_kredit_bukubantu"]').val(json["record"]["detail"]["K"]["bukubantu"]);

                    $('input[id="form_voucherout_debet_id_1"]').val(json["record"]["detail"]["D"]["dperkir_id"]);
                    $('input[id="form_voucherout_debet_kode_1"]').val(json["record"]["detail"]["D"]["kdperkiraan"]);
                    if(json["record"]["detail"]["D"]["is_sbdaya"] === "t" || json["record"]["detail"]["D"]["is_rekanan"] === "t"){
						$('#form_voucherout_debet_bukubantu_area_1').show();
					} else {
						$('#form_voucherout_debet_bukubantu_area_1').hide();
					}
					$('input[id="form_voucherout_debet_kode_bukubantu_1"]').val(json["record"]["detail"]["D"]["bukubantu"]);
                    $('input[id="is_sbdaya_debet_1"]').val(json["record"]["detail"]["D"]["is_sbdaya"]);
                    $('input[id="is_rekanan_debet_1"]').val(json["record"]["detail"]["D"]["is_rekanan"]);
                    $('input[id="form_voucherout_debet_1"]').val(json["record"]["detail"]["D"]["kdperkiraan"]);
                    $('input[id="form_voucherout_debet_bukubantu_1"]').val(json["record"]["detail"]["D"]["bukubantu"]);

                    $('input[id="form_voucherout_keterangan_1"]').val(json["record"]["keterangan"]);
                    $('input[id="form_voucherout_nilai_1"]').val(json["record"]["nilai"]);

                    bukubantu_kredit();
                    bukubantu_debet();
                }
                scrollup();
                createAutoClosingAlert('div.alert', 3000);
                form_voucherout_refresh_grid();

            }
        });
    }

    function form_voucherout_refresh_grid() {
        jQuery("#form_voucherout_list").jqGrid('setGridParam', {
            url: root + mod + '/sess2json',
            page: 1
        }).trigger("reloadGrid");
    }

    function bukubantu_kredit() {
        var is_rekanan_kredit = $('input[name="is_rekanan_kredit"]').val();
        var is_sbdaya_kredit = $('input[name="is_sbdaya_kredit"]').val();
        var coa_kredit = $('input[name="form_voucherout_kredit_id"]').val();

        if (is_rekanan_kredit === 't') {
            $('input[name="form_voucherout_kredit_bukubantu"]').autocomplete({
                minLength: 2,
                source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa_kredit,
                create: function(event, ui) {
                    $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                    $('input[name="form_voucherout_kredit_bukubantu"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[name="form_voucherout_kredit_kode_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherout_kredit_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherout_no_dokumen"]').focus();
                    } else {
                        $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                        $('input[name="form_voucherout_kredit_bukubantu"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                }
            });
        } else if (is_sbdaya_kredit === 't') {
            $('input[name="form_voucherout_kredit_bukubantu"]').autocomplete({
                minLength: 2,
                source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa_kredit,
                create: function(event, ui) {
                    $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                    $('input[name="form_voucherout_kredit_bukubantu"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[name="form_voucherout_kredit_kode_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherout_kredit_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherout_no_dokumen"]').focus();
                    } else {
                        $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                        $('input[name="form_voucherout_kredit_bukubantu"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
                }
            });
        } else {
            $('input[name="form_voucherout_kredit_bukubantu"]').autocomplete("destroy");
        }
    }

    function bukubantu_debet() {
        var is_rekanan_debet = $('input[id="is_rekanan_debet_'+thisrow+'"]').val();
        var is_sbdaya_debet = $('input[id="is_sbdaya_debet_'+thisrow+'"]').val();
        var coa_debet = $('input[id="form_voucherout_debet_id_'+thisrow+'"]').val();

        if (is_rekanan_debet === 't') {
            $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_voucherout_keterangan_'+thisrow+'"]').focus();
                    } else {
                        $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                        $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                }
            });
        } else if (is_sbdaya_debet === 't') {
            $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val(ui.item.id);
                        $('input[id="form_voucherout_keterangan_'+thisrow+'"]').focus();
                    } else {
                        $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                        $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val("");
                }
            });
        } else {
            $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').autocomplete("destroy");
        }
    }

    function getpicker_kreditperkiraan(json) {
        $('input[name="form_voucherout_kredit_id"]').val(json.dperkir_id);
        $('input[name="form_voucherout_kredit"]').val(json.kdperkiraan);
        $('input[name="form_voucherout_kredit_kode"]').val(json.kdperkiraan);
        $('.popup_kodeperkiraan').dialog('close');
        $('input[name="form_voucherout_kredit_bukubantu"]').focus();
        
        if (json.flag_sumberdaya === 't') {
			$('input[name="is_sbdaya_kredit"]').val(json.flag_sumberdaya);			
			$('#form_voucherout_kredit_bukubantu_area').show();
		} else {
			$('input[name="is_sbdaya_kredit"]').val("");
			$('#form_voucherout_kredit_bukubantu_area').hide();
		}

		if (json.flag_nasabah === 't') {
			$('input[name="is_rekanan_kredit"]').val(json.flag_nasabah);
			$('#form_voucherout_kredit_bukubantu_area').show();
		} else {
			$('input[name="is_rekanan_kredit"]').val("");
			$('#form_voucherout_kredit_bukubantu_area').hide();
		}
    }

	function getpicker_debetperkiraan(json) {
		$('input[id="form_voucherout_debet_id_'+thisrow+'"]').val(json.dperkir_id);
        $('input[id="form_voucherout_debet_'+thisrow+'"]').val(json.kdperkiraan);
        $('input[id="form_voucherout_debet_kode_'+thisrow+'"]').val(json.kdperkiraan);
        $('.popup_kodeperkiraan').dialog('close');
        $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').focus();
        
        if (json.flag_sumberdaya === 't') {
			$('input[id="is_sbdaya_debet_'+thisrow+'"]').val(json.flag_sumberdaya);			
			$('#form_voucherout_debet_bukubantu_area_'+thisrow).show();
			$('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
		}

		if (json.flag_nasabah === 't') {
			$('input[id="is_rekanan_debet_'+thisrow+'"]').val(json.flag_nasabah);
			$('#form_voucherout_debet_bukubantu_area_'+thisrow).show();
			$('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
		}
		
		if (json.flag_sumberdaya === null && json.flag_nasabah === null) {
			$('#form_voucherout_debet_bukubantu_area_'+thisrow).hide();
			$('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
			$('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
		}
    }

    function getpicker_kreditbukubantu(json) {
        $('input[name="form_voucherout_kredit_kode_bukubantu"]').val(json.kode_rekanan);
        $('input[name="form_voucherout_kredit_bukubantu"]').val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
        $('input[name="form_voucherout_keterangan"]').focus();
    }

    function getpicker_debetbukubantu(json) {
        $('input[id="form_voucherout_debet_kode_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
        $('input[id="form_voucherout_keterangan_'+thisrow+'"]').focus();
    }

	function getautocomplete(){
		/* begin autocomplete debet_kodeperkiraan*/

        $('input[id="form_voucherout_debet_'+thisrow+'"]').autocomplete({
            minLength: 2,
			matchContains:false, 
			minChars:1,  
			autoFill:false, 
			mustMatch:true, 
			cacheLength:20, 
			max:20,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan?_search=true&cat=" + $('select[name="form_voucherout_jenis"]').val(),
            create: function(event, ui) {
				$('input[id="form_voucherout_debet_id_'+thisrow+'"]').val("");
                $('input[id="form_voucherout_debet_kode_'+thisrow+'"]').val("");
                $('input[id="form_voucherout_debet_'+thisrow+'"]').val("");
                return false;
            },
            search: function(event, ui) {
                $('input[id="form_voucherout_debet_id_'+thisrow+'"]').val("");
                $('input[id="form_voucherout_debet_kode_'+thisrow+'"]').val("");
            },
            select: function(event, ui) {
				//alert(ui.item.flag_nasabah);
                if (ui.item.id != 0) {
                    $('input[id="form_voucherout_debet_id_'+thisrow+'"]').val(ui.item.id);
                    $('input[id="form_voucherout_debet_kode_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_voucherout_debet_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').focus();

                    if (ui.item.flag_sumberdaya === 't') {
                        $('input[id="is_sbdaya_debet_'+thisrow+'"]').val(ui.item.flag_sumberdaya);
                        $('#form_voucherout_debet_bukubantu_area_'+thisrow).show();
                        $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                    }

                    if (ui.item.flag_nasabah === 't') {
                        $('input[id="is_rekanan_debet_'+thisrow+'"]').val(ui.item.flag_nasabah);
                        $('#form_voucherout_debet_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
                    }
                    
                    if (ui.item.flag_sumberdaya === null && ui.item.flag_nasabah === null){
						$('#form_voucherout_debet_bukubantu_area_'+thisrow).hide();
                        $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                        $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
					}

                } else {
                    $('input[id="form_voucherout_debet_kode_'+thisrow+'"]').val("");
                    $('input[id="form_voucherout_debet_'+thisrow+'"]').val("");
                    $('input[id="form_voucherout_debet_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                }
                bukubantu_debet();
                return false;
            }
        });
	}
	
	function getDebetListBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_debet_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_debet_'+thisrow+'"]').val();
		//alert(is_sbdaya+' - '+is_rekanan);
		if (is_rekanan === 't') {
			showUrlInDialog(root + "mod_rekanan/popup_rekanan", "getpicker_debetbukubantu", "List Buku Bantu Rekanan", "popup_rekanan", 600, 500);
		}
		else if (is_sbdaya === 't') {
			showUrlInDialog(root + "mod_sbdaya/popup_sbdaya", "getpicker_debetbukubantu", "List Buku Bantu SB Daya", "popup_rekanan", 600, 500);
		}
		else {
			return false;
		}

	}
	
	function getDebetAddBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_debet_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_debet_'+thisrow+'"]').val();

		if (is_rekanan === 't') {
			showUrlInDialog(root + "mod_rekanan/popup_add", "", "Tambah Buku Bantu Rekanan", "popup_debet_bukubantu", 600, 500);
		}
		else if (is_sbdaya === 't') {
			showUrlInDialog(root + "mod_sbdaya/popup_add", "", "Add SB Daya", "popup_debet_bukubantu", 600, 500);
		}
		else {
			return false;
		}
	}
	
    function form_voucherout_clear() {
        $('div.alert').remove();
        //$('input[name="form_vocuherout_id"]').val("");
        //$('input[name="form_vocuherout_tanggal"]').val("");
        $('input[name="form_voucherout_jenis"]').val("");
        $('input[name="form_voucherout_kredit_id"]').val("");
        $('input[name="form_voucherout_kredit_kode"]').val("");
        $('input[name="form_voucherout_kredit_kode_bukubantu"]').val("");
        $('input[name="is_sbdaya_kredit"]').val("");
        $('input[name="is_rekanan_kredit"]').val("");
        $('input[name="form_voucherout_kredit"]').val("");
        $('input[name="form_voucherout_kredit_bukubantu"]').val("");
        $('input[name="form_voucherout_no_dokumen"]').val("");
        $('input[name="form_voucherout_kredit_bukubantu"]').autocomplete("destroy");
        
        $('input[id="form_voucherout_debet_id_1"]').val("");
        $('input[id="form_voucherout_debet_kode_1"]').val("");
        $('input[id="form_voucherout_debet_kode_bukubantu_1"]').val("");
        $('input[id="is_sbdaya_debet_1"]').val("");
        $('input[id="is_rekanan_debet_1"]').val("");
        $('input[id="form_voucherout_debet_1"]').val("");
        $('input[id="form_voucherout_debet_bukubantu_1"]').val("");
        $('input[id="form_voucherout_keterangan_1"]').val("");
        $('input[id="form_voucherout_nilai_1"]').val("");
        $('input[id="form_voucherout_debet_bukubantu_1"]').autocomplete("destroy");
        
		$('#form_voucherout_kredit_bukubantu_area').hide();
		$('#form_voucherout_debet_bukubantu_area_1').hide();
		
        while (rowCount > 1) {
			removeItem(rowCount);
		}
    }

    /* begin document ready */
    $(document).ready(function() {
		$('#form_voucherout_kredit_bukubantu_area').hide();
		$('#form_voucherout_debet_bukubantu_area_1').hide();
        $('input[id="form_voucherout_nilai_1"]').number(true, 2);
		form_voucherout_clear();
        $('input[name="form_voucherout_kredit_bukubantu"]').autocomplete("destroy");
        $('input[name="form_voucherout_debet_bukubantu"]').autocomplete("destroy");
        $('input[name="form_voucherout_kredit_bukubantu"]').val("");
        $('input[name="form_voucherout_debet_bukubantu"]').val("");
        $('input[name="is_sbdaya_kredit"]').val("");
        $('input[name="is_rekanan_kredit"]').val("");
        $('input[name="is_sbdaya_debet"]').val("");
        $('input[name="is_rekanan_debet"]').val("");
        $('input[name="form_voucherout_kredit"]').focus();
		$('button[id="add_item"]').bind('click', function(){
			addItem();
		});
		
        $('input[name="form_voucherout_keterangan"]').keypress(function(event) {
            keterangan = $(this).val();
            if (event.which == 13) {
                if (keterangan != "") {
                    $('input[name="form_voucherout_nilai"]').focus();
                    event.preventDefault();
                }
                event.preventDefault();
            }
        });

        $('a[id="form_voucherout_kredit_listcoa"]').bind('click', function() {
            showUrlInDialog(root + "mod_kdperkiraan/popup_kdperkir", "getpicker_kreditperkiraan", "List Kode Perkiraan", "popup_kodeperkiraan", 600, 500);
        });

        $('a[id="form_voucherout_kredit_listbukubantu"]').bind('click', function() {
            var is_sbdaya = $('input[name="is_sbdaya_kredit"]').val();
            var is_rekanan = $('input[name="is_rekanan_kredit"]').val();

            if (is_rekanan === 't') {
                showUrlInDialog(root + "mod_rekanan/popup_rekanan", "getpicker_kreditbukubantu", "List Buku Bantu Rekanan", "popup_rekanan", 600, 500);
            }
            else if (is_sbdaya === 't') {
                showUrlInDialog(root + "mod_sbdaya/popup_sbdaya", "getpicker_kreditbukubantu", "List Buku Bantu SB Daya", "popup_rekanan", 600, 500);
            }
            else {
                return false;
            }
        });


        $('a[id="form_voucherout_kredit_addbukubantu"]').bind('click', function() {
            var is_sbdaya = $('input[name="is_sbdaya_kredit"]').val();
            var is_rekanan = $('input[name="is_rekanan_kredit"]').val();

            if (is_rekanan === 't') {
                showUrlInDialog(root + "mod_rekanan/popup_add", "", "Tambah Buku Bantu Rekanan", "popup_kredit_bukubantu", 600, 500);
            }
            else if (is_sbdaya === 't') {
                showUrlInDialog(root + "mod_sbdaya/popup_add", "", "Add SB Daya", "popup_kredit_bukubantu", 600, 500);
            }
            else {
                return false;
            }
        });

        $(".datepicker").datepicker({
            showOn: "button",
            buttonImage: root + "images/calendar.gif",
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true
        });

        $('select[name="form_voucherout_jenis"]').change(function() {
            var jenis = $(this).val();
            var cat = $('select[name="form_voucherout_jenis"]').val();
            $('input[class="form_voucherout_debet"]').val("");
            $('input[class="form_voucherout_debet_kode"]').val("");

            $('input[class="form_voucherout_debet"]').autocomplete("option", {
                source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan?_search=true&cat=" + cat
            });
            form_voucherout_clear();
        });


        $('input[name="form_voucherout_kredit"]').autocomplete({
            minLength: 2,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
            create: function(event, ui) {
                $('input[name="form_voucherout_kredit_id"]').val("");
                $('input[name="form_voucherout_kredit_kode"]').val("");
                $('input[name="form_voucherout_kredit"]').val("");
                return false;
            },
            search: function(event, ui) {
                $('input[name="form_voucherout_kredit_id"]').val("");
                $('input[name="form_voucherout_kredit_kode"]').val("");
            },
            select: function(event, ui) {
                if (ui.item.id != 0) {
                    $('input[name="form_voucherout_kredit_id"]').val(ui.item.id);
                    $('input[name="form_voucherout_kredit_kode"]').val(ui.item.kode);
                    $('input[name="form_voucherout_kredit"]').val(ui.item.kode);
                    $('input[name="form_voucherout_kredit_bukubantu"]').val("");
                    $('input[name="form_voucherout_kredit_bukubantu"]').focus();

                    if (ui.item.flag_sumberdaya === 't') {
                        $('input[name="is_sbdaya_kredit"]').val(ui.item.flag_sumberdaya);
                        $('#form_voucherout_kredit_bukubantu_area').show();
                    } else {
                        $('input[name="is_sbdaya_kredit"]').val("");
                        $('#form_voucherout_kredit_bukubantu_area').hide();
                    }

                    if (ui.item.flag_nasabah === 't') {
                        $('input[name="is_rekanan_kredit"]').val(ui.item.flag_nasabah);
                        $('#form_voucherout_kredit_bukubantu_area').show();
                    } else {
                        $('input[name="is_rekanan_kredit"]').val("");
                        $('#form_voucherout_kredit_bukubantu_area').hide();
                    }
                } else {
                    $('input[name="form_voucherout_kredit_id"]').val("");
                    $('input[name="form_voucherout_kredit_kode"]').val("");
                    $('input[name="form_voucherout_kredit"]').val("");
                    $('input[name="form_voucherout_kredit_bukubantu"]').val("");
                    $('input[name="is_sbdaya_kredit"]').val("");
                    $('input[name="is_rekanan_kredit"]').val("");

                }
                bukubantu_kredit();
                return false;
            }
        });

        $('button[name="form_voucherout_tambah"]').bind('click', function() {
            var voucherout_id = $('input[name="form_voucherout_id"]').val();
            var voucherout_tanggal = $('input[name="form_voucherout_tanggal"]').val();
            var voucherout_jenis = $('select[name="form_voucherout_jenis"]').val();
            var voucherout_no_dokumen = $('input[name="form_voucherout_no_dokumen"]').val();
            var voucherout_kredit_id = $('input[name="form_voucherout_kredit_id"]').val();
            var voucherout_kredit_kode = $('input[name="form_voucherout_kredit_kode"]').val();
            var voucherout_kredit_kode_bukubantu = $('input[name="form_voucherout_kredit_kode_bukubantu"]').val();
            var voucherout_debet_id = $('input[class="form_voucherout_debet_id"]').serializeArray();
            var voucherout_debet_kode = $('input[class="form_voucherout_debet_kode"]').serializeArray();
            var voucherout_debet_kode_bukubantu = $('input[class="form_voucherout_debet_kode_bukubantu"]').serializeArray();
            var voucherout_keterangan = $('input[class="form_voucherout_keterangan"]').serializeArray();
            var voucherout_nilai = $('input[class="form_voucherout_nilai"]').serializeArray();
            var is_rekanan_kredit = $('input[name="is_rekanan_kredit"]').val();
            var is_sbdaya_kredit = $('input[name="is_sbdaya_kredit"]').val();
            var is_rekanan_debet = $('input[class="is_rekanan_debet"]').serializeArray();
            var is_sbdaya_debet = $('input[class="is_sbdaya_debet"]').serializeArray();
            $.ajax({
                url: root + 'mod_voucherout/add2session',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    id: voucherout_id,
                    tanggal: voucherout_tanggal,
                    jenis: voucherout_jenis,
                    no_dokumen: voucherout_no_dokumen,
                    debet_id: voucherout_debet_id,
                    debet_kode: voucherout_debet_kode,
                    debet_bukubantu: voucherout_debet_kode_bukubantu,
                    kredit_id: voucherout_kredit_id,
                    kredit_kode: voucherout_kredit_kode,
                    kredit_bukubantu: voucherout_kredit_kode_bukubantu,
                    keterangan: voucherout_keterangan,
                    nilai: voucherout_nilai,
                    is_rekanan_debet: is_rekanan_debet,
                    is_sbdaya_debet: is_sbdaya_debet,
                    is_rekanan_kredit: is_rekanan_kredit,
                    is_sbdaya_kredit: is_sbdaya_kredit
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
                        $('.form_vocuherout').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {

                        //alert(json['success']);
                        $('.form_vocuherout').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                        form_voucherout_clear();
                        form_voucherout_refresh_grid();
                    }
                    //scrollup();
                    //createAutoClosingAlert('div.alert', 3000);
                }
            });
        });

        var lebar = $('.inbody').height() - 120;
        var panjang = $('.content').width() - 20;

        jQuery("#form_voucherout_list").jqGrid({
            url: root + mod + '/sess2json',
            mtype: "post",
            datatype: "json",
            colNames: ['#', '', "<div id='jq_checkbox_head_added'><div>", 'No.', 'Keterangan', 'ID Perkiraan', 'Kode Perkiraan', 'Buku Bantu', 'Debet', 'Kredit'],
            colModel: [
                {name: 'act', index: 'act', width: 30, sortable: false, align: "center"},
                {name: 'id', key: true, index: 'id', hidden: true, width: 30},
                {name: 'check', index: 'check', width: 30, sortable: false, align: "center"},
                {name: 'itno', index: 'itno', width: 30, align: "center", sortable: false},
                {name: 'keterangan', index: 'keterangan', width: 300, sortable: false},
                {name: 'dperkir_id', index: 'dperkir_id', hidden: true, width: 300, sortable: false},
                {name: 'kdperkiraan', index: 'kdperkiraan', width: 150, align: "center", sortable: false},
                {name: 'bukubantu', index: 'bukubantu', width: 150, align: "left", sortable: false},
                {name: 'debet', index: 'debet', width: 150, align: "right", formatter: 'currency', sortable: false},
                {name: 'kredit', index: 'kredit', width: 150, align: "right", formatter: 'currency', sortable: false}
            ],
            scroll: true,
            width: panjang,
            height: lebar,
            rownumbers: true,
            rowNum: 1000,
            rownumWidth: 40,
            multiselect: false,
            pager: '#form_voucherout_page',
            viewrecords: true,
            footerrow: true,
            userDataOnFooter: true,
            altRows: true,
            shrinkToFit: false
            //caption:"List Proyek"
        });
        jQuery("#form_voucherout_list").jqGrid('navGrid', '#form_voucherout_page', {edit: false, add: false, del: false, search: false});

        $('div#jq_checkbox_head_added').prepend('<div class="checkicon_add"><image src="' + root + 'uncheckbox.gif" /></div>');
        $('div#jq_checkbox_head_added').removeClass('selected');

        $('div#jq_checkbox_head_added').click(function() {
            $('.checkicon_add').remove();

            if ($('div#jq_checkbox_head_added').hasClass('selected')) {
                $('div#jq_checkbox_head_added').removeClass('selected');
                $('div#jq_checkbox_head_added').prepend('<div class="checkicon_add"><image src="' + root + 'uncheckbox.gif" /></div>');
                $('.jq_checkbox_added').each(function() {
                    this.checked = false;
                });
            }
            else {
                $('div#jq_checkbox_head_added').addClass('selected')
                $('div#jq_checkbox_head_added').prepend('<div class="checkicon_add"><image src="' + root + 'checkbox.gif" /></div>');
                $('.jq_checkbox_added').each(function() {
                    this.checked = true;
                });
            }
        });

		$('button[name="form_voucherout_simpan"]').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();
            $.ajax({
                url: root + "mod_voucherout/addJurnal",
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
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        form_voucherout_clear();
                        form_voucherout_refresh_grid();
                    }
                }
            });
        });

        $('#form_voucherout_delete').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();


            $.ajax({
                url: root + "mod_voucherout/deletejurnal",
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
                        form_voucherout_refresh_grid();
                    }
                }
            });
        });
    });
</script>
