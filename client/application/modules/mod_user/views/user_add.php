<div class="content">
    <?= form_open("mod_user/user_add"); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> User Add</h4></div>
                <div class="basic box_content form-horizontal">
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="username">Username</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="username" name="username" value="<?= set_value('username'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="password">Password</label>
                            <div class="controls">
                                <input class="span8 text" type="password" id="password" name="password" value="<?= set_value('password'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="repassword">Re-Password</label>
                            <div class="controls">
                                <input class="span8 text" type="password" id="repassword" name="repassword" />
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="fullname">Nama Lengkap</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="fullname" name="fullname" value="<?= set_value('fullname'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="email">Email</label>
                            <div class="controls">
                                <input class="span8 text" type="text" id="email" name="email" value="<?= set_value('email'); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="enabled">Enabled</label>
                            <div class="controls">
                                <?= form_dropdown('enabled', $active, set_value('enabled'), 'id="enabled"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="username">Aktif</label>
                            <div class="controls">
                                <?= form_dropdown('active', $active, set_value('active'), 'id="active"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="id_group">Group Akses</label>
                            <div class="controls">
                                <?= form_dropdown('id_group', $id_group, set_value('id_group'), 'id="id_group"'); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" for="username">Kategori Unit Kerja</label>
                            <div class="controls">
                                <?= form_dropdown('is_proyek', $is_proyek, set_value('is_proyek')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <label class="control-label" id="label_response"></label>
                            <div class="controls" id="response">
                                <div id="form_user_add"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid">
                        <div class="control-group info">
                            <div class="controls">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info" name="form_useradd_save"><i class="icon-ok-sign icon-white"></i> Save</button>
                                    <button type="button" class="btn"><i class="icon-remove"></i>Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/msdropdown/css/msdropdown/dd.css" />
<script src="<?= base_url(); ?>assets/msdropdown/js/msdropdown/jquery.dd.min.js"></script>
<script type="text/javascript">
    function formUserAddClear() {
        $('input[name="username"]').val("");
        $('input[name="password"]').val("");
        $('input[name="repassword"]').val("");
        $('input[name="fullname"]').val("");
        $('input[name="email"]').val("");
        $('select[name="enabled"]').val("");
        $('select[name="active"]').val("");
        $('select[name="id_group"]').val("");
        $('select[name="is_proyek"]').val("");
        $('select[name="id_relasi"]').val("");
    }

    function getUnitProyek() {
        var id = $('select[name="is_proyek"]').val();
        $.ajax({
            url: root + 'mod_user/getUnitProyek',
            dataType: 'json',
            type: 'post',
            data: {id: id},
            success: function(json) {
                $('label#label_response').html(json['label']);
                $("#form_user_add").msDropDown({byJson: {data: json['data'], name: 'id_relasi'}}).data("dd");
            }
        });
    }

    $(document).ready(function() {
        getUnitProyek();

        $('select[name="is_proyek"]').change(function() {
            var id = $('select[name="is_proyek"]').val();
            $("#form_user_add").remove();
            $('div#response').append('<div id="form_user_add"></div>');
            $.ajax({
                url: root + 'mod_user/getUnitProyek',
                dataType: 'json',
                type: 'post',
                data: {id: id},
                success: function(json) {
                    $('label#label_response').html(json['label']);
                    $("#form_user_add").msDropDown({byJson: {data: json['data'], name: 'id_relasi'}}).data("dd");
                }
            });
        });

        $('button[name="form_useradd_save"]').bind('click', function() {
            var _username = $('input[name="username"]').val();
            var _password = $('input[name="password"]').val();
            var _repassword = $('input[name="repassword"]').val();
            var _fullname = $('input[name="fullname"]').val();
            var _email = $('input[name="email"]').val();
            var _enabled = $('select[name="enabled"]').val();
            var _active = $('select[name="active"]').val();
            var _id_group = $('select[name="id_group"]').val();
            var _is_proyek = $('select[name="is_proyek"]').val();
            var _id_relasi = $('select[name="id_relasi"]').val();

            $.ajax({
                url: root + "mod_user/user_add",
                dataType: 'json',
                type: 'post',
                data: {
                    username: _username,
                    password: _password,
                    repassword: _repassword,
                    fullname: _fullname,
                    email: _email,
                    enabled: _enabled,
                    active: _active,
                    id_group: _id_group,
                    is_proyek: _is_proyek,
                    id_relasi: _id_relasi
                },
                beforeSend: function() {
                    $(this).attr('disabled', true);
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
                        formUserAddClear();
                    }
                    scrollup();
                    createAutoClosingAlert('div.alert', 3000);
                }
            });
        });
    });
</script>