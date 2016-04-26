<div class="content form_perioda_year">
    <form id="perioda_add">
        <input type="hidden" name="yearperiod_key" value="<?= $rec["yearperiod_key"]; ?>" />
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3" for="start_date">Start Date</label>
                    <input class="span8 datepicker" type="text" name="start_date" id="start_date" value="<?= $rec["yearperiod_start"]; ?>" readonly />
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3" for="end_date">End Date</label>
                    <input class="span8 datepicker" type="text" name="end_date" id="end_date" value="<?= $rec["yearperiod_end"]; ?>" readonly />
                </div>
            </div>
        </div>
		<div class="row-fluid">
			<div class="span12">
				<div class="row-fluid">
					<label class="form-label span3">&nbsp;</label>
					<label class="span9 checkbox"><input type="checkbox" name="is_closed" <?php echo $rec["yearperiod_closed"] == 't' ? 'checked' : ''; ?>>Close</label>
				</div>
			</div>
		</div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3">&nbsp;</label>
                    <button type="button" name="form_perioda_year_save" class="btn btn-primary"><i class="icon-arrow-right icon-white"></i> Save</button>
<!--                    <button type="button" name="cancel" id="cancel" class="btn btn-primary"><i class="icon-remove icon-white"></i> Cancel</button>-->
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $('button[name="form_perioda_year_save"]').click(function() {
        var is_closed = $('input[name="is_closed"]').val();
        var yearperiod_key = $('input[name="yearperiod_key"]').val();
        
        $.ajax({
            url: root + 'mod_perioda/perioda_year_edit_act',
            dataType: 'json',
            type: 'post',
            data: { 
                is_closed:is_closed,
                yearperiod_key:yearperiod_key
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
    
    $(function() {
        $(".datepicker").datepicker({
            showOn: "button",
            buttonImage: root + "images/calendar.gif",
            dateFormat : 'yy-mm-dd',
            buttonImageOnly: true,
            changeMonth: true,
            changeYear: true
        });
    });
</script>
