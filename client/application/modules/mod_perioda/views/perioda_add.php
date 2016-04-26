<div class="content popup form_perioda_year">
    <form id="perioda_add">
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3" for="start_date">Start Date</label>
                    <input class="span8 datepicker" type="text" name="start_date" id="start_date" />
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3" for="end_date">End Date</label>
                    <input class="span8 datepicker" type="text" name="end_date" id="end_date" />
                </div>
            </div>
        </div>
        <!--        <div class="row-fluid">
                    <div class="span12">
                        <div class="row-fluid">
                            <label class="form-label span3">&nbsp;</label>
                            <label class="span9 checkbox"><input type="checkbox" name="is_closed" value="true" >Close</label>
                        </div>
                    </div>
                </div>-->
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span3">&nbsp;</label>
                    <button type="button" name="save" id="save" class="btn btn-primary"><i class="icon-arrow-right icon-white"></i> Save</button>
<!--                    <button type="button" name="cancel" id="cancel" class="btn btn-primary"><i class="icon-remove icon-white"></i> Cancel</button>-->
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    
    function form_perioda_year_clear() {
        $('form[id="perioda_add"] input[name="start_date"]').val("");
        $('form[id="perioda_add"] input[name="end_date"]').val("");
    }
    
    $('form[id="perioda_add"] button[name="save"]').click(function() {
        
        var start_date  =  $('form[id="perioda_add"] input[name="start_date"]').val();
        var end_date    =  $('form[id="perioda_add"] input[name="end_date"]').val();
        
        $.ajax({
            url: root + 'mod_perioda/addperioda',
            dataType: 'json',
            type: 'post',
            data: { 
                start_date:start_date, 
                end_date:end_date
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
                    $('.popup').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                    $('div.alert').fadeIn('slow');
                }
                
                if (json['success']) {
                    $('.popup').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                    $('div.alert').fadeIn('slow');
                    form_perioda_year_clear();
                    parent.pkcaller(json);
                }
            }
        });
    });
    
    $(function() {
        //$(".alert").alert();
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