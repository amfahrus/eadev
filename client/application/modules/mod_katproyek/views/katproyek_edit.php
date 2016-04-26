<div class="content">
    <?= form_open("mod_katproyek/katproyek_edit"); ?>
    <input type="hidden" name="id" value="<?= $detail["id_katproyek"]; ?>" />
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Kategori Proyek Edit</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="nama_kategoriproyek">Kategori Proyek</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="nama_kategoriproyek" name="nama_kategoriproyek" value="<?= $detail["nama_kategoriproyek"]; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="keterangan">Keterangan</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="keterangan" name="keterangan" value="<?= $detail["keterangan"]; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="is_active">IsAktif</label>
                            <div class="controls">
                                <?= form_dropdown('is_active', $is_active, $detail["is_active"], 'id="is_active"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="form_katproyek_save"><i class="icon-ok-sign icon-white"></i> Save</button>
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
    function formKatProyekAddClear() {
        $('input[name="nama_kategoriproyek"]').val("");
        $('input[name="keterangan"]').val("");
    }
    
    $(document).ready(function() {
        $('button[name="form_katproyek_save"]').bind('click', function() {
            $.ajax({
                url : root + 'mod_katproyek/katproyek_edit',
                type : 'post',
                dataType : 'json',
                data : $("form").serialize(),
                beforeSend : function() {
                    $(this).attr('disabled',false);
                },
                complete : function() {
                    $(this).attr('disabled',false);
                },
                success : function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                        formKatProyekAddClear();
                    }
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                }
            });
        });
    });
</script>