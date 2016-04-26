<form id="form_popupgantidomain">
    <div class="content popup">
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span4">Level User</label>
                    <label class="form-label span8"><?= $level; ?></label>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span4">Unit Kerja</label>
                    <?= form_dropdown('unitkerja', $unitkerja, set_value('unitkerja'), 'class="span8"'); ?>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span4">Sub Unit / Proyek</label>
                    <select class="span8 text" name="subunit_proyek"></select>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="span12">
                <div class="row-fluid">
                    <label class="form-label span4">&nbsp;</label>
                    <button type="button" name="save_gantidomain"  id="save_gantidomain" class="btn btn-primary"><i class="icon-ok icon-white"></i> Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function getDataProyek(id) {
        $.ajax({
            url: root + 'mod_user/getDataProyek',
            type: 'post',
            data: { id: id},
            success: function(data) {
                $('form[id="form_popupgantidomain"] select[name="subunit_proyek"]').html(data);
            }
        });	
    }
    
    $('form[id="form_popupgantidomain"] button[name="save_gantidomain"]').click(function() {
        var unitkerja = $('form[id="form_popupgantidomain"] select[name="unitkerja"]').val();
        var subunit_proyek = $('form[id="form_popupgantidomain"] select[name="subunit_proyek"]').val();
            
        $.ajax({
            url: root + 'mod_user/act_gantidomain',
            dataType: 'json',
            type: 'post',
            data: { 
                unitkerja:unitkerja,
                subunit_proyek:subunit_proyek                   
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
                    location.reload();
                }
            }
        });
    });
    
    $('form[id="form_popupgantidomain"] select[name="unitkerja"]').change(function() {
        var id =  $('form[id="form_popupgantidomain"] select[name="unitkerja"]').val();
        getDataProyek(id);
    });
    
    $(function() {
        var id_unitkerja = $('form[id="form_popupgantidomain"] select[name="unitkerja"]').val();
        getDataProyek(id_unitkerja);
        
    });
   
</script>
