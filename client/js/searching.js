$(document).ready(function() {
    $("select.cols_cari").each(function(){
        var n =  $("select.cols_cari").index(this);
        var m = n + 1;
        $("#col" + m).change();
    });
});

function select_options(CbBox, number) {
	//alert(number);
    var num = "<option value=''></option><option value='>'>></option><option value='<'><</option><option value='>='>>=</option><option value='<='><=</option><option value='='>=</option><option value='!='>!=</option>";
    var tgl = "<option value=''></option><option value='>'>></option><option value='<'><</option><option value='>='>>=</option><option value='<='><=</option><option value='='>=</option><option value='!='>!=</option>";
    var text = "<option value=''></option><option value='='>=</option><option value='!='>!=</option><option value='like'>like</option>";
    var vals2 = "<input name='vals[]' id='vals"+number+"' type='text' class='input-mini datepickers'/>";
    var vals3 = "<input name='vals[]' id='vals"+number+"' type='text' class='input-mini text'/>";
    var vals = $('#vals' + number).attr("class");
    if (CbBox.value.substring(0,4) == 'tgl:') {
        $('#ops'+ number).html(num);
        $('#td_val'+ number).html(vals2);
        $( ".datepickers" ).datepicker({
            showOn: "button",
            buttonImage: root + "media/images/calendar.gif",
            dateFormat:"yy-mm-dd",
            buttonImageOnly: true
        });
    } else if(CbBox.value.substring(0,4) == 'num:'){
        $('#ops'+ number).html(tgl);
        $('#td_val'+ number).html(vals3);
    } else {
        $('#ops'+ number).html(text);
        $('#td_val'+ number).html(vals3);
    }
}
