<div class="content form_importdbf">
    <?= form_open_multipart('mod_importdata/getDataImport'); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Import Data</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="form_importdbf_fileupload">Upload</label>
                            <div class="controls">
                                <input type="file" name="userfile" id="form_importdata_fileupload" />
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="submit" name="form_importdata_proses" class="btn btn-primary"><i class="cus-table-add"></i>Proses</button>
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
