
<div class="content form_memorial">
    <?= form_open(); ?>
    <input type="hidden" name="form_memorial_id" value="" />
        
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Memorial</h4></div>
                <div class="basic box_content form-horizontal">

								<div class="row-fluid">
									<div class="control-group success">
										<label class="control-label" for="form_memorial_tanggal">Tanggal Transaksi</label>
										<div class="controls">
											<input type="text" id="form_memorial_tanggal" name="form_memorial_tanggal" class="datepicker" value="<?= $tanggal; ?>" autocomplete="off"/>
										</div>
									</div>
								</div>
								
								<div class="row-fluid">
									<div class="control-group success">
										<label class="control-label" for="form_memorial_nobukti">Nomor</label>
										<div class="controls">
											<input type="hidden" id="form_memorial_nobukti" name="form_memorial_nobukti" readonly value="" placeholder="AUTO"/>
											<input type="text" id="form_memorial_no_dokumen" name="form_memorial_no_dokumen" autocomplete="off"/>
										</div>
									</div>
								</div>
								
								<input type="hidden" name="form_memorial_jenis" id="form_memorial_jenis" value="M"/>
							
					<table id="tabel_uraian" class="table table-bordered">
						<tr>
							<!--<th>NOMOR</th>-->
							<th>URAIAN</th>
							<th>DEBET</th>
							<th>KREDIT</th>
							<th>JUMLAH</th>
						</tr>
						<tr>
							<!--<td>
								<span class="nomor" id="nomor_1">1</span>
							</td>-->
							<td>
								<div class="control-group success">
									<input type="text" class="form_memorial_keterangan" id="form_memorial_keterangan_1" name="form_memorial_keterangan[]" autocomplete="off">
								</div>
							</td>
							<td>
								<!-- begin blok debet -->
								<input type="hidden" name="form_memorial_debet_id[]" class="form_memorial_debet_id" id="form_memorial_debet_id_1" />
								<input type="hidden" name="form_memorial_debet_kode[]" class="form_memorial_debet_kode" id="form_memorial_debet_kode_1" />
								<input type="hidden" name="form_memorial_debet_kode_bukubantu[]" class="form_memorial_debet_kode_bukubantu" id="form_memorial_debet_kode_bukubantu_1" />
								<input type="hidden" name="is_sbdaya_debet[]" class="is_sbdaya_debet" id="is_sbdaya_debet_1" />
								<input type="hidden" name="is_rekanan_debet[]" class="is_rekanan_debet" id="is_rekanan_debet_1" />
								<input type="hidden" name="is_proyek_debet[]" class="is_proyek_debet" id="is_proyek_debet_1" />
								
										<div class="control-group success">
											<div class="input-append">
												<input type="text" onkeydown="thisrow=1;getautocompletedebet();" class="form_memorial_debet" id="form_memorial_debet_1" name="form_memorial_debet[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_memorial_debet_listcoa" onclick="showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir', 'getpicker_debetperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500); thisrow=1;" id="form_memorial_debet_listcoa_1"><i class="cus-table"></i> List</a></li>
													</ul>
												</div>
											</div>
											<div id="form_memorial_debet_bukubantu_area_1" class="input-append">
												<input type="text" class="form_memorial_debet_bukubantu" id="form_memorial_debet_bukubantu_1" name="form_memorial_debet_bukubantu[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_memorial_debet_listbukubantu" onclick="thisrow=1;getDebetListBukuBantu();" id="form_memorial_debet_listbukubantu_1"><i class="cus-table"></i> List</a></li>
														<li><a href="#" class="form_memorial_debet_addbukubantu" onclick="thisrow=1;getDebetAddBukuBantu();" id="form_memorial_debet_addbukubantu_1"><i class="cus-table-add"></i> Add New</a></li>
													</ul>
												</div>
											</div>
										</div>
								<!-- End blok debet -->
							</td>
							<td>
								<!-- begin blok kredit -->
								<input type="hidden" name="form_memorial_kredit_id[]" class="form_memorial_kredit_id" id="form_memorial_kredit_id_1" />
								<input type="hidden" name="form_memorial_kredit_kode[]" class="form_memorial_kredit_kode" id="form_memorial_kredit_kode_1" />
								<input type="hidden" name="form_memorial_kredit_kode_bukubantu[]" class="form_memorial_kredit_kode_bukubantu" id="form_memorial_kredit_kode_bukubantu_1" />
								<input type="hidden" name="is_sbdaya_kredit[]" class="is_sbdaya_kredit" id="is_sbdaya_kredit_1" />
								<input type="hidden" name="is_rekanan_kredit[]" class="is_rekanan_kredit" id="is_rekanan_kredit_1" />
								<input type="hidden" name="is_proyek_kredit[]" class="is_proyek_kredit" id="is_proyek_kredit_1" />
								
										<div class="control-group success">
											<div class="input-append">
												<input type="text" onkeydown="thisrow=1;getautocompletekredit();" class="form_memorial_kredit" id="form_memorial_kredit_1" name="form_memorial_kredit[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_memorial_kredit_listcoa" onclick="showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir', 'getpicker_kreditperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500); thisrow=1;" id="form_memorial_kredit_listcoa_1"><i class="cus-table"></i> List</a></li>
													</ul>
												</div>
											</div>
											<div id="form_memorial_kredit_bukubantu_area_1" class="input-append">
												<input type="text" class="form_memorial_kredit_bukubantu" id="form_memorial_kredit_bukubantu_1" name="form_memorial_kredit_bukubantu[]"/>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn dropdown-toggle"><span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="#" class="form_memorial_kredit_listbukubantu" onclick="thisrow=1;getKreditListBukuBantu();" id="form_memorial_kredit_listbukubantu_1"><i class="cus-table"></i> List</a></li>
														<li><a href="#" class="form_memorial_kredit_addbukubantu" onclick="thisrow=1;getKreditAddBukuBantu();" id="form_memorial_kredit_addbukubantu_1"><i class="cus-table-add"></i> Add New</a></li>
													</ul>
												</div>
											</div>
										</div>
								<!-- End blok kredit -->
							</td>
							<td>
								<div class="control-group success">
									<input type="text" onkeyup="getTotal();" id="form_memorial_nilai_1" name="form_memorial_nilai[]" class="form_memorial_nilai" autocomplete="off"/>
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
								<input type="text" id="form_memorial_total" name="form_memorial_total" class="form_memorial_total" readonly autocomplete="off" value="0"/>
							</th>
						</tr>
					</table>	
                    <div class="row-fluid">
                        <div class="control-group success">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" name="form_memorial_tambah" class="btn btn-success"><i class="cus-table-save"></i>Simpan</button>
                                    <button type="button" name="form_memorial_batal" class="btn btn-success"><i class="cus-cancel"></i>Batal</button>
                                    <!--<button type="button" name="form_memorial_simpan" class="btn btn-success"><i class="cus-table-save"></i>Simpan</button>-->
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
            <table id="form_memorial_list"></table>
            <div id="form_memorial_page"></div>
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
		newCell.innerHTML = "<tr><td><div class=\"control-group success\"><input type=\"text\" class=\"form_memorial_keterangan\" id=\"form_memorial_keterangan_"+rowCount+"\" name=\"form_memorial_keterangan[]\" autocomplete=\"off\"></div></td>";
		
		var newCell = row.insertCell(1);
		newCell.innerHTML = "<td>"
								+"<input type=\"hidden\" name=\"form_memorial_debet_id[]\" class=\"form_memorial_debet_id\" id=\"form_memorial_debet_id_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_memorial_debet_kode[]\" class=\"form_memorial_debet_kode\" id=\"form_memorial_debet_kode_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_memorial_debet_kode_bukubantu[]\" class=\"form_memorial_debet_kode_bukubantu\" id=\"form_memorial_debet_kode_bukubantu_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_sbdaya_debet[]\" class=\"is_sbdaya_debet\" id=\"is_sbdaya_debet_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_rekanan_debet[]\" class=\"is_rekanan_debet\" id=\"is_rekanan_debet_"+rowCount+"\" />"								
								+"<input type=\"hidden\" name=\"is_proyek_debet[]\" class=\"is_proyek_debet\" id=\"is_proyek_debet_"+rowCount+"\" />"								
										+"<div class=\"control-group success\">"
											+"<div class=\"input-append\">"
												+"<input type=\"text\" onkeydown=\"thisrow="+rowCount+";getautocompletedebet();\" class=\"form_memorial_debet ui-autocomplete-input\" id=\"form_memorial_debet_"+rowCount+"\" name=\"form_memorial_debet[]\"  autocomplete=\"off\" role=\"textbox\" aria-autocomplete=\"list\" aria-haspopup=\"true\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" class=\"form_memorial_debet_listcoa\" onclick=\"showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir/'+$(this).attr('data-row'), 'getpicker_debetperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500);thisrow="+rowCount+";\" id=\"form_memorial_debet_listcoa_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
											+"<div id=\"form_memorial_debet_bukubantu_area_"+rowCount+"\" class=\"input-append\">"
												+"<input type=\"text\" class=\"form_memorial_debet_bukubantu\" id=\"form_memorial_debet_bukubantu_"+rowCount+"\" name=\"form_memorial_debet_bukubantu[]\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getDebetListBukuBantu();\" class=\"form_memorial_debet_listbukubantu\" id=\"form_memorial_debet_listbukubantu_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getDebetAddBukuBantu();\" class=\"form_memorial_debet_addbukubantu\" id=\"form_memorial_debet_addbukubantu_"+rowCount+"\"><i class=\"cus-table-add\"></i> Add New</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
										+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(2);
		newCell.innerHTML = "<td>"
								+"<input type=\"hidden\" name=\"form_memorial_kredit_id[]\" class=\"form_memorial_kredit_id\" id=\"form_memorial_kredit_id_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_memorial_kredit_kode[]\" class=\"form_memorial_kredit_kode\" id=\"form_memorial_kredit_kode_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"form_memorial_kredit_kode_bukubantu[]\" class=\"form_memorial_kredit_kode_bukubantu\" id=\"form_memorial_kredit_kode_bukubantu_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_sbdaya_kredit[]\" class=\"is_sbdaya_kredit\" id=\"is_sbdaya_kredit_"+rowCount+"\" />"
								+"<input type=\"hidden\" name=\"is_rekanan_kredit[]\" class=\"is_rekanan_kredit\" id=\"is_rekanan_kredit_"+rowCount+"\" />"								
								+"<input type=\"hidden\" name=\"is_proyek_kredit[]\" class=\"is_proyek_kredit\" id=\"is_proyek_kredit_"+rowCount+"\" />"								
										+"<div class=\"control-group success\">"
											+"<div class=\"input-append\">"
												+"<input type=\"text\" onkeydown=\"thisrow="+rowCount+";getautocompletekredit();\" class=\"form_memorial_kredit ui-autocomplete-input\" id=\"form_memorial_kredit_"+rowCount+"\" name=\"form_memorial_kredit[]\"  autocomplete=\"off\" role=\"textbox\" aria-autocomplete=\"list\" aria-haspopup=\"true\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" class=\"form_memorial_kredit_listcoa\" onclick=\"showUrlInDialog(root + 'mod_kdperkiraan/popup_kdperkir/'+$(this).attr('data-row'), 'getpicker_kreditperkiraan', 'List Kode Perkiraan', 'popup_kodeperkiraan', 600, 500);thisrow="+rowCount+";\" id=\"form_memorial_kredit_listcoa_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
											+"<div id=\"form_memorial_kredit_bukubantu_area_"+rowCount+"\" class=\"input-append\">"
												+"<input type=\"text\" class=\"form_memorial_kredit_bukubantu\" id=\"form_memorial_kredit_bukubantu_"+rowCount+"\" name=\"form_memorial_kredit_bukubantu[]\"/>"
												+"<div class=\"btn-group\">"
													+"<button data-toggle=\"dropdown\" class=\"btn dropdown-toggle\"><span class=\"caret\"></span></button>"
													+"<ul class=\"dropdown-menu\">"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getKreditListBukuBantu();\" class=\"form_memorial_kredit_listbukubantu\" id=\"form_memorial_kredit_listbukubantu_"+rowCount+"\"><i class=\"cus-table\"></i> List</a></li>"
														+"<li><a href=\"#\" onclick=\"thisrow="+rowCount+";getKreditAddBukuBantu();\" class=\"form_memorial_kredit_addbukubantu\" id=\"form_memorial_kredit_addbukubantu_"+rowCount+"\"><i class=\"cus-table-add\"></i> Add New</a></li>"
													+"</ul>"
												+"</div>"
											+"</div>"
										+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(3);
		newCell.innerHTML = "<td>"
								+"<div class=\"control-group success\">"
									+"<input type=\"text\" onkeyup=\"getTotal();\" id=\"form_memorial_nilai_"+rowCount+"\" name=\"form_memorial_nilai[]\" class=\"form_memorial_nilai\" autocomplete=\"off\"/>"
								+"</div>"
							+"</td>";
		
		var newCell = row.insertCell(4);
		newCell.innerHTML ="<td><button type=\"button\" onclick=\"addItem();\" class=\"btn btn-primary add_item\"><i class=\"icon-plus icon-white\"></I></button>"
							+"<button id=\"remove_item_"+rowCount+"\" type=\"button\" class=\"btn btn-danger\" onclick=\"removeItem(" + rowCount + ")\" ><i class=\"icon-remove icon-white\"></I></button><td></tr>"
		
		row.setAttribute("id", "item_" + rowCount);
		$('#form_memorial_debet_bukubantu_area_'+rowCount).hide();
		$('#form_memorial_kredit_bukubantu_area_'+rowCount).hide();
        $('input[id="form_memorial_nilai_'+rowCount+'"]').number(true, 2);
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
		getTotal();
	}
    function memorial_clear() {
        $('input[name="form_memorial_id"]').val("");
        $('input[name="form_voucherin_nobukti"]').val("");
        //$('input[name="form_memorial_tanggal"]').val("");
        $('input[class="form_memorial_debet_kode"]').val("");
        $('input[class="form_memorial_debet_kode_bukubantu"]').val("");
        $('input[class="is_sbdaya_debet"]').val("");
        $('input[class="is_rekanan_debet"]').val("");
        $('input[class="is_proyek_debet"]').val("");
        $('input[class="form_memorial_debet"]').val("");
        $('input[class="form_memorial_debet_bukubantu"]').val("");

        $('input[class="form_memorial_kredit_kode"]').val("");
        $('input[class="form_memorial_kredit_kode_bukubantu"]').val("");
        $('input[class="is_sbdaya_kredit"]').val("");
        $('input[class="is_rekanan_kredit"]').val("");
        $('input[class="is_proyek_kredit"]').val("");
        $('input[class="form_memorial_kredit"]').val("");
        $('input[class="form_memorial_kredit_bukubantu"]').val("");

        $('input[class="form_memorial_keterangan"]').val("");
        $('input[class="form_memorial_nilai"]').val("");
    }

    function memorial_getSession() {
        $.ajax({
            url: root + 'mod_vouchermem/getSession',
            dataType: 'json',
            success: function(json) {
                $('div.alert').remove();

                if (json['error']) {
                    //$('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                    //$('div.alert').fadeIn('slow');
                    memorial_clear();
                }

                if (json['success']) {
					//alert(json["record"]["detail"]["D"]["bukubantu"]);
                    //$('input[name="form_memorial_id"]').val(json["record"]["id"]);
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
					
					getTotal();
                    bukubantu_debet();
                    bukubantu_kredit();
                }
                scrollup();
                createAutoClosingAlert('div.alert', 3000);

            }
        });
    }

    function bukubantu_debet(row) {
        var is_rekanan_debet = $('input[id="is_rekanan_debet_'+row+'"]').val();
        var is_sbdaya_debet = $('input[id="is_sbdaya_debet_'+row+'"]').val();
        var is_proyek_debet = $('input[id="is_proyek_debet_'+row+'"]').val();
        var coa_debet = $('input[id="form_memorial_debet_id_'+row+'"]').val();

        if (is_rekanan_debet === 't') {
            $('input[id="form_memorial_debet_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').focus();
                    } else {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
					if (ui.item === null) {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else if (is_sbdaya_debet === 't') {
            $('input[id="form_memorial_debet_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa_debet,
                create: function(event, ui) {
                    $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').focus();
                    } else {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else if (is_proyek_debet === 't') {
            $('input[id="form_memorial_debet_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_proyek/autocomplete_proyek",
                create: function(event, ui) {
                    $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').focus();
                    } else {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
                        $('input[id="form_memorial_debet_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_debet_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else {
            $('input[id="form_memorial_debet_bukubantu_'+row+'"]').autocomplete("destroy");
        }
    }

    function bukubantu_kredit(row) {
        var is_rekanan_kredit = $('input[id="is_rekanan_kredit_'+row+'"]').val();
        var is_sbdaya_kredit = $('input[id="is_sbdaya_kredit_'+row+'"]').val();
        var is_proyek_kredit = $('input[id="is_proyek_kredit_'+row+'"]').val();
        var coa_kredit = $('input[id="form_memorial_kredit_id_'+row+'"]').val();

        if (is_rekanan_kredit === 't') {
            $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa_kredit,
                create: function(event, ui) {
                    $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_keterangan_'+row+'"]').focus();
                    } else {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else if (is_sbdaya_kredit === 't') {
            $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa_kredit,
                create: function(event, ui) {
                    $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_keterangan_'+row+'"]').focus();
                    } else {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else if (is_proyek_kredit === 't') {
            $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').autocomplete({
                minLength: 2,
                source: root + "mod_proyek/autocomplete_proyek",
                create: function(event, ui) {
                    $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                    $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    return false;
                },
                search: function(event, ui) {
                    $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                },
                select: function(event, ui) {
                    if (ui.item.id != 0) {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val(ui.item.id);
                        $('input[id="form_memorial_keterangan_'+row+'"]').focus();
                    } else {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    }
                    return false;
                },
                change: function(event, ui) {
                    if (ui.item === null) {
                        $('input[id="form_memorial_kredit_kode_bukubantu_'+row+'"]').val("");
                        $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').val("");
                    }
                }
            });
        } else {
            $('input[id="form_memorial_kredit_bukubantu_'+row+'"]').autocomplete("destroy");
        }
    }

    function getpicker_debetperkiraan(json) {
        $('input[id="form_memorial_debet_id_'+thisrow+'"]').val(json.dperkir_id);
        $('input[id="form_memorial_debet_'+thisrow+'"]').val(json.kdperkiraan);
        $('input[id="form_memorial_debet_kode_'+thisrow+'"]').val(json.kdperkiraan);
        $('.popup_kodeperkiraan').dialog('close');
        $('input[id="form_memorial_debet_bukubantu_'+thisrow+'"]').focus();
        
        if (json.flag_sumberdaya === 't') {
			$('input[id="is_sbdaya_debet_'+thisrow+'"]').val(json.flag_sumberdaya);			
			$('#form_memorial_debet_bukubantu_area_'+thisrow+'').show();
		} else {
			$('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
			$('#form_memorial_debet_bukubantu_area_'+thisrow+'').hide();
		}

		if (json.flag_nasabah === 't') {
			$('input[id="is_rekanan_debet_'+thisrow+'"]').val(json.flag_nasabah);
			$('#form_memorial_debet_bukubantu_area_'+thisrow+'').show();
		} else {
			$('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
			$('#form_memorial_debet_bukubantu_area_'+thisrow+'').hide();
		}
		
		if (json.proyek === 't') {
			$('input[id="is_proyek_debet_'+thisrow+'"]').val(json.proyek);
			$('#form_memorial_debet_bukubantu_area_'+thisrow+'').show();
		} else {
			$('input[id="is_proyek_debet_'+thisrow+'"]').val("");
			$('#form_memorial_debet_bukubantu_area_'+thisrow+'').hide();
		}
		
		if (json.flag_sumberdaya === null && json.flag_nasabah === null && json.proyek === null) {
			$('#form_memorial_debet_bukubantu_area_'+thisrow).hide();
			$('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
			$('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
			$('input[id="is_proyek_debet_'+thisrow+'"]').val("");
		}
    }

	function getpicker_kreditperkiraan(json) {
		$('input[id="form_memorial_kredit_id_'+thisrow+'"]').val(json.dperkir_id);
        $('input[id="form_memorial_kredit_'+thisrow+'"]').val(json.kdperkiraan);
        $('input[id="form_memorial_kredit_kode_'+thisrow+'"]').val(json.kdperkiraan);
        $('.popup_kodeperkiraan').dialog('close');
        $('input[id="form_memorial_kredit_bukubantu_'+thisrow+'"]').focus();
        
        if (json.flag_sumberdaya === 't') {
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val(json.flag_sumberdaya);			
			$('#form_memorial_kredit_bukubantu_area_'+thisrow).show();
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
		}

		if (json.flag_nasabah === 't') {
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val(json.flag_nasabah);
			$('#form_memorial_kredit_bukubantu_area_'+thisrow).show();
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
		}

		if (json.proyek === 't') {
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val(json.proyek);
			$('#form_memorial_kredit_bukubantu_area_'+thisrow).show();
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
		}
		
		if (json.flag_sumberdaya === null && json.flag_nasabah === null && json.proyek === null) {
			$('#form_memorial_kredit_bukubantu_area_'+thisrow).hide();
			$('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
			$('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
			$('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
		}
    }

    function getpicker_debetbukubantu(json) {
        $('input[id="form_memorial_debet_kode_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('input[id="form_memorial_debet_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
        $('input[id="form_memorial_keterangan_'+thisrow+'"]').focus();
    }

    function getpicker_kreditbukubantu(json) {
        $('input[id="form_memorial_kredit_kode_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('input[id="form_memorial_kredit_bukubantu_'+thisrow+'"]').val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
        $('input[id="form_memorial_keterangan_'+thisrow+'"]').focus();
    }

	function getautocompletedebet(){
		/* begin autocomplete debet_kodeperkiraan*/

        $('input[id="form_memorial_debet_'+thisrow+'"]').autocomplete({
            minLength: 2,
			matchContains:false, 
			minChars:1,  
			autoFill:false, 
			mustMatch:true, 
			cacheLength:20, 
			max:20,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
            create: function(event, ui) {
				$('input[id="form_memorial_debet_id_'+thisrow+'"]').val("");
                $('input[id="form_memorial_debet_kode_'+thisrow+'"]').val("");
                $('input[id="form_memorial_debet_'+thisrow+'"]').val("");
                return false;
            },
            search: function(event, ui) {
                $('input[id="form_memorial_debet_id_'+thisrow+'"]').val("");
                $('input[id="form_memorial_debet_kode_'+thisrow+'"]').val("");
            },
            select: function(event, ui) {
				//alert(ui.item.flag_nasabah);
                if (ui.item.id != 0) {
                    $('input[id="form_memorial_debet_id_'+thisrow+'"]').val(ui.item.id);
                    $('input[id="form_memorial_debet_kode_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_memorial_debet_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_memorial_debet_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_debet_bukubantu_'+thisrow+'"]').focus();

                    if (ui.item.flag_sumberdaya === 't') {
                        $('input[id="is_sbdaya_debet_'+thisrow+'"]').val(ui.item.flag_sumberdaya);
                        $('#form_memorial_debet_bukubantu_area_'+thisrow).show();
                        $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_debet_'+thisrow+'"]').val("");
                    }

                    if (ui.item.flag_nasabah === 't') {
                        $('input[id="is_rekanan_debet_'+thisrow+'"]').val(ui.item.flag_nasabah);
                        $('#form_memorial_debet_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_debet_'+thisrow+'"]').val("");
                    }

                    if (ui.item.proyek === 't') {
                        $('input[id="is_proyek_debet_'+thisrow+'"]').val(ui.item.proyek);
                        $('#form_memorial_debet_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
                        $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                    }
                    
                    if (ui.item.flag_sumberdaya === null && ui.item.flag_nasabah === null && ui.item.proyek === null){
						$('#form_memorial_debet_bukubantu_area_'+thisrow).hide();
                        $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                        $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_debet_'+thisrow+'"]').val("");
					}

                } else {
                    $('input[id="form_memorial_debet_id_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_debet_kode_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_debet_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_debet_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                    $('input[id="is_proyek_debet_'+thisrow+'"]').val("");
                }
                bukubantu_debet(thisrow);
                return false;
            },
            change: function(event, ui) {
                if (ui.item === null) {
					$('input[id="form_memorial_debet_id_'+thisrow+'"]').val("");
					$('input[id="form_memorial_debet_kode_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_debet_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_debet_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_debet_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_debet_'+thisrow+'"]').val("");
                    $('input[id="is_proyek_debet_'+thisrow+'"]').val("");
				}
            }
        });
	}
	
	function getDebetListBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_debet_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_debet_'+thisrow+'"]').val();
		var is_proyek = $('input[id="is_proyek_debet_'+thisrow+'"]').val();
		var coa_debet = $('input[id="form_memorial_debet_id_'+thisrow+'"]').val();
		//alert(is_sbdaya+' - '+is_rekanan);
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

	}
	
	function getDebetAddBukuBantu(){
		var is_sbdaya = $('input[id="is_sbdaya_debet_'+thisrow+'"]').val();
		var is_rekanan = $('input[id="is_rekanan_debet_'+thisrow+'"]').val();
		var is_proyek = $('input[id="is_proyek_debet_'+thisrow+'"]').val();

		if (is_rekanan === 't') {
			showUrlInDialog(root + "mod_rekanan/popup_add", "", "Tambah Buku Bantu Rekanan", "popup_debet_bukubantu", 600, 500);
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
	}
	
	function getautocompletekredit(){
		/* begin autocomplete kredit_kodeperkiraan*/

        $('input[id="form_memorial_kredit_'+thisrow+'"]').autocomplete({
            minLength: 2,
			matchContains:false, 
			minChars:1,  
			autoFill:false, 
			mustMatch:true, 
			cacheLength:20, 
			max:20,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
            create: function(event, ui) {
				$('input[id="form_memorial_kredit_id_'+thisrow+'"]').val("");
                $('input[id="form_memorial_kredit_kode_'+thisrow+'"]').val("");
                $('input[id="form_memorial_kredit_'+thisrow+'"]').val("");
                return false;
            },
            search: function(event, ui) {
                $('input[id="form_memorial_kredit_id_'+thisrow+'"]').val("");
                $('input[id="form_memorial_kredit_kode_'+thisrow+'"]').val("");
            },
            select: function(event, ui) {
				//alert(ui.item.flag_nasabah);
                if (ui.item.id != 0) {
                    $('input[id="form_memorial_kredit_id_'+thisrow+'"]').val(ui.item.id);
                    $('input[id="form_memorial_kredit_kode_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_memorial_kredit_'+thisrow+'"]').val(ui.item.kode);
                    $('input[id="form_memorial_kredit_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_kredit_bukubantu_'+thisrow+'"]').focus();

                    if (ui.item.flag_sumberdaya === 't') {
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val(ui.item.flag_sumberdaya);
                        $('#form_memorial_kredit_bukubantu_area_'+thisrow).show();
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
                    }

                    if (ui.item.flag_nasabah === 't') {
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val(ui.item.flag_nasabah);
                        $('#form_memorial_kredit_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
                    }

                    if (ui.item.proyek === 't') {
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val(ui.item.proyek);
                        $('#form_memorial_kredit_bukubantu_area_'+thisrow).show();
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                    }
                    
                    if (ui.item.flag_sumberdaya === null && ui.item.flag_nasabah === null && ui.item.proyek === null){
						$('#form_memorial_kredit_bukubantu_area_'+thisrow).hide();
                        $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                        $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
					}

                } else {
                    $('input[id="form_memorial_kredit_id_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_kredit_kode_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_kredit_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_kredit_bukubantu_'+thisrow+'"]').val("");
                    $('input[id="is_sbdaya_kredit_'+thisrow+'"]').val("");
                    $('input[id="is_rekanan_kredit_'+thisrow+'"]').val("");
                    $('input[id="is_proyek_kredit_'+thisrow+'"]').val("");
                }
                bukubantu_kredit(thisrow);
                return false;
            },
            change: function(event, ui) {
                if (ui.item === null) {
					$('input[id="form_memorial_kredit_id_'+thisrow+'"]').val("");
					$('input[id="form_memorial_kredit_kode_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_kredit_'+thisrow+'"]').val("");
                    $('input[id="form_memorial_kredit_bukubantu_'+thisrow+'"]').val("");
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
		var coa_kredit = $('input[id="form_memorial_kredit_id_'+thisrow+'"]').val();
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
	
	function getTotal(row){
		var sum = 0;
		
		var dataArray = $('input[class="form_memorial_nilai"]').serializeArray(),
			len = dataArray.length;

		for (i=0; i<len; i++) {
			sum += Number(dataArray[i].value);
		}
		$('input[id="form_memorial_total"]').val(sum);
	}
	
    function form_memorial_clear() {
        $('div.alert').remove();
        //$('input[name="form_memorial_id"]').val("");
        //$('input[name="form_memorial_tanggal"]').val("");
        $('input[name="form_memorial_nobukti"]').val("");
        
        $('input[name="form_memorial_debet_id_1"]').val("");
        $('input[name="form_memorial_debet_kode_1"]').val("");
        $('input[name="form_memorial_debet_kode_bukubantu_1"]').val("");
        $('input[name="is_sbdaya_debet_1"]').val("");
        $('input[name="is_rekanan_debet_1"]').val("");
        $('input[name="form_memorial_debet_1"]').val("");
        $('input[name="form_memorial_debet_bukubantu_1"]').val("");
        $('input[name="form_memorial_no_dokumen_1"]').val("");
        $('input[name="form_memorial_debet_bukubantu_1"]').autocomplete("destroy");
        
        $('input[id="form_memorial_kredit_id_1"]').val("");
        $('input[id="form_memorial_kredit_kode_1"]').val("");
        $('input[id="form_memorial_kredit_kode_bukubantu_1"]').val("");
        $('input[id="is_sbdaya_kredit_1"]').val("");
        $('input[id="is_rekanan_kredit_1"]').val("");
        $('input[id="form_memorial_kredit_1"]').val("");
        $('input[id="form_memorial_kredit_bukubantu_1"]').val("");
        $('input[id="form_memorial_keterangan_1"]').val("");
        $('input[id="form_memorial_nilai_1"]').val("");
        $('input[id="form_memorial_kredit_bukubantu_1"]').autocomplete("destroy");
        
		$('#form_memorial_debet_bukubantu_area_1').hide();
		$('#form_memorial_kredit_bukubantu_area_1').hide();
		$('input[id="form_memorial_total"]').val(0);
        while (rowCount > 1) {
			removeItem(rowCount);
		}
    }

    /* begin document ready */
    $(document).ready(function() {
		$('#form_memorial_debet_bukubantu_area_1').hide();
		$('#form_memorial_kredit_bukubantu_area_1').hide();
        $('input[id="form_memorial_nilai_1"]').number(true, 2);
        $('input[id="form_memorial_total"]').number(true, 2);
		form_memorial_clear();
        $('input[name="form_memorial_no_dokumen"]').focus();
		$('button[id="add_item"]').bind('click', function(){
			addItem();
		});
		memorial_getSession();

        $(".datepicker").datepicker({
            showOn: "button",
            buttonImage: root + "images/calendar.gif",
            dateFormat: 'yy-mm-dd',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true
        });

        $('button[name="form_memorial_tambah"]').bind('click', function() {
            var memorial_id = $('input[name="form_memorial_id"]').val();
            var memorial_nobukti = $('input[name="form_memorial_nobukti"]').val();
            var memorial_tanggal = $('input[name="form_memorial_tanggal"]').val();
            var memorial_jenis = $('input[name="form_memorial_jenis"]').val();
            var memorial_no_dokumen = $('input[name="form_memorial_no_dokumen"]').val();
            var memorial_debet_id = $('input[class="form_memorial_debet_id"]').serializeArray();
            var memorial_debet_kode = $('input[class="form_memorial_debet_kode"]').serializeArray();
            var memorial_debet_kode_bukubantu = $('input[class="form_memorial_debet_kode_bukubantu"]').serializeArray();
            var memorial_kredit_id = $('input[class="form_memorial_kredit_id"]').serializeArray();
            var memorial_kredit_kode = $('input[class="form_memorial_kredit_kode"]').serializeArray();
            var memorial_kredit_kode_bukubantu = $('input[class="form_memorial_kredit_kode_bukubantu"]').serializeArray();
            var memorial_keterangan = $('input[class="form_memorial_keterangan"]').serializeArray();
            var memorial_nilai = $('input[class="form_memorial_nilai"]').serializeArray();
            var is_rekanan_debet = $('input[class="is_rekanan_debet"]').serializeArray();
            var is_sbdaya_debet = $('input[class="is_sbdaya_debet"]').serializeArray();
            var is_proyek_debet = $('input[class="is_proyek_debet"]').serializeArray();
            var is_rekanan_kredit = $('input[class="is_rekanan_kredit"]').serializeArray();
            var is_sbdaya_kredit = $('input[class="is_sbdaya_kredit"]').serializeArray();
            var is_proyek_kredit = $('input[class="is_proyek_kredit"]').serializeArray();
            $.ajax({
                url: root + 'mod_vouchermem/add2session',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    id: memorial_id,
                    nobukti: memorial_nobukti,
                    tanggal: memorial_tanggal,
                    jenis: memorial_jenis,
                    no_dokumen: memorial_no_dokumen,
                    debet_id: memorial_debet_id,
                    debet_kode: memorial_debet_kode,
                    debet_bukubantu: memorial_debet_kode_bukubantu,
                    kredit_id: memorial_kredit_id,
                    kredit_kode: memorial_kredit_kode,
                    kredit_bukubantu: memorial_kredit_kode_bukubantu,
                    keterangan: memorial_keterangan,
                    nilai: memorial_nilai,
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
                        $('.form_memorial').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {

                        form_memorial_clear();
                        $('.form_memorial').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    //scrollup();
                    //createAutoClosingAlert('div.alert', 3000);
                }
            });
        });

		$('button[name="form_memorial_simpan"]').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();
            $.ajax({
                url: root + "mod_vouchermem/addJurnal",
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
                        form_memorial_clear();
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                }
            });
        });
		
		$('button[name="form_memorial_batal"]').click(function() {
            $.ajax({
                url: root + "mod_vouchermem/cleanTransaksi"
            });
            form_memorial_clear();
        });
		
        $('#form_memorial_delete').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();


            $.ajax({
                url: root + "mod_vouchermem/deletejurnal",
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
