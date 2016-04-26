<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/msdropdown/css/msdropdown/dd.css" />
<script src="<?= base_url(); ?>assets/msdropdown/js/msdropdown/jquery.dd.min.js"></script>

<div class="content">
    <?= form_open('mod_labarugi/to_excel',array('id' => 'filter')); ?>
     <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>Laporan Labarugi</span></h4></div>
                <div class="basic box_content">
                    <?php 
						if ($this->session->userdata("ba_is_proyek") == 'f') {
					?>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Konsolidasi</label>
                                <div class="span8 text">
										<div id="konsolidasi_opsi"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                    <div class="row-fluid konsolidasi">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Tahun</label>
                                <div class="span8 text">
									<select name="periode_konsolidasi" id="yearpicker" class="span8"></select>	
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid konsolidasi">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Periode</label>
                                <div class="span8 text">
										<?= form_dropdown('interval', $op_interval, set_value('interval'), 'class="span8"'); ?>	
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid nonkonsolidasi">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Pilih Periode</label>
                                <div class="span8 text">
										<div id="periodeyear">
											<?= form_dropdown('periode_year', $op_yearperiode, set_value('periode_year'), 'class="span8"'); ?>	
										</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid nonkonsolidasi">
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
<script type="text/javascript" src="<?= base_url(); ?>js/searching.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/jqGrid-4.4.1/src/grid.treegrid.js"></script>
<script type="text/javascript">
	function getDataProyek(id, id_proyek) {
        $.ajax({
            url: root + 'mod_labarugi/getDataProyek',
            type: 'post',
            data: { id: id, id_proyek:id_proyek},
            success: function(data) {
                $('select[name="kode_proyek"]').html(data);
            }
        });	
    };
    
    function daysInMonth(month, year) {
		return new Date(year, month, 0).getDate();
	};
	
	function getDataPeriode(id) {
        $.ajax({
            url: root + 'mod_labarugi/getDataPeriode',
            //dataType: 'json',
            type: 'post',
            data: { id: id},
            success: function(data) {
                $('#periode').html(data);
                //$("#periode").msDropDown({byJson:{data:json, name:'periode'}}).data("dd");
            }
        });	
    };
    
        	
	$(function() {
			var id_periodeyear = $('select[name="periode_year"]').val();
			getDataPeriode(id_periodeyear);
	});
    
    $(document).ready(function() {
		getDataKonsolidasi();
        $('.konsolidasi').hide();
		$('.nonkonsolidasi').show();
		/*$(".datepicker").datepicker({
            showOn: "button",
            buttonImage: root + "images/calendar.gif",
            dateFormat : 'yy-mm-dd',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true
        });*/
        for (i = new Date().getFullYear(); i > 2012; i--)
		{
			$('#yearpicker').append($('<option />').val(i).html(i));
		}
        var lebar = $('.inbody').height() - 370;
        var panjang = $('.content').width() - 20;
        jQuery("#list2").jqGrid({
            url: root + 'mod_labarugi/labarugi_json', 
			mtype : "post",
            datatype: "json", 
            colNames:['Grup', 'Uraian', 'Nilai'], 
            colModel:[ 
                {name:'nmlama',index:'nmlama', width:20, hidden:true},
                {name:'uraian',index:'uraian', width:100},
                {name:'total_ini',index:'total_ini', width:50, align:'right'}
            ],
            width:panjang,
			height:lebar,
            treeGrid: true,
            treeGridModel : 'adjacency',
            ExpandColumn : 'uraian',
            ExpandColClick: true,
            treeIcons: {leaf:'ui-icon-document'},
            pager: '#pager2',
            shrinkToFit: true
        }); 
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
        
        $('#mysubmit').click(function () {
            var str = $("form").serialize();
            var search = str;
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_labarugi/labarugi_json?'+ search,
                page:1
            }).trigger("reloadGrid");
        });
        
        $("#reset_search").click(function() {
            $('.cols_cari').val("");
            $('.ops_cari').val("");
            $('.text').val("");
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_labarugi/labarugi_json',
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
		//$("select[name=\"periode_year\"]").msDropDown();
		
		
		$('select[name="periode_year"]').change(function() {
			var id =  $('select[name="periode_year"]').val();
			getDataPeriode(id);
		});
    
        $("#form_labarugi_excel").click(function(){
			filter.submit();
		});
    });
    
    function opsikonsolidasi() {
		var val =  $('select[name="konsolidasi"]').val();
        if(val > 0){
			$('.konsolidasi').show();
			$('.nonkonsolidasi').hide();
		} else {
			$('.konsolidasi').hide();
			$('.nonkonsolidasi').show();
		}
    };
    
    function getDataKonsolidasi() {
        $.ajax({
            url: root + 'mod_labarugi/getDataKonsolidasi',
            type: 'post',
            success: function(data) {
                $('#konsolidasi_opsi').html(data);
            }
        });	
    }
</script>
