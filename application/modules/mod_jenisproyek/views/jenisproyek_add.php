<div class="content">
    <?= form_open("mod_jenisproyek/jenisproyek_add"); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> <?= $ptitle; ?></h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="jenisproyek_name">Jenis Proyek</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="jenisproyek_name" name="jenisproyek_name" value="<?= set_value('jenisproyek_name'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="jenisproyek_ket">Keterangan</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="jenisproyek_ket" name="jenisproyek_ket" value="<?= set_value('jenisproyek_ket'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="jenisproyek_save"><i class="icon-ok-sign icon-white"></i> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?= form_close(); ?>
</div>
<script type="text/javascript">
    function formJenisProyekAddClear() {
        $('input[name="jenisproyek_name"]').val("");
        $('input[name="jenisproyek_ket"]').val("");
    }

    $(document).ready(function() {
        $('button[name="jenisproyek_save"]').bind('click', function() {
            $.ajax({
                url: root + 'mod_jenisproyek/jenisproyek_add',
                type: 'post',
                dataType: 'json',
                data: $("form").serialize(),
                beforeSend: function() {
                    $(this).attr('disabled', false);
                },
                complete: function() {
                    $(this).attr('disabled', false);
                },
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['error'] + '</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>' + json['success'] + '</div>');
                        $('div.alert').fadeIn('slow');
                        formJenisProyekAddClear();
                    }
                    scrollup();
                    createAutoClosingAlert('div.alert', 3000);
                }
            });
        });
    });
</script>
