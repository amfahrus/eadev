<div class="content form_importdbf">
    <?= form_open(); ?>
    <input type="hidden" name="id" value="<?= $id; ?>" />
    <div class="row-fluid">
        <div class="span12">
            <table id="importedit_list"></table>
            <div id="importedit_pager"></div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
	var thisrow;
    function createAutoClosingAlert(selector, delay) {
        var alert = $(selector).alert();
        window.setTimeout(function() {
            alert.alert('close')
        }, delay);
    }
	function getpicker_rekanan(json) {
		//jQuery("#importedit_list").jqGrid('setCell', thisrow, 'kdnasabah', json.kode_rekanan);
		jQuery("#" + thisrow + "_kdnasabah", "#importedit_list").val(json.kode_rekanan);
        $('.popup_rekanan').dialog('close');
		jQuery("#" + thisrow + "_kdnasabah", "#importedit_list").focus();
		
    }
    function getpicker_sbdaya(json) {
		jQuery("#" + thisrow + "_kdsbdaya", "#importedit_list").val(json.kode_sbdaya);
        $('.popup_rekanan').dialog('close');
		jQuery("#" + thisrow + "_kdsbdaya", "#importedit_list").focus();
		
    }
    $(document).ready(function() {

        $('button[name="form_importdbf_save"]').bind('click', function() {
            $.ajax({
                url: root + 'mod_importdbf/importdbf_save',
                dataType: 'json',
                type: 'post',
                data: $("form").serialize(),
                beforeSend: function() {
                    $(this).attr('disabled', true);
                },
                complete: function() {
                    $(this).attr('disabled', false);
                },
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.form_importdbf').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }

                    if (json['success']) {
                        $('.form_importdbf').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    scrollup();
                    createAutoClosingAlert('div.alert', 3000);
                }
            });
        });

        var prevVal;
        var prevValId;
        var prevId;
        var lebar = $('.inbody').height() - 70;
        var panjang = $('.content').width() - 20;
        var id = $('input[name="id"]').val();
        jQuery("#importedit_list").jqGrid({
            url: root + 'mod_importdbf/importdbf_datajson/' + id,
            mtype: "post",
            datatype: "json",
            colNames: ["1", "<div id='jq_checkbox_head_added'><div>", "2", "3", "4", "5", "6", "COA GL", "CODE COA", "ID COA", "COA", "Rekanan", "SB Daya", "Debet", "Kredit"],
            colModel: [
                {name: 'id', index: 'id', width: 30, hidden: true, sortable: false},
                {name: 'check', index: 'check', width: 20, sortable: false, align: "center"},
                {name: 'txno', index: 'txno', width: 50, align: "center", sortable: false},
                {name: 'date', index: 'date', width: 100, formatter: 'date', sortable: false, formatoptions: {srcformat: "Y-m-d", newformat: "d M Y"}, align: "center"},
                {name: 'txcode', index: 'txcode', width: 100, hidden: true, sortable: false},
                {name: 'itno', index: 'itno', width: 50, align: "center", sortable: false},
                {name: 'desc', index: 'desc', width: 300, sortable: false},
                {name: 'glcode', index: 'glcode', width: 100, align: "center", sortable: false},
                {name: 'dperkir_id', index: 'dperkir_id', hidden: true, width: 100, align: "center", sortable: false},
                {name: 'kode', index: 'kode', hidden: true, width: 100, align: "center", sortable: false},
                {name: 'kdperkiraan', index: 'kdperkiraan', width: 100, align: "center", sortable: false, editable: true},
                {name: 'kdnasabah', index: 'kdnasabah', width: 100, align: "left", sortable: false, editable: true},
                {name: 'kdsbdaya', index: 'kdsbdaya', width: 100, align: "left", sortable: false, editable: true},
                {name: 'debet', index: 'debet', width: 150, align: "right", formatter: 'currency', sortable: false},
                {name: 'kredit', index: 'kredit', width: 150, align: "right", formatter: 'currency', sortable: false}
            ],
            width: panjang,
            height: lebar,
            rownumbers: true,
            rowNum: 20,
            rowList: [20, 50, 100],
            rownumWidth: 40,
            multiselect: false,
            pager: '#importedit_pager',
            viewrecords: true,
            shrinkToFit: false,
            cellEdit: true,
            cellsubmit: 'clientArray',
            afterEditCell: function(id, name, val, iRow, iCol) {
                var id = jQuery("#importedit_list").jqGrid('getCell', id, 1);
                var coa = jQuery("#importedit_list").jqGrid('getCell', id, 9);

                if (name == 'kdperkiraan') {
                    jQuery("#" + iRow + "_kdperkiraan", "#importedit_list").autocomplete({
                        minLength: 2,
                        source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
                        select: function(event, ui) {
                            if (ui.item.id != 0) {
                                $(this).val(ui.item.kode);
                                jQuery("#importedit_list").jqGrid('setCell', id, 'dperkir_id', ui.item.id);
                                jQuery("#importedit_list").jqGrid('setCell', id, 'kode', ui.item.kode);
                                //$("#" + iRow + "_dperkir_id").val(ui.item.id);
                            } else {
                                $(this).val("");
                                jQuery("#importedit_list").jqGrid('setCell', id, 'dperkir_id', '');
                                jQuery("#importedit_list").jqGrid('setCell', id, 'kode', '');
                                //$("#" + iRow + "_dperkir_id").val("");
                            }
                            return false;
                        },
                        change: function(event, ui) {
							//alert(val);
							prevVal = val;
							prevId = id;
							prevValId = coa;
							if(ui.item === null){
                                $(this).val("");
							}
                        },
                        focus: function(event, ui) {
                            if (ui.item.id != 0) {
                                $(this).val(ui.item.kode);
                                jQuery("#importedit_list").jqGrid('setCell', id, 'dperkir_id', ui.item.id);
                                jQuery("#importedit_list").jqGrid('setCell', id, 'kode', ui.item.kode);
                                //$("#" + iRow + "_dperkir_id").val(ui.item.id);
                            } else {
                                $(this).val("");
                                jQuery("#importedit_list").jqGrid('setCell', id, 'dperkir_id', '');
                                jQuery("#importedit_list").jqGrid('setCell', id, 'kode', '');
                                //$("#" + iRow + "_dperkir_id").val("");
                            }
                            return false;
                        }
                    });
                }

                if (name == 'kdnasabah') {
                    jQuery("#" + iRow + "_kdnasabah", "#importedit_list").autocomplete({
                        minLength: 2,
                        source: root + "mod_rekanan/autocomplete_rekanan?coa=" + coa,
                        open: function(event, ui) {

                            var downmenu = '';
                            downmenu += '<li><a class="btn" onClick="thisrow=' + iRow + ';showUrlInDialog(\'' + root + 'mod_rekanan/popup_rekanan?coa='+coa+'\', \'getpicker_rekanan\', \'List Buku Bantu Rekanan\', \'popup_rekanan\', 600, 500);" href="#">List</a>';
                            downmenu += '<a class="btn" onClick="thisrow=' + iRow + ';showUrlInDialog(\'' + root + 'mod_rekanan/popup_add\', \'getpicker_rekanan\', \'Add Buku Bantu Rekanan\', \'popup_rekanan\', 600, 500);" href="#">Add</a></li>';
                            $('.ui-autocomplete').append(downmenu);
                        },
                        select: function(event, ui) {
                            if (ui.item.id != 0) {
                                $(this).val(ui.item.id);
                            } else {
                                $(this).val("");
                            }
                            return false;
                        },
                        change: function(event, ui) {
							prevVal = val;
							prevId = id;
							if(ui.item === null){
                                $(this).val("");
							}
                        },
                        focus: function(event, ui) {
                            if (ui.item.id != 0) {
                                $(this).val(ui.item.id);
                            } else {
                                $(this).val("");
                            }
                            return false;
                        }
                    });
                }

                if (name == 'kdsbdaya') {
                    jQuery("#" + iRow + "_kdsbdaya", "#importedit_list").autocomplete({
                        minLength: 2,
                        source: root + "mod_sbdaya/autocomplete_sbdaya?coa=" + coa,
                        open: function(event, ui) {

                            var downmenu = '';
                            downmenu += '<li><a class="btn" onClick="thisrow=' + iRow + ';showUrlInDialog(\'' + root + 'mod_sbdaya/popup_sbdaya\', \'getpicker_sbdaya\', \'List Buku Bantu SB Daya\', \'popup_rekanan\', 600, 500);" href="#">List</a>';
                            downmenu += '<a class="btn" onClick="thisrow=' + iRow + ';showUrlInDialog(\'' + root + 'mod_sbdaya/popup_add\', \'getpicker_sbdaya\', \'Add Buku Bantu SB daya\', \'popup_rekanan\', 600, 500);" href="#">Add</a></li>';
                            $('.ui-autocomplete').append(downmenu);
                        },
                        select: function(event, ui) {
                            if (ui.item.id != 0) {
                                $(this).val(ui.item.id);
                            } else {
                                $(this).val("");
                            }
                            return false;
                        },
                        change: function(event, ui) {
							prevVal = val;
							prevId = id;
							if(ui.item === null){
                                $(this).val("");
							}
                        },
                        focus: function(event, ui) {
                            if (ui.item.id != 0) {
                                $(this).val(ui.item.id);
                            } else {
                                $(this).val("");
                            }
                            return false;
                        }
                    });
                }
            },
            afterSaveCell: function(rowid, name, val, iRow, iCol) {
                var id = jQuery("#importedit_list").jqGrid('getCell', rowid, 1);
                var dk = jQuery("#importedit_list").jqGrid('getCell', rowid, 2);
				var dperkir_id = jQuery("#importedit_list").jqGrid('getCell',rowid,'dperkir_id');
				var kode = jQuery("#importedit_list").jqGrid('getCell',rowid,'kode');
				if (name == 'kdperkiraan') {
					if (kode == '') {
						jQuery("#importedit_list").jqGrid('setCell', rowid, 'kdperkiraan', '-');
						jQuery("#importedit_list").jqGrid('setCell', rowid, 'dperkir_id', '');
					} else {
						jQuery("#importedit_list").jqGrid('setCell', rowid, 'kdperkiraan', kode);
						jQuery("#importedit_list").jqGrid('setCell', rowid, 'dperkir_id', dperkir_id);
						$.ajax({
							url: root + "mod_importdbf/importdbf_edit",
							dataType: 'json',
							type: 'post',
							data: {
								id: id,
								name: name,
								val_id: dperkir_id,
								val: val
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
									$('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
								}
								if (json['success']) {
									return false;
								}
								createAutoClosingAlert('div.alert', 2000);
							}
						});
					}
				}

                if (name == 'kdnasabah') {
					$.ajax({
						url: root + "mod_importdbf/importdbf_edit",
						dataType: 'json',
						type: 'post',
						data: {
							id: id,
							name: name,
							val: val
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
								$('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
							}
							if (json['success']) {
								return false;
							}
							createAutoClosingAlert('div.alert', 2000);
						}
					});
                }

                if (name == 'kdsbdaya') {
                    $.ajax({
                        url: root + "mod_importdbf/importdbf_edit",
                        dataType: 'json',
                        type: 'post',
                        data: {
                            id: id,
                            name: name,
                            val: val
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
                                $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                            }
                            if (json['success']) {
                                return false;
                            }
                            createAutoClosingAlert('div.alert', 2000);
                        }
                    });
                }
            }
        });
        jQuery("#importedit_list").jqGrid('navGrid', '#importedit_pager', {edit: false, add: false, del: false, search: false});

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
    });
</script>
