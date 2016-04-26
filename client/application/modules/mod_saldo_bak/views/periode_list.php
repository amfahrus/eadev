<div class="content">
    <?= form_open(); ?>
     <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>Saldo Perkiraan</span></h4></div>
                <div class="basic box_content">
                    
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
                    
                    <div class="form-actions">
                        <button type="button" id="mysubmit" class="btn btn-info"><i class="icon-ok-sign icon-white"></i>Submit</button>
                        <button type="button" class="btn"><i class="icon-remove"></i>Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <table id="list2"></table>
    <div id="pager2"></div>
    
    <?= form_close(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var lebar = $('.inbody').height() - 100;
        var panjang = $('.content').width() - 20;
        jQuery("#list2").jqGrid({
            url: root + 'mod_saldo/perkiraan_json', 
            mtype : "post",
            datatype: "json", 
            colNames:['Dperkir ID','Perkiraan', 'Debit', 'Kredit'/*'Saldo Awal', 'Debit', 'Kredit', 'Saldo Akhir'*/], 
            colModel:[ 
                {name:'id',index:'id', hidden:true, width:250, editable:false}, 
                {name:'perkiraan',index:'perkiraan', width:350, editable:false}, 
                {name:'debit',index:'debit', width:150, formatter: 'currency', editable:true}, 
                {name:'kredit',index:'kredit', width:150, formatter: 'currency', editable:true}
                /*{name:'beginning',index:'beginning', width:350, formatter: 'currency', editable:true}, 
                {name:'debit',index:'debit', width:150, formatter: 'currency', editable:true}, 
                {name:'kredit',index:'kredit', width:150, formatter: 'currency', editable:true},
                {name:'ending',index:'ending', width:150, formatter: 'currency', editable:true}*/
            ], 
            rowNum:10, 
            width: panjang, 
            height: lebar, 
            rownumbers: true, 
            rownumWidth: 40,
            rowList:[10,20,30], 
            pager: '#pager2', 
            multiselect: false,
            viewrecords: true, 
            shrinkToFit: false,
			cellEdit: true,
            cellsubmit: 'clientArray',
            afterSaveCell: function(rowid, name, val, iRow, iCol) {
                var dperkir_id = jQuery("#list2").jqGrid('getCell', rowid, 1);
                var period_key = $('select[name="periode"]').val();
				/*if (name == 'beginning') {
					$.ajax({
						url: root + "mod_saldo/edit",
						dataType: 'json',
						type: 'post',
						data: {
							dperkir_id: dperkir_id,
							period_key: period_key,
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
				}*/
				
                if (name == 'debit') {
					$.ajax({
						url: root + "mod_saldo/edit",
						dataType: 'json',
						type: 'post',
						data: {
							dperkir_id: dperkir_id,
							period_key: period_key,
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

                if (name == 'kredit') {
                    $.ajax({
                        url: root + "mod_saldo/edit",
						dataType: 'json',
						type: 'post',
						data: {
							dperkir_id: dperkir_id,
							period_key: period_key,
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
                /*
                if (name == 'ending') {
                    $.ajax({
                        url: root + "mod_saldo/edit",
						dataType: 'json',
						type: 'post',
						data: {
							dperkir_id: dperkir_id,
							period_key: period_key,
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
                }*/
            }
        }); 
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:true,add:false,del:false,search:false});
        
    });
    
    $("#mysubmit").click(function() {
		var period_key = $('select[name="periode"]').val();
		$.ajax({
			url: root + 'mod_saldo/perkiraan_json',
			type: 'post',
			data: { period_key: period_key},
			success: function(json) {
				jQuery("#list2").jqGrid('setGridParam',{
					datatype: "jsonstring",
					datastr: json
				}).trigger("reloadGrid");
			}
		});
    });
    
    function createAutoClosingAlert(selector, delay) {
        var alert = $(selector).alert();
        window.setTimeout(function() {
            alert.alert('close')
        }, delay);
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
    
</script>
