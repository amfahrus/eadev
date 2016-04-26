<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/msdropdown/css/msdropdown/dd.css" />
<script type="text/javascript" src="<?= base_url(); ?>assets/msdropdown/js/msdropdown/jquery.dd.js"></script>
<div class="content">
    <?= form_open("mod_module/module_add"); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> Module Add</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="modules">Module</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="modules" name="modules" value="<?= set_value('modules'); ?>" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="icon">Icon</label>
                            <div class="controls">
								<div id="form_moduleadd_icons"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="parent">Parent</label>
                            <div class="controls">
                                <?= form_dropdown('parent', $parent, set_value('parent'), 'id="parent"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="publish">Publish</label>
                            <div class="controls">
                                <?= form_dropdown('publish', $publish, set_value('publish'), 'id="publish"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="link">Link</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="link" name="link" value="<?= set_value('link'); ?>" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="sort">Urutan</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="sort" name="sort" value="<?= set_value('sort'); ?>" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="form_moduleadd_save"><i class="icon-ok-sign icon-white"></i> Save</button>
                                    <button type="button" class="btn"><i class="icon-remove"></i>Cancel</button>
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
	function getIcons(selectedvalue) {
        selectedvalue = typeof selectedvalue !== 'undefined' ? selectedvalue : '';
        $.ajax({
            url: root + "mod_module/module_icon",
            dataType: 'json',
            type: 'post',
            success: function(json) {
                $("#form_moduleadd_icons").msDropDown({byJson: {data: json, name: 'icon'}}).data("dd").setIndexByValue(selectedvalue);
            }
        });
    }
    function formModuleAddClear() {
        $('input[name="modules"]').val("");
        $('select[name="icon"]').val("");
        $('select[name="parent"]').val("");
        $('select[name="publish"]').val("");
        $('input[name="link"]').val("");
        $('input[name="sort"]').val("");
    }

    $(document).ready(function() {
        getIcons();
        $('button[name="form_moduleadd_save"]').bind('click', function() {
            var _modules = $('input[name="modules"]').val();
            var _icon = $('select[name="icon"]').val();
            var _parent = $('select[name="parent"]').val();
            var _publish = $('select[name="publish"]').val();
            var _link = $('input[name="link"]').val();
            var _sort = $('input[name="sort"]').val();

            $.ajax({
                url: root + 'mod_module/module_add',
                dataType: 'json',
                type: 'post',
                data: {
                    modules: _modules,
                    icon: _icon,
                    parent: _parent,
                    publish: _publish,
                    link: _link,
                    sort: _sort,
                },
                beforeSend: function() {
                    $(this).attr('disabled', true);
                },
                complete: function() {
                    $(this).attr('disabled', true);
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
                        formModuleAddClear();
                    }
                    scrollup();
                    createAutoClosingAlert('div.alert', 3000);
                }
            });
        });
    });
</script>
