<div class="content">
    <?= form_open(); ?>
    <div class="row-fluid">
        <div class="span12">
            <div class="box">
                <div class="basic box_title"><h4><span>#</span> <?= $ptitle; ?></h4></div>
                <div class="basic box_content form_search" ></div>
            </div>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <table id="list_fiscalyears"></table>
            <div id="pager_fiscalyears"></div>
        </div>
    </div>
    <?= form_close(); ?> 
</div>
<?= $searchform; ?>
<script type="text/javascript">

    $('a#form_fiscalyears_new').click(function() {
        showUrlInDialog(root + "mod_fiscalyears/fiscalyears_form_add", "refreshGrid", "Periode Tahun", "form_fiscalyears_add");
    });

    $('a#form_fiscalyears_delete').click(function() {
        alert('form_fiscalyears_delete');
    });

    $(document).ready(function() {
        var lebar = $('.inbody').height() - 200;
        var panjang = $('.content').width() - 20;

        jQuery("#list_fiscalyears").jqGrid({
            url: root + 'mod_group/group_json',
            mtype: "post",
            datatype: "json",
            colNames: ['ID Group', 'Nama Group', 'Keterangan', ''],
            colModel: [
                {name: 'id_group', index: 'id_group', hidden: true, width: 150},
                {name: 'nama_group', index: 'nama_group', width: 150},
                {name: 'keterangan', index: 'keterangan', width: 150},
                {name: 'aksi', width: 30, align: "center"}],
            rowNum: 10,
            width: panjang,
            height: lebar,
            rownumbers: true,
            rownumWidth: 40,
            rowList: [10, 20, 30],
            pager: '#pager_fiscalyears',
            multiselect: true,
            viewrecords: true,
            sortorder: "desc",
            shrinkToFit: false
        });

        jQuery("#list_fiscalyears").jqGrid('navGrid', '#pager_fiscalyears', {
            edit: false,
            add: false,
            del: false,
            search: false
        });

        $('#button_search').click(function() {
            var str = $("form").serialize();
            var search = "_search=true&" + str;
            jQuery("#list_fiscalyears").jqGrid('setGridParam', {
                url: root + 'mod_group/group_json?' + search,
                page: 1
            }).trigger("reloadGrid");
        });

        $("#reset_search").click(function() {
            $('.cols_cari').val("");
            $('.ops_cari').val("");
            $('.text').val("");
            jQuery("#list_fiscalyears").jqGrid('setGridParam', {
                url: root + 'mod_group/group_json',
                page: 1
            }).trigger("reloadGrid");
        });

    });
</script>