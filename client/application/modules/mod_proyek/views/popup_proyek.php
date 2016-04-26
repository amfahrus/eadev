<div class="content popup_proyek">
    <?= form_open(); ?>
    <div class="row-fluid form-horizontal">
        <div class="span12">
            <div class="row-fluid">
                <div class="control-group info">
                    <label class="control-label" for="popup_kodeproyek">Kode Proyek</label>
                    <div class="controls">
                        <input type="text" name="popup_kodeproyek" id="popup_kodeproyek"/> 
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="control-group info">
                    <label class="control-label" for="popup_namaproyek">Nama Proyek</label>
                    <div class="controls">
                        <input type="text" name="popup_namaproyek" id="popup_namaproyek"/> 
                    </div>
                </div>
            </div>
            <div class="row-fluid">
                <div class="control-group info">
                    <div class="controls">
                        <button type="button" name="popup_cariproyek" class="btn"><i class="cus-zoom"></i> Cari</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <table id="list2"></table>
            <div id="pager2"></div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
    function popupproyek_accept(id) {
        $.ajax({
            url: root + 'mod_proyek/get_proyek',
            type:'POST',
            dataType:'json',
            data:{
                item: id
            },
            beforeSend: function() {
                $(this).attr('disabled',true);
            },
            complete: function() {
                $(this).attr('disabled',true);
            },
            success: function(json) {
                parent.pkcaller(json);
            }
        });
    }
    
    function popup_proyek_refresh_grid() {
        jQuery("#list2").jqGrid('setGridParam',{
            url: root + "mod_proyek/popup_json", 
            page:1
        }).trigger("reloadGrid");
    }
    
    
    $(document).ready(function() {
        $('button[name="popup_cariproyek"]').bind('click', function() {
            var kode_proyek = $('input[name="popup_kodeproyek"]').val();
            var nama_proyek = $('input[name="popup_namaproyek"]').val();
            var search = "_search=true&kode_proyek="+kode_proyek+"&nama_proyek="+nama_proyek;
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_proyek/popup_json?'+ search,
                page:1
            }).trigger("reloadGrid");
        });
        
        jQuery("#list2").jqGrid({
            url: root + 'mod_proyek/popup_json/', 
            mtype : "post",
            datatype: "json", 
            colNames:['#', '#', 'Kode Proyek', 'Nama Proyek'], 
            colModel:[ 
                {name:'act',index:'act', width:80, align:"center", sortable:false}, 
                {name:'id_proyek',index:'id_rekanan',hidden:true}, 
                {name:'kode_proyek',index:'kode_proyek',width:150}, 
                {name:'nama_proyek',index:'nama_proyek', width:300}, 
            ], 
            rowNum:10, 
            width: 530, 
            height: 250, 
            rownumbers: true, 
            rownumWidth: 40,
            rowList:[10,20,30], 
            pager: '#pager2', 
            viewrecords: true, 
            shrinkToFit: false,
            gridComplete: function(){ 
                var ids = jQuery("#list2").jqGrid('getDataIDs'); 
                for(var i=0;i < ids.length;i++){ 
                    var cl = ids[i]; 
                    ce = "<a href=\"#\" onclick=\"popupproyek_accept("+ids[i]+");\" class=\"link_edit tooltips\" data-placement=\"right\" data-toggle=\"tooltip\" data-original-title=\"Pilih Data Buku Bantu\"><img  src=\"<?= base_url(); ?>media/application_add.png\" /></a>"; 
                    jQuery("#list2").jqGrid('setRowData',ids[i],{act:ce}); 
                } 
                $(".tooltips").tooltip();
            }
        }); 
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
    });
</script>
