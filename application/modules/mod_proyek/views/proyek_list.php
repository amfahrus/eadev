<div class="content">
    <?= form_open(); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Master Proyek</h4></div>
                <div class="basic box_content form_search" ></div>
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
<?= $searchform; ?>
<script type="text/javascript">
    $(document).ready(function() {
        var lebar = $('.inbody').height() - 200;
        var panjang = $('.content').width() - 20;
        jQuery("#list2").jqGrid({
            url: root + 'mod_proyek/proyek_json', 
            mtype : "post",
            datatype: "json", 
            colNames:['id_proyek','Group Proyek', 'Kategori Proyek', 'Jenis Proyek','Kode Proyek', 'Nama Proyek',''], 
            colModel:[ 
                {name:'id_proyek',index:'id_proyek',hidden:true, width:100}, 
                {name:'nama_subunit',index:'nama_subunit', width:150}, 
                {name:'nama_kategoriproyek',index:'nama_kategoriproyek', width:150}, 
                {name:'jenisproyek_name',index:'jenisproyek_name', width:150}, 
                {name:'kode_proyek',index:'kode_proyek', width:150}, 
                {name:'nama_proyek',index:'nama_proyek', width:250},
                {name:'aksi',width:30, align: "center"}], 
            rowNum:10, 
            width: panjang, 
            height: lebar, 
            rownumbers: true, 
            rownumWidth: 40,
            rowList:[10,20,30], 
            pager: '#pager2', 
            multiselect: true,
            viewrecords: true, 
            sortorder: "desc",
            shrinkToFit: false
            //caption:"List Users" 
        }); 
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
        
        $('#button_search').click(function () {
            var str = $("form").serialize();
            var search = "_search=true&"+ str;
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_proyek/proyek_json?'+ search,
                page:1
            }).trigger("reloadGrid");
        });
        
        $("#reset_search").click(function() {
            $('.cols_cari').val("");
            $('.ops_cari').val("");
            $('.text').val("");
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_proyek/proyek_json',
                page:1
            }).trigger("reloadGrid");
        });
        
        $("#form_proyek_delete").click(function() {
            var id = jQuery("#list2").jqGrid('getGridParam','selarrrow'); 
            if(id){
                $.ajax({
                    url: root + 'mod_proyek/proyek_delete',
                    type: 'post',
                    data: { id: id},
                    success: function() {
                        jQuery("#list2").jqGrid().trigger('reloadGrid');
                    }
                });	
            } else {
                alert("Please Select Row to delete!");
            }
        });
    });
</script>