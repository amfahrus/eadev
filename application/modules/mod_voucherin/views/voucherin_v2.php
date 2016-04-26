
<div class="content form_voucherin">
    <?= form_open(); ?>
    <input type="hidden" name="form_voucherin_id" value="" />
        
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="blue box_title"><h4><span>#</span> Voucher In</h4></div>
                <div class="blue box_content form-horizontal">

								<div class="row-fluid">
									<div class="control-group info">
										<label class="control-label" for="form_voucherin_tanggal">Tanggal Transaksi</label>
										<div class="controls">
											<input type="text" id="form_voucherin_tanggal" name="form_voucherin_tanggal" class="datepicker" value="<?= $tanggal; ?>" autocomplete="off"/>
										</div>
									</div>
								</div>
								
								<div class="row-fluid">
									<div class="control-group info">
										<label class="control-label" for="form_voucherin_nobukti">Nomor</label>
										<div class="controls">
											<input type="hidden" id="form_voucherin_nobukti" name="form_voucherin_nobukti" readonly value="" placeholder="AUTO"/>
											<input type="text" id="form_voucherin_no_dokumen" name="form_voucherin_no_dokumen" autocomplete="off"/>
										</div>
									</div>
								</div>
									
								<div class="row-fluid">
									<div class="control-group info">
										<label class="control-label" for="form_voucherin_jenis">Jenis Transaksi</label>
										<div class="controls">
											<select name="form_voucherin_jenis" id="form_voucherin_jenis">
												<option value="K">Kas</option>
												<option value="B">Bank</option>
											</select>
										</div>
									</div>
								</div>
							
								 <!--  begin blok debet -->
								<input type="hidden" name="form_voucherin_debet_id" id="form_voucherin_debet_id"/>
								<input type="hidden" name="form_voucherin_debet_kode" id="form_voucherin_debet_kode"/>
								<input type="hidden" name="form_voucherin_debet_kode_bukubantu" id="form_voucherin_debet_kode_bukubantu"/>
								<input type="hidden" name="is_sbdaya_debet" id="is_sbdaya_debet"/>
								<input type="hidden" name="is_rekanan_debet" id="is_rekanan_debet"/>
								<input type="hidden" name="is_proyek_debet" id="is_proyek_debet"/>
								<div class="row-fluid">
									<div class="control-group info">
										<label class="control-label" for="form_voucherin_debet">Debet</label>
										<div class="controls">
											<div class="input-append">
												<input type="text" id="form_voucherin_debet" name="form_voucherin_debet"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" id="form_voucherin_debet_listcoa"><i class="cus-table"></i> List</a></li>
													</ul>
												</div>
											</div>
											<div id="form_voucherin_debet_bukubantu_area" class="input-append">
												<input type="text" id="form_voucherin_debet_bukubantu" name="form_voucherin_debet_bukubantu"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" id="form_voucherin_debet_listbukubantu"><i class="cus-table"></i> List</a></li>
														<li><a href="#" id="form_voucherin_debet_addbukubantu"><i class="cus-table-add"></i> Add New</a></li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--  End blok debet -->
							
								<!--<div class="row-fluid">
									<div class="control-group info">
										<label class="control-label" for="form_voucherin_no_dokumen">Nomor Dokumen</label>
										<div class="controls">
											<input type="text" id="form_voucherin_no_dokumen" name="form_voucherin_no_dokumen" autocomplete="off"/>
										</div>
									</div>
								</div>-->
							
					<table id="tabel_uraian" class="table table-bordered control-group info">
						<tr>
							<!--<th>NOMOR</th>-->
							<th>URAIAN</th>
							<th>KREDIT</th>
							<th>JUMLAH</th>
						</tr>
						<tr>
							<!--<td>
								<span class="nomor" id="nomor_1">1</span>
							</td>-->
							<td>
								<div class="control-group info">
									<input type="text" class="form_voucherin_keterangan" id="form_voucherin_keterangan_1" name="form_voucherin_keterangan[]" autocomplete="off">
								</div>
							</td>
							<td>
								<!-- begin blok kredit -->
								<input type="hidden" name="form_voucherin_kredit_id[]" class="form_voucherin_kredit_id" id="form_voucherin_kredit_id_1" />
								<input type="hidden" name="form_voucherin_kredit_kode[]" class="form_voucherin_kredit_kode" id="form_voucherin_kredit_kode_1" />
								<input type="hidden" name="form_voucherin_kredit_kode_bukubantu[]" class="form_voucherin_kredit_kode_bukubantu" id="form_voucherin_kredit_kode_bukubantu_1" />
								<input type="hidden" name="is_sbdaya_kredit[]" class="is_sbdaya_kredit" id="is_sbdaya_kredit_1" />
								<input type="hidden" name="is_rekanan_kredit[]" class="is_rekanan_kredit" id="is_rekanan_kredit_1" />
								<input type="hidden" name="is_proyek_kredit[]" class="is_proyek_kredit" id="is_proyek_kredit_1" />
								
										<div class="control-group info">
											<div class="input-append">
												<input type="text" onkeydown="thisrow=1;getautocomplete();" class="form_voucherin_kredit" id="form_voucherin_kredit_1" name="form_voucherin_kredit[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_voucherin_kredit_listcoa" onclick="showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir', 'getpicker_kreditperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500); thisrow=1;" id="form_voucherin_kredit_listcoa_1"><i class="cus-table"></i> List</a></li>
													</ul>
												</div>
											</div>
											<div id="form_voucherin_kredit_bukubantu_area_1" class="input-append">
												<input type="text" class="form_voucherin_kredit_bukubantu" id="form_voucherin_kredit_bukubantu_1" name="form_voucherin_kredit_bukubantu[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_voucherin_kredit_listbukubantu" onclick="thisrow=1;getKreditListBukuBantu();" id="form_voucherin_kredit_listbukubantu_1"><i class="cus-table"></i> List</a></li>
														<li><a href="#" class="form_voucherin_kredit_addbukubantu" onclick="thisrow=1;getKreditAddBukuBantu();" id="form_voucherin_kredit_addbukubantu_1"><i class="cus-table-add"></i> Add New</a></li>
													</ul>
												</div>
											</div>
										</div>
								<!-- End blok kredit -->
							</td>
							<td>
								<div class="control-group info">
									<input type="text" onkeyup="getTotal();" id="form_voucherin_nilai_1" name="form_voucherin_nilai[]" class="form_voucherin_nilai" autocomplete="off"/>
								</div>
							</td>
							<td>
								<button id="add_item" type="button" class="btn btn-primary"><i class="icon-plus icon-white"></I></button>
							</td>
						</tr>
					</table>
                    <table id="tabel_total" class="table table-bordered">
						<tr>
							<th>
							TOTAL
							</th>
							<th>
								<input type="text" id="form_voucherin_total" name="form_voucherin_total" class="form_voucherin_total" readonly autocomplete="off" value="0"/>
							</th>
						</tr>
					</table>	
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" name="form_voucherin_tambah" class="btn btn-primary"><i class="cus-table-save"></i>Simpan</button>
                                    <button type="button" name="form_voucherin_batal" class="btn btn-primary"><i class="cus-cancel"></i>Batal</button>
                                    <!--<button type="button" name="form_voucherin_simpan" class="btn btn-primary"><i class="cus-table-save"></i>Simpan</button>-->
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
            <table id="form_voucherin_list"></table>
            <div id="form_voucherin_page"></div>
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
		
		//var newCell = row.insertCell(0);
		//newCell.innerHTML = "<tr><td><span class=\"nomor\" id=\"nomor_"+rowCount+"\">"+rowCount+"</span></td>";

		var newCell = row.insertCell(0);
		newCell.innerHTML = "<tr><td><div class=\"control-group info\"><input type=\"text\" class=\"form_voucherin_keterangan\" id=\"form_voucherin_keterangan_"+rowCount+"\" name=\"form_voucherin_keterangan[]\" autocomplete=\"off\"></div></td>";

		var newCell = row.insertCell(1);
		newCell.innerHTML = "<td>"
								+"<input type=\"hidden\" name=\"form_voucherin_kredit_id[]\" class=\"form_voucherin_kredit_id\" id=\"form_voucherin_kredit_id_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_voucherin_kredit_kode[]\" class=\"form_voucherin_kredit_kode\" id=\"form_voucherin_kredit_kode_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_voucherin_kredit_kode_bukubantu[]\" class=\"form_voucherin_kredit_kode_bukubantu\" id=\"form_voucherin_kredit_kode_bukubantu_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_sbdaya_kredit[]\" class=\"is_sbdaya_kredit\" id=\"is_sbdaya_kredit_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_rekanan_kredit[]\" class=\"is_rekanan_kredit\" id=\"is_rekanan_kredit_"+rowCount+"\" />"								
								+"<input type=\"hidden\" name=\"is_proyek_kredit[]\" class=\"is_proyek_kredit\" id=\"is_proyek_kredit_"+rowCount+"\" />"								
										+"<div class=\"control-group info\">"
											+"<div class=\"input-append\">"
												+"<input type=\"text\" onkeydown=\"thisrow="+rowCount+";getautocomplete();\" class=\"form_voucherin_kredit ui-autocomplete-input\" id=\"form_voucherin_kredit_"+rowCount+"\" name=\"form_voucherin_kredit[]\"  autocomplete=\"off\" role=\"textbox\" aria-autocomplete=\"list\" aria-haspopup=\"true\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" class=\"form_voucherin_kredit_listcoa\" onclick=\"showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir/'+$(this).attr('data-row'), 'getpicker_kreditperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500);thisrow="+rowCount+";\" id=\"form_voucherin_kredit_listcoa_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
											+"<div id=\"form_voucherin_kredit_bukubantu_area_"+rowCount+"\" class=\"input-append\">"
												+"<input type=\"text\" class=\"form_voucherin_kredit_bukubantu\" id=\"form_voucherin_kredit_bukubantu_"+rowCount+"\" name=\"form_voucherin_kredit_bukubantu[]\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getKreditListBukuBantu();\" class=\"form_voucherin_kredit_listbukubantu\" id=\"form_voucherin_kredit_listbukubantu_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getKreditAddBukuBantu();\" class=\"form_voucherin_kredit_addbukubantu\" id=\"form_voucherin_kredit_addbukubantu_"+rowCount+"\"><i class=\"cus-table-add\"></i> Add New</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
										+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(2);
		newCell.innerHTML = "<td>"
								+"<div class=\"control-group info\">"
									+"<input type=\"text\" onkeyup=\"getTotal();\" id=\"form_voucherin_nilai_"+rowCount+"\" name=\"form_voucherin_nilai[]\" class=\"form_voucherin_nilai\" autocomplete=\"off\"/>"
								+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(3);
		newCell.innerHTML ="<td><button type=\"button\" onclick=\"addItem();\" class=\"btn btn-primary add_item\"><i class=\"icon-plus icon-white\"></I></button>"
							+"<button id=\"remove_item_"+rowCount+"\" type=\"button\" class=\"btn btn-danger\" onclick=\"removeItem(" + rowCount + ")\" ><i class=\"icon-remove icon-white\"></I></button><td></tr>"
		
		row.setAttribute("id", "item_" + rowCount);
		$('#form_voucherin_kredit_bukubantu_area_'+rowCount).hide();
        $('input[id="form_voucherin_nilai_'+rowCount+'"]').number(true, 2);
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
		}
		rowCount--;
		getTotal();
	}
    function voucherin_clear() {
        $('input[name="form_voucherin_id"]').val("");
        $('input[name="form_voucherin_nobukti"]').val("");
        $('input[name="form_voucherin_jenis"]').val("");
        $('input[name="form_voucherin_debet_kode"]').val("");
        $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
        $('input[name="is_sbdaya_debet"]').val("");
        $('input[name="is_rekanan_debet"]').val("");
        $('input[name="is_proyek_debet"]').val("");
        $('input[name="form_voucherin_debet"]').val("");
        $('input[name="form_voucherin_debet_bukubantu"]').val("");

        $('input[class="form_voucherin_kredit_kode"]').val("");
        $('input[class="form_voucherin_kredit_kode_bukubantu"]').val("");
        $('input[class="is_sbdaya_kredit"]').val("");
        $('input[class="is_rekanan_kredit"]').val("");
        $('input[class="is_proyek_kredit"]').val("");
        $('input[class="form_voucherin_kredit"]').val("");
        $('input[class="form_voucherin_kredit_bukubantu"]').val("");

        $('input[class="form_voucherin_keterangan"]').val("");
        $('input[class="form_voucherin_nilai"]').val("");
    }

    function voucherin_getSession() {
        $.ajax({
            url: root + 'mod_voucherin/getSession',
            dataType: 'json',
            success: function(json) {
                $('div.alert').remove();

                if (json['error']) {
                   // $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                    //$('div.alert').fadeIn('slow');
                    voucherin_clear();
                }

                if (json['success']) {
					//alert(json["record"]["detail"]["D"]["bukubantu"]);
                    //$('input[name="form_voucherin_id"]').val("");
                    $('input[name="form_voucherin_nobukti"]').val(json["nobukti"]);
                    $('input[name="form_voucherin_no_dokumen"]').val(json["no_dokumen"]);
                    $('input[name="form_voucherin_tanggal"]').val(json["tanggal"]);
                    $('select[name="form_voucherin_jenis"]').val(json["jenis_transaksi"]);

                    $('input[name="form_voucherin_debet_id"]').val(json["debet_id"]);
                    $('input[name="form_voucherin_debet_kode"]').val(json["debet_kode"]);
					if(json["debet_sbdaya"] === "t" || json["debet_rekanan"] === "t"){
						$('#form_voucherin_debet_bukubantu_area').show();
					} else {
						$('#form_voucherin_debet_bukubantu_area').hide();
					}
                    $('input[name="form_voucherin_debet_kode_bukubantu"]').val(json["debet_bukubantu"]);
                    $('input[name="is_sbdaya_debet"]').val(json["debet_sbdaya"]);
                    $('input[name="is_rekanan_debet"]').val(json["debet_rekanan"]);
                    $('input[name="form_voucherin_debet"]').val(json["debet_kode"]);
                    $('input[name="form_voucherin_debet_bukubantu"]').autocomplete().val(json["debet_bukubantu"]).data('autocomplete')._trigger('select');
                    //alert(json["kredit_length"]);
					$.each(json["kredit"], function(i, item) {
						$('input[id="form_voucherin_kredit_id_'+i+'"]').val(json["kredit"][i]["dperkir_id"]);
						$('input[id="form_voucherin_kredit_kode_'+i+'"]').val(json["kredit"][i]["kdperkiraan"]);
						if(json["kredit"][i]["is_sbdaya"] === "t" || json["kredit"][i]["is_rekanan"] === "t"){
							$('#form_voucherin_kredit_bukubantu_area_'+i+'').show();
						} else {
							$('#form_voucherin_kredit_bukubantu_area_'+i+'').hide();
						}
						$('input[id="form_voucherin_kredit_kode_bukubantu_'+i+'"]').val(json["kredit"][i]["bukubantu"]);
						$('input[id="is_sbdaya_kredit_'+i+'"]').val(json["kredit"][i]["is_sbdaya"]);
						$('input[id="is_rekanan_kredit_'+i+'"]').val(json["kredit"][i]["is_rekanan"]);
						$('input[id="form_voucherin_kredit_'+i+'"]').val(json["kredit"][i]["kdperkiraan"]);
						$('input[id="form_voucherin_kredit_bukubantu_'+i+'"]').autocomplete().val(json["kredit"][i]["bukubantu"]).data('autocomplete')._trigger('select');

						$('input[id="form_voucherin_keterangan_'+i+'"]').val(json["kredit"][i]["keterangan"]);
						$('input[id="form_voucherin_nilai_'+i+'"]').val(json["kredit"][i]["nilai"]);
						if(i < json["kredit_length"]){
							addItem();
						}
					})
				
					getTotal();	
                    bukubantu_debet();
                    bukubantu_kredit();
                }
                scrollup();
                createAutoClosingAlert('div.alert', 3000);

            }
        });
    }

    function bukubantu_debet() {
        var is_rekanan_debet = $('input[name="is_rekanan_debet"]').val();
        var is_sbdaya_debet = $('input[name="is_sbdaya_debet"]').val();
        var is_proyek_debet = $('input[name="is_proyek_debet"]').val();
        var coa_debet = $('input[name="form_voucherin_debet_id"]').val();

        if (is_rekanan_debet === 't') {
            $('input[name="form_voucherin_debet_bukubantu"]').autocomplete({
                minLength: 2,
                source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                    $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[name="form_voucherin_debet_kode_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherin_debet_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherin_no_dokumen"]').focus();
                    } else {
                        $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                        $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
						$('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                        $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    }
                }
            });
        } else if (is_sbdaya_debet === 't') {
            $('input[name="form_voucherin_debet_bukubantu"]').autocomplete({
                minLength: 2,
                source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                    $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[name="form_voucherin_debet_kode_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherin_debet_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherin_no_dokumen"]').focus();
                    } else {
                        $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                        $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
						$('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                        $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    }
                }
            });
        } else if (is_proyek_debet === 't') {
            $('input[name="form_voucherin_debet_bukubantu"]').autocomplete({
                minLength: 2,
                source: root + "mod_proyek/autocomplete_proyek",
                create: function(event, ui) {
                    $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                    $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[name="form_voucherin_debet_kode_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherin_debet_bukubantu"]').val(ui.item.id);
                        $('input[name="form_voucherin_no_dokumen"]').focus();
                    } else {
                        $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                        $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
						$('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
                        $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    }
                }
            });
        } else {
            $('input[name="form_voucherin_debet_bukubantu"]').autocomplete("destroy");
        }
    }

    function bukubantu_kredit(row) {
        var is_rekanan_kredit = $('input[id="is_rekanan_kredit_'+row+'"]').val();
        var is_sbdaya_kredit = $('input[id="is_sbdaya_kredit_'+row+'"]').val();
        var is_proyek_kredit = $('input[id="is_proyek_kredit_'+row+'"]').val();
        var coa_kredit = $('input[id="form_voucherin_kredit_id_'+row+'"]').val();

        if (is_rekanan_kredit === 't') {
            $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa_kredit,
                create: function(event, ui) {
                    $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_voucherin_keterangan_'+row+'"]').focus();
                    } else {
                        $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
					if (ui.item === null) {
						$('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else if (is_sbdaya_kredit === 't') {
            $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa_kredit,
                create: function(event, ui) {
                    $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_voucherin_keterangan_'+row+'"]').focus();
                    } else {
                        $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
						$('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else if (is_proyek_kredit === 't') {
            $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_proyek/autocomplete_proyek",
                create: function(event, ui) {
                    $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_voucherin_keterangan_'+row+'"]').focus();
                    } else {
                        $('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
						$('input[id="form_voucherin_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else {
            $('input[id="form_voucherin_kredit_bukubantu_'+row+'"]').autocomplete("destroy");
        }
    }

    function getpicker_debetperkiraan(json) {
        $('input[name="form_voucherin_debet_id"]').val(json.dperkir_id);
        $('input[name="form_voucherin_debet"]').val(json.kdperkiraan);
        $('input[name="form_voucherin_debet_kode"]').val(json.kdperkiraan);
        $('.popup_kodeperkiraan').dialog('close');
        $('input[name="form_voucherin_debet_bukubantu"]').focus();
        
        if (json.flag_sumberdaya === 't') {
			$('input[name="is_sbdaya_debet"]').val(json.flag_sumberdaya);			
			$('#form_voucherin_debet_bukubantu_area').show();
		} else {
			$('input[name="is_sbdaya_debet"]').val("");
			$('#form_voucherin_debet_bukubantu_area').hide();
		}

		if (json.flag_nasabah === 't') {
			$('input[name="is_rekanan_debet"]').val(json.flag_nasabah);
			$('#form_voucherin_debet_bukubantu_area').show();
		} else {
			$('input[name="is_rekanan_debet"]').val("");
			$('#form_voucherin_debet_bukubantu_area').hide();
		}

		if (json.proyek === 't') {
			$('input[name="is_proyek_debet"]').val(json.proyek);
			$('#form_voucherin_debet_bukubantu_area').show();
		} else {
			$('input[name="is_proyek_debet"]').val("");
			$('#form_voucherin_debet_bukubantu_area').hide();
		}
		
		if (json.flag_sumberdaya === null && json.flag_nasabah === null && json.proyek === null) {
			$('#form_voucherin_debet_bukubantu_area').hide();
			$('input[name="is_rekanan_debet"]').val("");
			$('input[name="is_sbdaya_debet"]').val("");
			$('input[name="is_proyek_debet"]').val("");
		}
    }

	function getpicker_kreditperkiraan(json) {
		$('input[id="form_voucherin_kredit_id_'+thisrow+'"]').val(json.dperkir_id);
        $('input[id="form_voucherin_kredit_'+thisrow+'"]').val(json.kdperkiraan);
        $('input[id="form_voucherin_kredit_kode_'+thisrow+'"]').val(json.kdperkiraan);
        $('.popup_kodeperkiraan').dialog('close');
        $('input[id="form_voucherin_kredit_bukubantu_'+thisrow+'"]').focus();
        
        if (json.flag_sumberdaya === 't') {
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val(json.flag_sumberdaya);			
			$('#form_voucherin_kredit_bukubantu_area_'+thisrow).show();
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
		}

		if (json.flag_nasabah === 't') {
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val(json.flag_nasabah);
			$('#form_voucherin_kredit_bukubantu_area_'+thisrow).show();
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
		}

		if (json.proyek === 't') {
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val(json.proyek);
			$('#form_voucherin_kredit_bukubantu_area_'+thisrow).show();
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
		}
		
		if (json.flag_sumberdaya === null && json.flag_nasabah === null && json.proyek === null) {
			$('#form_voucherin_kredit_bukubantu_area_'+thisrow).hide();
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
		}
    }

    function getpicker_debetbukubantu(json) {
        $('input[name="form_voucherin_debet_kode_bukubantu"]').val(json.kode_rekanan);
        $('input[name="form_voucherin_debet_bukubantu"]').val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
        $('input[name="form_voucherin_keterangan"]').focus();
    }

    function getpicker_kreditbukubantu(json) {
        $('input[id="form_voucherin_kredit_kode_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('input[id="form_voucherin_kredit_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
        $('input[id="form_voucherin_keterangan_'+thisrow+'"]').focus();
    }

	function getautocomplete(){
		/* begin autocomplete kredit_kodeperkiraan*/

        $('input[id="form_voucherin_kredit_'+thisrow+'"]').autocomplete({
            minLength: 2,
			matchContains:false, 
			minChars:1,  
			autoFill:false, 
			mustMatch:true, 
			cacheLength:20, 
			max:20,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
            create: function(event, ui) {
				$('input[id="form_voucherin_kredit_id_'+thisrow+'"]').val("");
                $('input[id="form_voucherin_kredit_kode_'+thisrow+'"]').val("");
                $('input[id="form_voucherin_kredit_'+thisrow+'"]').val("");
                return false;
            },
            search: function(event, ui) {
                $('input[id="form_voucherin_kredit_id_'+thisrow+'"]').val("");
                $('input[id="form_voucherin_kredit_kode_'+thisrow+'"]').val("");
            },
            select: function(event, ui) {
				//alert(ui.item.flag_nasabah);
                if (ui.item.id != 0) {
                    $('input[id="form_voucherin_kredit_id_'+thisrow+'"]').val(ui.item.id);
                    $('input[id="form_voucherin_kredit_kode_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_voucherin_kredit_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_voucherin_kredit_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_voucherin_kredit_bukubantu_'+thisrow+'"]').focus();

                    if (ui.item.flag_sumberdaya === 't') {
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val(ui.item.flag_sumberdaya);
                        $('#form_voucherin_kredit_bukubantu_area_'+thisrow).show();
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
                    }

                    if (ui.item.flag_nasabah === 't') {
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val(ui.item.flag_nasabah);
                        $('#form_voucherin_kredit_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
                    }

                    if (ui.item.proyek === 't') {
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val(ui.item.proyek);
                        $('#form_voucherin_kredit_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                    }
                    
                    if (ui.item.flag_sumberdaya === null && ui.item.flag_nasabah === null && ui.item.proyek === null){
						$('#form_voucherin_kredit_bukubantu_area_'+thisrow).hide();
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
					}

                } else {
                    $('input[id="form_voucherin_kredit_id_'+thisrow+'"]').val("");
                    $('input[id="form_voucherin_kredit_kode_'+thisrow+'"]').val("");
                    $('input[id="form_voucherin_kredit_'+thisrow+'"]').val("");
                    $('input[id="form_voucherin_kredit_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                    $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
                }
                bukubantu_kredit(thisrow);
                return false;
            },
            change: function(event, ui) {
                if (ui.item === null) {
					$('input[id="form_voucherin_kredit_id_'+thisrow+'"]').val("");
					$('input[id="form_voucherin_kredit_kode_'+thisrow+'"]').val("");
                    $('input[id="form_voucherin_kredit_'+thisrow+'"]').val("");
                    $('input[id="form_voucherin_kredit_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                    $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
				}
            }
        });
	}
	
	function getKreditListBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_kredit_'+thisrow+'"]').val();
		var is_proyek = $('input[id="is_proyek_kredit_'+thisrow+'"]').val();
		var coa_kredit = $('input[id="form_voucherin_kredit_id_'+thisrow+'"]').val();
		//alert(is_sbdaya+' - '+is_rekanan);
		if (is_rekanan === 't') {
			showUrlInDialog(root + "mod_rekanan/popup_rekanan?coa="+coa_kredit, "getpicker_kreditbukubantu", "List Buku Bantu Rekanan", "popup_rekanan", 600, 500);
		}
		else if (is_sbdaya === 't') {
			showUrlInDialog(root + "mod_sbdaya/popup_sbdaya", "getpicker_kreditbukubantu", "List Buku Bantu SB Daya", "popup_rekanan", 600, 500);
		}
		else if (is_proyek === 't') {
			showUrlInDialog(root + "mod_proyek/popup_proyek", "getpicker_kreditbukubantu", "List Buku Bantu", "popup_rekanan", 600, 500);
		}
		else {
			return false;
		}

	}
	
	function getKreditAddBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_kredit_'+thisrow+'"]').val();
		var is_proyek = $('input[id="is_proyek_kredit_'+thisrow+'"]').val();

		if (is_rekanan === 't') {
			showUrlInDialog(root + "mod_rekanan/popup_add", "", "Tambah Buku Bantu Rekanan", "popup_kredit_bukubantu", 600, 500);
		}
		else if (is_sbdaya === 't') {
			showUrlInDialog(root + "mod_sbdaya/popup_add", "", "Add SB Daya", "popup_kredit_bukubantu", 600, 500);
		}
		else if (is_proyek === 't') {
			alert("Tidak Tersedia!");
		}
		else {
			return false;
		}
	}
	
	function getTotal(){
		var sum = 0;
		
		var dataArray = $('input[class="form_voucherin_nilai"]').serializeArray(),
			len = dataArray.length;

		for (i=0; i<len; i++) {
			sum += Number(dataArray[i].value);
		}
		$('input[id="form_voucherin_total"]').val(sum);
	}
	
    function form_voucherin_clear() {
        $('div.alert').remove();
        //$('input[name="form_voucherin_id"]').val("");
        //$('input[name="form_voucherin_tanggal"]').val("");
        $('input[name="form_voucherin_nobukti"]').val("");
        $('input[name="form_voucherin_jenis"]').val("");
        $('input[name="form_voucherin_debet_id"]').val("");
        $('input[name="form_voucherin_debet_kode"]').val("");
        $('input[name="form_voucherin_debet_kode_bukubantu"]').val("");
        $('input[name="is_sbdaya_debet"]').val("");
        $('input[name="is_rekanan_debet"]').val("");
        $('input[name="is_proyek_debet"]').val("");
        $('input[name="form_voucherin_debet"]').val("");
        $('input[name="form_voucherin_debet_bukubantu"]').val("");
        $('input[name="form_voucherin_no_dokumen"]').val("");
        $('input[name="form_voucherin_debet_bukubantu"]').autocomplete("destroy");
        
        $('input[id="form_voucherin_kredit_id_1"]').val("");
        $('input[id="form_voucherin_kredit_kode_1"]').val("");
        $('input[id="form_voucherin_kredit_kode_bukubantu_1"]').val("");
        $('input[id="is_sbdaya_kredit_1"]').val("");
        $('input[id="is_rekanan_kredit_1"]').val("");
        $('input[id="is_proyek_kredit_1"]').val("");
        $('input[id="form_voucherin_kredit_1"]').val("");
        $('input[id="form_voucherin_kredit_bukubantu_1"]').val("");
        $('input[id="form_voucherin_keterangan_1"]').val("");
        $('input[id="form_voucherin_nilai_1"]').val("");
        $('input[id="form_voucherin_kredit_bukubantu_1"]').autocomplete("destroy");
        
		$('#form_voucherin_debet_bukubantu_area').hide();
		$('#form_voucherin_kredit_bukubantu_area_1').hide();
		$('input[id="form_voucherin_total"]').val(0);
        while (rowCount > 1) {
			removeItem(rowCount);
		}
    }

    /* begin document ready */
    $(document).ready(function() {
		$('#form_voucherin_debet_bukubantu_area').hide();
		$('#form_voucherin_kredit_bukubantu_area_1').hide();
        $('input[id="form_voucherin_nilai_1"]').number(true, 2);
        $('input[id="form_voucherin_total"]').number(true, 2);
		form_voucherin_clear();
        $('input[name="form_voucherin_debet_bukubantu"]').autocomplete("destroy");
        $('input[name="form_voucherin_kredit_bukubantu"]').autocomplete("destroy");
        $('input[name="form_voucherin_debet_bukubantu"]').val("");
        $('input[name="form_voucherin_kredit_bukubantu"]').val("");
        $('input[name="is_sbdaya_debet"]').val("");
        $('input[name="is_rekanan_debet"]').val("");
        $('input[name="is_proyek_debet"]').val("");
        $('input[name="is_sbdaya_kredit"]').val("");
        $('input[name="is_rekanan_kredit"]').val("");
        $('input[name="is_proyek_kredit"]').val("");
        $('input[name="form_voucherin_no_dokumen"]').focus();
		$('button[id="add_item"]').bind('click', function(){
			addItem();
		});
		voucherin_getSession();
        $('a[id="form_voucherin_debet_listcoa"]').bind('click', function() {
            showUrlInDialog(root + "mod_kdperkiraan/popup_kdperkir", "getpicker_debetperkiraan", "List Kode Perkiraan", "popup_kodeperkiraan", 600, 500);
        });

        $('a[id="form_voucherin_debet_listbukubantu"]').bind('click', function() {
            var is_sbdaya = $('input[name="is_sbdaya_debet"]').val();
            var is_rekanan = $('input[name="is_rekanan_debet"]').val();
            var is_proyek = $('input[name="is_proyek_debet"]').val();
			var coa_debet = $('input[name="form_voucherin_debet_id"]').val();

            if (is_rekanan === 't') {
                showUrlInDialog(root + "mod_rekanan/popup_rekanan?coa="+coa_debet, "getpicker_debetbukubantu", "List Buku Bantu Rekanan", "popup_rekanan", 600, 500);
            }
            else if (is_sbdaya === 't') {
                showUrlInDialog(root + "mod_sbdaya/popup_sbdaya", "getpicker_debetbukubantu", "List Buku Bantu SB Daya", "popup_rekanan", 600, 500);
            }
            else if (is_proyek === 't') {
                showUrlInDialog(root + "mod_proyek/popup_proyek", "getpicker_debetbukubantu", "List Buku Bantu", "popup_rekanan", 600, 500);
            }
            else {
                return false;
            }
        });


        $('a[id="form_voucherin_debet_addbukubantu"]').bind('click', function() {
            var is_sbdaya = $('input[name="is_sbdaya_debet"]').val();
            var is_rekanan = $('input[name="is_rekanan_debet"]').val();
            var is_proyek = $('input[name="is_proyek_debet"]').val();

            if (is_rekanan === 't') {
                showUrlInDialog(root + "mod_rekanan/popup_add", "", "Tambah Buku Bantu Rekanan", "popup_kredit_bukubantu", 600, 500);
            }
            else if (is_sbdaya === 't') {
                showUrlInDialog(root + "mod_sbdaya/popup_add", "", "Add SB Daya", "popup_debet_bukubantu", 600, 500);
            }
            else if (is_proyek === 't') {
				alert("Tidak Tersedia!");
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

        $('select[name="form_voucherin_jenis"]').change(function() {
            var jenis = $(this).val();
            var cat = $('select[name="form_voucherin_jenis"]').val();
            $('input[name="form_voucherin_debet"]').val("");
            $('input[name="form_voucherin_debet_kode"]').val("");

            $('input[name="form_voucherin_debet"]').autocomplete("option", {
                source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan?_search=true&cat=" + cat
            });
            form_voucherin_clear();
        });


        $('input[name="form_voucherin_debet"]').autocomplete({
            minLength: 2, 
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan?_search=true&cat=" + $('select[name="form_voucherin_jenis"]').val(),
            create: function(event, ui) {
                $('input[name="form_voucherin_debet_id"]').val("");
                $('input[name="form_voucherin_debet_kode"]').val("");
                $('input[name="form_voucherin_debet"]').val("");
                return false;
            },
            search: function(event, ui) {
                $('input[name="form_voucherin_debet_id"]').val("");
                $('input[name="form_voucherin_debet_kode"]').val("");
            },
            select: function(event, ui) {
                if (ui.item.id != 0) {
                    $('input[name="form_voucherin_debet_id"]').val(ui.item.id);
                    $('input[name="form_voucherin_debet_kode"]').val(ui.item.kode);
                    $('input[name="form_voucherin_debet"]').val(ui.item.kode);
                    $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    $('input[name="form_voucherin_debet_bukubantu"]').focus();

                    if (ui.item.flag_sumberdaya === 't') {
                        $('input[name="is_sbdaya_debet"]').val(ui.item.flag_sumberdaya);
                        $('#form_voucherin_debet_bukubantu_area').show();
                    } else {
                        $('input[name="is_sbdaya_debet"]').val("");
                        $('#form_voucherin_debet_bukubantu_area').hide();
                    }

                    if (ui.item.flag_nasabah === 't') {
                        $('input[name="is_rekanan_debet"]').val(ui.item.flag_nasabah);
                        $('#form_voucherin_debet_bukubantu_area').show();
                    } else {
                        $('input[name="is_rekanan_debet"]').val("");
                        $('#form_voucherin_debet_bukubantu_area').hide();
                    }

                    if (ui.item.proyek === 't') {
                        $('input[name="is_proyek_debet"]').val(ui.item.proyek);
                        $('#form_voucherin_debet_bukubantu_area').show();
                    } else {
                        $('input[name="is_proyek_debet"]').val("");
                        $('#form_voucherin_debet_bukubantu_area').hide();
                    }
                } else {
                    $('input[name="form_voucherin_debet_id"]').val("");
                    $('input[name="form_voucherin_debet_kode"]').val("");
                    $('input[name="form_voucherin_debet"]').val("");
                    $('input[name="form_voucherin_debet_bukubantu"]').val("");
                    $('input[name="is_sbdaya_debet"]').val("");
                    $('input[name="is_rekanan_debet"]').val("");
                    $('input[name="is_proyek_debet"]').val("");

                }
                bukubantu_debet();
                return false;
            },
            change: function(event, ui) {
				if (ui.item === null) {
					$('input[name="form_voucherin_debet_id"]').val("");
					$('input[name="form_voucherin_debet_kode"]').val("");
					$('input[name="form_voucherin_debet"]').val("");
					$('input[name="form_voucherin_debet_bukubantu"]').val("");
					$('input[name="is_sbdaya_debet"]').val("");
					$('input[name="is_rekanan_debet"]').val("");
					$('input[name="is_proyek_debet"]').val("");
				}
            }
        });

        $('button[name="form_voucherin_tambah"]').bind('click', function() {
            var voucherin_id = $('input[name="form_voucherin_id"]').val();
            var voucherin_nobukti = $('input[name="form_voucherin_nobukti"]').val();
            var voucherin_tanggal = $('input[name="form_voucherin_tanggal"]').val();
            var voucherin_jenis = $('select[name="form_voucherin_jenis"]').val();
            var voucherin_no_dokumen = $('input[name="form_voucherin_no_dokumen"]').val();
            var voucherin_debet_id = $('input[name="form_voucherin_debet_id"]').val();
            var voucherin_debet_kode = $('input[name="form_voucherin_debet_kode"]').val();
            var voucherin_debet_kode_bukubantu = $('input[name="form_voucherin_debet_kode_bukubantu"]').val();
            var voucherin_kredit_id = $('input[class="form_voucherin_kredit_id"]').serializeArray();
            var voucherin_kredit_kode = $('input[class="form_voucherin_kredit_kode"]').serializeArray();
            var voucherin_kredit_kode_bukubantu = $('input[class="form_voucherin_kredit_kode_bukubantu"]').serializeArray();
            var voucherin_keterangan = $('input[class="form_voucherin_keterangan"]').serializeArray();
            var voucherin_nilai = $('input[class="form_voucherin_nilai"]').serializeArray();
            var is_rekanan_debet = $('input[name="is_rekanan_debet"]').val();
            var is_sbdaya_debet = $('input[name="is_sbdaya_debet"]').val();
            var is_proyek_debet = $('input[name="is_proyek_debet"]').val();
            var is_rekanan_kredit = $('input[class="is_rekanan_kredit"]').serializeArray();
            var is_sbdaya_kredit = $('input[class="is_sbdaya_kredit"]').serializeArray();
            var is_proyek_kredit = $('input[class="is_proyek_kredit"]').serializeArray();
            $.ajax({
                url: root + 'mod_voucherin/add2session',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    id: voucherin_id,
                    nobukti: voucherin_nobukti,
                    tanggal: voucherin_tanggal,
                    jenis: voucherin_jenis,
                    no_dokumen: voucherin_no_dokumen,
                    debet_id: voucherin_debet_id,
                    debet_kode: voucherin_debet_kode,
                    debet_bukubantu: voucherin_debet_kode_bukubantu,
                    kredit_id: voucherin_kredit_id,
                    kredit_kode: voucherin_kredit_kode,
                    kredit_bukubantu: voucherin_kredit_kode_bukubantu,
                    keterangan: voucherin_keterangan,
                    nilai: voucherin_nilai,
                    is_rekanan_debet: is_rekanan_debet,
                    is_sbdaya_debet: is_sbdaya_debet,
                    is_proyek_debet: is_proyek_debet,
                    is_rekanan_kredit: is_rekanan_kredit,
                    is_sbdaya_kredit: is_sbdaya_kredit,
                    is_proyek_kredit: is_proyek_kredit
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
                        $('.form_voucherin').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        
                        form_voucherin_clear();
                        $('.form_voucherin').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    //scrollup();
                    //createAutoClosingAlert('div.alert', 3000);
                }
            });
        });

		$('button[name="form_voucherin_simpan"]').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();
            $.ajax({
                url: root + "mod_voucherin/addJurnal",
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
                        //form_voucherin_clear();
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                }
            });
        });
		
        $('button[name="form_voucherin_batal"]').click(function() {
            $.ajax({
                url: root + "mod_voucherin/cleanTransaksi"
            });
            form_voucherin_clear();
        });

    });
</script>
