<div class="content">
    <?= form_open('mod_cashflow/to_excel',array('id' => 'filter')); ?>
     <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>Laporan Cashflow</span></h4></div>
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
<script type="text/javascript" src="<?= base_url(); ?>js/searching.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jqGrid-4.4.1/src/grid.treegrid.js"></script>
<script type="text/javascript">
	function getDataProyek(id, id_proyek) {
        $.ajax({
            url: root + 'mod_cashflow/getDataProyek',
            type: 'post',
            data: { id: id, id_proyek:id_proyek},
            success: function(data) {
                $('select[name="kode_proyek"]').html(data);
            }
        });	
    }
    $(document).ready(function() {
        var lebar = $('.inbody').height() - 370;
        var panjang = $('.content').width() - 20;
        jQuery("#list2").jqGrid({
            url: root + 'mod_cashflow/cashflow_json', 
            treeGrid: true,
			treeGridModel: 'adjacency',
			ExpandColumn: 'uraian',
			ExpandColClick: true,
			mtype : "post",
            datatype: "json", 
            colNames:['Nomor', 'Uraian', 'RBP/AKP', 'Ri s/d Des Tahun Lalu', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Total', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember', 'Total', 'Total Tahun', 'Total Awal Proyek s/d Tahun'], 
            colModel:[ 
                {name:'nomor',index:'nomor', width:20},
                {name:'uraian',index:'uraian', width:250},
                {name:'akp',index:'akp', width:50, align:'right'},
                {name:'ri_sd_lalu',index:'ri_sd_lalu', width:50, align:'right'},
                {name:'ri_1',index:'ri_1', width:50, align:'right'},
                {name:'ri_2',index:'ri_2', width:50, align:'right'},
                {name:'ri_3',index:'ri_3', width:50, align:'right'},
                {name:'ri_4',index:'ri_4', width:50, align:'right'},
                {name:'ri_5',index:'ri_5', width:50, align:'right'},
                {name:'ri_6',index:'ri_6', width:50, align:'right'},
                {name:'ri_s1',index:'ri_s1', width:50, align:'right'},
                {name:'ri_7',index:'ri_7', width:50, align:'right'},
                {name:'ri_8',index:'ri_8', width:50, align:'right'},
                {name:'ri_9',index:'ri_9', width:50, align:'right'},
                {name:'ri_10',index:'ri_10', width:50, align:'right'},
                {name:'ri_11',index:'ri_11', width:50, align:'right'},
                {name:'ri_12',index:'ri_12', width:50, align:'right'},
                {name:'ri_s2',index:'ri_s2', width:50, align:'right'},
                {name:'ri_total',index:'ri_total', width:50, align:'right'},
                {name:'ri_grandtotal',index:'ri_grandtotal', width:50, align:'right'},
                ],
            rowNum:100, 
            width: panjang, 
            height: lebar, 
            rownumbers: false, 
            rownumWidth: 40,
            rowList:[1,10,20,30], 
            pager: '#pager2', 
            multiselect: true,
			viewrecords: true,
			gridview: true,
			treeIcons: {leaf:'ui-icon-document'},
            sortorder: "desc"
            //caption:"Assets" 
        }); 
        jQuery("#list2").jqGrid('setGroupHeaders', {
		  useColSpanStyle: true, 
		  groupHeaders:[
			{startColumnName: 'ri_1', 
			numberOfColumns: 14, 
			titleText: '<table style="width:100%;border-spacing:0px;">' +
                        '<tr><td id="h0" colspan="2">REALISASI TAHUN</td></tr>' +
                        '<tr>' +
                            '<td id="h1">Semester I</td>' +
                            '<td id="h2">Semester II</td>' +
                        '</tr>' +
                        '</table>'
            }
		  ]
		});
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});

        $('#mysubmit').click(function () {
            var str = $("form").serialize();
            var search = str;
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_cashflow/cashflow_json/?'+ search,
                page:1
            }).trigger("reloadGrid");
        });
        
        $("#reset_search").click(function() {
            $('.cols_cari').val("");
            $('.ops_cari').val("");
            $('.text').val("");
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_cashflow/cashflow_json',
                page:1
            }).trigger("reloadGrid");
        });
        $("#form_cashflow_excel").click(function(){
			filter.submit();
		});
    });
    function getDataPeriode(id) {
        $.ajax({
            url: root + 'mod_cashflow/getDataPeriode',
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
    
    function tes(){
		alert('yes');
	}

</script>
