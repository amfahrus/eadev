<div class="content form-horizontal form_user_chage_password">
    <input type="hidden" name="form_changepass_userid" value="<?= $id; ?>"/>
    <div class="row-fluid">
        <div class="control-group info">
            <label class="control-label" for="form_changepass_password">Password</label>
            <div class="controls">
                <input type="password" class="text" name="form_changepass_password" id="form_changepass_password"/>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="control-group info">
            <label class="control-label" for="form_changepass_repassword">Re - Password</label>
            <div class="controls">
                <input type="password" class="text" name="form_changepass_repassword" id="form_changepass_repassword"/>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="control-group info">
            <div class="controls">
                <button type="submit" class="btn btn-info" id="form_changepass_save" name="form_changepass_save"><i class="icon-ok-sign icon-white"></i> Save</button>
<!--                <button type="button" class="btn"><i class="icon-remove"></i>Cancel</button>            -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('button[name="form_changepass_save"]').click(function() {
            var form_changepass_userid = $('input[name="form_changepass_userid"]').val();
            var form_changepass_password = $('input[name="form_changepass_password"]').val();
            var form_changepass_repassword = $('input[name="form_changepass_repassword"]').val();

            $.ajax({
                url: root + 'mod_user/act_userchangepass',
                dataType: 'json',
                type: 'post',
                data: {
                    id: form_changepass_userid,
                    password: form_changepass_password,
                    repassword: form_changepass_repassword
                },
                beforeSend: function() {
                    $(this).attr('disabled', true);
                },
                complete: function() {
                    $(this).attr('disabled', false);
                },
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.form_user_chage_password').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }

                    if (json['success']) {
                        $('.form_user_chage_password').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    scrollup();
                    createAutoClosingAlert('div.alert', 3000);
                }
            });
        });
    });
</script>