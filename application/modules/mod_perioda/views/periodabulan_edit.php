<input type="hidden" name="form_perioda_bulan_period_key" value="<?= $rec["period_key"]; ?>" />
<div class="content form_perioda_bulan">
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="name">Name</label>
                <input class="span9" type="text" name="form_perioda_bulan_name" id="name" value="<?= $rec["period_name"]; ?>" />
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="start_date">Start Date</label>
                <input class="span8 datepicker" type="text" name="form_perioda_bulan_start_date" value="<?= $rec["period_start"]; ?>" readonly />
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3" for="end_date">End Date</label>
                <input class="span8 datepicker" type="text" name="form_perioda_bulan_end_date" value="<?= $rec["period_end"]; ?>" readonly />
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3">Number</label>
                <input class="span9" type="text" name="form_perioda_bulan_number" value="<?= $rec["period_number"]; ?>" readonly />
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <div class="row-fluid">
                <label class="form-label span3">Quarter</label>
                <input class="span9" type="text" name="form_perioda_bulan_quarter" value="<?= $rec["period_quarter"]; ?>"/>
            </div>
        </div>
    </div>
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
    $(document).ready(function() {
        $('button[name="form_perioda_bulan_save"]').click(function() {
            var form_perioda_bulan_name = $('input[name="form_perioda_bulan_name"]').val();
            var form_perioda_bulan_quarter = $('input[name="form_perioda_bulan_quarter"]').val();
            var form_perioda_bulan_period_key = $('input[name="form_perioda_bulan_period_key"]').val();
            
            $.ajax({
                url: root + 'mod_perioda/perioda_bulan_edit_act',
                dataType: 'json',
                type: 'post',
                data: { 
                    form_perioda_bulan_name:form_perioda_bulan_name,
                    form_perioda_bulan_quarter:form_perioda_bulan_quarter,
                    form_perioda_bulan_period_key:form_perioda_bulan_period_key
                },
                beforeSend: function() {
                    $(this).attr('disabled',true);
                },	
                complete: function() {
                    $(this).attr('disabled',false);
                },			
                success: function(json) {
					parent.pkcaller(json);
                }
            });
        });
        
    });
</script>
