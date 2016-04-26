<div class="content">
    <?= form_open('mod_hubrk/to_excel',array('id' => 'filter')); ?>
     <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>List Hubungan RK</span></h4></div>
                <div class="basic box_content">
                   
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Unit</label>
                                <div class="span8 text">
										<div id="konsolidasi_opsi"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Tahun</label>
                                <div class="span8 text">
									<select name="periode_konsolidasi" id="yearpicker" class="span8"></select>	
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Periode</label>
                                <div class="span8 text">
										<?= form_dropdown('interval', $op_interval, set_value('interval'), 'class="span8"'); ?>	
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
    $(document).ready(function() {
        getDataKonsolidasi();
        for (i = new Date().getFullYear(); i > 2012; i--)
		{
			$('#yearpicker').append($('<option />').val(i).html(i));
		}
        var lebar = $('.inbody').height() - 370;
        var panjang = $('.content').width() - 20;
        jQuery("#list2").jqGrid({
            url: root + 'mod_hubrk/hubrk_json', 
            treeGrid: true,
			treeGridModel: 'adjacency',
			ExpandColumn: 'uraian',
			ExpandColClick: true,
			mtype : "post",
            datatype: "json", 
            colNames:['Uraian', 'Hubungan RK', 'Kantor Pusat'], 
            colModel:[ 
                {name:'uraian',index:'uraian', width:100},
                {name:'rk',index:'rk', width:50, align:'right'},
                {name:'kp',index:'kp', width:50, align:'right'},
                ],
            rowNum:10, 
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
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});

        $('#mysubmit').click(function () {
            var str = $("form").serialize();
            var search = str;
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_hubrk/hubrk_json/?'+ search,
                page:1
            }).trigger("reloadGrid");
        });
        
        $("#reset_search").click(function() {
            $('.cols_cari').val("");
            $('.ops_cari').val("");
            $('.text').val("");
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_hubrk/hubrk_json',
                page:1
            }).trigger("reloadGrid");
        });
        $("#form_neraca_excel").click(function(){
			filter.submit();
		});
    });
	
	function getDataKonsolidasi() {
        $.ajax({
            url: root + 'mod_hubrk/getDataKonsolidasi',
            type: 'post',
            success: function(data) {
                $('#konsolidasi_opsi').html(data);
            }
        });	
    }
    

</script>
