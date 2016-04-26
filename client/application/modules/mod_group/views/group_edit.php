<!-- start checkboxTree configuration -->
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>js/checkboxtree/library/jquery-ui-1.8.12.custom/css/smoothness/jquery-ui-1.8.12.custom.css"/>
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>js/checkboxtree/jquery.checkboxtree.css"/>
<script type="text/javascript" src="<?= base_url(); ?>js/checkboxtree/library/jquery-ui-1.8.12.custom/js/jquery-ui-1.8.12.custom.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/checkboxtree/jquery.checkboxtree.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>js/checkboxtree/library/jquery.cookie.js"></script>
<!-- end checkboxTree configuration -->

<div class="content">
    <?= form_open("mod_group/group_edit"); ?>
    <input type="hidden" name="id" value="<?= $detail['id_group']; ?>" />
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Group Akses Edit</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="nama_group">Nama Group</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="nama_group" name="nama_group" value="<?= $detail['nama_group']; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="keterangan">Keterangan</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="keterangan" name="keterangan" value="<?= $detail['keterangan']; ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label">Modules</label>
                            <div class="controls">
                                <ul id="tree1">
                                    <?php
                                    if (isset($hak_akses)) {
                                        foreach ($hak_akses as $parent) {
                                            echo '<li><label class="checkbox"><input type="checkbox" class="checktree" name="hak_akses[]" value="' . $parent['row']['id_modules'] . '" ' . $parent['row']['is_checked'] . '> ' . $parent['row']['modules'] . '</label>';
                                            echo "<ul>";
                                            foreach ($parent['child'] as $module) {
                                                echo '<li><label class="checkbox"><input type="checkbox" class="checktree" name="hak_akses[]" value="' . $module['row']['id_modules'] . '" ' . $module['row']['is_checked'] . '> ' . $module['row']['modules'] . '</label>';
                                                if (count($module['child'])) {
                                                    echo "<ul>";
                                                    foreach ($module['child'] as $submodule) {
                                                        echo '<li><label class="checkbox"><input type="checkbox" class="checktree" name="hak_akses[]" value="' . $submodule['row']['id_modules'] . '" ' . $submodule['row']['is_checked'] . '> ' . $submodule['row']['modules'] . '</label>';

                                                        if (count($submodule['child'])) {
                                                            echo "<ul>";
                                                            foreach ($submodule['child'] as $submodulelast) {
                                                                echo '<li><label class="checkbox"><input type="checkbox" class="checktree" name="hak_akses[]" value="' . $submodulelast['row']['id_modules'] . '" ' . $submodulelast['row']['is_checked'] . '> ' . $submodulelast['row']['modules'] . '</label>';
                                                            }
                                                            echo "</ul>";
                                                        }
                                                    }
                                                    echo "</ul>";
                                                }
                                            }
                                            echo "</ul>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="form_groupadd_save"><i class="icon-ok-sign icon-white"></i> Save</button>
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
    function formGroupAddClear() {
        $('input[name="nama_group"]').val("");
        $('input[name="keterangan"]').val("");
        $('input[class="checktree"]').each(function() {
            this.checked = false;
        });
    }

    $(document).ready(function() {
        $('#tree1').checkboxTree();

        $('button[name="form_groupadd_save"]').bind('click', function() {
            $.ajax({
                url: root + 'mod_group/group_edit',
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
                        formGroupAddClear();
                    }

                    if (json['redirect']) {
                        location = json['redirect'];
                    }
                }
            });
        });
    });
</script>