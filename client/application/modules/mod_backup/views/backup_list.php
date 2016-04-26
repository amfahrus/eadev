<!-- start checkboxTree configuration -->
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>js/checkboxtree/library/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css"/>
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>js/checkboxtree/jquery.checkboxtree.css"/>
<script type="text/javascript" src="<?= base_url(); ?>js/checkboxtree/library/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/checkboxtree/jquery.checkboxtree.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/checkboxtree/library/jquery.cookie.js"></script>
<script>
    $(document).ready(function() {
        $('#tree1').checkboxTree();
    });
</script>
<!-- end checkboxTree configuration -->

<div class="content">
    <?= form_open('mod_backup/backup'); ?>
    
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="box_title"><h4><span>Backup Database</span></h4></div>
                <div class="box_content">
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Pilih Periode</label>
                                <div class="span8 text">
										<?= form_dropdown('periode_year', $op_yearperiode, set_value('periode_year'), 'class="span8"'); ?>	
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Pilih Periode Akunting</label>
                                <div class="span8 text">
										<select class="span8 text" name="periode"></select>	
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row-fluid">
                        <div class="span12">
                            <div class="row-fluid">
                                <label class="form-label span4">Pilih Database</label>
                                <div class="span8 text">
                                    <ul id="tree1">
                                        <?php
                                        if (isset($tables)) {
                                            foreach ($tables as $table) {
                                                echo "<li><input type='checkbox' name='table[]' value='" . $table . "'><label class=\"checkboxtree\">" . $table . "</label>";
                                                echo "</li>";
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-info"><i class="icon-ok-sign icon-white"></i> Backup</button>
                        <button type="button" class="btn"><i class="icon-remove"></i>Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?= form_close(); ?>
</div>

<script>
function getDataPeriode(id) {
        $.ajax({
            url: root + 'mod_backup/getDataPeriode',
            type: 'post',
            data: { id: id},
            success: function(data) {
                $('select[name="periode"]').html(data);
            }
        });	
    }
    
    $('select[name="periode_year"]').change(function() {
        var id =  $('select[name="periode_year"]').val();
        getDataPeriode(id);
    });
    
    $(function() {
        var id_periodeyear = $('select[name="periode_year"]').val();
        getDataPeriode(id_periodeyear);
        
    });
</script>
