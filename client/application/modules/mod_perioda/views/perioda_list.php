<div class="content form_year_list">
    <?= form_open(); ?>
    <table id="list2"></table>
    <div id="pager2"></div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
    function refreshGrid() {
        jQuery("#list2").jqGrid('setGridParam',{
            url: root + 'mod_perioda/periodayears_json', 
            page:1
        }).trigger("reloadGrid");
    }
    
    function get_update_period(json) {
        $('.form_period_edit').dialog( "close" );
        $('div.alert').remove();
		if (json['error']) {
			$('.form_year_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
			$('div.alert').fadeIn('slow');
		}
	
		if (json['success']) {
			$('.form_year_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
			$('div.alert').fadeIn('slow');
			refreshGrid();
		}
		createAutoClosingAlert('div.alert', 3000);
    }
    
    $("#form_period_year_new").click(function() {
        showUrlInDialog(root + "mod_perioda/perioda_add", "refreshGrid", "Periode Tahun", "form_period_add");
    }); 
        
    $("#form_period_year_edit").click(function() {
        var gr = jQuery("#list2").jqGrid('getGridParam','selrow');
        if( gr != null ) {
            showUrlInDialog(root + "mod_perioda/perioda_year_edit?id="+gr, "get_update_period", "Periode Tahun Edit", "form_period_edit");
        }
    });
    
    $("#form_period_year_delete").click(function() {
        var id = jQuery("#list2").jqGrid('getGridParam','selrow');
        if( id != null ) {
            $.ajax({
                url: root + 'mod_perioda/perioda_year_delete',
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
                        $('.form_year_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                
                    if (json['success']) {
                        $('.form_year_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        refreshGrid();
                    }
                }
            });
        }
    });
    
    $("#form_period_year_lock").click(function() {
        var id = jQuery("#list2").jqGrid('getGridParam','selrow');
        if( id != null ) {
            $.ajax({
                url: root + 'mod_perioda/perioda_year_lock',
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
                        $('.form_year_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                
                    if (json['success']) {
                        $('.form_year_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        refreshGrid();
                    }
                }
            });
        }
    });
    
    $("#form_period_year_unlock").click(function() {
        var id = jQuery("#list2").jqGrid('getGridParam','selrow');
        if( id != null ) {
            $.ajax({
                url: root + 'mod_perioda/perioda_year_unlock',
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
                        $('.form_year_list').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                
                    if (json['success']) {
                        $('.form_year_list').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        refreshGrid();
                    }
                }
            });
        }
    });
        
    $(document).ready(function() {
        var lebar = $('.inbody').height() - 100;
        var panjang = $('.content').width() - 20;

        jQuery("#list2").jqGrid({
            url: root + mod +'/periodayears_json', 
            mtype : "post",
            datatype: "json", 
            colNames:['','yearperiod_id','yearperiod_key','id_proyek','Kategori','Proyek', 'Perioda Mulai', 'Perioda Akhir', 'Status'], 
            colModel:[ 
                {name:'act',index:'act', width:40,sortable:false, align:"center"},
                {name:'yearperiod_id',index:'yearperiod_id',hidden:true,width:100}, 
                {name:'yearperiod_key',index:'yearperiod_key',hidden:true,width:100}, 
                {name:'id_proyek',index:'id_proyek',hidden:true,width:100}, 
                {name:'nama_kategoriproyek',index:'nama_kategoriproyek',width:100}, 
                {name:'proyek',index:'proyek', width:200}, 
                {name:'DATE(yearperiod_start)',index:'DATE(yearperiod_start)', width:100}, 
                {name:'DATE(yearperiod_end)',index:'DATE(yearperiod_end)', width:100}, 
                {name:'yearperiod_closed',index:'yearperiod_closed', width:50}], 
            rowNum:10, 
            width: panjang, 
            height: lebar, 
            rownumbers: true, 
            rownumWidth: 40,
            rowList:[10,20,30,40,50], 
            pager: '#pager2', 
            viewrecords: true, 
            sortorder: "desc",
            shrinkToFit: false,
            gridComplete: function(){ 
                var ids = jQuery("#list2").jqGrid('getDataIDs'); 
                for(var i=0;i < ids.length;i++){ 
                    var cl = ids[i]; 
                    ce = '<a href="#" onclick="showUrlInDialog(\'' + root + 'mod_perioda/perioda_bulan?id='+ ids[i] +'\', \'susumurninasional\',\'Accounting Period\',\'form_period_bulan\', 620, 550);" class="link_edit"><img  src="<?= base_url(); ?>media/edit.png" /></a>'; 
                    jQuery("#list2").jqGrid('setRowData',ids[i],{act:ce}); 
                } 
            }
        }); 
        
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
        
        $(".item_delete").click(function() {
            refreshGrid();
        });
    });
</script>
