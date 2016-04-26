<input type="hidden" name="yearperiod_id" value="<?= $id; ?>" />
<div class="content form_perioda_bulan">
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="name">Name</label>
                <input class="span9" type="text" name="form_perioda_bulan_name" id="name" />
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="start_date">Start Date</label>
                <input class="span8 datepicker" type="text" name="form_perioda_bulan_start_date" id="start_date" />
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="end_date">End Date</label>
                <input class="span8 datepicker" type="text" name="form_perioda_bulan_end_date" id="end_date" />
            </div>
        </div>
    </div>
    <!--    <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3">Number</label>
                    <input class="span9" type="text" name="form_perioda_bulan_number"/>
                </div>
            </div>
        </div>-->
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3">Quarter</label>
                <input class="span9" type="text" name="form_perioda_bulan_quarter"/>
            </div>
        </div>
    </div>
    <!--    <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3">&nbsp;</label>
                    <label class="span3 checkbox"><input type="checkbox" name="form_perioda_bulan_close" value="123" >Close</label>
                    <label class="span3 checkbox"><input type="checkbox" name="form_perioda_bulan_frozen" value="123" >Frozen</label>
                </div>
            </div>
        </div>-->
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3">&nbsp;</label>
                <button type="button" name="form_perioda_bulan_save" id="form_perioda_bulan_save" class="btn btn-primary"><i class="icon-arrow-right icon-white"></i> Save</button>
            </div>
        </div>
    </div>
</div>
<script>
    function form_perioda_bulan_clear() {
        $('input[name="form_perioda_bulan_name"]').val("");
        $('input[name="form_perioda_bulan_start_date"]').val("");
        $('input[name="form_perioda_bulan_end_date"]').val("");
        $('input[name="form_perioda_bulan_quarter"]').val("");
    }
    
    $(document).ready(function() {
        $('button[name="form_perioda_bulan_save"]').click(function() {
            var form_perioda_bulan_name = $('input[name="form_perioda_bulan_name"]').val();
            var form_perioda_bulan_start_date = $('input[name="form_perioda_bulan_start_date"]').val();
            var form_perioda_bulan_end_date = $('input[name="form_perioda_bulan_end_date"]').val();
            var form_perioda_bulan_quarter = $('input[name="form_perioda_bulan_quarter"]').val();
            var form_perioda_year_id = $('input[name="yearperiod_id"]').val();
            
            $.ajax({
                url: root + 'mod_perioda/perioda_bulan_add_act',
                dataType: 'json',
                type: 'post',
                data: { 
                    form_perioda_bulan_name:form_perioda_bulan_name, 
                    form_perioda_bulan_start_date:form_perioda_bulan_start_date, 
                    form_perioda_bulan_end_date:form_perioda_bulan_end_date,
                    form_perioda_bulan_quarter:form_perioda_bulan_quarter,
                    form_perioda_year_id:form_perioda_year_id
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
                        $('.form_perioda_bulan').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                                        
                    if (json['success']) {
                        form_perioda_bulan_clear();
                        $('.form_perioda_bulan').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        parent.pkcaller();
                    }
                }
            });
        });
        
        $( ".datepicker" ).datepicker({
            showOn: "button",
            buttonImage: root + "images/calendar.gif",
            dateFormat : 'yy-mm-dd',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true
        });
    });
</script>