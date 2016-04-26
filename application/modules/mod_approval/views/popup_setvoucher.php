<form id="form_popupsetvoucher">
	<input type="hidden" name="nobukti" id="nobukti" value="<?php echo $nobukti; ?>" >
    <div class="content popup">
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span4">Jenis Voucher</label>
                    <select class="span8 text" name="jenis_voucher">
						<option value="1">Voucher In</option>
						<option value="2">Voucher Out</option>
						<option value="3">Memorial</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span4">&nbsp;</label>
                    <button type="button" name="save_setvoucher" id="save_setvoucher" class="btn btn-primary"><i class="icon-ok icon-white"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $('form[id="form_popupsetvoucher"] button[name="save_setvoucher"]').click(function() {
        var nobukti = $('form[id="form_popupsetvoucher"] input[name="nobukti"]').val();
        var jenis_voucher = $('form[id="form_popupsetvoucher"] select[name="jenis_voucher"]').val();
            
        $.ajax({
            url: root + 'mod_approval/act_setvoucher',
            dataType: 'json',
            type: 'post',
            data: { 
                nobukti:nobukti,
                jenis_voucher:jenis_voucher                   
            },
            beforeSend: function() {
                $(this).attr('disabled',true);
            },	
            complete: function() {
                $(this).attr('disabled',false);
            }, success: function(json) {
                $('div.alert').remove();
                if (json['error']) {
                    $('.popup').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+json['error']+'</div>');
                    $('div.alert').fadeIn('slow');
                }
                
                if (json['success']) {
                    $('.popup').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>Warning!</strong> '+json['success']+'</div>');
                    $('div.alert').fadeIn('slow');
                    //location.reload();
                }
                parent.pkcaller(json);
            }
        });
    });
   
</script>
