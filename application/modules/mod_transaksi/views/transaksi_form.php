<style>
    .ui-autocomplete {
        max-height: 100px;
        overflow-y: auto;
        /* prevent horizontal scrollbar */
        overflow-x: hidden;
    }
    * html .ui-autocomplete {
        height: 100px;
    }
</style>
<script type="text/javascript">
//    function showUrlInDialog(url){
//        var tag = $('<div></div>');
//        $.ajax({
//            url: url,
//            success: function(data) {
//                tag.html(data).dialog({
//                    autoOpen: false,
//                    height: 500,
//                    width: 500,
//                    modal: true,
//                    title:"Modal",
//                    close:function() {
//                        location.reload();
//                    }
//                }).dialog('open');
//                tag.html(data).addClass("ui-dialog-extend");
//            }
//        });
//    }
    
    function getSession(id) {
        $.ajax({
            url: root + 'mod_transaksi/getSessionId',
            dataType: 'json',
            type: 'post',
            data: { id: id},
            success: function(json) {
                $('div.alert').remove();
                    
                if (json['error']) {
                    $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+json['error']+'</div>');
                    $('div.alert').fadeIn('slow');
                    clean();
                }
                                        
                if (json['success']) {
                    if(json['record']["debet_rekanan"] != "") {
                        $('button[name="slide_debet"]').click();
                    } else {
                        $('button[name="slide_kredit"]').click();
                    }
                    
                    $('input[name="id"]').val(json['record']["id"]);
                    //$('input[name="tanggal"]').val(json['record']["tanggal"]);
                    $('input[name="debet"]').val(json['record']["debet"]);
                    $('input[name="label_debet"]').val(json['record']["label_debet"]);
                    $('input[name="debet_kode_alat"]').val(json['record']["debet_kode_alat"]);
                    $('input[name="debet_rekanan"]').val(json['record']["debet_rekanan"]);
                    $('input[name="debet_sumberdaya"]').val(json['record']["debet_sumberdaya"]);
                    $('input[name="debet_volume"]').val(json['record']["debet_volume"]);
                    $('input[name="debet_work_item"]').val(json['record']["debet_work_item"]);
                    $('input[name="kredit"]').val(json['record']["kredit"]);
                    $('input[name="label_kredit"]').val(json['record']["label_kredit"]);
                    $('input[name="kredit_kodealat"]').val(json['record']["kredit_kodealat"]);
                    $('input[name="kredit_rekanan"]').val(json['record']["kredit_rekanan"]);
                    $('input[name="kredit_sumberdaya"]').val(json['record']["kredit_sumberdaya"]);
                    $('input[name="kredit_volume"]').val(json['record']["kredit_volume"]);
                    $('input[name="kredit_workitem"]').val(json['record']["kredit_workitem"]);
                    $('input[name="keterangan"]').val(json['record']["keterangan"]);
                    $('input[name="nilai"]').val(json['record']["nilai"]);
                }
            }
        });
    }
    
    function clean() {
        $('input[name="id"]').val("");
        //$('input[name="tanggal"]').val();
        $('input[name="debet"]').val("");
        $('input[name="label_debet"]').val("");
        $('input[name="debet_kode_alat"]').val("");
        $('input[name="debet_rekanan"]').val("");
        $('input[name="debet_sumberdaya"]').val("");
        $('input[name="debet_volume"]').val("");
        $('input[name="debet_work_item"]').val("");
        $('input[name="kredit"]').val("");
        $('input[name="label_kredit"]').val("");
        $('input[name="kredit_kodealat"]').val("");
        $('input[name="kredit_rekanan"]').val("");
        $('input[name="kredit_sumberdaya"]').val("");
        $('input[name="kredit_volume"]').val("");
        $('input[name="kredit_workitem"]').val("");
        $('input[name="keterangan"]').val("");
        $('input[name="nilai"]').val("");
  
        $('div#jq_checkbox_head_added').removeClass('selected');
        $('div#jq_checkbox_head_added').prepend('<div class="checkicon_add"><image src="' + root + 'uncheckbox.gif" /></div>');
        $('.jq_checkbox_added').each(function() {
            this.checked = false;
        });
    }
    
    function getDataProyek(id) {
        $.ajax({
            url: root + 'mod_transaksi/getDataProyek',
            type: 'post',
            data: { id: id},
            success: function(data) {
                $('select[name="subunit_proyek"]').html(data);
            }
        });	
    }
    
    $(document).ready(function(){
        $('button[name="save"]').click(function() {
            var tanggal = $('input[name="tanggal"]').val();
            var tipe_transaksi = $('select[name="tipe_transaksi"]').val();
            var kode_proyek = $('select[name="subunit_proyek"]').val();
            
            $.ajax({
                url: root + 'mod_transaksi/addJurnal',
                dataType: 'json',
                type: 'post',
                data: { 
                    tanggal:tanggal, 
                    tipe_transaksi:tipe_transaksi, 
                    kode_proyek:kode_proyek
                },
                beforeSend: function() {
                    $('button[name="save"]').attr('disabled',true);
                },	
                complete: function() {
                    $('button[name="save"]').attr('disabled',false);
                },			
                success: function(json) {
                    $('div.alert').remove();
                    
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                                        
                    if (json['success']) {
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button> '+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        jQuery("#list2").jqGrid('setGridParam',{
                            url: root + mod + '/sess2json', 
                            page:1
                        }).trigger("reloadGrid");
                    }
                }
            });	
        });
    
        $('button[name="cancel"]').click(function(){
            clean();
        });
    
        $('.item_delete').click(function() {
            var id = $('input[class="jq_checkbox_added"]:checked').map(function() {
                return $(this).val();
            }).get();
            
            $.ajax({
                url: 'mod_transaksi/deletejurnal',
                dataType: 'json',
                type: 'post',
                data: {id:id},
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                                        
                    if (json['success']) {
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        jQuery("#list2").jqGrid('setGridParam',{
                            url: root + mod + '/sess2json', 
                            page:1
                        }).trigger("reloadGrid");
                        clean();
                    }
                }
            });	
        });
    
        $("#add").click(function() {
            $.ajax({
                url: 'mod_transaksi/add',
                dataType: 'json',
                type: 'post',
                data: $("form").serialize(),
                beforeSend: function() {
                    $('button[name="add"]').attr('disabled',true);
                },	
                complete: function() {
                    $('button[name="add"]').attr('disabled',false);
                },	
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        jQuery("#list2").jqGrid('setGridParam',{
                            url: root + mod + '/sess2json', 
                            page:1
                        }).trigger("reloadGrid");
                        clean();
                    }
                }
            });	
        });

        $( ".datepicker" ).datepicker({
            showOn: "button",
            buttonImage: root + "images/calendar.gif",
            dateFormat : 'yy-mm-dd',
            buttonImageOnly: true
        });
        
        var id_unitkerja = $('select[name="unitkerja"]').val();
        getDataProyek(id_unitkerja);

        $('select[name="unitkerja"]').change(function() {
            var id =  $('select[name="unitkerja"]').val();
            getDataProyek(id);
        });
        
        $('div#form_debet').hide();
        $('div#form_kredit').hide();
        
        $('button[name="slide_debet"]').click(function() {
            $this = $(this);
            $target =  $('button[name="slide_kredit"]');
            if(!$this.hasClass('active')){
                $this.addClass('active');
                $target.removeClass('active');
                
                $("#slide_debet_icon").removeClass("icon-arrow-down").addClass("icon-arrow-up");
                $("#slide_kredit_icon").removeClass("icon-arrow-up").addClass("icon-arrow-down");
                $('div#form_debet').slideDown(700);
                $('div#form_kredit').slideUp(700);
            }
            return false;
        });
        
        $('button[name="slide_kredit"]').click(function() {
            $this = $(this);
            $target =  $('button[name="slide_debet"]');
            if(!$this.hasClass('active')){
                $this.addClass('active');
                $target.removeClass('active');
                
                $("#slide_kredit_icon").removeClass("icon-arrow-down").addClass("icon-arrow-up");
                $("#slide_debet_icon").removeClass("icon-arrow-up").addClass("icon-arrow-down");
                $('div#form_debet').slideUp(700);
                $('div#form_kredit').slideDown(700);
            }
            return false;
        });
        
        $('input[name="debet"]').autocomplete({
            minLength: 2,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
            focus: function( event, ui ) {
                $('input[name="kode_debet"]').val( ui.item.id );
                $('input[name="debet"]').val( ui.item.id );
                $('input[name="label_debet"]').val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $('input[name="kode_debet"]').val( ui.item.id );
                $('input[name="debet"]').val( ui.item.id );
                $('input[name="label_debet"]').val( ui.item.label );
                return false;
            }
        });
        
        $('input[name="kredit"]').autocomplete({
            minLength: 2,
            source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
            focus: function( event, ui ) {
                $('input[name="kode_kredit"]').val( ui.item.id );
                $('input[name="kredit"]').val( ui.item.id );
                $('input[name="label_kredit"]').val( ui.item.label );
                return false;
            },
            select: function( event, ui ) {
                $('input[name="kode_kredit"]').val( ui.item.id );
                $('input[name="kredit"]').val( ui.item.id );
                $('input[name="label_kredit"]').val( ui.item.label );
                return false;
            }
        });
        
        $('input[name="debet_rekanan"]').autocomplete({
            minLength: 2,
            source: root + "mod_rekanan/autocomplete_rekanan",
            open: function( event, ui ) {
                $('.ui-autocomplete').append('<li><a class="btn btn-success" onClick="showUrlInDialog(\''+ root +'mod_rekanan/popup_add\');" href="#"><i class="icon-search icon-white"></i>Add New</a></li>');
            },
            focus: function( event, ui ) {
                $('input[name="kode_debet_rekanan"]').val( ui.item.id );
                $('input[name="debet_rekanan"]').val( ui.item.id );
                return false;
            },
            select: function( event, ui ) {
                $('input[name="kode_debet_rekanan"]').val( ui.item.id );
                $('input[name="debet_rekanan"]').val( ui.item.id );
                return false;
            }
        });
        
        $('input[name="kredit_rekanan"]').autocomplete({
            minLength: 2,
            source: root + "mod_rekanan/autocomplete_rekanan",
            open: function( event, ui ) {
                $('.ui-autocomplete').append('<li><a class="btn btn-success" onClick="showUrlInDialog(\''+ root +'mod_rekanan/popup_add\');" href="#"><i class="icon-search icon-white"></i>Add New</a></li>');
            },
            focus: function( event, ui ) {
                $('input[name="kode_debet_rekanan"]').val( ui.item.id );
                $('input[name="debet_rekanan"]').val( ui.item.id );
                return false;
            },
            select: function( event, ui ) {
                $('input[name="kode_debet_rekanan"]').val( ui.item.id );
                $('input[name="debet_rekanan"]').val( ui.item.id );
                return false;
            }
        });
        
        var lebar = $('.inbody').height() - 320;
        var panjang = $('.content').width() - 20;
        
        jQuery("#list2").jqGrid({
            url: root + mod + '/sess2json', 
            mtype : "post",
            datatype: "json",
            //colNames:['#','','','','','','','','','COA','Rekanan', 'Sumber Daya','Volume','Debet','Kredit','Uraian'], 
            colNames:['#','',"<div id='jq_checkbox_head_added'><div>",'','','','','','','COA','Rekanan', 'Debet','Kredit','Uraian'], 
            colModel:[
                {name:'act',index:'act', width:10,sortable:false, align:"center"},
                {name:'id',key : true, index:'id', hidden:true,width:10},
                {name:'check', index:'check',width:10, sortable: false, align:"center"},
                {name:'kode_perkiraan', index:'kode_perkiraan',hidden:true,width:50},
                {name:'nama_perkiraan', index:'nama_perkiraan',hidden:true,width:50},
                {name:'kode_nasabah',index:'kode_nasabah',hidden:true,width:50},
                {name:'nama_nasabah',index:'nama_nasabah',hidden:true,width:50},
                {name:'kode_sbdaya',index:'kode_sbdaya',hidden:true,width:50},
                {name:'nama_sbdaya',index:'nama_sbdaya', hidden:true,width:50},
                {name:'perkiraan',index:'perkiraan', width:100},
                {name:'nasabah',index:'nasabah', width:100},
                //{name:'sbdaya',index:'sbdaya', width:100},
                //{name:'volume',index:'volume', width:30, align:"right"},
                {name:'debet',index:'debet', width:50, align:"right",formatter:'currency',formatoptions:{thousandsSeparator:","}},
                {name:'kredit',index:'kredit', width:50, align:"right",formatter:'currency',formatoptions:{thousandsSeparator:","}},
                {name:'uraian',index:'uraian', width:100}
            ],
            scroll: true,
            width: panjang,
            height: lebar,
            rownumbers: true,
            rowNum:1000,
            rownumWidth: 40,
            multiselect: false,
            pager: '#pager2',
            viewrecords: true,
            footerrow : true, 
            userDataOnFooter : true, 
            altRows : true,
            gridComplete: function(){ 
                var ids = jQuery("#list2").jqGrid('getDataIDs'); 
                for(var i=0;i < ids.length;i++){ 
                    var cl = ids[i]; 
                    ce = "<a href=\"#\" onclick=\"getSession("+ids[i]+");\" class=\"link_edit\"><img  src=\"<?= base_url(); ?>media/edit.png\" /></a>"; 
                    jQuery("#list2").jqGrid('setRowData',ids[i],{act:ce}); 
                } 
            }
            //caption:"List Proyek"
        }); 
        jQuery("#list2").jqGrid('navGrid','#pager2',{edit:false,add:false,del:false,search:false});
        
        $('div#jq_checkbox_head_added').prepend('<div class="checkicon_add"><image src="' + root + 'uncheckbox.gif" /></div>');
        $('div#jq_checkbox_head_added').removeClass('selected');
        
        $('div#jq_checkbox_head_added').click(function() {
            $('.checkicon_add').remove();
            
            if($('div#jq_checkbox_head_added').hasClass('selected')) {
                $('div#jq_checkbox_head_added').removeClass('selected');
                $('div#jq_checkbox_head_added').prepend('<div class="checkicon_add"><image src="' + root + 'uncheckbox.gif" /></div>');
                $('.jq_checkbox_added').each(function() {
                    this.checked = false;
                });
            }
            else {
                $('div#jq_checkbox_head_added').addClass('selected')
                $('div#jq_checkbox_head_added').prepend('<div class="checkicon_add"><image src="' + root + 'checkbox.gif" /></div>');
                $('.jq_checkbox_added').each(function() {
                    this.checked = true;
                });
            }
        });
    });
    
</script>
<div class="content">
    <?= form_open(); ?>
    <input type="hidden" name="id" value="" />
    <div class="row-fluid">
        <div class="span7">
            <div class="box">
                <div class="box_title"><h4><span>#</span></h4></div>
                <div class="box_content">
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3" for="no_bukti">Nomor Bukti</label>
                                <input class="span6" type="text" name="no_bukti" id="no_bukti" readonly value="S/ASDF/B/000205/2013" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3" for="tanggal">Tanggal</label>
                                <input class="span6 datepicker" type="text" name="tanggal" id="tanggal" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3">Jenis Transaksi</label>
                                <select class="span6" name="tipe_transaksi">
                                    <option value="K">Kas</option>
                                    <option value="B">Bank</option>
                                    <option value="M">Memorial</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3">Tipe Transaksi</label>
                                <select class="span6" name="tipe_transaksi">
                                    <option value="in">Cash In</option>
                                    <option value="out">Cash Out</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="kode_debet" />
                    <input type="hidden" name="label_debet" />
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3" for="debet">Debet</label>
                                <div class="input-append">
                                    <input class="span12 text" type="text" name="debet" id="debet" />
                                    <button class="btn btn-info pklist" type="button" title="Kode Perkiraan" rel="type=iframe&src=<?= site_url('mod_kdperkiraan/popup_kdperkir'); ?>&width=600&height=400&func=kodePicker"><i class="icon-search icon-white"></i></button>
                                    <button class="btn btn-info" type="button" name="slide_debet"><i class="icon-arrow-down icon-white" id="slide_debet_icon"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Begin Form Debet-->
                    <input type="hidden" name="kode_debet_rekanan" />
                    <div id="form_debet" class="form_slide">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid form-inline">
                                    <label class="form-label span3" for="debet_rekanan">Rekanan</label>
                                    <div class="input-append">
                                        <input class="text" type="text" name="debet_rekanan" id="debet_rekanan" />
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="debet_sumberdaya">Sumber Daya</label>
                                    <div class="input-append">
                                        <input class="span12 text" type="text" name="debet_sumberdaya" id="debet_sumberdaya"/>
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="debet_volume">Volume</label>
                                    <input class="span6" type="text" name="debet_volume" id="debet_volume"/>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="debet_workitem">Work Item</label>
                                    <div class="input-append">
                                        <input class="span12 text" type="text" name="debet_workitem" id="debet_workitem"/>
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="debet_kodealat">Kode Alat</label>
                                    <div class="input-append">
                                        <input class="span12 text" type="text" name="debet_kodealat" id="debet_kodealat"/>
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--End form debet-->
                    <input type="hidden" name="kode_kredit" />
                    <input type="hidden" name="label_kredit" />
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3" for="kredit">Kredit</label>
                                <div class="input-append">
                                    <input class="span12 text" type="text" name="kredit" id="kredit" />
                                    <button class="btn btn-info pklist" type="button" title="Kode Perkiraan" rel="type=iframe&src=<?= site_url('mod_kdperkiraan/popup_kdperkir'); ?>&width=600&height=400&func=kodePicker"><i class="icon-search icon-white"></i></button>
                                    <button class="btn btn-info" type="button" name="slide_kredit"><i class="icon-arrow-down icon-white" id="slide_kredit_icon"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Begin Form Kredit-->
                    <div id="form_kredit" class="form_slide">
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="kredit_rekanan">Rekanan</label>
                                    <div class="input-append">
                                        <input class="span12 text" type="text" name="kredit_rekanan" id="kredit_rekanan" />
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="kredit_sumberdaya">Sumber Daya</label>
                                    <div class="input-append">
                                        <input class="span12 text" type="text" name="kredit_sumberdaya" id="kredit_sumberdaya"/>
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="kredit_volume">Volume</label>
                                    <input class="span6" type="text" name="kredit_volume" id="kredit_volume"/>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="kredit_workitem">Work Item</label>
                                    <div class="input-append">
                                        <input class="span12 text" type="text" name="kredit_workitem" id="kredit_workitem"/>
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="span12">
                                <div class="row-fluid">
                                    <label class="form-label span3" for="kredit_kodealat">Kode Alat</label>
                                    <div class="input-append">
                                        <input class="span12 text" type="text" name="kredit_kodealat" id="kredit_kodealat"/>
                                        <button class="btn btn-info" type="button"><i class="icon-search icon-white"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End form debet-->
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3" for="keterangan">Keterangan</label>
                                <input class="span9 text" type="text" name="keterangan" value="" id="keterangan" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3" for="nilai">Nilai</label>
                                <input class="span4 price_format" type="text" name="nilai" id="nilai"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span3"></label>
                                <button type="button" name="add" id="add" class="btn btn-primary"><i class="icon-ok icon-white"></i> Add</button>
                                <button type="button" name="cancel" id="cancel" class="btn btn-primary"><i class="icon-remove icon-white"></i> Cancel</button>
                                <button type="button" name="save" id="save" class="btn btn-primary"><i class="icon-arrow-right icon-white"></i> Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        <div class="span5">
                    <div class="box">
                        <div class="box_title"><h4><span>#</span></h4></div>
                        <div class="box_content">
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span3">Level User</label>
                                        <label class="form-label span8"><?= $level; ?></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span3">Unit Kerja</label>
        <?= form_dropdown('unitkerja', $unitkerja, set_value('unitkerja'), 'class="span9"'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span3">Sub Unit / Proyek</label>
                                        <select class="span9 text" name="subunit_proyek"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>-->
    </div>
    <div class="row-fluid">
        <div class="span12">
            <table id="list2"></table>
            <div id="pager2"></div>
        </div>
    </div>
    <?= form_close(); ?>
</div>