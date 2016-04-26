<div class="content">
    <?= form_open("mod_subunitkerja/subunitkerja_add"); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Sub Group Data Add</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="nama_subunit">Nama Sub Group</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="nama_subunit" name="nama_subunit" value="<?= set_value('nama_subunit'); ?>" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="is_proyek">isProyek</label>
                            <div class="controls">
                                <?= form_dropdown('is_proyek', $is_proyek, set_value('is_proyek'), 'id="is_proyek"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="keterangan">Keterangan</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="keterangan" name="keterangan" value="<?= set_value('keterangan'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="form_subunitkerja_save"><i class="icon-ok-sign icon-white"></i> Save</button>
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
    function formSubGroupDataAddClear() {
        $('input[name="nama_subunit"]').val("");
        $('select[name="is_proyek"]').val("");
        $('input[name="keterangan"]').val("");
    }

    $(document).ready(function() {
        $('button[name="form_subunitkerja_save"]').bind('click', function() {
            var _nama_subunit = $('input[name="nama_subunit"]').val();
            var _is_proyek = $('select[name="is_proyek"]').val();
            var _keterangan = $('input[name="keterangan"]').val();

            $.ajax({
                url: root + 'mod_subunitkerja/subunitkerja_add',
                type: 'post',
                dataType: 'json',
                data: {
                    nama_subunit: _nama_subunit,
                    is_proyek: _is_proyek,
                    keterangan: _keterangan
                },
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
                        formSubGroupDataAddClear();
                    }
                    scrollup();
                    createAutoClosingAlert('div.alert', 3000);
                }
            });
        });
    });
</script>