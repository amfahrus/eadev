<div class="content">
    <?= form_open('mod_listjurnalnot/to_excel', array('id' => 'xsearch')); ?>
	<div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> List Jurnal Not Approved</h4></div>
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
<script type="text/javascript" src="<?= base_url(); ?>js/searching.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jqGrid-4.4.1/src/grid.treegrid.js"></script>
<script type="text/javascript">
    function getDataProyek(id, id_proyek) {
        $.ajax({
            url: root + 'mod_listjurnalnot/getDataProyek',
            type: 'post',
            data: { id: id, id_proyek:id_proyek},
            success: function(data) {
                $('select[name="kode_proyek"]').html(data);
            }
        });	
    }
    $(document).ready(function() {
        var lebar = $('.inbody').height() - 200;
        var panjang = $('.content').width() - 20;
        jQuery("#list2").jqGrid({
            url: root + 'mod_listjurnalnot/listjurnal_json', 
            mtype : "post",
            datatype: "json", 
            colNames:['No','Tanggal', 'Nomor Bukti', 'Kode Proyek', 'COA', 'Rekanan', 'Keterangan', 'Debit', 'Kredit'], 
            colModel:[ 
                {name:'nomor',index:'id_tempjurnal', sortable: false, width:25, align:"center"}, 
                {name:'tanggal',index:'tanggal', sortable: false, width:100, formatter:'date', formatoptions:{srcformat:"Y-m-d",newformat:"d M Y"}, align:"center"},  
                {name:'nobukti',index:'nobukti', sortable: false, width:100},
                {name:'kode_proyek',index:'kode_proyek', sortable: false, width:100}, 
                {name:'coa',index:'coa', sortable: false, width:100},
                {name:'rekanan',index:'rekanan', sortable: false, width:100},
                {name:'keterangan',index:'keterangan', sortable: false, width:100},
                {name:'debit',index:'debit', sortable: false, width:100, align:"right"},
                {name:'kredit',index:'kredit', sortable: false, width:100, align:"right"}],
            rowNum:10, 
            width: panjang, 
            height: lebar, 
            rownumWidth: 40,
            rowList:[10,20,30], 
            pager: '#pager2'
            //caption:"List Users" 
        }); 
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
        
        $('#button_search').click(function () {
            var str = $("form").serialize();
            var search = "_search=true&"+ str;
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_listjurnalnot/listjurnal_json?'+ search,
                page:1
            }).trigger("reloadGrid");
        });
        
        $("#reset_search").click(function() {
            $('.cols_cari').val("");
            $('.ops_cari').val("");
            $('.text').val("");
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_listjurnalnot/listjurnal_json',
                page:1
            }).trigger("reloadGrid");
        });
        var id_unitkerja = $('select[name="unitkerja"]').val();
        var id_proyek = <?php
    $id_proyek = set_value('kode_proyek');
    if (!empty($id_proyek)) {
        echo $id_proyek;
    } else {
        echo "0";
    }
    ?>;
        
                                getDataProyek(id_unitkerja, id_proyek);
                                $('select[name="unitkerja"]').change(function() {
                                    var id_unitkerja = $('select[name="unitkerja"]').val();
                                    getDataProyek(id_unitkerja, id_proyek);
                                });
        
                            });
</script>
