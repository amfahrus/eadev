
<div class="content">
    <?= form_open_multipart('mod_restore/restore'); ?>
    
    <?php
    $messages = $this->session->flashdata('messages');
    if (!empty($messages)) {

        echo '<div class="alert alert-success">';
        echo '<button type="button" class="close" data-dismiss="alert">&times;</button>';
        echo '<h4>' . $messages . '</h4> ';
        echo '</div>';
    }
    ?>
    
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box_title"><h4><span>Restore Database</span></h4></div>
                <div class="box_content">
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Pilih Database</label>
                                <div class="span8">
									<input name="file" class="span2" id="appendedInputButton" type="file">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-info"><i class="icon-ok-sign icon-white"></i> Upload</button>
                        <button type="button" class="btn"><i class="icon-remove"></i>Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= form_close(); ?>
</div>
