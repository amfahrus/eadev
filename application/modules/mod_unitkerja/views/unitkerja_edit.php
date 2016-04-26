<div class="content">
    <?= form_open("mod_unitkerja/unitkerja_edit"); ?>
    <input type="hidden" name="id" value="<?= $detail["id_unitkerja"]; ?>"/>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Group Data Add</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="nama_unitkerja">Nama Group Data</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="nama_unitkerja" name="nama_unitkerja" value="<?= $detail["nama_unitkerja"]; ?>" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="keterangan">Keterangan</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="keterangan" name="keterangan" value="<?= $detail["keterangan"]; ?>" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="keterangan">Akses Data</label>
                            <div class="controls">
                                <?php
                                foreach ($hak_akses as $value) {
                                    echo '<label class="checkbox"><input type="checkbox" name="hak_akses[]" value="' . $value['id_subunitkerja'] . '" ' . $value['is_checked'] . '>' . $value['nama_subunit'] . '</label>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="form_unitkerja_save"><i class="icon-ok-sign icon-white"></i> Save</button>
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
    $(document).ready(function() {
        $('button[name="form_unitkerja_save"]').bind('click', function() {
            $.ajax({
                url: root + 'mod_unitkerja/unitkerja_edit',
                type: 'post',
                dataType : 'json',
                data : $("form").serialize(),
                beforeSend : function() {
                    $(this).attr('disabled',false);
                },
                complete: function() {
                    $(this).attr('disabled',false);
                },
                success: function(json) {
                    $('div.alert').remove();
                    if (json['error']) {
                        $('.content').prepend('<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['error']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    if (json['success']) {
                        $('.content').prepend('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button>'+json['success']+'</div>');
                        $('div.alert').fadeIn('slow');
                    }
                    
                    if(json['redirect']) {
                        location = json['redirect'];
                    }
                }
            });
        });
    });
</script>