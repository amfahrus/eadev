<div class="content">
    <?= form_open("mod_sbdaya/sbdaya_edit"); ?>
    <input type="hidden" name="id" value="<?= $detail["id_sbdaya"]; ?>" />
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Sumber Daya Edit</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="kode_sbdaya">Kode Sumber Daya</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="kode_sbdaya" name="kode_sbdaya" value="<?= $detail["kode_sbdaya"]; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="nama_sbdaya">Nama Sumber Daya</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="nama_sbdaya" name="nama_sbdaya" value="<?= $detail["sbdaya"]; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="satuan">Satuan</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="satuan" name="satuan" value="<?= $detail["satuan"]; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="tipe">Type</label>
                            <div class="controls">
                                <?= form_dropdown('tipe', $tipe, $detail["tipe"], 'id="tipe"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="form_sbdaya_save"><i class="icon-ok-sign icon-white"></i> Save</button>
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
    function formSbdayaAddClear() {
        $('input[name="kode_sbdaya"]').val("");
        $('input[name="nama_sbdaya"]').val("");
        $('input[name="satuan"]').val("");
        $('select[name="tipe"]').val("");
    }
    
    $(document).ready(function() {
        $('button[name="form_sbdaya_save"]').bind('click', function() {
            $.ajax({
                url : root + 'mod_sbdaya/sbdaya_edit',
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
                        formSbdayaAddClear();
                    }
                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                }
            });
        });
    });
</script>