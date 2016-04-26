<div class="content form_perioda_bulan_list">
    <?= form_open(); ?>
    <input type="hidden" name="yearperiod_id" value="<?= $id; ?>" />
    <div class="row-fluid">
        <div class="row-fluid">
            <div class="span12">
                <div style="margin: 0;" class="btn-toolbar">
                    <div class="btn-group">
                        <button type="button" class="btn" id="form_period_bulan_add"><i class="icon-book"></i></button>
                        <button type="button" class="btn" id="form_period_bulan_edit"><i class="icon-edit"></i></button>
                        <button type="button" class="btn" id="form_period_bulan_del"><i class="icon-trash"></i></button>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn" id="form_period_bulan_lock"><i class="icon-lock"></i></button>
                        <button type="button" class="btn" id="form_period_bulan_unlock"><i class="icon-lock"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <br/>
        <div class="row-fluid">
            <div class="span12">
                <table id="list4"></table>
                <div id="pager4"></div>
            </div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
    function form_period_bulan_refresh() {
        var id = $('input[name="yearperiod_id"]').val();
        jQuery("#list4").jqGrid('setGridParam',{
            url: root + 'mod_perioda/perioda_json?id=' + id,
            page:1
        }).trigger("reloadGrid");
    }
    
    function get_update_period_bulan(json) {
        $('.form_period_bulan_edit').dialog( "close" );
        $('div.alert').remove();
        if (json['error']) {
			$('.form_perioda_bulan_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
			$('div.alert').fadeIn('slow');
		}
	
		if (json['success']) {
			$('.form_perioda_bulan_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
			$('div.alert').fadeIn('slow');
			form_period_bulan_refresh();
		}
		createAutoClosingAlert('div.alert', 3000);
    }
    
    $('button[id="form_period_bulan_add"]').click(function() {
        var id = $('input[name="yearperiod_id"]').val();
        showUrlInDialog(root + "mod_perioda/perioda_bulan_add?id="+id, "form_period_bulan_refresh", "Periode Bulan Tambah", "form_period_bulan_add", 500, 400);
    });
    
    $('button[id="form_period_bulan_edit"]').click(function() {
        var gr = jQuery("#list4").jqGrid('getGridParam','selrow');
        if( gr != null ) {
            showUrlInDialog(root + "mod_perioda/perioda_bulan_edit?id="+gr, "get_update_period_bulan", "Periode Bulan Edit", "form_period_bulan_edit", 500, 400);
        }
    });
    
    $('button[id="form_period_bulan_del"]').click(function() {
        var id = jQuery("#list4").jqGrid('getGridParam','selrow');
        if( id != null ) {
            $.ajax({
                url : root + "mod_perioda/perioda_bulan_delete",
                dataType: 'json',
                type: 'post',
                data: { 
                    id:id
                },
                beforeSend: function() {
                    $(this).attr('disabled',true);
                },	
                complete: function() {
                    $(this).attr('disabled',false);
                },			
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.form_perioda_bulan_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                
                    if (json['success']) {
                        $('.form_perioda_bulan_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        form_period_bulan_refresh();
                    }
                }
            });
        }
    });
    
    $('button[id="form_period_bulan_lock"]').click(function() {
        var id = jQuery("#list4").jqGrid('getGridParam','selrow');
        if( id != null ) {
            $.ajax({
                url: root + "mod_perioda/perioda_bulan_lock",
                dataType: "json",
                type:"post",
                data:{
                    id:id
                },
                beforeSend: function() {
                    $(this).attr('disabled',true);
                },
                complete:function() {
                    $(this).attr('disabled',true);
                },
                success:function(json){
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.form_perioda_bulan_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        $('.form_perioda_bulan_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        form_period_bulan_refresh();
                    }
                }
            });
        }
    });
    
    $('button[id="form_period_bulan_unlock"]').click(function() {
        var id = jQuery("#list4").jqGrid('getGridParam','selrow');
        if( id != null ) {
            $.ajax({
                url: root + "mod_perioda/perioda_bulan_unlock",
                dataType: "json",
                type:"post",
                data:{
                    id:id
                },
                beforeSend: function() {
                    $(this).attr('disabled',true);
                },
                complete:function() {
                    $(this).attr('disabled',true);
                },
                success:function(json){
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.form_perioda_bulan_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        $('.form_perioda_bulan_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        form_period_bulan_refresh();
                    }
                }
            });
        }
    });
    
    $(document).ready(function() {
        var id = $('input[name="yearperiod_id"]').val();
        jQuery("#list4").jqGrid({
            url: root + 'mod_perioda/perioda_json?id=' + id,
            mtype: "post",
            datatype: "json",
            colNames: [
                'period_id',
                'id_proyek',
                'nama_kategoriproyek',
                'proyek',
                'Periode Name',
                'Start',
                'End',
                'IsClosed'
            ],
            colModel: [{
                    name: 'period_id',
                    index: 'period_id',
                    hidden: true,
                    width: 100
                }, {
                    name: 'id_proyek',
                    index: 'id_proyek',
                    hidden: true,
                    width: 100
                }, {
                    name: 'nama_kategoriproyek',
                    index: 'nama_kategoriproyek',
                    hidden: true,
                    width: 100
                }, {
                    name: 'proyek',
                    index: 'proyek',
                    hidden: true,
                    width: 200
                }, {
                    name: 'period_name',
                    index: 'period_name',
                    width: 200
                }, {
                    name: 'DATE(period_start)',
                    index: 'DATE(period_start)',
                    width: 100
                }, {
                    name: 'DATE(period_end)',
                    index: 'DATE(period_end)',
                    width: 100
                }, {
                    name: 'period_closed',
                    index: 'period_closed',
                    width: 100
                }
            ],
            width: 550,
            height: 300,
            rowNum: 12,
            rownumbers: true,
            rownumWidth: 40,
            rowList: [12, 24, 36, 48],
            pager: '#pager4',
            viewrecords: true,
            sortorder: "desc",
            shrinkToFit: false
        });

        jQuery("#list4").jqGrid('navGrid', '#pager4', {
            edit: false,
            add: false,
            del: false,
            search: false
        });
    });
</script>
