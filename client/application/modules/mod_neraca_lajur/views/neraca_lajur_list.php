<div class="content">
    <?= form_open('mod_neraca_lajur/to_excel',array('id' => 'filter')); ?>
     <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>Laporan Neraca Lajur</span></h4></div>
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
										<?= form_dropdown('periode_year', $op_yearperiode, set_value('periode_year'), 'class="span8"'); ?>	
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
<script type="text/javascript" src="<?= base_url(); ?>js/jqGrid-4.5.2/src/grid.treegrid.js"></script>
<script type="text/javascript">
	function getDataProyek(id, id_proyek) {
        $.ajax({
            url: root + 'mod_neraca_lajur/getDataProyek',
            type: 'post',
            data: { id: id, id_proyek:id_proyek},
            success: function(data) {
                $('select[name="kode_proyek"]').html(data);
            }
        });	
    }
    $(document).ready(function() {
        getDataKonsolidasi();
        $('.konsolidasi').hide();
		$('.nonkonsolidasi').show();
        for (i = new Date().getFullYear(); i > 2012; i--)
		{
			$('#yearpicker').append($('<option />').val(i).html(i));
		}
        var lebar = $('.inbody').height() - 370;
        var panjang = $('.content').width() - 20;
        jQuery("#list2").jqGrid({
            url: root + 'mod_neraca_lajur/neraca_lajur_json', 
			mtype : "post",
            datatype: "json", 
            colNames:['kode', 'Uraian', 'Debet', 'Kredit', 'Debet', 'Kredit', 'Debet', 'Kredit', 'Debet', 'Kredit', 'Debet', 'Kredit'], 
            colModel:[ 
                {name:'kode',index:'kode', width:80},
                {name:'uraian',index:'uraian', width:140},
                {name:'debet_lalu',index:'debet_lalu', width:100,align:'right'},
                {name:'kredit_lalu',index:'kredit_lalu', width:100,align:'right'},
                {name:'debet_skrg',index:'debet_skrg', width:100,align:'right'},
                {name:'kredit_skrg',index:'kredit_skrg', width:100,align:'right'},
                {name:'debet_akhir',index:'debet_akhir', width:100,align:'right'},
                {name:'kredit_akhir',index:'kredit_akhir', width:100,align:'right'},
                {name:'debet_lr',index:'debet_lr', width:100,align:'right'},
                {name:'kredit_lr',index:'kredit_lr', width:100,align:'right'},
                {name:'debet_nrc',index:'debet_nrc', width:100,align:'right'},
                {name:'kredit_nrc',index:'kredit_nrc', width:100,align:'right'}
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
        jQuery("#list2").jqGrid('setGroupHeaders', {
		  useColSpanStyle: true, 
		  groupHeaders:[
			{startColumnName: 'debet_lalu', numberOfColumns: 2, titleText: 'SALDO PERIODE LALU'},
			{startColumnName: 'debet_skrg', numberOfColumns: 2, titleText: 'TRANSAKSI PERIODE INI'},
			{startColumnName: 'debet_akhir', numberOfColumns: 2, titleText: 'SALDO AKHIR PERIODE INI'},
			{startColumnName: 'debet_lr', numberOfColumns: 2, titleText: 'LABA/RUGI PERIODE INI'},
			{startColumnName: 'debet_nrc', numberOfColumns: 2, titleText: 'NERACA SALDO PERIODE INI'}
		  ]
		});
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});

        $('#mysubmit').click(function () {
            var str = $("form").serialize();
            var search = str;
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_neraca_lajur/neraca_lajur_json/?'+ search,
                page:1
            }).trigger("reloadGrid");
        });
        
        $("#reset_search").click(function() {
            $('.cols_cari').val("");
            $('.ops_cari').val("");
            $('.text').val("");
            jQuery("#list2").jqGrid('setGridParam',{
                url: root + 'mod_neraca_lajur/neraca_lajur_json',
                page:1
            }).trigger("reloadGrid");
        });
        $("#form_neraca_lajur_excel").click(function(){
			filter.submit();
		});
    });
    function getDataPeriode(id) {
        $.ajax({
            url: root + 'mod_neraca_lajur/getDataPeriode',
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
    
    function tes(){
		alert('yes');
	}

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
            url: root + 'mod_neraca_lajur/getDataKonsolidasi',
            type: 'post',
            success: function(data) {
                $('#konsolidasi_opsi').html(data);
            }
        });	
    }
</script>
