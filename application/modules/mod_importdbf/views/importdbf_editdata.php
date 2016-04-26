<div class="content form_importdbf">
    <?= form_open(); ?>
    <input type="hidden" name="id" value="<?= $id; ?>" />
    <div class="row-fluid">
        <div class="span12">
            <table id="list2"></table>
            <div id="pager2"></div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
  
    $(document).ready(function() {
        
        $('button[name="form_importdbf_save"]').bind('click', function() {
            alert("wew");
        });
        
        
        var prevVal;
        var prevId;
        var lebar = $('.inbody').height() - 70;
        var panjang = $('.content').width() - 20;
        var id = $('input[name="id"]').val();
        jQuery("#list2").jqGrid({
            url: root + 'mod_importdbf/importdbf_datajson/' + id, 
            mtype : "post",
            datatype: "json",
            colNames:["1","<div id='jq_checkbox_head_added'><div>","2","3","4","5","6","COA GL","COA","Rekanan","SB Daya","Debet","Kredit"], 
            colModel:[
                {name:'id',index:'id', width:100, hidden:true, sortable:false},
                {name:'check',index:'check', width:20, sortable:false, align:"center"},
                {name:'txno',index:'txno', width:50, align:"center", sortable:false},
                {name:'date',index:'date', width:100, formatter:'date', sortable:false, formatoptions:{srcformat:"Y-m-d",newformat:"d M Y"}, align:"center"},
                {name:'txcode',index:'txcode', width:100, hidden:true, sortable:false},
                {name:'itno',index:'itno', width:50, align:"center", sortable:false},
                {name:'desc',index:'desc', width:300, sortable:false},
                {name:'glcode',index:'glcode', width:100, align:"center", sortable:false},
                {name:'kdperkiraan',index:'kdperkiraan', width:100, align:"center", sortable:false, editable:true},
                {name:'kdnasabah',index:'kdnasabah', width:100, align:"left", sortable:false, editable:true},
                {name:'kdsbdaya',index:'kdsbdaya', width:100, align:"left", sortable:false, editable:true},
                {name:'debet',index:'debet', width:150, align:"right", formatter:'currency', sortable:false},
                {name:'kredit',index:'kredit', width:150, align:"right", formatter:'currency', sortable:false}
            ],
            width: panjang,
            height: lebar,
            rownumbers: true,
            rowNum:20,
            rowList:[20,50,100],
            rownumWidth: 40,
            multiselect: false,
            pager: '#pager2',
            viewrecords: true,
            shrinkToFit: false,
            cellEdit: true, 
            cellsubmit: 'clientArray',
            afterEditCell: function (id,name,val,iRow,iCol) { 
                var id = jQuery("#list2").jqGrid('getCell',id,1);
                var coa = jQuery("#list2").jqGrid('getCell',id,9);
                               
                if(name=='kdperkiraan') { 
                    jQuery("#"+iRow+"_kdperkiraan","#list2").autocomplete({ 
                        minLength: 2,
                        source: root + "mod_kdperkiraan/autocomplete_kodeperkiraan",
                        select: function( event, ui ) {
                            if(ui.item.id != 0) {
                                $(this).val( ui.item.id );
                            } else {
                                $(this).val("");
                            }
                            return false;
                        },
                        change: function(event, ui) {
                            prevVal = val;
                            prevId = id;
                        },
                        focus:function( event, ui ) {
                            if(ui.item.id != 0) {
                                $(this).val( ui.item.id );
                            } else {
                                $(this).val("");
                            }
                            return false;
                        }
                    });
                }
                
                if(name=='kdnasabah') {
                    jQuery("#"+iRow+"_kdnasabah","#list2").autocomplete({ 
                        minLength: 2,
                        source: root + "mod_rekanan/autocomplete_rekanan?coa="+coa,
                        select: function( event, ui ) {
                            if(ui.item.id != 0) {
                                $(this).val( ui.item.id );
                            } else {
                                $(this).val("");
                            }
                            return false;
                        },
                        change: function(event, ui) {
                            prevVal = val;
                            prevId = id;
                        },
                        focus:function( event, ui ) {
                            if(ui.item.id != 0) {
                                $(this).val( ui.item.id );
                            } else {
                                $(this).val("");
                            }
                            return false;
                        }
                    });
                }
            }, 
            afterSaveCell : function(rowid,name,val,iRow,iCol) {
                var id = jQuery("#list2").jqGrid('getCell',rowid,1);
                var dk = jQuery("#list2").jqGrid('getCell',rowid,2);
                                
                if(name=='kdperkiraan') {
                    $.ajax({
                        url: root + "mod_importdbf/importdbf_edit",
                        dataType: 'json',
                        type: 'post',
                        data: {
                            id:id,
                            name:name,
                            val:val
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
                                $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                            }
                            if (json['success']) {
                                return false;
                            }
                            createAutoClosingAlert('div.alert', 2000);
                        }
                    });
                }
                
                if(name=='kdnasabah') {
                    $.ajax({
                        url: root + "mod_importdbf/importdbf_edit",
                        dataType: 'json',
                        type: 'post',
                        data: {
                            id:id,
                            name:name,
                            val:val
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
                                $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                            }
                            if (json['success']) {
                                return false;
                            }
                            createAutoClosingAlert('div.alert', 2000);
                        }
                    });
                }
            }
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