<div class="content form_importdbf">
    <?= form_open_multipart('mod_importdbf/importdbf_add'); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Import DBF</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="form_importdbf_periode">Periode</label>
                            <div class="controls">
                                <div id="form_importdbf_periode"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="form_importdbf_fileupload">Upload</label>
                            <div class="controls">
                                <input type="file" name="userfile" id="form_importdbf_fileupload" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="submit" name="form_importdbf_proses" class="btn btn-primary"><i class="cus-table-add"></i>Proses</button>
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
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/msdropdown/css/msdropdown/dd.css" />
<script type="text/javascript" src="<?= base_url(); ?>assets/msdropdown/js/msdropdown/jquery.dd.min.js"></script>
<script type="text/javascript">
    function getperiod(selectedvalue) {
        selectedvalue = typeof selectedvalue !== 'undefined' ? selectedvalue : '';
        $.ajax({
            url: root + "rpt_bukubesar/getjsonperiod",
            dataType: 'json',
            type: 'post',
            success: function(json) {
                $('#form_importdbf_periode').msDropDown({byJson:{data:json, name:'form_importdbf_periode'}}).data("dd").setIndexByValue(selectedvalue);
            }
        });	
    }
    
    $(document).ready(function() {
        getperiod();
    });
</script>